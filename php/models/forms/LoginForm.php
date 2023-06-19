<?php
namespace app\models\forms;
use app\core\form\Form;
use app\models\User;
use app\core\ValidateException;

class LoginForm extends Form {
    public $user;
    public array $email = [
        'label' => 'Email',
        'type' => 'email'
    ];
    public array $password = [
        'label' => 'Password',
        'type' => 'password'
    ];
    public static function begin() {
        echo '<form action="" method="POST">';
        return new self;
    }
    public static function end() {
        echo "</form>";
    }
    public function model() {
        return new User();
    }
    protected function rules() {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED],
        ];
    }
    public function val_email() {
        $this->user = $this->model->filter(['email' => $this->email['value']]);
        if (!$this->user) {
            throw new ValidateException("User does not exist with this email");
        }
    }
    public function val_password() {
        if ($this->user && !password_verify($this->password['value'], $this->user->password)) {
            throw new ValidateException("Password is incorrect");
        }
    }
    public function login() {
    }
}
