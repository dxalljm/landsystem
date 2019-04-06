<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Verisonlist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="verisonlist-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ver')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'update')->textarea(['rows' => 6]) ?>
    <?php
        if(empty($model->update_at)) {
            $model->update = date('Y-m-d');
        }
    ?>
    <?= $form->field($model, 'update_at')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
