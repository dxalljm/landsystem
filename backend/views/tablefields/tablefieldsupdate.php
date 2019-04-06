<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\tablefields */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '表项管理', 'url' => ['tablefieldsindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['tablefieldsview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="tablefields-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
