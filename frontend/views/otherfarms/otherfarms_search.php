<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\otherfarmsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="otherfarms-search">

    <?php $form = ActiveForm::begin([
        'action' => ['otherfarmsindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'measure') ?>

    <?= $form->field($model, 'describe') ?>

    <?= $form->field($model, 'contractnumber') ?>

    <?php // echo $form->field($model, 'zongdi') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
