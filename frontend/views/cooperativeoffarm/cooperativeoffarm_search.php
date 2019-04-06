<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\cooperativeoffarmSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cooperative-of-farm-search">

    <?php $form = ActiveForm::begin([
        'action' => ['cooperativeoffarmindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'cia') ?>

    <?= $form->field($model, 'proportion') ?>

    <?= $form->field($model, 'bonus') ?>

    <?php // echo $form->field($model, 'cooperative_id') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
