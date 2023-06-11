<?php
namespace app\core;
use ReflectionClass;
use Reflectionattribute;
use Exception;
class ValidateException extends Exception {}
abstract class Model {
    private $rClass;
    private $methods;
    private $columns;

    protected $hasData = False;
    protected $validated = False;
    protected const RULE_REQUIRED = 'required';
    protected const RULE_EMAIL = 'email';
    protected const RULE_MIN = 'min';
    protected const RULE_MAX = 'max';
    protected const RULE_UNIQUE = 'unique';

    public $errors = [];

    private function getAttributes() {
        return array_filter($this->rClass->getProperties(), function ($attribute) {
            return $attribute->class === $this->rClass->getName();
        });
    }

    private function getMethods() {
        return array_filter($this->rClass->getMethods(), function ($method) {
            return $method->class === $this->rClass->getName();
        });
    }

    private function getReflectionObjectName($arr) {
        $Names = [];
        foreach ($arr as $obj) {
            $Names[] = $obj->name;
        }
        return $Names;
    }

    abstract protected function rules();

    protected function ruleFunctions($rule) {
        return array(
            self::RULE_REQUIRED => function ($attribute) {
                if ($this->$attribute === null || !$this->$attribute || empty($this->$attribute)) {
                    return "This field is required";
                } else {
                    return False;
                }
            },
            self::RULE_EMAIL => function ($attribute) {
                if (!filter_var($this->$attribute, FILTER_VALIDATE_EMAIL)) {
                    return "This field must be valid email address";
                } else {
                    return False;
                }
            },
            self::RULE_MAX => function ($attribute, $max) {
                if (strlen($this->$attribute) > $max) {
                    return "Maximum length of this field must be $max";
                } else {
                    return False;
                }
            },
            self::RULE_MIN => function ($attribute, $min) {
                if (strlen($this->$attribute) < $min) {
                    return "Maximum length of this field must be $min";
                } else {
                    return False;
                }
            },
            self::RULE_UNIQUE => function ($attribute, $class, $column) {
                $instance = new $class();
                $table = $instance->tableName();
                $sql = "SELECT * FROM $table WHERE $column = ?;";
                if (Application::getInstance()->db->getCount($sql, [$this->{$attribute}]) === 0) {
                    return false;
                }
                return "This field must be unique";
            }
        )[$rule];
    }

    protected function addError($attribute, $error) {
        $this->errors[$attribute][] = $error;
    }

    public function __construct() {
        $this->rClass = new ReflectionClass($this);
        $this->columns = Application::getInstance()->db->tableColumns($this->tableName());
    }

    abstract function tableName() : string;

    public function attributes() {
        return $this->getReflectionObjectName($this->getAttributes());
    }

    public function hasError($attribute) {
        return array_key_exists($attribute, $this->errors);
    }

    public function getError($attribute) {
        if (array_key_exists($attribute, $this->errors)) {
            return $this->errors[$attribute][0];
        }
        return '';
    }

    public function loadData(array $data) {
        $attributes = $this->getReflectionObjectName($this->getAttributes());
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (in_array($key, $attributes)) {
                    $this->$key = $value;
                } // end if
            } // end for
            $this->hasData = true;
            $this->validated = false;
        }// end if
    }

    public function validate() {
        if (!$this->hasData) return false;
        $attributes = $this->getAttributes();
        $methods = $this->getReflectionObjectName($this->getMethods());
        foreach($attributes as $attribute) {
            $rules = @$this->rules()[$attribute->name] ?? [];
            foreach ($rules as $rule) {
                $error = is_array($rule)
                    ? $this->ruleFunctions($rule[0])($attribute->name, ...array_slice($rule, 1))
                    : $this->ruleFunctions($rule)($attribute->name);
                if ($error) {
                    $this->addError($attribute->name, $error);
                }
            }
            $methodName = "val_" . $attribute->name;
            if (in_array($methodName, $methods)) {
                try {
                    $this->$methodName();
                } catch(ValidateException $e) {
                    $this->addError($attribute->name, $e->getMessage());
                } // end try
            } // end if
        } // end for
        $this->validated = true;
        return empty($this->errors);
    }
    public function isValid() {
        return $this->validated && empty($this->errors);

    }
    public function save() {
        if (!$this->isValid()) return false;
        try {
            $attrs = array_intersect($this->attributes(), $this->columns);
            $params = array_map(fn($item) => ":$item", $attrs);
            $stmt = Application::getInstance()->db->pdo->prepare(
                "INSERT INTO ". $this->tableName() ." (". implode(", ", $attrs) .") VALUES (". implode(", ", $params) .");"
            );
            foreach ($attrs as $attr) {
                $stmt->bindValue(":$attr", $this->{$attr});
            }
            $stmt->execute();
            return true;
        } catch(Exception $e) {
            return false;
        }

    }
}
 ?>
