<?php
namespace app\core;
class Controller {
    public string $layout = 'base';
    public function setLayout($layout) {
        $this->layout = $layout;
    }
    public function render(...$params)  {
        return Application::getInstance()->router->renderView(...$params);
    }
    public function __get($name) {
        if(array_key_exists($name, get_object_vars(Application::getInstance()))) {
            return Application::getInstance()->{$name};
        }
        return $this->{$name};

    }
}
 ?>
