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
</section>
