<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tables */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tables-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tablename')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'Ctablename')->textInput(['maxlength' => 100]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
