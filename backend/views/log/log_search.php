<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\logSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['logindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'user_ip') ?>

    <?= $form->field($model, 'action') ?>

    <?= $form->field($model, 'action_type') ?>

    <?php // echo $form->field($model, 'object_name') ?>

    <?php // echo $form->field($model, 'object_id') ?>

    <?php // echo $form->field($model, 'operate_desc') ?>

    <?php // echo $form->field($model, 'operate_time') ?>

    <?php // echo $form->field($model, 'object_old_attr') ?>

    <?php // echo $form->field($model, 'object_new_attr') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
