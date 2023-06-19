<?php
namespace app\controllers;
use app\core\Controller;
use app\models\forms\RegisterForm;
use app\models\forms\LoginForm;
class AuthController extends Controller {
    public function __construct() {
        $this->setLayout('auth');
    }
    public function login() {
        $loginForm = new LoginForm();
        if ($this->request->isPost()) {
            $loginForm->loadData($this->request->body());
            if ($loginForm->validate() && $loginForm->login()) {
                $this->response->redirect("/");
            }
        }
        return $this->render('login', ['form' => $loginForm]);

    }
    public function register() {
        $registerForm = new RegisterForm();
        if ($this->request->isPost()) {
            $registerForm->loadData($this->request->body());
            if ($registerForm->validate()) {
                $user = $registerForm->dump();
                $user->password = password_hash($user->password, PASSWORD_DEFAULT);
                $user->save();
                $this->session->setFlash("success", "Thanks for registering");
                $this->response->redirect("/");
            } // end if
        } // end if
        return $this->render('register', ['form' => $registerForm]);
    }
}
 ?>
