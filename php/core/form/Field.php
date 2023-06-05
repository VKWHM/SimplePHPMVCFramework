<?php
namespace app\core\form;

class Field {
    public $model;
    public $name = '';
    public $label = '';
    public $type = 'text';
    public function __construct($model, $name, array $options) {
        $this->model = $model;
        $this->name = $name;
        foreach ($options as $key => $value) {
            $this->$key = $value;
        }
    }
    public function fieldLabel(...$classes) {
        echo sprintf('
            <label for="%s" class="%s">%s</label>
        ',$this->name, join(" ", $classes), $this->label);
    }
    public function fieldInput(...$classes) {
        if ($this->model->hasError($this->name)) {
            $classes[] = 'is-invalid';
            $errorDiv = sprintf('
                <div class="invalid-feedback">
                    %s
                </div>
            ',$this->model->getError($this->name));
        }
        echo sprintf('
            <input id="%s" name="%s" class="%s" type="%s" value="%s">
            %s
        ',$this->name, $this->name, join(" ", $classes), $this->type, $this->model->{$this->name}, @$errorDiv ?? '');

    }
}
 ?>
