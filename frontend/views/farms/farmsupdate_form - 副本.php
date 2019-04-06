<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Parcel;
use app\models\Lease;
use app\models\ManagementArea;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $model app\models\Farms */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
	.remove{cursor:pointer}
</style>
<div class="farms-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table
		class="table table-bordered table-hover">
		<tr>
			<td width=15% align='right'>农场名称</td>
			<td align='left'><?= $form->field($model, 'farmname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			<td align='right'>承包人姓名</td>
			<td align='left'><?= $form->field($model, 'farmername')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			<td align='right'>身份证号</td>
			<td colspan="3" align='left'><?= $form->field($model, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>电话号码</td>
			<td align='left'><?= $form->field($model, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			<td align='right'>农场位置</td>
			<td align='left'><?= $form->field($model, 'address')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			<td align='right'>经度</td>
			<td align='left'><?= $form->field($model, 'longitude')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			<td align='right'>纬度</td>
			<td align='left'><?= $form->field($model, 'latitude')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			</tr>
			<tr>
			<td width=15% align='right'>管理区</td>
			<td align='left'><?= $form->field($model, 'management_area')->dropDownList(ArrayHelper::map(ManagementArea::find()->all(), 'id', 'areaname'))->label(false)->error(false) ?></td>
			<td align='right'>合同号</td>
			<td align='left'><?php if($model->contractnumber == '') $model->contractnumber = Farms::getNewContractnumber()?><?= $form->field($model, 'contractnumber')->textInput(['readonly' => false])->label(false)->error(false) ?></td>
			<td align='right'>审批年度</td>
			<td align='left'><?= $form->field($model, 'spyear')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'startView' => 4,
        	'minView' => 4,
            'format' => 'yyyy'
        ]]);  ?></td>
			<td align='right'>合同领取日期</td><?php if($model->surveydate) $model->surveydate = date('Y-m-d',$model->surveydate);?>
			<td align='left'><?= $form->field($model, 'surveydate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 3,
        	'maxView' => 3,
            'format' => 'yyyy-mm-dd'
        ]]); ?></td>
			</tr>
		<tr>
			<td width=15% align='right'>承包年限</td><?php $model->begindate = '2010-09-13'?>
			<td align='center'>自</td>
			<td align='center'><?= $form->field($model, 'begindate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 3,
        	'maxView' => 3,
            'format' => 'yyyy-mm-dd'
        ]]) ?></td>
			<td align='center'>至</td><?php $model->enddate = '2025-09-13'?>
			<td align='center'><?= $form->field($model, 'enddate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 3,
        	'maxView' => 3,
            'format' => 'yyyy-mm-dd'
        ]])?></td>
			<td align='center'>止</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
		</tr>
		<tr>
			<?= Html::hiddenInput('newzongdi','',['id'=>'new-zongdi']) ?><?= $form->field($model, 'zongdi')->hiddenInput()->label(false)->error(false) ?>
			<td width=15% align='right'>宗地</td><?= html::hiddenInput('tempzongdi','',['id'=>'temp-zongdi'])?>
			<td colspan="7" align='left'>
			
			<span id="inputZongdi" class="select2-container select2-container--default select2-container--below" dir="ltr" style="width: 100%; color: #000;">
	<span id="inputZongdi" class="select2-container select2-container--default select2-container--below" dir="ltr" style="width: 100%; color: #000;">
	<span class="selection">
		<span class="select2-selection select2-selection--multiple" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false" tabindex="0">
			<ul class="select2-selection__rendered">
				<li class="select2-search select2-search--inline">
					<input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" placeholder="" style="width: 0.75em;">
					<?php
					if($model->zongdi) {
					$iden = explode('、',$model->zongdi);
					if($iden) {
						foreach ($iden as $zongdi) {
//							echo '<a href="#" id="zongdiinfo"><li class="select2-selection__choice" id="new' . Lease::getZongdi($zongdi) . '" title="' . $zongdi . '"><span class="remove text-red" role="presentation" onclick=zongdiRemove("' . Lease::getZongdi($zongdi) . '","' . Lease::getArea($zongdi) . '","dialog")>×</span>' . $zongdi . '</li></a>';
							echo '<li class="select2-selection__choice" id="new' . Lease::getZongdi($zongdi) . '" title="' . $zongdi . '"><span class="remove text-red" role="presentation" onclick=zongdiRemove("' . Lease::getZongdi($zongdi) . '","' . Lease::getArea($zongdi) . '","dialog")>×</span>' . $zongdi . '</li>';
						}
					}} else {
						echo '<li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" placeholder="" style="width: 0.75em;"></li>';
					}
					?>
				</li>
			</ul>
		</span>
	</span>
	<span class="dropdown-wrapper" aria-hidden="true"></span>

			</td>
		</tr>
		<tr>
		  <td align='right'>合同面积</td>
		  <td align='left'><?= $form->field($model, 'contractarea')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
		  <td align='right'>宗地面积</td>
		  <td align='left'><?= $form->field($model, 'measure')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
		  <td align='right'>未明确地块面积</td>
		  <td align='left'><?= $form->field($model, 'notclear')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		  <td align='right'><?= $form->field($model, 'notstateinfo')->dropDownList(Farms::notstateInfo([4,5,6,7,8]))->label(false)->error(false)?></td>
		  <td align='left'><?= $form->field($model, 'notstate')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    </tr>
		<tr>
			<td width=15% align='right'>地产科签字</td>
			<td align='left'><?= $form->field($model, 'groundsign')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			<td align='right'>农场法人签字</td>
			<td align='left'><?= $form->field($model, 'farmersign')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			<td align='right'>状态</td><?php if(!$model->state) $model->state = 1;?>
			<td colspan="3" align='left'><?= $form->field($model, 'state')->radioList(Farms::getFarmsState([0,1,2,3]))->label(false)->error(false) ?></td>
		</tr>
	<tr>
			<td width=15% align='right'>备注</td>
			<td colspan="7" align='left'><?= $form->field($model, 'remarks')->textarea(['rows' => 5])->label(false)->error(false) ?></td>
			<?php if(!$model->state) $model->state = 1;?>
		</tr>
	</table>
	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	<div id="dialog" title="宗地信息">
		<table width=100%>
			<tr>
				<td align="right">宗地号：</td>
				<td><?= html::textInput('zongdinumber','',['id'=>'zongdi','disabled'=>true])?></td>
			</tr>
			<tr>
				<td align="right">面积：</td>
				<td><?= html::textInput('zongdimeasure','',['id'=>'measure'])?></td>
			</tr>
		</table>
	</div>
	<div id="dialog2" title="宗地信息">
		<table width=100%>
			<tr>
				<td align="right">宗地号：</td>
				<td><?= html::textInput('findzongdi','',['id'=>'findZongdi'])?></td>
			</tr>
			<tr>
				<td align="right">面积：</td>
				<td><?= html::textInput('findmeasure','',['id'=>'findMeasure'])?></td>
			</tr>
		</table>
	</div>
    <?php ActiveFormrdiv::end(); ?>

