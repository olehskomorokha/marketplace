<?php

namespace app\controllers;

use app\models\User;
use app\services\UserService;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['home'],
                'rules' => [
                    [
                        'actions' => ['home'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionHome()
    {
        return $this->render('home', [
            'user' => Yii::$app->user->identity,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = User::findOne($id);
        $updatedModel = Yii::$app->request->post();

        if ($model->load($updatedModel))
        {
            $modelService = new UserService();
            $modelService->updateUser($model, $id);
            if ($updatedModel === false) {
                Yii::$app->session->setFlash('error', 'user was not updated.');

                return $this->render('update', [
                    'model' => $model
                ]);
            }
            Yii::$app->session->setFlash('success', 'user updated.');
            return $this->redirect(['/user/home']);
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }
}
