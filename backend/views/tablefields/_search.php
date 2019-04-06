<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\tablefieldsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tablefields-search">

    <?php $form = ActiveForm::begin([
        'action' => ['tablefieldsindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tables_id') ?>

    <?= $form->field($model, 'fields') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'cfields') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
