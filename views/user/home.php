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
                <?= Html::a('Update Profile', ['update-profile'], ['class' => 'btn btn-primary']) ?>
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
                            <h3><?= Html::encode($product->name) ?></h3>
                            <p><?= Html::encode($product->description) ?></p>
                            <strong><?= Html::encode($product->price) ?></strong>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="products-empty">No products loaded.</p>
            <?php endif; ?>
        </aside>
    </div>
</section>
