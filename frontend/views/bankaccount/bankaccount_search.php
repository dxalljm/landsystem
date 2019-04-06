<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\bankaccountSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-account-search">

    <?php $form = ActiveForm::begin([
        'action' => ['bankaccountindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'accountnumber') ?>

    <?= $form->field($model, 'farmer_id') ?>

    <?= $form->field($model, 'bank') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
