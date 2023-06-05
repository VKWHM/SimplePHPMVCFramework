<?php
namespace app\models\forms;
use app\core\form\Form;

class RegisterForm extends Form {
    public array $username = [
        'label' => 'Username',
        'type' => 'text'
    ];
    public $email = [
        'label' => 'Email',
        'type' => 'email'
    ];
    public $password = [
        'label' => 'Password',
        'type' => 'password'
    ];
    public $confirmPassword = [
        'label' => 'Confirm Password',
        'type' => 'password'
    ];
    public static function begin() {
        echo '<form action="" method="POST">';
        return new self;
    }
    public static function end() {
        echo "</form>";
    }
}
