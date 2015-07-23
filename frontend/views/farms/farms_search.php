<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\farmsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farms-search">

    <?php $form = ActiveForm::begin([
        'action' => ['farmsindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farmname') ?>

    <?= $form->field($model, 'address') ?>

    <?= $form->field($model, 'management_area') ?>

    <?= $form->field($model, 'spyear') ?>

    <?php // echo $form->field($model, 'iscontract') ?>

    <?php // echo $form->field($model, 'contractlife') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
