<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\parcelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parcel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['parcelindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'serialnumber') ?>

    <?= $form->field($model, 'temporarynumber') ?>

    <?= $form->field($model, 'unifiedserialnumber') ?>

    <?= $form->field($model, 'powei') ?>

    <?php // echo $form->field($model, 'poxiang') ?>

    <?php // echo $form->field($model, 'podu') ?>

    <?php // echo $form->field($model, 'agrotype') ?>

    <?php // echo $form->field($model, 'stonecontent') ?>

    <?php // echo $form->field($model, 'grossarea') ?>

    <?php // echo $form->field($model, 'piecemealarea') ?>

    <?php // echo $form->field($model, 'netarea') ?>

    <?php // echo $form->field($model, 'figurenumber') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
