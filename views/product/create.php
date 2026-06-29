<?php

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var array $categories */

use app\models\Product;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create Product';
?>
<section class="create-products-page">
    <div class="page-heading">
        <h1>Create Product</h1>
    </div>

    <div class="form-panel">
        <?php $form = ActiveForm::begin([
            'action' => ['product/create-page'],
            'method' => 'post',
        ]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'price')->input('number', ['step' => '0.01', 'min' => '0']) ?>

        <?= $form->field($model, 'category_id')->dropDownList($categories, ['prompt' => 'Select Category']) ?>

        <?= $form->field($model, 'status')->dropDownList([
            Product::STATUS_DRAFT => 'Записаинй',
            Product::STATUS_ACTIVE => 'Активний',
            Product::STATUS_ARCHIVED => 'Архівований',
        ]) ?>

        <div class="form-actions">
            <?= Html::a('Cancel', ['/site/index'], ['class' => 'btn btn-outline-secondary']) ?>
            <?= Html::submitButton('Create Product', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</section>
