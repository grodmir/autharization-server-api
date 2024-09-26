<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
        ];
    }

    public static function buildFromPostData($data)
    {
        $instance = new LoginForm();
        $instance->email = $data['email'] ?? null;
        $instance->password = $data['password'] ?? null;

        return $instance;
    }
}
