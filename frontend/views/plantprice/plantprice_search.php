<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\plantpriceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plant-price-search">

    <?php $form = ActiveForm::begin([
        'action' => ['plantpriceindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'plant') ?>

    <?= $form->field($model, 'price') ?>

    <?= $form->field($model, 'years') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
