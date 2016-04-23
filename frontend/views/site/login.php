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


 <?php $form = ActiveForm::begin(['id' => 'login-form']); ?><br /><br /><br /><br />
        <table background="images/login3.jpg" height="849" width="1680px" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="690" height="361"></td>
            <td width="288">&nbsp;</td>
            <td width="702">&nbsp;</td>

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
