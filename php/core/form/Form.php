<?php
namespace app\core\form;
use ReflectionClass;
abstract class Form {
    abstract static public function begin();
    abstract static public function end();
    private $rClass;
    private function getAttributes() {
        return array_filter($this->rClass->getProperties(), function ($attribute) {
            return $attribute->class === $this->rClass->getName();
        });
    }
    public function __construct() {
        $this->rClass = new ReflectionClass($this);
    }
    public function fields($model) {
        $fields = [];
        foreach($this->getAttributes() as $attribute) {
            $fields[] = $this->field($model, $attribute->name);
        }
        return $fields;
    }

    public function field($model, $attribute) {
        return new Field($model, $attribute, $this->$attribute);
    }
}
 ?>
