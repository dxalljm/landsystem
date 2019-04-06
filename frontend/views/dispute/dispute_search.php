<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\disputeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dispute-search">

    <?php $form = ActiveForm::begin([
        'action' => ['disputeindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'create_at') ?>

    <?= $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
