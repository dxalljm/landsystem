<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\firepreventionemployeeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="firepreventionemployee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['firepreventionemployeeindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'employee_id') ?>

    <?= $form->field($model, 'is_smoking') ?>

    <?= $form->field($model, 'is_retarded') ?>

    <?= $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
