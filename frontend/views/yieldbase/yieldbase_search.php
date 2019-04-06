<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\yieldbaseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="yieldbase-search">

    <?php $form = ActiveForm::begin([
        'action' => ['yieldbaseindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'plant_id') ?>

    <?= $form->field($model, 'yield') ?>

    <?= $form->field($model, 'year') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
