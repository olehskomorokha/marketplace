<?php

namespace app\services;

use Yii;
use app\models\User;
use app\models\ProductPriceHistory;use yii\web\NotFoundHttpException;

class UserService
{
    public function updateUser(User $updatedUser, $id)
    {
        $model = User::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('User not found.');
        }

        $model->username = $updatedUser->username;
        $model->email = $updatedUser->email;
        $model->updated_at = date('Y-m-d H:i:s');

        $model->update(false, ['username', 'email', 'updated_at']);
        return $model;

    }
}