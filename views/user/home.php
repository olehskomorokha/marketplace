<?php

/** @var yii\web\View $this */
/** @var app\models\User $user */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'User Profile';
$this->registerCssFile(Url::to('@web/css/user-home.css'), ['depends' => [\app\assets\AppAsset::class]]);

?>
<section class="home-page">
    <div class="page-heading">
        <h1><?= Html::encode($user->username) ?></h1>
    </div>

    <div class="user-home-layout">
        <div class="user-profile-panel">
            <h2>Profile</h2>

            <dl class="profile-details">
                <div>
                    <dt>ID</dt>
                    <dd><?= Html::encode($user->id) ?></dd>
                </div>
                <div>
                    <dt>Username</dt>
                    <dd><?= Html::encode($user->username) ?></dd>
                </div>
                <div>
                    <dt>Email</dt>
                    <dd><?= Html::encode($user->email) ?></dd>
                </div>
            </dl>

            <div class="profile-actions">
                <?= Html::a('Update Profile', ['user/update', 'id' => $user->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Change Password', ['change-password'], ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::a('Logout', ['site/logout'], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'method' => 'post',
                    ],
                ]) ?>
            </div>

        </div>

        <aside class="loaded-products">
            <div class="products-header">
                <h2>User Products</h2>
                <span><?= count($user->products) ?></span>
            </div>

            <?php if (!empty($user->products)): ?>
                <div class="user-product-list">
                    <?php foreach ($user->products as $product): ?>
                        <article class="user-product-item">
                            <?php
                            $deleteModalId = 'delete-product-modal-' . $product->id;
                            ?>
                            <h3><?= Html::encode($product->name) ?></h3>
                            <p><?= Html::encode($product->description) ?></p>
                            <strong><?= Html::encode($product->price) ?></strong>

                            <div class="product-hover-menu">
                                <div class="product-hover-details">
                                    <span><?= Html::encode($product->status) ?></span>
                                    <strong><?= Html::encode($product->category ? $product->category->name : 'No category') ?></strong>
                                    <small>ID: <?= Html::encode($product->id) ?></small>
                                </div>
                                <?= Html::a('Edit', ['/product/update', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
                                <?= Html::button('Delete', [
                                    'class' => 'btn btn-outline-danger',
                                    'data-bs-toggle' => 'modal',
                                    'data-bs-target' => '#' . $deleteModalId,
                                ]) ?>
                            </div>

                        </article>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($user->products as $product): ?>
                    <?php $deleteModalId = 'delete-product-modal-' . $product->id; ?>
                    <div class="modal fade" id="<?= Html::encode($deleteModalId) ?>" tabindex="-1" aria-labelledby="<?= Html::encode($deleteModalId) ?>-label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="<?= Html::encode($deleteModalId) ?>-label">Delete product?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete "<?= Html::encode($product->name) ?>"?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <?= Html::beginForm(['/product/delete', 'id' => $product->id], 'post') ?>
                                        <?= Html::submitButton('OK', ['class' => 'btn btn-danger']) ?>
                                    <?= Html::endForm() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="products-empty">No products loaded.</p>
            <?php endif; ?>
        </aside>
    </div>
</section>
