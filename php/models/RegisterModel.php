<?php

namespace app\models;
use app\core\Model;
use app\core\ValidateException;
class RegisterModel extends Model {
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_DELETED = 2;

    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';
    public int $status = self::STATUS_INACTIVE;

    public function tableName() : string {
        return "users";
    }
    protected function rules() {
        return [
            'username' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class, 'column' => 'email']],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 6]],
            'confirmPassword' => [self::RULE_REQUIRED]
        ];
    }
    public function save() {
        if ($this->isValid()) {
            $this->password = password_hash($this->confirmPassword, PASSWORD_DEFAULT);
            if (!parent::save()) {
                $this->password = $this->confirmPassword;
                return false;
            }
            return true;
        }
        return false;
    }
    public function val_confirmPassword() {
        if ($this->password !== $this->confirmPassword) throw new ValidateException("The password do not match.");
    }
}
?>
