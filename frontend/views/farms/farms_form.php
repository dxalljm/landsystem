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
			<td colspan="5" align='left'><?= $form->field($model, 'measure')->textInput()->label(false)->error(false) ?></td>
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

$('#farms-notclear').keyup(function (event) {
	var input=$(this).val();
	
	//alert(event.keyCode);
	if (event.keyCode == 8 || event.keyCode == 13 || event.keyCode == 48 || event.keyCode == 49 || event.keyCode == 50 || event.keyCode == 51 || event.keyCode == 52 || event.keyCode == 53 || event.keyCode == 54 || event.keyCode == 55 || event.keyCode == 56 || event.keyCode == 57 || event.keyCode == 110 || event.keyCode == 190 || event.keyCode == 96 || event.keyCode == 97 || event.keyCode == 98 || event.keyCode == 99 || event.keyCode == 100 || event.keyCode == 101 || event.keyCode == 102 || event.keyCode == 103 || event.keyCode == 104 || event.keyCode == 105) {  
		if(event.keyCode == 8) {
			$(this).val('');
			$('#farms-measure').val($('#temp_measure').val());
			if($('#temp_notclear').val() !== '') {
				$('#farms-measure').val($('#farms-measure').val()-$('#temp_notclear').val());
			}
		} else {
			if(event.keyCode == 13) {
				measure = $('#farms-measure').val();
				if($('#temp_notclear').val() == '') {
					$('#farms-measure').val(measure*1+input*1);
				}
				
				if(input == $('#temp_notclear').val()){

					$('#farms-measure').val($('#temp_measure').val());
				}
				else
					$('#farms-measure').val(measure*1+input*1);
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
