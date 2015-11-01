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
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        过户
                    </h3>
                </div>
                <div class="box-body">
    <?php $form = ActiveFormrdiv::begin(); ?>
  <table width="100%" border="0">
    <tr>
    <td width="47%">
    <table width="100%" height="408px" class="table table-bordered table-hover">
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
    <td width="4%" align="center"><font size="5"><i class="fa fa-arrow-right"></i></font></td>
    <td width="50%" valign="top">
    <table class="table table-bordered table-hover">
      <tr>
        <td width="25%" align='right'>农场名称</td>
        <td align='left'><?= $form->field($nowModel, 'farmname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>承包人姓名</td>
        <td align='left'><?= $form->field($nowModel, 'farmername')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>身份证号</td>
        <td align='left'><?= $form->field($nowModel, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        </tr>
      <tr>
        <td align='right'>电话号码</td>
        <td align='left'><?= $form->field($nowModel, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        </tr>
      <tr>
        <td align='right'>法人签字</td>
        <td align='left'><?= $form->field($nowModel, 'farmersign')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        </tr>
    </table></td>
  </tr>
</table>
<div class="form-group">
      <?= Html::submitButton($nowModel->isNewRecord ? '添加' : '更新', ['class' => $nowModel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
</div>

    <?php ActiveFormrdiv::end(); ?>
	                </div>
            </div>
        </div>
    </div>
</section>
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