</div>
<?php
//
//$script = <<<JS
//
//$('#farms-management_area').change(function(){
//	var input = $(this).val();
//	var hth = $('#farms-contractnumber').val();
//	var arrayhth = hth.split('-');
//	arrayhth[3] = input;
//	$('#farms-contractnumber').val(arrayhth.join('-'));
//});
//
//$('#farms-notstate').keyup(function (event) {
//	var input = $(this).val();
//	if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
//		if(event.keyCode == 8) {
//			$(this).val('');
//
//			if($('#temp_notclear').val() !== '') {
//				var result = $('#farms-measure').val()-$('#temp_notclear').val();
//				$('#farms-measure').val(result.toFixed(2));
//			}
//
//		}
//	} else {
//		alert('输入的必须为数字');
//		var last = input.substr(input.length-1,1);
//		$('#farms-notclear').val(input.substring(0,input.length-1));
//	}
//});
//
//function toHTH()
//{
//	//生成合同号
//	var hth = $('#farms-contractnumber').val();
//	var arrayhth = hth.split('-');
//	var contractarea = $('#farms-measure').val()*1 + $('#farms-notclear').val()*1 - $('#farms-notstate').val()*1;
//	arrayhth[2] = cutZero(contractarea.toFixed(2));
//	$('#farms-contractarea').val(arrayhth[2]);
//	$('#farms-contractnumber').val(arrayhth.join('-'));
//}
//$('#farms-notclear').blur(function(){
//	toHTH();
//});
//$('#farms-notstate').blur(function(){
//	toHTH();
//});
//$('#farms-notclear').keyup(function (event) {
//	var input = $(this).val();
//	if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
//		if(event.keyCode == 8) {
//			$(this).val('');
//
//			if($('#temp_notclear').val() !== '') {
//				var result = $('#farms-measure').val()-$('#temp_notclear').val();
//				$('#farms-measure').val(result.toFixed(2));
//			}
//
//		}
//	} else {
//		alert('输入的必须为数字');
//		var last = input.substr(input.length-1,1);
//		$('#farms-notclear').val(input.substring(0,input.length-1));
//	}
//});
//
//$("#farms-zongdi").keyup(function (event) {
//
//    var input = $(this).val();
//	if (event.keyCode == 32) {
//		input = $.trim(input);
//		$.getJSON('index.php?r=parcel/parcelarea', {zongdi: input}, function (data) {
//			//alert(data.area);
//			if (data.status == 1) {
//				var value = $('#farms-measure').val()*1+data.area*1;
//				$('#farms-measure').val(value.toFixed(2));
//				$('#temp_measure').val(value.toFixed(2));
//				$('#temp-zongdi').val($.trim(input)+'、');
//				$("#farms-zongdi").val($.trim(input)+'、');
//				var contractarea = $("#farms-contractarea").val()*1;
//				var measure = $("#farms-measure").val()*1;
//				if(measure < contractarea) {
//					var cha = contractarea - measure;
//					$("#farms-notclear").val(cha.toFixed(2));
//				} else {
//					$("#farms-notclear").val(0);
//					var cha =measure - contractarea;
//					$("#farms-notstate").val(cha.toFixed(2));
//				}
//
//				toHTH();
//			}
//			else {
//				alert(data.message);
//				$("#farms-zongdi").val($('#temp-zongdi').val());
//				toHTH();
//			}
//		});
//	}
//	if (event.keyCode == 8) {
//		var zongdi = $('#farms-zongdi').val();
//		var arrayZongdi = zongdi.split('、');
//		var rows = arrayZongdi.length*1 - 1;
//		arrayZongdi.splice(rows,1);
//		$('#farms-zongdi').val(arrayZongdi.join('、'));
//		var input = $(this).val();
//		if(input) {
//		    input = $.trim(input);
//			$.getJSON('index.php?r=parcel/getformatzongdi', {zongdi: input}, function (data) {
//				if (data.status == 1) {
//					$("#farms-zongdi").val($.trim(data.formatzongdi));
//					$("#farms-measure").val(data.sum);
//					var contractarea = $("#farms-contractarea").val()*1;
//					var measure = $("#farms-measure").val()*1;
//					if(measure < contractarea) {
//						$("#farms-notstate").val(0);
//						var cha = contractarea - measure;
//						$("#farms-notclear").val(cha.toFixed(2));
//					} else {
//						$("#farms-notclear").val(0);
//						var cha =measure - contractarea;
//						$("#farms-notstate").val(cha.toFixed(2));
//					}
//					toHTH();
//				}
//			});
//		} else {
//			$("#farms-measure").val(0);
//			$("#farms-notclear").val($("#farms-contractarea").val());
//			toHTH();
//		}
//	}
// });
//
//$('#farms-zongdi').blur(function(){
//	var input = $(this).val();
//	if(input) {
//	    input = $.trim(input);
//		$.getJSON('index.php?r=parcel/getformatzongdi', {zongdi: input}, function (data) {
//			if (data.status == 1) {
//				$("#farms-zongdi").val($.trim(data.formatzongdi));
//				$("#farms-measure").val(data.sum);
//				var contractarea = $("#farms-contractarea").val()*1;
//					var measure = $("#farms-measure").val()*1;
//					if(measure < contractarea) {
//						$("#farms-notstate").val(0);
//						var cha = contractarea - measure;
//						$("#farms-notclear").val(cha.toFixed(2));
//					} else {
//						$("#farms-notclear").val(0);
//						var cha =measure - contractarea;
//						$("#farms-notstate").val(cha.toFixed(2));
//					}
//				toHTH();
//			}
//		});
//	} else {
//		$("#farms-measure").val(0);
//		toHTH();
//	}
//});
//JS;
//$this->registerJs($script);





