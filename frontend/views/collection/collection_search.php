<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\collectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collection-search">

    <?php $form = ActiveForm::begin([
        'action' => ['collectionindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'payyear') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'billingtime') ?>

    <?= $form->field($model, 'amounts_receivable') ?>

    <?php // echo $form->field($model, 'real_income_amount') ?>

    <?php // echo $form->field($model, 'ypayyear') ?>

    <?php // echo $form->field($model, 'ypayarea') ?>

    <?php // echo $form->field($model, 'ypaymoney') ?>

    <?php // echo $form->field($model, 'owe') ?>

    <?php // echo $form->field($model, 'isupdate') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
