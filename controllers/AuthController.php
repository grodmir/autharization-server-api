<?php

namespace app\controllers;

use Yii;
use yii\web\{Controller, BadRequestHttpException, UnauthorizedHttpException};
// use yii\rest\ActiveController;
use app\models\{LoginForm, User, Token};
use DateTime;
use DateInterval;
use app\util\AuthChecker;

class AuthController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionMe()
    {
        $user = AuthChecker::check();
        
        return $this->asJson($user->toResponse());
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;

        $model = LoginForm::buildFromPostData($request->post());

        if (!$model->validate()) {
            throw new BadRequestHttpException();
        }

        $user = User::find()->where([
            'email' => $model->email,
        ])->one();

        if ($user == null) {
            throw new UnauthorizedHttpException();
        }

        if (!Yii::$app->getSecurity()->validatePassword($model->password, $user->password)) {
            throw new BadRequestHttpException();
        }

        $tokens = Token::find()->where(['user_id' => $user->id])->all();
        foreach ($tokens as $token) {
            $token->delete();
        }

        $token = Token::build(
            $user->id,
            Yii::$app->security->generateRandomString(),
            (new DateTime())->add(DateInterval::createFromDateString('1 day')),
            new DateTime()
        );
        $token->save();
        return $token->token;
    }
}
