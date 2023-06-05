<?php
namespace app\core;
use ReflectionClass;
use Reflectionattribute;
use Exception;
class ValidateException extends Exception {}
abstract class Model {
    private $rClass;
    private $methods;

    protected $hasData;
    protected const RULE_REQUIRED = 'required';
    protected const RULE_EMAIL = 'email';
    protected const RULE_MIN = 'min';
    protected const RULE_MAX = 'max';

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
            }
        )[$rule];
    }

    protected function addError($attribute, $error) {
        $this->errors[$attribute][] = $error;
    }

    public function __construct() {
        $this->rClass = new ReflectionClass($this);
    }

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
        return empty($this->errors);
    }
}
 ?>
