<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pesticides */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pesticides-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pesticidename')->textInput(['maxlength' => 500]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
