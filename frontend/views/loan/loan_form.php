<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Processname;
use app\models\Farms;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Loan;
use app\models\Auditprocess;
use app\models\Tablefields;
/* @var $this yii\web\View */
/* @var $model app\models\Loan */
/* @var $form yii\widgets\ActiveForm */
//$process = explode('>', Auditprocess::find()->where(['actionname'=>'loancreate'])->one()['process']);

?>
<style type="text/css">
	.table {
		/* 	font-family: "仿宋"; */
		font-size: 12px;
		text-align: center;
	}
	.italic {
		font-style: italic;
	}

	#estateUndo {
		display: none;
	}
</style>
<div class="loan-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
	<?= Farms::showFarminfo2($_GET['farms_id'])?>
<table class="table table-bordered table-hover">
	<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
<tr>
	<td width=15% align='right'>抵押面积(亩）</td><?php $model->mortgagearea = Farms::find()->where(['id'=>$_GET['farms_id']])->one()['contractarea'];?>
	<td align='left'><?= $form->field($model, 'mortgagearea')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
	<td width=15% align='right'>抵押银行</td>
	<td align='left'><?= $form->field($model, 'mortgagebank')->dropDownList(Loan::getBankName())->label(false)->error(false) ?></td>
	<td width=15% align='right'>贷款金额（万元）</td>
	<td align='left'><?= $form->field($model, 'mortgagemoney')->textInput(['placeholder'=>'万元'])->label(false)->error(false) ?></td>
</tr>
<tr>
	<?php
	if(empty($model->begindate)) $model->begindate = date('Y-m-d');
	$y = date('Y') + 1;
	$m = date('m');
	$d = date('d') -1 ;
	if(empty($model->enddate)) $model->enddate = $y.'-'.$m.'-'.$d;
	?>
<td width=15% align='right'>贷款年限</td>
			<td align='center'>自</td>
			<td align='center'><?= $form->field($model, 'begindate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 2,
        	'maxView' => 4,
            'format' => 'yyyy-mm-dd'
        ]]) ?></td>
			<td align='center'>至</td>
			<td align='center'><?= $form->field($model, 'enddate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 2,
        	'maxView' =>4,
            'format' => 'yyyy-mm-dd'
        ]])?></td>
			<td align='center'>止</td>
</tr>
</table>
	<table class="table table-bordered table-hover" id="isTable">
		<?php
//		foreach ($process as $value) {?>
		<tr>
			<td align="right" rowspan="2" width="20%">地产科意见：</td>
			<?php echo Loan::showReviewprocess('estate', $process); ?>
		</tr>
	</table>
    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-success','disabled'=>true,'id'=>'submitbutton']) ?>
        <?= Html::a('返回', [Yii::$app->controller->id.'index','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
        <br>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
	$('#loan-begindate').change(function(){
		var input = $(this).val();
		var arr = input.split('-');
		var year = arr[0]*1+1;
		var day = arr[2]*1-1;
		var newdate = year+'-'+arr[1]+'-'+day;
		$('#loan-enddate').val(newdate);
	});
	function showContent(key,process)
	{
		$('#'+key+'content').remove();
		var state = true;
		state = contentListState();
// 	alert($('input:radio[name="'+key+'"]:checked').val());
		if(key == 'isdydk' || key == 'sfdj' || key == 'isqcbf' || key == 'other' || key == 'sfyzy' || key.indexOf('isAgree')>=0) {

// 		alert($('input:radio[name="'+key+'"]').prop('checked'));
			if($('input:radio[name="'+key+'"]').prop('checked') == false) {
				$('#'+key+'-text').append('事由：');
				if($('#'+key+'content').val() == undefined) {
					var html = '<textarea id="'+key+'content" name="'+key+'content" rows="1" cols="150" class="isText form-control"></textarea>';
					$('#'+key+'-add').append(html);

					$('#'+key+'content').focus();
					state = contentListState();
					$('#'+key+'content').keyup(function(e){
						if(key.indexOf('isAgree')>=0) {
							$('#reviewprocess-'+process+'content').val($(this).val());
						}
						state = contentListState();
// 					alert(state);
						$('#submitbutton').attr('disabled',state);
					});
				}
			} else {
				$('#'+key+'-text').empty();
			}
		} else {

			if($('input:radio[name="'+key+'"]').prop('checked') == true) {
// 			alert($('#'+key+'content').val());
				if($('#'+key+'content').val() == undefined) {
					var html = '<textarea id="'+key+'content" name="'+key+'content" rows="1" cols="50" class="isText form-control"></textarea>';
// 				$('#submitbutton').attr('disabled',true);
					$('#'+key+'-add').append(html);
					$('#'+key+'content').focus();
					state = contentListState();
					$('#'+key+'content').keyup(function(e){
						state = contentListState();
						$('#submitbutton').attr('disabled',state);
					});
				}
			}
		}
		if(key.indexOf('isAgree')>=0){
			$('#'+process+'Undo').show();
			$('#reviewprocess-'+process).val($('input:radio[name="'+key+'"]:checked').val());
			if($('input:radio[name="'+key+'"]:checked').val() == 1) {
				$('#reviewprocess-' + process + 'content').val('');
				$('#'+process+'Undo').hide();
			}
		}
		$('#submitbutton').attr('disabled',state);

	}
	function contentListState()
	{
		var state = radioListState();
// 	alert(state);
		if(state === false) {
// 		alert($(".isText").val());
			$(".isText").each(function(){
// 			alert($(this).val());
				if($(this).val() == "") {
					state = true;
				}
			});
		}
		return state;
	}
	function radioListState()
	{
		var arr = new Array();
		var str = "<?= implode(',',\app\models\Estate::loanAttributesKey());?>";
		arr = str.split(',');
		var state = false
		$.each(arr,function(){
// 		alert(this +'=='+$('input:radio[name="'+this+'"]:checked').val());
			if($('input:radio[name="'+this+'"]:checked').val() == undefined) {
				state = true;
			}
// 		if(this == 'isdydk' || this == 'sfdj' || this == 'isqcbf' || this == 'other' || this == 'sfyzy') {
// 			if($('input:radio[name="'+this+'"]:checked').val() == 1) {
// 				state = true;
// 			}
// 		} else {
// 			if($('input:radio[name="'+this+'"]:checked').val() == 0) {
// 				state = true;
// 			}
// 		}
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
//	function processListState()
//	{
//		var str = "<?//= implode(',', $process)?>//";
//		arr = str.split(',');
//		var state = true;
//		$.each(arr,function(){
//			if($('#reviewprocess-'+this).val() != undefined) {
//				if($('input:radio[name="Reviewprocess['+this+']"]:checked').val() == 1) {
//					state = false;
//				} else {
//// 				alert(state);
//					if($('#reviewprocess-'+this+'content').val() == '')
//						state = true;
//					else
//						state = false;
//				}
//			}
//		});
//// 	alert(state);
//		return state;
//	}
</script>