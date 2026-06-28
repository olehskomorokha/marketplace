<?php

/** @var yii\web\View $this */

use app\models\Product;
use yii\helpers\Html;

$this->title = 'Products';

$products = Product::find()
    ->with('category')
    ->orderBy(['id' => SORT_DESC])
    ->limit(50)
    ->all();
?>
<section class="products-page">
    <div class="page-heading">
        <h1>Products</h1>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <h2>No products yet</h2>
            <p>Add products through the API or create a product form next.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= Html::encode($product->name) ?></td>
                            <td><?= Html::encode($product->category->name ?? '-') ?></td>
                            <td><?= Html::encode(number_format((float) $product->price, 2)) ?></td>
                            <td><?= Html::encode($product->status) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
