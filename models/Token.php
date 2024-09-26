<?php

namespace app\models;

use yii\db\ActiveRecord;
use DateTime;

class Token extends ActiveRecord
{
    public static function build(int $userId, string $token, DateTime $expiresAt, DateTime $createdAt)
    {
        $instance = new Token();
        $instance->user_id = $userId;
        $instance->token = $token;
        $instance->expires_at = $expiresAt->format('Y-m-d H:i:s');
        $instance->created_at = $createdAt->format('Y-m-d H:i:s');

        return $instance;
    }

    public function getExpiresAt()
    {
        return new DateTime($this->expires_at);
    }
}
