<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Parcel;
use app\models\ManagementArea;
use app\models\Lease;
/* @var $this yii\web\View */
/* @var $model app\models\Farms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farms-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
  <table width="100%" border="0">
    <tr>
    <td width="46%"><table width="104%" height="458px"
		class="table table-bordered table-hover">
      <tr>
        <td width="15%" align='right' valign="middle">农场名称</td>
        <td align='left' valign="middle"><?= $oldFarm->farmname?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">承包人姓名</td>
        <td align='left' valign="middle"><?= $oldFarm->farmername ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">身份证号</td>
        <td align='left' valign="middle"><?= $oldFarm->cardid ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">电话号码</td>
        <td align='left' valign="middle"><?= $oldFarm->telephone ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">农场位置</td>
        <td align='left' valign="middle"><?= $oldFarm->address?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">宗地</td>
        <td align='left' valign="middle"><?php $arrayZongdi = explode('、', $oldFarm->zongdi);
        $i=0;
        foreach($arrayZongdi as $value) {
        	echo html::button($value,['onclick'=>'toZongdi("'.$value.'","'.Lease::zongdiToArea($value).'")','value'=>$value,'id'=>$value,'class'=>"btn btn-default"]).'&nbsp;&nbsp;&nbsp;';
        	$i++;
        	if($i%5 == 0)
        		echo '<br><br>';
        }
        ?></td>
        </tr>
      <tr><?= Html::hiddenInput('oldzongdi',$oldFarm->zongdi,['id'=>'oldfarm-zongdi']) ?>
      <?= Html::hiddenInput('ttpozongdi','',['id'=>'ttpozongdi-zongdi']) ?>
      <?= Html::hiddenInput('ttpoarea',0,['id'=>'ttpozongdi-area']) ?>
        <td align='right' valign="middle">面积</td>
        <td align='left' valign="middle"><?= html::textInput('oldmeasure',$oldFarm->measure,['readonly' => true,'id'=>'oldfarms-measure','class'=>'form-control']) ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">未明确地块</td>
        <td align='left' valign="middle"><?= html::textInput('oldnotclear',$oldFarm->notclear,['readonly' => true,'id'=>'oldfarms-notclear','class'=>'form-control']) ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">法人签字</td>
        <td align='left' valign="middle"><?= $oldFarm->farmersign ?></td>
        </tr>
    </table></td>
    <td width="4%" align="center"><font size="5"><i class="fa fa-arrow-right"></i></font></td>
    <td width="50%"><table width="99%" height="458px"
		class="table table-bordered table-hover">
      <tr>
        <td width="30%" align='right'>农场名称</td>
        <td align='left'><?= $model->farmname ?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>承包人姓名</td>
        <td align='left'><?= $model->farmername ?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>身份证号</td>
        <td align='left'><?= $model->cardid?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>电话号码</td>
        <td align='left'><?= $model->telephone?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>农场位置</td>
        <td align='left'><?= $model->address ?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>宗地</td>
        <td align='left'><?= $form->field($model, 'zongdi')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
        </tr>
      <tr>
        <td align='right'>面积</td><?= html::hiddenInput('tempmeasure',$model->measure,['id'=>'temp_measure']) ?>
												<?= html::hiddenInput('tempnotclear',$model->notclear,['id'=>'temp_notclear']) ?>
        <td align='left'><?= $form->field($model, 'measure')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
        </tr>
      <tr>
        <td align='right'>未明确地块面积</td>
        <td align='left'><?= $form->field($model, 'notclear')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
       </tr>
       <tr>
        <td align='right'>转让未明确地块面积</td>
        <td align='left'><?= html::textInput('inputnotclear','',['id'=>'input-notclear','class'=>'form-control']) ?></td>
       </tr>
      <tr>
        <td align='right'>法人签字</td>
        <td align='left'><?= $model->farmersign ?></td>
        </tr>
    </table></td>
  </tr>
</table>
<div class="form-group">
      <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      <?= Html::button('重置', ['class' => 'btn btn-primary','id'=>'reset']) ?>
</div>

    <?php ActiveFormrdiv::end(); ?>
    <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
function toZongdi(zongdi,area){
	$('#'+zongdi).attr('disabled',true);
	var value = $('#oldfarms-measure').val()*1-area*1;
	$('#oldfarms-measure').val(value.toFixed(2));
	var newvalue = $('#farms-measure').val()*1 + area*1;
	$('#farms-measure').val(newvalue.toFixed(2));
	var newzongdi = $('#farms-zongdi').val()+'、'+zongdi;
	$('#farms-zongdi').val(newzongdi);
	var oldzongdi = $('#oldfarm-zongdi').val();
	$('#oldfarm-zongdi').val(oldzongdi.replace(zongdi, ""));
	oldzongdistr = $('#oldfarm-zongdi').val();
	var first = oldzongdistr.substr(0,1);
	var last = oldzongdistr.substr(oldzongdistr.length-1,1);
	if(first == '、') {
		$('#oldfarm-zongdi').val(oldzongdistr.substring(1));
	}
	if(last == '、') {
		$('#oldfarm-zongdi').val(oldzongdistr.substring(0,oldzongdistr.length-1));
	}
	
	var ttpozongdi = $('#ttpozongdi-zongdi').val();
	ttpozongdi = ttpozongdi + '、' + zongdi;
	var first = ttpozongdi.substr(0,1);
	if(first == '、') {
		$('#ttpozongdi-zongdi').val(ttpozongdi.substring(1));
	}
	else
		$('#ttpozongdi-zongdi').val(ttpozongdi);
	
	var ttpoarea = $('#ttpozongdi-area').val();
	ttpoarea = area*1 + ttpoarea*1;
	$('#ttpozongdi-area').val(ttpoarea);
	
}
$('#reset').click(function() {
	 
    location.reload();

});
$('#input-notclear').keyup(function (event) {
	var input=$(this).val();

	if (event.keyCode == 8 || event.keyCode == 13 || event.keyCode == 48 || event.keyCode == 49 || event.keyCode == 50 || event.keyCode == 51 || event.keyCode == 52 || event.keyCode == 53 || event.keyCode == 54 || event.keyCode == 55 || event.keyCode == 56 || event.keyCode == 57 || event.keyCode == 110 || event.keyCode == 190 || event.keyCode == 96 || event.keyCode == 97 || event.keyCode == 98 || event.keyCode == 99 || event.keyCode == 100 || event.keyCode == 101 || event.keyCode == 102 || event.keyCode == 103 || event.keyCode == 104 || event.keyCode == 105) { 
		if(input*1 > $('#oldfarms-notclear').val()*1)
			alert('输入的面积不能大于原农场未明确地块面积');
		else {
			if(event.keyCode == 8) {
				$(this).val('');
				$('#farms-measure').val($('#temp_measure').val());
				$('#farms-notclear').val($('#temp_notclear').val());
// 				if($('#temp_notclear').val() !== '') {
// 					$('#farms-measure').val($('#farms-measure').val()-$('#temp_notclear').val());
// 				}
			} else {
				if(event.keyCode == 13) {
					measure = $('#farms-measure').val();
					if($('#temp_notclear').val() == '') {
						$('#farms-measure').val(measure*1+input*1);
						$('#farms-notclear').val($('#farms-notclear').val()*1+input*1)
						$('#oldfarms-measure').val($('#oldfarms-measure').val()*1-input*1);
						$('#oldfarms-notclear').val($('#oldfarms-notclear').val()*1-input*1)
						
						var ttpozongdi = $('#ttpozongdi-zongdi').val();
						ttpozongdi = ttpozongdi + '、' + '未明确地块(' + input + ')';
						var first = ttpozongdi.substr(0,1);
						if(first == '、') {
							$('#ttpozongdi-zongdi').val(ttpozongdi.substring(1));
						}
						else
							$('#ttpozongdi-zongdi').val(ttpozongdi);
						
						var ttpoarea = $('#ttpozongdi-area').val();
						ttpoarea = input*1 + ttpoarea*1;
						$('#ttpozongdi-area').val(ttpoarea);
					} else {
					
// 						if(input == $('#temp_notclear').val()){
// 							$('#farms-measure').val($('#temp_measure').val());
// 							$('#farms-notclear').val($('#temp_notclear').val());
// 							var ttpozongdi = $('#ttpozongdi-zongdi').val();
// 							ttpozongdi = ttpozongdi + '、' + '未明确地块(' + input + ')';
// 							var first = ttpozongdi.substr(0,1);
// 							if(first == '、') {
// 								$('#ttpozongdi-zongdi').val(ttpozongdi.substring(1));
// 							}
// 							else
// 								$('#ttpozongdi-zongdi').val(ttpozongdi);
							
// 							var ttpoarea = $('#ttpozongdi-area').val();
// 							ttpoarea = input*1 + ttpoarea*1;
// 							$('#ttpozongdi-area').val(ttpoarea);
// 						}
// 						else {
							$('#farms-measure').val(measure*1+input*1);
							$('#farms-notclear').val($('#farms-notclear').val()*1+input*1);
							$('#oldfarms-measure').val($('#oldfarms-measure').val()*1-input*1);
							$('#oldfarms-notclear').val($('#oldfarms-notclear').val()*1-input*1);
							var ttpozongdi = $('#ttpozongdi-zongdi').val();
							ttpozongdi = ttpozongdi + '、' + '未明确地块(' + input + ')';
							var first = ttpozongdi.substr(0,1);
							if(first == '、') {
								$('#ttpozongdi-zongdi').val(ttpozongdi.substring(1));
							}
							else
								$('#ttpozongdi-zongdi').val(ttpozongdi);
							
							var ttpoarea = $('#ttpozongdi-area').val();
							ttpoarea = input*1 + ttpoarea*1;
							$('#ttpozongdi-area').val(ttpoarea);
// 						}
					}
				}
			}
			//$('#oldfarms-notclear').val($('#oldfarms-notclear').val()-input);
		}
	}
	else {
		alert('输入的必须为数字');
		var last = input.substr(input.length-1,1);
		$('#farms-notclear').val(input.substring(0,input.length-1));
		
	}
});
</script>
<?php

$script = <<<JS


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
