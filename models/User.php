<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use DateTime;

class User extends ActiveRecord
{
    public function toResponse()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
        ];
    }

    public static function build(string $username, string $email, string $password, bool $role)
    {
        $instance = new User();
        $instance->username = $username;
        $instance->email = $email;
        $instance->password = Yii::$app->getSecurity()->generatePasswordHash($password);
        $instance->role = $role;
        $instance->created_at = (new DateTime())->format('Y-m-d H:i:s');
        $instance->updated_at = (new DateTime())->format('Y-m-d H:i:s');

        return $instance;
    }

    public function updateData(string $username, string $email, string $password, bool $role)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($password);
        $this->role = $role;
        $this->updated_at = (new DateTime())->format('Y-m-d H:i:s');
    }
}
