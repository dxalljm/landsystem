<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tables */

$this->title = '创建数据库表';
$this->params['breadcrumbs'][] = ['label' => '数据库表管理', 'url' => ['tablesindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tables-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
