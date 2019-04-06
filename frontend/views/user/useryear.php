<?php
use dosamigos\datetimepicker\DateTimePicker;
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
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                </div>
                <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'year')->textInput(['maxlength' => 11])->label('年度设置')->widget(
        DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false,
    	'language'=>'zh-CN',
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'startView' => 4,
        	'minView' => 4,
            'format' => 'yyyy'
        ]
]);   ?>
	<?= $form->field($model, 'autoyear')->checkbox(['id'=>'isAuto'])->label(false)?>  
    <div class="form-group">
        <?= Html::submitButton('更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
$('#isAuto').click(function(){
	//alert($(this).is(":checked"));
	if($(this).is(":checked")==true) {
		$('#user-year').attr('disabled', 'disabled');
		var d = new Date();
		$('#user-year').val(d.getFullYear());
	} else {
		$('#user-year').removeAttr('disabled');
		$('#user-year').val(<?= $model->year?>);
	}
});
$('#signupform-password_again').blur(function(){
	input = $(this).val();
	//alert(input);
	if($('#signupform-password').val() !== input) {
		alert('两次输入的密码不一致，请重新输入');
		$('#signupform-password_again').focus();
	}
})
</script>