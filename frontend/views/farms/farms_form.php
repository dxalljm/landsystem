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
			<td align='left'><?= $form->field($model, 'farmname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>农场位置</td>
			<td align='left'><?= $form->field($model, 'address')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>管理区</td>
			<td align='left'><?= $form->field($model, 'management_area')->dropDownList(ArrayHelper::map(ManagementArea::find()->all(), 'id', 'areaname'))->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>审批年度</td>
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
		</tr>
		<tr>
			<td width=15% align='right'>面积</td>
			<td align='left'><?= $form->field($model, 'measure')->textInput(['readonly'=>'readonly'])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>宗地</td>
			<td align='left'><?= $form->field($model, 'zongdi')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>调查日期</td>
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
			<td width=15% align='right'>地产科签字</td>
			<td align='left'><?= $form->field($model, 'groundsign')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>地星调查员</td>
			<td align='left'><?= $form->field($model, 'investigator')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>农场法人签字</td>
			<td align='left'><?= $form->field($model, 'farmersign')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
	</table>
	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<?php

$script = <<<JS
// $("#farms-zongdi").keydown(function(event){ 
// $("div").html("Key: " + event.which); 
// }); 
		
// $(document).ready(  
// 	function() {  
// 	    $("#farms-zongdi").keydown(function(event) {  
// 			var input = $(this).val();
// 		    if (event.keyCode == 32) {  
//  			    //$('#farms-measure').val(input);
// 				$.getJSON('index.php?r=parcel/parcelarea', {zongdi: input}, function (data) {
// 			        if (data.status == 1) {
// 						$('#farms-measure').val(data.area);
// 			        }
// 		   		});
// 		    } 
// 	    }  
//     );
// });

$("#farms-zongdi").keyup(function (event) {
    var input = $(this).val();
	if (event.keyCode == 32) {  
		input = $.trim(input)+'、';  
		$("#farms-zongdi").val(input);
	}
	$.getJSON('index.php?r=parcel/parcelarea', {zongdi: input}, function (data) {
		if (data.status == 1) {
			$('#farms-measure').val(data.area);
		}
	});
 });

JS;
$this->registerJs($script);





?>
