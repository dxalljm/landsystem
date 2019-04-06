<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */

$this->title = '更新权限: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => ['permissionindex']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['permissionview', 'id' => $model->name]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="auth-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
