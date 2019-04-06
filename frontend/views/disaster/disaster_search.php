<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\disasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="disaster-search">

    <?php $form = ActiveForm::begin([
        'action' => ['disasterindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'disastertype_id') ?>

    <?= $form->field($model, 'disasterarea') ?>

    <?= $form->field($model, 'disasterplant') ?>

    <?php // echo $form->field($model, 'insurancearea') ?>

    <?php // echo $form->field($model, 'yieldreduction') ?>

    <?php // echo $form->field($model, 'socmoney') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
