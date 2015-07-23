<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ManagementArea;
use yii\bootstrap\Dropdown;
use yii\helpers\ArrayHelper;
use dosamigos\datetimepicker\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model app\models\farms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farms-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'management_area')->dropDownList(ArrayHelper::map(ManagementArea::find()->all(),'id','areaname'));?>
	
    <?= $form->field($model, 'spyear')->textInput(['maxlength' => 500])->widget(DateTimePicker::className(), [
    'language' => 'zh-CN',
    'size' => 'xs',
    'template' => '{input}',
    //'pickButtonIcon' => 'glyphicon-calendar',
    'inline' => false,
    'clientOptions' => [
        'startView' => 'decade',
        'minView' => 4,
        //'maxView' => 2,
        'autoclose' => true,
        //'CustomFormat' => 'yyyy',
        'format' => 'yyyy', // if inline = false
        'todayBtn' => false
    ]
]);?>
    
    <?= $form->field($model, 'farmname')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 500]) ?>
	
	<?= $form->field($model, 'zongdi')->textInput(['maxlength' => 500]) ?>
	    
    <?= $form->field($model, 'measure')->textInput() ?>
    	
	<?= $form->field($model, 'contractlife')->textInput(['maxlength' => 500]) ?>

     <?= $form->field($model, 'iscontract')->hiddeninput()->label('') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
