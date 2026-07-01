<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var string $submitLabel */
/** @var array|string $action */
/** @var array|string $cancelUrl */

use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="form-panel">
    <?php $form = ActiveForm::begin([
        'action' => $action,
        'method' => 'post',
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textarea(['rows' => 4]) ?>

    <div class="form-actions">
        <?= Html::a('Cancel', $cancelUrl, ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($submitLabel, ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
