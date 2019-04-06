<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
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
  <table width="100%" border="0">
    <tr>
    <td width="46%"><table width="104%" height="308px"
		class="table table-bordered table-hover">
      <tr>
        <td width="20%" align='right' valign="middle">农场名称</td>
        <td width="30%" align='left' valign="middle"><?= $model->farmname?></td>
        <td width="20%" align='left' valign="middle">承包人姓名</td>
        <td width="30%" align='left' valign="middle"><?= $model->farmername ?></td>
      </tr>
      <tr>
        <td width="20%" align='right' valign="middle">身份证号</td>
        <td align='left' valign="middle"><?= $model->cardid ?></td>
        <td align='left' valign="middle">电话号码</td>
        <td align='left' valign="middle"><?= $model->telephone ?></td>
      </tr>
      <tr>
        <td width="20%" align='right' valign="middle">农场位置</td>
        <td colspan="3" align='left' valign="middle"><?= $model->address?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">宗地</td>
        <td colspan="3" align='left' valign="middle"><?= $model->zongdi?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">面积</td>
        <td align='left' valign="middle"><?= $model->measure ?></td>
        <td align='left' valign="middle">未明确地块</td>
        <td align='left' valign="middle"><?= $model->notclear?></td>
      </tr>
      <tr>
        <td width="20%" align='right' valign="middle">法人签字</td>
        <td align='left' valign="middle"><?= $model->farmersign ?></td>
        <td align='left' valign="middle">&nbsp;</td>
        <td align='left' valign="middle">&nbsp;</td>
      </tr>
    </table></td>
    <td width="4%" align="center">=&gt;</td>
    <td width="50%"><table width="99%"
		class="table table-bordered table-hover">
      <tr>
        <td width="20%" align='right'>农场名称</td>
        <td width="30%" align='left'><?= $form->field($model, 'farmname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        <td width="20%" align='left'>承包人姓名</td>
        <td width="30%" align='left'><?= $form->field($model, 'farmername')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
      </tr>
      <tr>
        <td width="20%" align='right'>身份证号</td>
        <td align='left'><?= $form->field($model, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        <td align='left'>电话号码</td>
        <td align='left'><?= $form->field($model, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
      </tr>
      <tr>
        <td width="20%" align='right'>农场位置</td>
        <td colspan="3" align='left'><?= $form->field($model, 'address')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>宗地</td>
        <td colspan="3" align='left'><?= $form->field($model, 'zongdi')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>面积</td>
        <td align='left'><?= $form->field($model, 'measure')->textInput()->label(false)->error(false) ?></td>
        <td align='left'>未明确地块</td>
        <td align='left'><?= $form->field($model, 'notclear')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
      </tr>
      <tr>
        <td width="20%" align='right'>法人签字</td>
        <td align='left'><?= $form->field($model, 'farmersign')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        <td align='left'>&nbsp;</td>
        <td align='left'>&nbsp;</td>
      </tr>
    </table></td>
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
