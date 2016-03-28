<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Parcel;
use app\models\ManagementArea;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $model app\models\Farms */
/* @var $form yii\widgets\ActiveForm */
?>

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
			<td colspan="5" align='left'><?= $form->field($model, 'address')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			</tr>
			<tr>
			<td width=15% align='right'>管理区</td>
			<td align='left'><?= $form->field($model, 'management_area')->dropDownList(ArrayHelper::map(ManagementArea::find()->all(), 'id', 'areaname'))->label(false)->error(false) ?></td>
			<td align='right'>合同号</td>
			<td align='left'><?php if($model->contractnumber == '') $model->contractnumber = Farms::getNewContractnumber()?><?= $form->field($model, 'contractnumber')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
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
			<td align='right'>合同更换日期</td>
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
			<td width=15% align='right'>宗地</td><?= html::hiddenInput('tempzongdi','',['id'=>'temp-zongdi'])?>
			<td colspan="7" align='left'><?= $form->field($model, 'zongdi')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
		  <td align='right'>合同面积</td>
		  <td align='left'><?= $form->field($model, 'contractarea')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
		  <td align='right'>宗地面积</td>
		  <td align='left'><?= $form->field($model, 'measure')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
		  <td align='right'>未明确地块面积</td>
		  <td align='left'><?= $form->field($model, 'notclear')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		  <td align='right'>未明确状态面积</td>
		  <td align='left'><?= $form->field($model, 'notstate')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    </tr>
		<tr>
			<td width=15% align='right'>地产科签字</td>
			<td align='left'><?= $form->field($model, 'groundsign')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			<td align='right'>农场法人签字</td>
			<td align='left'><?= $form->field($model, 'farmersign')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			<td align='right'>状态</td><?php if(!$model->state) $model->state = 1;?>
			<td colspan="3" align='left'><?= $form->field($model, 'state')->radioList(['销户','正常'])->label(false)->error(false) ?></td>
		</tr>
	</table>
	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<?php

$script = <<<JS

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

function toHTH()
{
	//生成合同号
	var hth = $('#farms-contractnumber').val();
	var arrayhth = hth.split('-');
	var contractarea = $('#farms-measure').val()*1 + $('#farms-notclear').val()*1 - $('#farms-notstate').val()*1;
	arrayhth[2] = cutZero(contractarea.toFixed(2));
	$('#farms-contractarea').val(arrayhth[2]);
	$('#farms-contractnumber').val(arrayhth.join('-'));
}
$('#farms-notclear').blur(function(){
	toHTH();
});
$('#farms-notstate').blur(function(){
	toHTH();
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

$("#farms-zongdi").keyup(function (event) {

    var input = $(this).val();
	if (event.keyCode == 32) {
		input = $.trim(input);
		$.getJSON('index.php?r=parcel/parcelarea', {zongdi: input}, function (data) {
			//alert(data.area);
			if (data.status == 1) {
				var value = $('#farms-measure').val()*1+data.area*1;
				$('#farms-measure').val(value.toFixed(2));
				$('#temp_measure').val(value.toFixed(2));
				$('#temp-zongdi').val($.trim(input)+'、');
				$("#farms-zongdi").val($.trim(input)+'、');
				toHTH();
			}
			else {
				alert(data.message);
				$("#farms-zongdi").val($('#temp-zongdi').val());
				toHTH();
			}
		});
	}
	if (event.keyCode == 8) {
		var zongdi = $('#farms-zongdi').val();
		var arrayZongdi = zongdi.split('、');
		var rows = arrayZongdi.length*1 - 1;
		arrayZongdi.splice(rows,1); 
		$('#farms-zongdi').val(arrayZongdi.join('、'));
		var input = $(this).val();
		if(input) {
		    input = $.trim(input);
			$.getJSON('index.php?r=parcel/getformatzongdi', {zongdi: input}, function (data) {
				if (data.status == 1) {
					$("#farms-zongdi").val($.trim(data.formatzongdi));	
					$("#farms-measure").val(data.sum);
					toHTH();
				}	
			});
		} else {
			$("#farms-measure").val(0);
			toHTH();
		}
	}
 });

$('#farms-zongdi').blur(function(){
	var input = $(this).val();
	if(input) {
	    input = $.trim(input);
		$.getJSON('index.php?r=parcel/getformatzongdi', {zongdi: input}, function (data) {
			if (data.status == 1) {
				$("#farms-zongdi").val($.trim(data.formatzongdi));	
				$("#farms-measure").val(data.sum);
				toHTH();
			}	
		});
	} else {
		$("#farms-measure").val(0);
		toHTH();
	}
});
JS;
$this->registerJs($script);





?>
