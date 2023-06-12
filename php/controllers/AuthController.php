<?php
namespace app\controllers;
use app\core\Controller;
use app\models\RegisterModel;
class AuthController extends Controller {
    public function __construct() {
        $this->setLayout('auth');
    }
    public function login() {
        if ($this->request->isPost()) {
            return "Handle post data";
        }
        return $this->render('login');

    }
    public function register() {
        $registerModel = new RegisterModel();

        if ($this->request->isPost()) {
            $registerModel->loadData($this->request->body());
            if ($registerModel->validate() && $registerModel->save()) {
                $this->session->setFlash("success", "Thanks for registering");
                $this->response->redirect("/");
            } // end if
        } // end if
        return $this->render('register', ['model' => $registerModel]);
    }
}
 ?>
