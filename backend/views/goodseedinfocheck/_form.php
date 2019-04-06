<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Goodseedinfocheck */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goodseedinfocheck-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'farms_id')->textInput() ?>

    <?= $form->field($model, 'management_area')->textInput() ?>

    <?= $form->field($model, 'lease_id')->textInput() ?>

    <?= $form->field($model, 'planting_id')->textInput() ?>

    <?= $form->field($model, 'plant_id')->textInput() ?>

    <?= $form->field($model, 'goodseed_id')->textInput() ?>

    <?= $form->field($model, 'zongdi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area')->textInput() ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'total_area')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
