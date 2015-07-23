<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Goodseed */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goodseed-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'plant_id')->textInput() ?>

    <?= $form->field($model, 'plant_model')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'planting_area')->textInput() ?>

    <?= $form->field($model, 'plant_measurearea')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