?>
<script>
$('#farms-management_area').change(function(){
var input = $(this).val();
var hth = $('#farms-contractnumber').val();
var arrayhth = hth.split('-');
arrayhth[3] = input;
$('#farms-contractnumber').val(arrayhth.join('-'));
});
$('#farms-notstate').keyup(function (event) {
	var input = $(this).val();
	if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
		if(event.keyCode == 8) {
			$(this).val('');

			if($('#temp_notclear').val() !== '') {
				var result = $('#farms-measure').val()-$('#temp_notclear').val();
				$('#farms-measure').val(result.toFixed(2));
			}

		}
	} else {
		alert('输入的必须为数字');
		var last = input.substr(input.length-1,1);
		$('#farms-notclear').val(input.substring(0,input.length-1));
	}
});
$('#farms-notclear').keyup(function (event) {
	var input = $(this).val();
	if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
		if(event.keyCode == 8) {
			$(this).val('');

			if($('#temp_notclear').val() !== '') {
				var result = $('#farms-measure').val()-$('#temp_notclear').val();
				$('#farms-measure').val(result.toFixed(2));
			}

		}
	} else {
		alert('输入的必须为数字');
		var last = input.substr(input.length-1,1);
		$('#farms-notclear').val(input.substring(0,input.length-1));
	}
});
$('#farms-notclear').blur(function(){
toHTH();
});
$('#farms-notstate').blur(function(){
toHTH();
});
	function zongdiRemove(zongdi,measure,dialogID)
	{
 		removeZongdiForm(zongdi,measure);
 		removeNowZongdi(zongdi);
 		oldZongdiChange(zongdi,measure,'back');
	
		$('#new'+zongdi).remove();
// 	var zongdiarr = zongdi.split('_');
		
		if(dialogID == 'dialog') {
			//宗地面积计算开始
			
			//如果存在未明确状态面积，那么先减未明确状态面积
			var notstate = Number($('#farms-notstate').val());

			if(notstate > 0) {
				if(notstate >= Number(measure)) {
					$('#farms-notstate').val(notstate - Number(measure));
				} else {
					$('#farms-notstate').val(0);
				}
			}
			var newvalue = $('#farms-measure').val()*1 - measure*1;
			$('#farms-measure').val(newvalue.toFixed(2));
			$('#temp_measure').val(newvalue.toFixed(2));
			$('#'+zongdi).text($('#'+zongdi).val());
		}
		if(dialogID == 'dialog2') {
			
			//如果存在未明确状态面积，那么先减未明确状态面积
 			var notstate = $('#farms-notstate').val();
			var notclear = $('#farms-notclear').val();

			var newvalue = $('#farms-measure').val()*1 - measure*1;
			$('#farms-measure').val(newvalue.toFixed(2));
			$('#temp_measure').val(newvalue.toFixed(2));

			if($('#farms-measure').val()*1 > $('#farms-contractarea').val()*1) {
				var newnotstate = notstate - measure*1;
				$('#farms-notstate').val(newnotstate.toFixed(2));
			} else {
//				$('#farms-notstate').val(0);
				if($('#farms-measure').val() == 0) {

//					var newnotclear = notclear*1 + measure*1
					$('#farms-notclear').val($('#farms-contractarea').val());
					$('#farms-notstate').val(0);
				} else {
					if($('#farms-notstate').val()*1 > 0) {
						if(measure*1 > $('#farms-notstate').val()*1) {
							$('#farms-notstate').val(0);
							var newnotclear = $('#farms-contractarea').val()*1 - $('#farms-measure').val()*1;
							$('#farms-notclear').val(newnotclear.toFixed(2));
						} else {
							var newnotstate = $('#farms-notstate').val()*1 - measure*1;
							$('#farms-notstate').val(newnotstate.toFixed(2));
						}
					} else {
						if($('#farms-notclear').val()*1 > 0) {
							var newnotclear = $('#farms-notclear').val()*1 + measure*1;
							$('#farms-notclear').val(newnotclear.toFixed(2));
						} else {
							alert('notclear_else');
						}
					}
				}

			}
		}
		//宗地面积计算结束
		toHTH();
	}
	function removeZongdiForm(zongdi,measure)
	{
		var findzongdi = zongdi + "("+measure+")";
		var zongdi = $('#farms-zongdi').val();

		var arr1 = zongdi.split('、');
		$.each(arr1, function(i,val){
			if(val === findzongdi)
				arr1.splice(i,1);
		});
		var newnewzongdi = arr1.join('、');
		$('#farms-zongdi').val(newnewzongdi);
	}
	function removeNowZongdi(zongdi)
	{
		var nowzongdi = $('#new-zongdi').val();
		var arr1 = zongdi.split('|');
		$.each(arr1, function(i,val){
			if(val === zongdi)
				arr1.splice(i,1);
		});
		var newnewzongdi = arr1.join('|');
// 	alert(newnewzongdi);
		$('#new-zongdi').val(newnewzongdi);
// 	return result;
	}
	function oldZongdiChange(zongdi,measure,state)
	{
		var yzongdi = $('#oldzongdiChange').val();
// 	alert(yzongdi);
		$.getJSON("<?= Url::to(['farms/oldzongdichange'])?>", {yzongdi: yzongdi, zongdi:zongdi,measure:measure,state:state}, function (data) {
			$('#oldzongdiChange').val(data.zongdi);
		});
	}
	function nowZongdiFind(zongdi)
	{
		var result = false;
		var newzongdi = $('#new-zongdi').val();
		if(newzongdi != '') {
			var arr1 = newzongdi.split('|');
			$.each(arr1, function(i,val){
				if(val === zongdi)
					result = true;
			});
		}
		return result;
	}
