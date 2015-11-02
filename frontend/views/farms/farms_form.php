<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Parcel;
use app\models\ManagementArea;
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
			<td colspan="5" align='left'><?= $form->field($model, 'farmname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>承包人姓名</td>
			<td colspan="5" align='left'><?= $form->field($model, 'farmername')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			</tr>
			<tr>
			<td width=15% align='right'>身份证号</td>
			<td colspan="5" align='left'><?= $form->field($model, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			</tr>
			<tr>
			<td width=15% align='right'>电话号码</td>
			<td colspan="5" align='left'><?= $form->field($model, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
			</tr>
		<tr>
			<td width=15% align='right'>农场位置</td>
			<td colspan="5" align='left'><?= $form->field($model, 'address')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>管理区</td>
			<td colspan="5" align='left'><?= $form->field($model, 'management_area')->dropDownList(ArrayHelper::map(ManagementArea::find()->all(), 'id', 'areaname'))->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>合同号</td>
			<td colspan="5" align='left'><?= $form->field($model, 'contractnumber')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>承包年限</td>
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
			<td align='center'>至</td>
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
		</tr>
		<tr>
			<td width=15% align='right'>审批年度</td>
			<td colspan="5" align='left'><?= $form->field($model, 'spyear')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
		</tr>
		<tr>
			<td width=15% align='right'>面积</td><?= html::hiddenInput('tempmeasure',$model->measure,['id'=>'temp_measure']) ?>
												<?= html::hiddenInput('tempnotclear',$model->notclear,['id'=>'temp_notclear']) ?>
			<td colspan="5" align='left'><?= $form->field($model, 'measure')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>宗地</td>
			<td colspan="5" align='left'><?= $form->field($model, 'zongdi')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>未明确地块面积</td>
			<td colspan="5" align='left'><?= $form->field($model, 'notclear')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>调查日期</td>
			<td colspan="5" align='left'><?= $form->field($model, 'surveydate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
			<td width=15% align='right'>地产科签字</td>
			<td colspan="5" align='left'><?= $form->field($model, 'groundsign')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>地星调查员</td>
			<td colspan="5" align='left'><?= $form->field($model, 'investigator')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>农场法人签字</td>
			<td colspan="5" align='left'><?= $form->field($model, 'farmersign')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
	</table>
	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<?php

$script = <<<JS
$('#farms-notclear').blur(function(){
	var input = $(this).val();
	//$('#farms-measure').val(input);
	if(input > $('#temp_notclear').val()) {
		var tempmeasure = $('#temp_measure').val();
		var farmsmeasure = $('#farms-measure').val();
		if(farmsmeasure < tempmeasure) {
			var result = farmsmeasure*1 + input*1;
			$('#temp_measure').val(result.toFixed(2));
			$('#farms-measure').val(result.toFixed(2));
			$('#temp_notclear').val(input);	
			
		} else {
			var cha = input*1 - $('#temp_notclear').val()*1;
			var result = tempmeasure*1 + cha*1;
			$('#temp_measure').val(result.toFixed(2));
			$('#farms-measure').val(result.toFixed(2));
			$('#temp_notclear').val(input);	
		}
	} 
	if(input < $('#temp_notclear').val()) {
		var tempmeasure = $('#temp_measure').val();
		var farmsmeasure = $('#farms-measure').val();
		if(farmsmeasure < tempmeasure) {
			var result = farmsmeasure*1 + input*1;
			$('#temp_measure').val(result.toFixed(2));
			$('#farms-measure').val(result.toFixed(2));
			
			$('#temp_notclear').val(input);	
		} else {
			var cha = $('#temp_notclear').val()*1 - input*1;
			var result = tempmeasure*1 - cha*1;
			$('#temp_measure').val(result.toFixed(2));
			$('#farms-measure').val(result.toFixed(2));
			$('#temp_notclear').val(input);	
		}
	}

});
$('#farms-notclear').keyup(function (event) {
	var input = $(this).val();
	if(/^\d+(\.\d+)?$/.test(input)) {
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
		input = $.trim(input)+'、';  
		$("#farms-zongdi").val(input);
	}
	$.getJSON('index.php?r=parcel/parcelarea', {zongdi: input}, function (data) {
		if (data.status == 1) {
			$('#farms-measure').val(data.area);
			$('#temp_measure').val(data.area);
		}
	});
 });

JS;
$this->registerJs($script);





?>
