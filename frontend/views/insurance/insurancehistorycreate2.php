<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\ManagementArea;
use app\models\Farms;
use yii\helpers\ArrayHelper;
use app\models\Insurancecompany;
use app\models\Insurancedck;
use app\models\Insurance;
use app\models\User;
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
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                    <div class="box-body">
<h2><?php echo date('Y');?>年种植业保险申请书</h2>
  <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
    <tr>
        <td width="12%" align='right' valign="middle">管理区</td>
        <td align='left' valign="middle"><?= $form->field($model,'management_area')->dropDownList(ManagementArea::getAreaname())->label(false)->error(false)?></td>
        <td align='right' valign="middle"></td>
        <td align='right' valign="middle"></td>
        <td align='right' valign="middle"></td>
        <td align='left' valign="middle"></td>
        <td align='right' valign="middle"></td>
        <td align='left' valign="middle"></td>
    </tr>
		<tr>
<td width="12%" align='right' valign="middle">农场名称</td>
<td align='left' valign="middle"><?= $form->field($model,'farmname')->textInput()->label(false)->error(false)?></td>
<td align='right' valign="middle">法人姓名</td>
<td align='right' valign="middle"><?= $form->field($model,'farmername')->textInput()->label(false)->error(false)?></td>
<td align='right' valign="middle">合同编号</td>
<td align='left' valign="middle"><?= $form->field($model,'contractnumber')->label(false)->error(false)?></td>
<td align='right' valign="middle">宜农林地面积</td>
<td align='left' valign="middle"><?= $form->field($model,'contractarea')->label(false)->error(false)?>
  </td>
