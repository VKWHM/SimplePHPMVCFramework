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
    public function getRequest() {
        return Application::getInstance()->request;
    }
    public function getResponse() {
        return Application::getInstance()->response;
    }
    public function getRouter() {
        return Application::getInstance()->router;
    }
}
 ?>
