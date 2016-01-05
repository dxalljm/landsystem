<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

?>
<style type="text/css">
.checkbox {
	color:#FFF;
}
</style> 
<div class="site-index">

<<<<<<< HEAD
 <?php $form = ActiveForm::begin(['id' => 'login-form']); ?><br /><br /><br /><br />
        <table background="images/login3.jpg" height="849" width="1680px" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="690" height="361"></td>
            <td width="288">&nbsp;</td>
            <td width="702">&nbsp;</td>
=======
<div class="site-index">

 <?php $form = ActiveForm::begin(['id' => 'login-form']); ?><br /><br /><br /><br />
        <table background="images/login3.jpg" height="758" width="1500px" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="593" height="369"></td>
            <td width="325">&nbsp;</td>
            <td width="582">&nbsp;</td>
>>>>>>> 45fc62d906003e87654d2a744573963a9ec5e4ec
          </tr>
          <tr>
            <td height="36"> </td>
            <td><?= $form->field($model, 'username', [
        'inputTemplate' => '<div class="input-group"><span class="input-group-addon">用户名:</span>{input}</div>',
])->label('') ?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="35">
           </td>
            <td><?= $form->field($model, 'password', [
        'inputTemplate' => '<div class="input-group"><span class="input-group-addon">密&nbsp;&nbsp;码：</span>{input}</div>',
])->passwordInput()->label('') ?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="36"></td>
            <td><?= $form->field($model, 'rememberMe')->checkbox(['class'=>"rememberme"])->label('记住此用户') ?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="38">&nbsp;</td>
            <td align="center"><?= Html::submitButton('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;录&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
         <?php ActiveForm::end(); ?>
</div>
