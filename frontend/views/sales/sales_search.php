<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\salesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-search">

    <?php $form = ActiveForm::begin([
        'action' => ['salesindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'planting_id') ?>

    <?= $form->field($model, 'whereabouts') ?>

    <?= $form->field($model, 'volume') ?>

    <?= $form->field($model, 'price') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
