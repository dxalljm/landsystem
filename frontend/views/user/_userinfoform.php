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
	<?php $model->username = Yii::$app->user->identity->username?>
    <?= $form->field($model, 'username')->textInput(['readonly' => true])->label('用户名') ?>
    <?php if(!empty(Yii::$app->user->identity->realname)) $model->realname = Yii::$app->user->identity->realname;?>
    <?= $form->field($model, 'realname')->textInput(['maxlength' => 255])->label('真实姓名') ?>
	
	<?= $form->field($model, 'password')->passwordInput()->label('新密码') ?>

    <?= $form->field($model, 'password_again')->passwordInput()->label('密码确认') ?>

    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

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