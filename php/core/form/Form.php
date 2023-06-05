<?php
namespace app\core\form;
use ReflectionClass;
abstract class Form {
    abstract static public function begin();
    abstract static public function end();
    public function fields($model) {
        $fields = [];
        foreach($model->attributes() as $attribute) {
            $fields[] = $this->field($model, $attribute);
        }
        return $fields;
    }

    public function field($model, $attribute) {
        return new Field($model, $attribute, $this->$attribute);
    }
}
 ?>
