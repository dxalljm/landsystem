<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\plantinputproductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plantinputproduct-search">

    <?php $form = ActiveForm::begin([
        'action' => ['plantinputproductindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'lessee_id') ?>

    <?= $form->field($model, 'father_id') ?>

    <?= $form->field($model, 'son_id') ?>

    <?php // echo $form->field($model, 'inputproduct_id') ?>

    <?php // echo $form->field($model, 'pconsumption') ?>

    <?php // echo $form->field($model, 'zongdi') ?>

    <?php // echo $form->field($model, 'plant_id') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
