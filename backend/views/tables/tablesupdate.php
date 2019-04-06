<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tables */

$this->title = '数据库表管理';
$this->params['breadcrumbs'][] = ['label' => '数据库表管理', 'url' => ['tablesindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['tablesview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="tables-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
