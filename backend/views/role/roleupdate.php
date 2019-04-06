<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */

$this->title = '更新角色: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['roleindex']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['roleview', 'id' => $model->name]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="auth-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
