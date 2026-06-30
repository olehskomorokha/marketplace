<?php

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var array $categories */

use yii\helpers\Html;

$this->title = 'Edit Product';
?>
<section class="update-products-page">
    <div class="page-heading">
        <h1>Edit <?= Html::encode($model->name) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'submitLabel' => 'Save Changes',
        'action' => ['/product/update', 'id' => $model->id],
        'cancelUrl' => ['/user/home'],
    ]) ?>

<!--    <div class="form-panel">-->
<!--        <h2>Update Price</h2>-->
<!---->
<!--        --><?php //= Html::beginForm(['/product/update-price', 'id' => $model->id], 'post') ?>
<!--            --><?php //= Html::input('number', 'newPrice', $model->price, [
//                'class' => 'form-control',
//                'step' => '0.01',
//                'min' => '0',
//            ]) ?>
<!---->
<!--            <div class="form-actions">-->
<!--                --><?php //= Html::submitButton('Update Price', ['class' => 'btn btn-primary']) ?>
<!--            </div>-->
<!--        --><?php //= Html::endForm() ?>
<!--    </div>-->
</section>
