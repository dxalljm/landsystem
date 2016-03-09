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
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                </div>
                <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
	
    <?= $form->field($model, 'year')->textInput(['maxlength' => 11])->label('年度设置') ?>    

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
$('#signupform-password_again').blur(function(){
	input = $(this).val();
	//alert(input);
	if($('#signupform-password').val() !== input) {
		alert('两次输入的密码不一致，请重新输入');
		$('#signupform-password_again').focus();
	}
})
</script>