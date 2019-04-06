<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Plant;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Plant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plant-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $listdata = Plant::find()->where(['father_id'=>0]);?>
    <?php $plant = Plant::findAll(['father_id'=>1]);?>
    <?= $form->field($model, 'father_id')->dropDownList(ArrayHelper::map($plant, 'id', 'typename')) ?>
    
    <?= $form->field($model, 'typename')->textInput(['maxlength' => 500]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
