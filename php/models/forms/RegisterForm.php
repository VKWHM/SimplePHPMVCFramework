<?php
namespace app\models\forms;
use app\core\form\Form;
use app\models\User;
use app\core\ValidateException;

class RegisterForm extends Form {
    public array $username = [
        'label' => 'Username',
        'type' => 'text'
    ];
    public array $email = [
        'label' => 'Email',
        'type' => 'email'
    ];
    public array $password = [
        'label' => 'Password',
        'type' => 'password'
    ];
    public array $confirmPassword = [
        'label' => 'Confirm Password',
        'type' => 'password'
    ];
    public function model() {
        return new User();
    }
    protected function rules() {
        return [
            'username' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, self::RULE_UNIQUE],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 6]],
            'confirmPassword' => [self::RULE_REQUIRED]
        ];
    }
    public function val_confirmPassword() {
        if ($this->password['value'] !== $this->confirmPassword['value']) throw new ValidateException("The password do not match.");
    }
    public static function begin() {
        echo '<form action="" method="POST">';
        return new self;
    }
    public static function end() {
        echo "</form>";
    }
}
