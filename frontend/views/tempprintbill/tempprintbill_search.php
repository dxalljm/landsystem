<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\tempprintbillSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tempprintbill-search">

    <?php $form = ActiveForm::begin([
        'action' => ['tempprintbillindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farmername') ?>

    <?= $form->field($model, 'standard') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'amountofmoney') ?>

    <?php // echo $form->field($model, 'bigamountofmoney') ?>

    <?php // echo $form->field($model, 'nonumber') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
