<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Tempprintbill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tempprintbill-form">

    <?php $form = ActiveFormrdiv::begin(); ?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3" align="center">大兴安岭岭南宜农林地承包费专用票据</td>
    </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('Y').'年'.date('m').'月'.date('d').'日'; ?></td>
    <td align="right">NO:</td><?php $model->nonumber = '201500000001'?>
    <td width="30%"><?= $form->field($model, 'nonumber')->textInput(['disabled'=>'disabled'])->label(false)->error(false) ?></td>
  </tr>
</table>
<table width="100%" border="1">
  <tr>
    <td width="14%" height="31" align="center">&nbsp;收款单位（缴款人）      </td>
    <td height="31" colspan="5"><?= $form->field($model, 'farmername')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    </tr>
  <tr>
    <td height="31" colspan="2" align="center">收费项目</td>
    <td width="13%" align="center">单位</td>
    <td width="18%" align="center">数量</td>
    <td width="17%" align="center">标准</td>
    <td width="21%" align="center">金额</td>
  </tr>
  <tr>
    <td height="23" colspan="2" align="center" valign="middle">      宜农林地承包费</td>
    <td align="center" valign="middle">      元/亩<br /></td>
    <td align="center" valign="middle"><?= $form->field($model, 'number')->textInput()->label(false)->error(false) ?></td>
    <td align="center" valign="middle"><?= $form->field($model, 'standard')->textInput(['value'=>32,'disabled'=>'disabled'])->label(false)->error(false) ?></td>
    <td align="center" valign="middle"><?= $form->field($model, 'amountofmoney')->textInput(['disabled'=>'disabled'])->label(false)->error(false) ?></td>
  </tr>
  <tr>
    <td align="center">金额合计（大写）</td>
    <td colspan="3"><?= $form->field($model, 'bigamountofmoney')->textInput(['disabled'=>'disabled'])->label(false)->error(false) ?></td>
    <td align="right">￥：</td>
    <td><?= html::textInput('money','',['id'=>'viewmoney','class'=>'form-control','disabled'=>'disabled']) ?></td>
  </tr>
  <tr>
    <td colspan="6">备注：</td>
    </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="60%">收款单位（盖章）大兴安岭林业管理局岭南管委会</td>
    <td width="13%">收款人：王丽静</td>
    <td width="27%" align="right">（微机专用 手填无效）</td>
  </tr>
</table>
<br/>
<div class="form-group">
      <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

    <?php ActiveFormrdiv::end(); ?>
<script>
$('#tempprintbill-number').keyup(function(event){
	input = $(this).val();
	result = input*$('#tempprintbill-standard').val();
	$('#tempprintbill-amountofmoney').val(result);
	$('#viewmoney').val(result);
});
</script>
</div>
