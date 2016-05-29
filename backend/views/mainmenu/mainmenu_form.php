<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\mainmenu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mainmenu-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'sort')->textInput() ?>
	
	<?= $form->field($model, 'typename')->dropDownList(['主页导航','板块','业务菜单']) ?>
	
    <?= $form->field($model, 'menuname')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'menuurl')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'dropdown')->textInput(['maxlength' => 500]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
