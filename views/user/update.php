<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */

use yii\helpers\Html;

$this->title = 'Edit settings';
?>
 *
<section class="update-user-page">
    <div class="page-heading">
        <h1>Edit <?= Html::encode($model->username) ?></h1>
</div>

<?= $this->render('_updateUserForm', [
    'model' => $model,
    'submitLabel' => 'Save Changes',
    'action' => ['/user/update', 'id' => $model->id],
    'cancelUrl' => ['/user/home'],
]) ?>

</section>