</tr>
    <tr>
          <td align='right' valign="middle">被保险人姓名</td><?php echo Html::hiddenInput('overarea',0,['id'=>'OverArea'])?>
          <td width="12%" align='left' valign="middle"><?= $form->field($model, 'policyholder')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
          <td align='right' valign="middle">被保险人身份证</td>
          <td colspan="3" align='left' valign="middle"><?= $form->field($model, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
          <td align='right' valign="middle">联系电话</td>
          <td align='left' valign="middle"><?= $form->field($model, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    </tr>
    <tr>
  <td width=12% solspan="2" align='right' valign="middle">种植结构</td>
  <td width="12%" align='left' valign="middle"><?= $form->field($model, 'insurancearea')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
  <td width="12%" align='right' valign="middle">小麦：</td>
  <td width="12%" align='left' valign="middle"><?= $form->field($model, 'wheat')->textInput()->label(false)->error(false) ?></td>
  <td width="12%" align='right' valign="middle">大豆：</td>
  <td width="12%" align='left' valign="middle"><?= $form->field($model, 'soybean')->textInput()->label(false)->error(false) ?></td>
  <td width="17%" align='right' valign="middle">其它：</td>
  <td valign="middle"><?= $form->field($model, 'other')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
  <td width=12% align='right' valign="middle">保险面积</td>
  <td align='left' valign="middle"><?= $form->field($model, 'insuredarea')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
  <td align='right' valign="middle">小麦：</td>
  <td align='left' valign="middle"><?= $form->field($model, 'insuredwheat')->textInput()->label(false)->error(false) ?></td>
  <td align='right' valign="middle">大豆：</td>
  <td align='left' valign="middle"><?= $form->field($model, 'insuredsoybean')->textInput()->label(false)->error(false) ?></td>
  <td align='right' valign="middle">其它：</td>
  <td align='left' valign="middle"><?= $form->field($model,'insuredother')->textInput()->label(false)->error(false)?></td>
</tr>
<tr>
  <td align='right' valign="middle">保险公司</td>
  <td colspan="2" align='left' valign="middle"><?= $form->field($model, 'company_id')->dropDownList(ArrayHelper::map(Insurancecompany::find()->all(), 'id', 'companynname'),['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
  <td align='right' valign="middle">&nbsp;</td>
  <td align='left' valign="middle">&nbsp;</td>
  <td align='right' valign="middle">&nbsp;</td>
  <td align='left' valign="middle">&nbsp;</td>
  <td align='left' valign="middle">&nbsp;</td>
</tr>
    </table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '申请' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'submitButton']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    function radioListState()
    {
        var arr = new Array();
        var str = "<?= implode(',',Insurancedck::attributesKey())?>";
        arr = str.split(',');
        var state = false
        $.each(arr,function(){
            if ($('input:radio[name="iswt"]').prop('checked') == false) {
                state = true;
            } else {
                if(this != 'isoneself') {
                    if ($('input:radio[name="' + this + '"]').prop('checked') == false) {
                        state = true;
                    }
                }
            }
            if ($('input:radio[name="islease"]').prop('checked') == false) {
                state = true;
            } 
        });
        return state;
    }
    function radioCheck(name) {
        var state = false;
        if(name == 'isoneself') {
            if($('input:radio[name="isoneself"]:checked').val() == 0) {
//                $('#submitButton').attr('disabled',true);
                var html = '<tr id="tr-iswt"><td><div id="iswt-id" onclick=radioCheck("iswt")><label><input type="radio" name="iswt" value="1"> 是</label><label><input type="radio" name="iswt" value="0"> 否</label></div></td><td>提供委托书及委托人身份证</td></tr>';
                if($('#tr-iswt').val() == undefined)
                    $('#isTable tr:eq(0)').after(html);
//
            } else {
                $('#tr-iswt').remove();
            }
        }
        state = radioListState();
   	 	$('#submitButton').attr('disabled',state);
    }
    $('#insurancehistory-policyholder').change(function(){
        var farmername = $('#insurancehistory-farmername').val();
		if($(this).val() != farmername) {
			$('#insurancehistory-cardid').val('');
			$('#insurancehistory-telephone').val('');
			var html = '<tr id="tr-islease"><td><div id="iswt-id" onclick=radioCheck("islease")><label><input type="radio" name="islease" value="1"> 是</label> <label><input type="radio" name="islease" value="0"> 否</label></div></td><td>提供租赁合同或租赁协议书</td></tr>';
			if($('#tr-islease').val() == undefined)
            	$('#isTable tr:eq(3)').after(html);
		}
    });
//    $('#insurance-cardid').change(function(){
//    	var cardid = "<?//= $farm['cardid']?>//";
//		if($(this).val() != cardid) {
//			 var html = '<tr id="tr-islease"><td><div id="iswt-id" onclick=radioCheck("islease")><label><input type="radio" name="islease" value="1"> 是</label> <label><input type="radio" name="islease" value="0"> 否</label></div></td><td>提供租赁合同或租赁协议书</td></tr>';
//			 if($('#tr-islease').val() == undefined)
//                 $('#isTable tr:eq(3)').after(html);
//		}
//    });
$('#insurancehistory-wheat').keyup(function(){
	var sum = $(this).val()*1 + $('#insurancehistory-soybean').val()*1 + $('#insurancehistory-other').val()*1;
	var ycontractarea = $('#insurancehistory-contractarea').val()*1;
//	var overarea = $('#OverArea').val()*1;
//	if(overarea > 0.0)
//		var contractarea = ycontractarea - overarea;
//	else
//		var contractarea = ycontractarea;
	if(sum > ycontractarea) {
		alert('对不起，已经超过当前农场总面积，请重新填写。');
		$('#insurancehistory-wheat').focus();
		$('#insurancehistory-wheat').val('');
        sum = $(this).val()*1 + $('#insurancehistory-soybean').val()*1 + $('#insurancehistory-other').val()*1;
	}
	$('#insurancehistory-insuredwheat').val($(this).val());
	$('#insurancehistory-insurancearea').val(sum.toFixed(2));
    $('#insurancehistory-insuredarea').val(sum.toFixed(2));
});
$('#insurancehistory-soybean').keyup(function(){
	var sum = $(this).val()*1 + $('#insurancehistory-wheat').val()*1 + $('#insurancehistory-other').val()*1;
	var ycontractarea = $('#insurancehistory-contractarea').val()*1;
//	var overarea = $('#OverArea').val()*1;
//	if(overarea > 0.0)
//		var contractarea = ycontractarea - overarea;
//	else
//		var contractarea = ycontractarea;
	if(sum > ycontractarea) {
		alert('对不起，已经超过当前农场总面积，请重新填写。');
		$('#insurancehistory-soybean').focus();
		$('#insurancehistory-soybean').val('');
        sum = $(this).val()*1 + $('#insurancehistory-wheat').val()*1 + $('#insurancehistory-other').val()*1;
	}
	$('#insurancehistory-insuredsoybean').val($(this).val());
	$('#insurancehistory-insurancearea').val(sum.toFixed(2));
    $('#insurancehistory-insuredarea').val(sum.toFixed(2));
});
$('#insurancehistory-other').keyup(function(){
	var sum = $(this).val()*1 + $('#insurancehistory-soybean').val()*1 + $('#insurancehistory-wheat').val()*1;
	var ycontractarea = $('#insurancehistory-contractarea').val()*1;
//	var overarea = $('#OverArea').val()*1;
//	if(overarea > 0.0)
//		var contractarea = ycontractarea - overarea;
//	else
//		var contractarea = ycontractarea;
	if(sum > ycontractarea) {
		alert('对不起，已经超过当前农场总面积，请重新填写。');
		$('#insurancehistory-other').focus();
		$('#insurancehistory-other').val('');
        sum = $(this).val()*1 + $('#insurancehistory-soybean').val()*1 + $('#insurancehistory-wheat').val()*1;
	}
    $('#insurancehistory-insuredother').val($(this).val());
    $('#insurancehistory-insurancearea').val(sum.toFixed(2));
    $('#insurancehistory-insuredarea').val(sum.toFixed(2));
});
$('#insurancehistory-insuredwheat').keyup(function(){
    var sum = $(this).val()*1 + $('#insurancehistory-insuredsoybean').val()*1 + $('#insurancehistory-insuredother').val()*1;
    var ycontractarea = $('#insurancehistory-contractarea').val()*1;

    if(sum > ycontractarea) {
        alert('对不起，已经超过当前种植结构面积，请重新填写。');
        $('#insurancehistory-insuredwheat').focus();
        $('#insurancehistory-insuredwheat').val('');
        $('#insurancehistory-insuredsoybean').val('');
        $('#insurancehistory-insuredother').val('');
        sum = $(this).val()*1 + $('#insurancehistory-insuredsoybean').val()*1 + $('#insurancehistory-insuredother').val()*1;
    }
    $('#insurancehistory-insuredarea').val(sum.toFixed(2));
});
$('#insurancehistory-insuredsoybean').keyup(function(){
    var sum = $(this).val()*1 + $('#insurancehistory-insuredwheat').val()*1 + $('#insurancehistory-insuredother').val()*1;
    var ycontractarea = $('#insurancehistory-contractarea').val()*1;

    if(sum > ycontractarea) {
        alert('对不起，已经超过当前植结构面积，请重新填写。');
        $('#insurancehistory-insuredsoybean').focus();
        $('#insurancehistory-insuredwheat').val('');
        $('#insurancehistory-insuredsoybean').val('');
        $('#insurancehistory-insuredother').val('');
        sum = $(this).val()*1 + $('#insurancehistory-insuredwheat').val()*1 + $('#insurancehistory-insuredother').val()*1;
    }
    $('#insurancehistory-insuredarea').val(sum.toFixed(2));
});
$('#insurancehistory-insuredother').keyup(function(){
    var sum = $(this).val()*1 + $('#insurancehistory-insuredsoybean').val()*1 + $('#insurancehistory-insuredwheat').val()*1;
    var ycontractarea = $('#insurancehistory-contractarea').val()*1;

    if(sum > ycontractarea) {
        alert('对不起，已经超过当前植结构面积，请重新填写。');
        $('#insurancehistory-insuredother').focus();
        $('#insurancehistory-insuredwheat').val('');
        $('#insurancehistory-insuredsoybean').val('');
        $('#insurancehistory-insuredother').val('');
        sum = $(this).val()*1 + $('#insurancehistory-insuredsoybean').val()*1 + $('#insurancehistory-insuredwheat').val()*1;
    }
    $('#insurancehistory-insuredarea').val(sum.toFixed(2));
});
</script>