<?php
namespace app\controllers;
use app\core\Controller;
class AuthController extends Controller {
    public function __construct() {
        $this->setLayout('auth');
    }
    public function login() {
        if ($this->getRequest()->isPost()) {
            return "Handle post data";
        }
        return $this->render('login');

    }
    public function register() {
        if ($this->getRequest()->isPost()) {
            return "Handle post data";
        }
        return $this->render('register');
    }
}
 ?>
