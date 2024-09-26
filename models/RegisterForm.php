<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $role;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'role'], 'required'],
            ['email', 'email'],
            [['username', 'email', 'password'], 'string'],
            ['role', 'boolean'],
        ];
    }

    public static function buildFromPostData($data)
    {
        $instance = new RegisterForm();
        $instance->username = $data['username'] ?? null;
        $instance->email = $data['email'] ?? null;
        $instance->password = $data['password'] ?? null;
        $instance->role = $data['role'] ?? null;

        return $instance;
    }
}
