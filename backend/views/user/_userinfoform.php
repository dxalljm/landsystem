<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\groups;
use yii\helpers\ArrayHelper;
use app\models\Department;
/* @var $this yii\web\View */
/* @var $model app\models\user */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['readonly' => true]) ?>
    
    <?= $form->field($model, 'realname')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => 255]) ?>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
