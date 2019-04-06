<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\plantpesticidesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plantpesticides-search">

    <?php $form = ActiveForm::begin([
        'action' => ['plantpesticidesindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'lessee_id') ?>

    <?= $form->field($model, 'pesticides_id') ?>

    <?= $form->field($model, 'pconsumption') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
