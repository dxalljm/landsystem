<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MachineSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="machine-search">

    <?php $form = ActiveForm::begin([
        'action' => ['machineindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'productname') ?>

    <?= $form->field($model, 'implementmodel') ?>

    <?= $form->field($model, 'filename') ?>

    <?= $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'enterprisename') ?>

    <?php // echo $form->field($model, 'parameter') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
