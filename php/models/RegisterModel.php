<?php

namespace app\models;
use app\core\Model;
use app\core\ValidateException;
class RegisterModel extends Model {
    public string $username;
    public string $email;
    public string $password;
    public string $confirmPassword;
    protected function rules() {
        return [
            'username' => self::RULE_REQUIRED,
            'email' => self::RULE_EMAIL,
            'password' => [self::RULE_MIN, 'min' => 6],
        ];
    }
    public function val_confirmPassword() {
        if ($this->password !== $this->confirmPassword) throw new ValidateException("The password do not match.");
    }
}
?>
