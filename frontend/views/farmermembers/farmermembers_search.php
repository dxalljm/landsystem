<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\farmermembersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farmermembers-search">

    <?php $form = ActiveForm::begin([
        'action' => ['farmermembersindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farmer_id') ?>

    <?= $form->field($model, 'relationship') ?>

    <?= $form->field($model, 'membername') ?>

    <?= $form->field($model, 'cardid') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
