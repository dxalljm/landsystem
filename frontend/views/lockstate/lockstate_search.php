<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\LockstateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lockstate-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'systemstate') ?>

    <?= $form->field($model, 'systemstatedate') ?>

    <?= $form->field($model, 'platestate') ?>

    <?= $form->field($model, 'loanconfig') ?>

    <?php // echo $form->field($model, 'loanconfigdate') ?>

    <?php // echo $form->field($model, 'transferconfig') ?>

    <?php // echo $form->field($model, 'transferconfigdate') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
