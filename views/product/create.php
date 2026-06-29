<?php

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var array $categories */

$this->title = 'Create Product';
?>
<section class="create-products-page">
    <div class="page-heading">
        <h1>Create Product</h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'submitLabel' => 'Create Product',
        'action' => ['/product/create-page'],
        'cancelUrl' => ['/site/index'],
    ]) ?>
</section>
