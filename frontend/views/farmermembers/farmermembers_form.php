<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Farmermembers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farmermembers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'farmer_id')->textInput() ?>

    <?= $form->field($model, 'relationship')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'membername')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'cardid')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?>
	
	<?= $form->field($model, 'isupdate')->hiddenInput() ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
