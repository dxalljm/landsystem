<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

?>

<div class="site-index">

  <div class="jumbotron">
    <h1>岭南管理委员会土地管理系统</h1>
 <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><?= $form->field($model, 'username', [
        'inputTemplate' => '<div class="input-group"><span class="input-group-addon">用户名:</span>{input}</div>',
])->label('') ?></td>
          </tr>
          <tr>
            <td> <?= $form->field($model, 'password', [
        'inputTemplate' => '<div class="input-group"><span class="input-group-addon">密&nbsp;&nbsp;码：</span>{input}</div>',
])->passwordInput()->label('') ?></td>
          </tr>
          <tr>
            <td> <?= $form->field($model, 'rememberMe')->checkbox()->label('记住此用户') ?>
           </td>
          </tr>
          <tr>
            <td> <?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?></td>
          </tr>
        </table>
        <br />
         如果您忘记了密码可以 <?= Html::a('点击这里', ['site/request-password-reset']) ?>.
         <?php ActiveForm::end(); ?>
        
    </div>
    </div>
</div>
