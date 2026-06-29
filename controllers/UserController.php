<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

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
            'verbs' => [
                'class' => VerbFilter::class,
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
        return $this->render('home', [
            'user' => Yii::$app->user->identity,
        ]);
    }

    public function actionLoadProducts()
    {
        $user = Yii::$app->user->identity;

        if ($user) {
            $products = $user->products; // Assuming a relation exists in the User model
            return $this->render('home', [
                'user' => $user,
                'products' => $products,
            ]);
        }

        return $this->redirect(['site/login']);
    }
}
