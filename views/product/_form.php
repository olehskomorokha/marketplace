<?php

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var array $categories */
/** @var string $submitLabel */
/** @var array|string $action */
/** @var array|string $cancelUrl */

use app\models\Product;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="form-panel">
    <?php $form = ActiveForm::begin([
        'action' => $action,
        'method' => 'post',
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'price')->input('number', ['step' => '0.01', 'min' => '0']) ?>

    <?= $form->field($model, 'category_id')->dropDownList($categories, ['prompt' => 'Select Category']) ?>

    <?= $form->field($model, 'status')->dropDownList([
        Product::STATUS_DRAFT => 'записаний',
        Product::STATUS_ACTIVE => 'активний',
        Product::STATUS_ARCHIVED => 'архівований',
    ]) ?>

    <div class="form-actions">
        <?= Html::a('Cancel', $cancelUrl, ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($submitLabel, ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