//处理宗地里的"、"号,将最后一个或最前一个删除
	function zongdiForm(zongdi,measure)
	{
		var newfarmszongdi = $('#farms-zongdi').val();
		var zongdistr = zongdi+"("+measure+")";
		$('#farms-zongdi').val(newfarmszongdi +'、'+ zongdistr);
// 	alert(zongdistr);
		var farmszongdi = $('#farms-zongdi').val();
		var first = farmszongdi.substr(0,1);
		var last = farmszongdi.substr(farmszongdi.length-1,1);
		if(first == '、') {
			$('#farms-zongdi').val(farmszongdi.substring(1));
		}
		if(last == '、') {
			$('#farms-zongdi').val(farmszongdi.substring(0,farmszongdi.length-1));
		}

	}

	$( "#dialog" ).dialog({
		autoOpen: false,
		width: 400,
		buttons: [
			{
				text: "确定",
				click: function() {
					var zongdi = $('#zongdi').val();
					var measure = Number($('#measure').val());
					var ymeasure = Number($('#ymeasure').val());
					if(measure == '') {
						alert("对不起，您面积不能为空。");
						$('#measure').val(ymeasure);
					} else {
							$( this ).dialog( "close" );
							zongdiForm(zongdi,measure);
							var newzongdi = zongdi+'('+measure+')';
							var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+measure+'","dialog")>×</span>'+newzongdi+'</li>';
							$('.select2-selection__rendered').append(newzongdihtml);
							var newvalue = $('#farms-measure').val()*1 + measure*1;
							$('#farms-measure').val(newvalue.toFixed(2));
							$('#temp_measure').val(newvalue.toFixed(2));
							toHTH();
							var ycontractarea = parseFloat($('#farms-contractarea').val());

					}
				}
			},
			{
				text: "取消",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});

	$('#dialog2').dialog({
		autoOpen: false,
		width:400,

		buttons: [
			{
				text: "确定",
				click: function() {
					var zongdi = $('#findZongdi').val();
// 	  				alert(zongdi);
					var measure = Number($('#findMeasure').val());
//					var ymeasure = Number($('#ymeasure').val());
					if(measure == '' || zongdi == '') {
						alert("对不起，宗地或面积不能为空。");
						$('#findMeasure').val();
					} else {
						$( this ).dialog( "close" );
						zongdiForm(zongdi,measure);
//						oldZongdiChange(zongdi,measure,'change');
						var newzongdi = zongdi+'('+measure+')';
						var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+measure+'","dialog2")>×</span>'+newzongdi+'</li>';

						$('.select2-selection__rendered').append(newzongdihtml);
						$('#'+zongdi).attr('disabled',true);
						var newvalue = $('#farms-measure').val()*1 + measure*1;
						$('#farms-measure').val(newvalue.toFixed(2));
						$('#temp_measure').val(newvalue.toFixed(2));
						var notclear = $('#farms-notclear').val()*1;
						var notstate = $('#farms-notstate').val()*1;
						if(notclear > 0) {
							if(measure > notclear) {
								var newnotstate = measure*1 - notclear*1;
								$('#farms-notstate').val(newnotstate.toFixed(2));
								$('#farms-notclear').val(0);
							} else {
								var newnotclear = notclear*1 - measure*1;
								$('#farms-notclear').val(newnotclear.toFixed(2));
							}

						}
						if(notstate > 0) {
							if($('#farms-measure').val()*1 > $('#farms-contractarea').val()*1) {
								var newnotstate = $('#farms-notstate').val()*1 + measure*1;
								$('#farms-notstate').val(newnotstate.toFixed(2));
							} else {
								$('#farms-notstate').val(0);
							}
						}
						toHTH();
// 								var ycontractarea = parseFloat($('#farms-contractarea').val());

						var newtempzongdi = $('#new-zongdi').val();
						$("#new-zongdi").val(zongdi+'|'+newtempzongdi);
//						$('#ymeasure').val(0);
						$('#findZongdi').val('');
						$('#findMeasure').val('');

						}

//					}
				}
			},
			{
				text: "取消",
				click: function() {
					$('#findZongdi').val('');
					$('#findMeasure').val('');
					$( this ).dialog( "close" );
				}
			}
		]
	});
	$('#inputZongdi').dblclick(function(){
		$("#dialogSelect").val('dialog2');
		$( "#dialog2" ).dialog( "open" );
		$('#findZongdi').val('');
		$('#findMeasure').val('');
	});
	//点击宗地输入框弹出宗地信息查找框
	$('#zongdiinfo').click(function(){
			$("#dialogSelect").val('dialog');
			$( "#dialog2" ).dialog( "open" );
//			$('#findZongdi').val('');
//			$('#findMeasure').val('');
	});
	$('#findZongdi').keyup(function (event) {
		var input = $(this).val();
		if(event.keyCode == 13) {
			if(nowZongdiFind(input)){
				alert('您已经输入过此宗地号，请不要重复输入');
				$('#findZongdi').val('');
				$('#findMeasure').val('');
			} else {
				$.getJSON("<?= Url::to(['parcel/parcelarea'])?>", {zongdi: input}, function (data) {
					if (data.status == 1) {

						$('#findMeasure').val(data.area);
						$('#ymeasure').val(data.area);
					}
					else {
						if(input != '') {
							if(data.showmsg)
								alert(data.message);
							$("#findZongdi").val('');
							$('#findMeasure').val('');
							$("#findZongdi").focus();
						}
					}
				});
			}
		}
	});
	$('#findZongdi').blur(function (event) {
		var input = $(this).val();
		if(input != '') {
			if(nowZongdiFind(input)){
				alert('您已经输入过此宗地号，请不要重复输入');
				$('#findZongdi').val('');
				$('#findMeasure').val('');
			} else {
				$.getJSON("<?= Url::to(['parcel/parcelarea'])?>", {zongdi: input}, function (data) {
					if (data.status == 1) {
						if(data.showmsg)
							alert(data.message);
						$('#findMeasure').val(data.area);
						$('#ymeasure').val(data.area);
					}
					else {
						if(input != '') {
							if(data.showmsg)
								alert(data.message);
							$("#findZongdi").val('');
							$('#findMeasure').val('');
							$("#findZongdi").focus();
						}
					}
				});
			}
		}
	});
	// Link to open the dialog
	$( ".dialog-link" ).click(function( event ) {
		$("#dialogSelect").val('dialog1');
		$( "#dialog" ).dialog( "open" );

		event.preventDefault();
	});
	function resetZongdi(zongdi,area)
	{
		$('#'+zongdi).attr('disabled',false);
		var oldmeasure = $('#oldfarms-measure').val()*1 + area*1;
		$('#oldfarms-measure').val(oldmeasure.toFixed(2));
		toHTH();
	}
	function getArea(zongdi)
	{
		re = /-([\s\S]*)\(([0-9.]+?)\)/
		var area = zongdi.match(re);
		return area[2];

	}

	function toZongdi(zongdi,area){
		$( "#dialog" ).dialog( "open" );
		event.preventDefault();
		$('#zongdi').val(zongdi);
		$('#measure').val(area);
		$('#ymeasure').val(area);
	}
	$('#reset').click(function() {

		location.reload();

	});
	function toHTH()
	{
		//生成合同号
		var hth = $('#farms-contractnumber').val();
		var arrayhth = hth.split('-');
		var contractarea = $('#farms-measure').val()*1 + $('#farms-notclear').val()*1 - $('#farms-notstate').val()*1;
		arrayhth[2] = cutZero(contractarea.toFixed(2));
		$('#farms-contractnumber').val(arrayhth.join('-'));
		$('#farms-contractarea').val(arrayhth[2]);
	}
// 	$('#farms-notclear').blur(function(){
// 		var input = $(this).val();
// 		if(input*1 > $('#temp_oldnotclear').val()*1) {

// 			alert('输入的数值不能大于'+$('#temp_oldnotclear').val());
// 			$('#oldfarms-notclear').val($('#temp_oldnotclear').val());
// 			$(this).val(0);
// 			$('#farms-notclear').val(0);
// 			$(this).focus();
// 			toHTH();
// 		}});

// 	$('#farms-notclear').keyup(function (event) {
// 		var input = $(this).val();
// 		if(event.keyCode == 8) {
// 			$(this).val('');
// 			$('#farms-notclear').val($('#temp_notclear').val());
// 			$('#oldfarms-notclear').val($('#temp_oldnotclear').val());

// 			$('#temp_oldcontractarea').val($('#oldfarms-contractarea').val());

// 		} else {
// 			if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
// 				if($('#temp_notclear').val() != '') {
// 					var result = $('#temp_oldnotclear').val()*1 - input*1;
// 					$('#oldfarms-notclear').val(result.toFixed(2));
// 					$('#farms-notclear').val(input);
// 					toHTH();
// 				} else {
// 					var result = $('#temp_oldcontractarea').val()*1 - input*1
// 					$('#oldfarms-notclear').val(result.toFixed(2));
// 					$('#farms-notclear').val(input);
// 					toHTH();
// 				}
// 			} else {
// 				alert('输入的必须为数字');
// 				var last = input.substr(input.length-1,1);
// 				$('#input-notclear').val(input.substring(0,input.length-1));

// 			}
// 		}
// 		toHTH();
// 	});
	$('#searchFarms').click(function(){
		var input = $('#farms-farmname').val();
		$.getJSON('index.php?r=farms/getfarminfo', {str: input}, function (data) {
			if (data.status == 1) {
				$('#farms-farmername').val(data.data['farmername']);
				$('#farms-cardid').val(data.data['cardid']);
				$('#farms-telephone').val(data.data['telephone']);
			}
		});
	});
	$('#searchFarmer').click(function(){
		var input = $('#farms-farmername').val();

		$.getJSON('index.php?r=farms/getfarmerinfo', {str: input}, function (data) {
			if (data.status == 1) {
				$('#farms-farmname').val(data.data['farmname']);
				$('#farms-cardid').val(data.data['cardid']);
				$('#farms-telephone').val(data.data['telephone']);
			}
		});
	});
	$('#searchCardid').click(function(){
		var input = $('#farms-cardid').val();

		$.getJSON('index.php?r=farms/getcardidinfo', {str: input}, function (data) {
			if (data.status == 1) {
				$('#farms-farmname').val(data.data['farmname']);
				$('#farms-farmername').val(data.data['farmername']);
				$('#farms-telephone').val(data.data['telephone']);
			}
		});
	});
	$('#searchTelephone').click(function(){
		var input = $('#farms-telephone').val();

		$.getJSON('index.php?r=farms/gettelephoneinfo', {str: input}, function (data) {
			if (data.status == 1) {
				$('#farms-farmname').val(data.data['farmname']);
				$('#farms-cardid').val(data.data['cardid']);
				$('#farms-farmername').val(data.data['farmername']);
			}
		});
	});
</script>

