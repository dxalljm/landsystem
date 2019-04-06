<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\breedinfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="breedinfo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['breedinfoindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'breed_id') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'basicinvestment') ?>

    <?= $form->field($model, 'housingarea') ?>

    <?php // echo $form->field($model, 'breedtype_id') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
