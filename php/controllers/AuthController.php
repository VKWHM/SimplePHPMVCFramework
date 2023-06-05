<?php
namespace app\controllers;
use app\core\Controller;
use app\models\RegisterModel;
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
        $registerModel = new RegisterModel();
        if ($this->getRequest()->isPost()) {
            $registerModel->loadData($this->getRequest()->body());
            if ($registerModel->validate()) {
                return 'Success';
            } // end if
        } // end if
        return $this->render('register', ['model' => $registerModel]);
    }
}
 ?>
