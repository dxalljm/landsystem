<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

?>
<style type="text/css">
.checkbox {
	color:#000;
}
</style>
<br><br><br>
<div class="site-index">


 <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <table  height="100%" width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image: url('images/userback.png');background-repeat:no-repeat;background-position:center">
          <tr>
            <td width="690" height="0"></td>
            <td width="288">&nbsp;</td>
            <td width="702">&nbsp;</td>

          </tr>
          <tr>
            <td height="36"> </td>
            <td><?= $form->field($model, 'username', [
        'inputTemplate' => '<div class="input-group"><span class="input-group-addon" style="color: #FFF">用户名:</span>{input}</div>',
])->label('') ?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="35">
           </td>
            <td><?= $form->field($model, 'password', [
        'inputTemplate' => '<div class="input-group"><span class="input-group-addon" style="color: #FFF">密&nbsp;&nbsp;码：</span>{input}</div>',
])->passwordInput()->label('') ?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="38">&nbsp;</td>
            <td align="center"><?= Html::submitButton('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;录&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', ['class' => 'btn btn-success', 'name' => 'login-button']) ?></td>
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
