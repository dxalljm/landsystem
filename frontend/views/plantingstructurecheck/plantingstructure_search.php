<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\plantingstructureSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plantingstructure-search">

    <?php $form = ActiveForm::begin([
        'action' => ['plantingstructureindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'plant_id') ?>

    <?= $form->field($model, 'area') ?>

    <?= $form->field($model, 'inputproduct_id') ?>

    <?= $form->field($model, 'pesticides_id') ?>

    <?php // echo $form->field($model, 'pconsumption') ?>

    <?php // echo $form->field($model, 'goodseed_id') ?>

    <?php // echo $form->field($model, 'zongdi') ?>

    <?php // echo $form->field($model, 'farms_id') ?>

    <?php // echo $form->field($model, 'lease_id') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
