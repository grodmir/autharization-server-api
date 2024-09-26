<?php

namespace app\util;

use Yii;
use yii\web\UnauthorizedHttpException;
use app\models\{User, Token};
use DateTime;

class AuthChecker 
{
    public static function check()
    {
        $headers = Yii::$app->request->headers;

        if (!$headers->has('Authorization')) {
            throw new UnauthorizedHttpException();
        }

        $authorizationHeader = $headers->get('Authorization');
        $splittedData = explode(' ', $authorizationHeader);

        if (count($splittedData) !== 2 || $splittedData[0] !== 'Bearer') {
            throw new UnauthorizedHttpException();
        }

        $token = Token::find()->where([
            'token' => $splittedData[1],
        ])->one(); 

        if ($token === null || $token->getExpiresAt() < new DateTime()) {
            throw new UnauthorizedHttpException();
        }

        $user = User::findOne($token->user_id);

        return $user;
    }
}
