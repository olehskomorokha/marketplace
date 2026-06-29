<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\User;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'login' => ['post'],
                    'logout' => ['post'],
                    'view' => ['get'],
                ],
            ],
        ];
    }

   public function actionHome()
    {
        return $this->render('home');
    }
}