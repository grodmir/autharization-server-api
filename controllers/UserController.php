<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\util\AuthChecker;
use yii\web\{ForbiddenHttpException, BadRequestHttpException, NotFoundHttpException};
use app\models\User;
use app\models\RegisterForm;

class UserController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionGetUsers()
    {
        $user = AuthChecker::check();
        if (!$user->role) {
            throw new ForbiddenHttpException();
        }

        $users = User::find()->all();
        $users = array_map(function ($user) {
            return $user->toResponse();
        }, $users);
        
        return $this->asJson($users);
    }

    public function actionPostUsers()
    {
        $user = AuthChecker::check();
        if (!$user->role) {
            throw new ForbiddenHttpException();
        }

        $request = Yii::$app->request;

        $model = RegisterForm::buildFromPostData($request->post());

        if (!$model->validate()) {
            throw new BadRequestHttpException();
        }

        $user = User::build($model->username, $model->email, $model->password, $model->role);
        $user->save();

        return $this->asJson($user->toResponse());
    }

    public function actionPatchUser(int $id)
    {
        $user = AuthChecker::check();
        if (!$user->role) {
            throw new ForbiddenHttpException();
        }

        $user = User::findOne($id);

        if ($user === null) {
            throw new NotFoundHttpException();
        }

        $request = Yii::$app->request;

        $model = RegisterForm::buildFromPostData($request->post());

        if (!$model->validate()) {
            throw new BadRequestHttpException();
        }

        $user->updateData($model->username, $model->email, $model->password, $model->role);
        $user->save();
        return $this->asJson($user->toResponse());
    }

    public function actionDeleteUser(int $id)
    {
        $user = AuthChecker::check();
        if (!$user->role) {
            throw new ForbiddenHttpException();
        }

        $user = User::findOne($id);

        if ($user === null) {
            throw new NotFoundHttpException();
        }
        
        $user->delete();
    }
}
