<?php
namespace app\core\form;
use ReflectionClass;
use app\core\ValidateException;

abstract class Form {
    private $rClass;
    protected $hasData = False;
    protected $validated = False;
    protected const RULE_REQUIRED = 'required';
    protected const RULE_EMAIL = 'email';
    protected const RULE_MIN = 'min';
    protected const RULE_MAX = 'max';
    protected const RULE_UNIQUE = 'unique';

    public $errors = [];
    public $model;

    abstract public function model();

    public function __construct() {
        $this->rClass = new ReflectionClass($this);
        $this->model = $this->model();
    }

    private function getAttributes() {
        $attrs =  array_filter($this->rClass->getProperties(), function ($attribute) {
            return $attribute->class === $this->rClass->getName() && $attribute->getType() == "array";
        });
        return array_map(fn($attr) => $attr->name, $attrs);
    }
    private function getMethods() {
        $methods = array_filter($this->rClass->getMethods(), function ($method) {
            return $method->class === $this->rClass->getName();
        });
        return array_map(fn($method) => $method->name, $methods);
    }

    public function dump() {
        $data = array();
        foreach($this->getAttributes() as $attr) {
            $data[$attr] = $this->$attr['value'];
        }
        $this->model->load($data);
        return $this->model;
    }

    abstract protected function rules();
    protected function ruleFunctions($rule) {
        return array(
            self::RULE_REQUIRED => function ($attribute) {
                if ($this->$attribute['value'] === null || !$this->$attribute['value'] || empty($this->$attribute['value'])) {
                    return "This field is required";
                } else {
                    return False;
                }
            },
            self::RULE_EMAIL => function ($attribute) {
                if (!filter_var($this->$attribute['value'], FILTER_VALIDATE_EMAIL)) {
                    return "This field must be valid email address";
                } else {
                    return False;
                }
            },
            self::RULE_MAX => function ($attribute, $max) {
                if (strlen($this->$attribute['value']) > $max) {
                    return "Maximum length of this field must be $max";
                } else {
                    return False;
                }
            },
            self::RULE_MIN => function ($attribute, $min) {
                if (strlen($this->$attribute['value']) < $min) {
                    return "Maximum length of this field must be $min";
                } else {
                    return False;
                }
            },
            self::RULE_UNIQUE => function ($attribute) {
                if (!$this->model->filter([$attribute => $this->$attribute['value']])) {
                    return false;
                } else {
                    return "This field must be unique";
                }
            }
        )[$rule];
    }

    protected function addError($attribute, $error) {
        $this->errors[$attribute][] = $error;
    }
    public function fields() {
        $fields = [];
        foreach($this->getAttributes() as $attribute) {
            $fields[] = $this->field($attribute);
        }
        return $fields;
    }

    public function field($attribute) {
        return new Field($this, $attribute, $this->$attribute);
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
        $attributes = $this->getAttributes();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (in_array($key, $attributes)) {
                    $this->$key["value"] = $value;
                } // end if
            } // end for
            $this->hasData = true;
            $this->validated = false;
        }// end if
    }

    public function validate() {
        if (!$this->hasData) return false;
        $attributes = $this->getAttributes();
        $methods = $this->getMethods();
        foreach($attributes as $attribute) {
            $rules = @$this->rules()[$attribute] ?? [];
            foreach ($rules as $rule) {
                $error = is_array($rule)
                    ? $this->ruleFunctions($rule[0])($attribute, ...array_slice($rule, 1))
                    : $this->ruleFunctions($rule)($attribute);
                if ($error) {
                    $this->addError($attribute, $error);
                }
            }
            $methodName = "val_" . $attribute;
            if (in_array($methodName, $methods)) {
                try {
                    $this->$methodName();
                } catch(ValidateException $e) {
                    $this->addError($attribute, $e->getMessage());
                } // end try
            } // end if
        } // end for
        $this->validated = true;
        return empty($this->errors);
    }
    public function isValid() {
        return $this->validated && empty($this->errors);
    }
}
 ?>
