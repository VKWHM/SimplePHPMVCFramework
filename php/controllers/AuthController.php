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
        if ($this->getRequest()->isPost()) {
            $registerModel = new RegisterModel();
            $registerModel->loadData($this->getRequest()->body());
            if (!$registerModel->validate()) {
                foreach ($registerModel->errors as $field => $errors) {
                    echo $field . '<br>';
                    foreach($errors as $error) {
                        echo $error;
                    } // end for
                } // end for
            } // end if
        } // end if
        return $this->render('register');
    }
}
 ?>
