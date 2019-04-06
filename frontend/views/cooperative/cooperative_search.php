<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\cooperativeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cooperative-search">

    <?php $form = ActiveForm::begin([
        'action' => ['cooperativeindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'cooperativename') ?>

    <?= $form->field($model, 'cooperativetype') ?>

    <?= $form->field($model, 'directorname') ?>

    <?php // echo $form->field($model, 'peoples') ?>

    <?php // echo $form->field($model, 'finance') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
