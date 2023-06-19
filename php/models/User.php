<?php

namespace app\models;
use app\core\Model;
class User extends Model {
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_DELETED = 2;
    public function tableName() : string {
        return "users";
    }
    public function primaryKey() : string {
        return "id";
    }
    public int $id;
    public string $created_at;
    public string $username;
    public string $email;
    public string $password;
    public int $status = self::STATUS_INACTIVE;
}
?>
