<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\ManagementArea;
use app\models\Farms;
use yii\helpers\ArrayHelper;
use app\models\Insurancecompany;

/* @var $this yii\web\View */
/* @var $model app\models\Insurance */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
.insurance-form h2 {
	text-align: center;
}
</style>


<div class="insurance-form">
<h2><?php echo date('Y');?>年种植业保险申请书</h2>
<?php $farm = Farms::find()->where(['id'=>$farms_id])->one();?>
  <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width="12%" align='right' valign="middle">农场名称</td>
<td width="12%" align='left'><?= $farm['farmname']?></td>
<td align='right'>投保人姓名</td><?php $model->policyholder = $farm['farmername'];?>
<td align='right'><?= $form->field($model, 'policyholder')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
<td align='right'>法人身份证</td>
<?php $model->cardid = $farm['cardid'];?>
<td align='left'><?= $form->field($model, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
<td align='right'>联系电话</td>
<?php $model->telephone = $farm['telephone'];?>
<td align='left'><?= $form->field($model, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    </tr>
<tr>
  <td width=12% align='right' valign="middle">宜农林地面积</td>
  <td align='left'><?= $farm['contractarea']?>亩</td>
  <td width="12%" align='right'>小麦：</td>
  <td width="12%" align='left'><?= $form->field($model, 'wheat')->textInput()->label(false)->error(false) ?></td>
  <td width="12%" align='right'>大豆：</td>
  <td width="12%" align='left'><?= $form->field($model, 'soybean')->textInput()->label(false)->error(false) ?></td>
  <td width="12%" align='right'>其他：</td>
  <td width="17%" align='left'><?= $form->field($model, 'other')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
  <td width=12% align='right' valign="middle">投保面积</td>
  <td align='left'><?= $form->field($model, 'insuredarea')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
  <td align='center'>其中</td>
  <td align='right'>小麦：</td>
  <td align='left'><?= $form->field($model, 'insuredwheat')->textInput()->label(false)->error(false) ?></td>
  <td align='right'>大豆：</td>
  <td align='left'><?= $form->field($model, 'insuredsoybean')->textInput()->label(false)->error(false) ?></td>
  <td align='left'>&nbsp;</td>
</tr>
<tr>
  <td width=12% align='right' valign="middle">保险公司</td>
  <td align='left'><?= $form->field($model, 'company_id')->dropDownList(ArrayHelper::map(Insurancecompany::find()->all(), 'id', 'companynname'),['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
  <td align='left'>&nbsp;</td>
  <td align='left'>&nbsp;</td>
  <td align='left'>&nbsp;</td>
  <td align='left'>&nbsp;</td>
  <td align='left'>&nbsp;</td>
  <td align='left'>&nbsp;</td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '申请' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
$('#insurance-wheat').blur(function(){
	var sum = $(this).val()*1 + $('#insurance-soybean').val()*1 + $('#insurance-other').val()*1;
	var contractarea = <?= $farm['contractarea']?>*1;
	if(sum > contractarea) {
		alert('对不起，已经超过当前农场总面积，请重新填写。');
		$(this).focus();
	}
	$('#insurance-insuredwheat').val($(this).val());
	var insuredarea = $('#insurance-insuredarea').val();
	$('#insurance-insuredarea').val(insuredarea*1 + $(this).val()*1);
});
$('#insurance-soybean').blur(function(){
	var sum = $(this).val()*1 + $('#insurance-wheat').val()*1 + $('#insurance-other').val()*1;
	var contractarea = <?= $farm['contractarea']?>*1;
	if(sum > contractarea) {
		alert('对不起，已经超过当前农场总面积，请重新填写。');
		$(this).focus();
	}
	$('#insurance-insuredsoybean').val($(this).val());
	var insuredarea = $('#insurance-insuredarea').val();
	$('#insurance-insuredarea').val(insuredarea*1 + $(this).val()*1);
});
$('#insurance-other').blur(function(){
	var sum = $(this).val()*1 + $('#insurance-soybean').val()*1 + $('#insurance-wheat').val()*1;
	var contractarea = <?= $farm['contractarea']?>*1;
	if(sum > contractarea) {
		alert('对不起，已经超过当前农场总面积，请重新填写。');
		$(this).focus();
	}
});
</script>