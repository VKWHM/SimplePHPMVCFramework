<?php
namespace app\core;
class Controller {
    public function render(...$params)  {
        return Application::getInstance()->router->renderView(...$params);
    }
}
 ?>
 
