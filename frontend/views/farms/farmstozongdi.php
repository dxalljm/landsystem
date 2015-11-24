<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Parcel;
use app\models\ManagementArea;
use app\models\Lease;
use app\models\Farms;
use app\models\Loan;

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
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
                <?php if(!Farms::getLocked($_GET['oldfarms_id'])) {?>
                <?php $form = ActiveFormrdiv::begin(); ?>
  <table width="100%" border="0">
    <tr>
    <td width="46%"><table width="104%" height="458px"
		class="table table-bordered table-hover">
      <tr>
        <td width="15%" align='right' valign="middle">农场名称</td>
        <td colspan="5" align='left' valign="middle"><?= $oldFarm->farmname?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">承包人姓名</td>
        <td colspan="5" align='left' valign="middle"><?= $oldFarm->farmername ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">身份证号</td>
        <td colspan="5" align='left' valign="middle"><?= $oldFarm->cardid ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">电话号码</td>
        <td colspan="5" align='left' valign="middle"><?= $oldFarm->telephone ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">农场位置</td>
        <td colspan="5" align='left' valign="middle"><?= $oldFarm->address?></td>
        </tr>
      <tr>
      <tr>
        <td width="20%" align='right' valign="middle">管理区</td>
        <td colspan="5" align='left' valign="middle"><?= ManagementArea::find()->where(['id'=>$oldFarm->management_area])->one()['areaname']?></td>
        </tr>
      <tr>
      <tr>
			<td width=15% align='right'>合同号</td>
			<td colspan="5" align='left'><?= html::textInput('oldcontractnumber','',['id'=>'oldfarms-contractnumber','class'=>'form-control'])?></td>
		</tr>
		<tr>
			<td width=15% align='right'>承包年限</td>
			<td align='center'>自</td>
			<td align='center'><?php echo DateTimePicker::widget([
				'name' => 'oldbegindate',
				'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
				'options' => [
					'readonly' => true
				],
				'clientOptions' => [
					'language' => 'zh-CN',
					'format' => 'yyyy-mm-dd',
					'todayHighlight' => true,
					'autoclose' => true,
					'minView' => 3,
					'maxView' => 3,
				]
			]);?></td>
			<td align='center'>至</td>
			<td align='center'><?php echo DateTimePicker::widget([
				'name' => 'oldenddate',
				'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
				//'type' => DatePicker::TYPE_COMPONENT_APPEND,
				'options' => [
					'readonly' => true
				],
				'clientOptions' => [
					'language' => 'zh-CN',
					'format' => 'yyyy-mm-dd',
					//'todayHighlight' => true,
					'autoclose' => true,
					'minView' => 3,
					'maxView' => 3,
				]
			]);?></td>
			<td align='center'>止</td>
		</tr>
        <td width="20%" align='right' valign="middle">宗地</td>
        <td colspan="5" align='left' valign="middle"><?php $arrayZongdi = explode('、', $oldFarm->zongdi);
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
        <td align='right' valign="middle">面积</td><?= html::hiddenInput('tempoldnotclear',$oldFarm->measure,['id'=>'temp_oldnotclear']) ?>
        <td colspan="5" align='left' valign="middle"><?= html::textInput('oldmeasure',$oldFarm->measure,['readonly' => true,'id'=>'oldfarms-measure','class'=>'form-control']) ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">未明确地块</td>
        <td colspan="5" align='left' valign="middle"><?= html::textInput('oldnotclear',$oldFarm->notclear,['readonly' => true,'id'=>'oldfarms-notclear','class'=>'form-control']) ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">法人签字</td>
        <td colspan="5" align='left' valign="middle"><?= html::textInput('oldfarmersign', $oldFarm->farmersign ,['class'=>'form-control'])?></td>
        </tr>
    </table></td>
    <td width="4%" align="center"><font size="5"><i class="fa fa-arrow-right"></i></font></td>
    <td width="50%"><table width="99%" height="458px"
		class="table table-bordered table-hover">
      <tr>
        <td width="30%" align='right'>农场名称</td>
        <td colspan="5" align='left'><?= html::textInput('farmname',$model->farmname,['class'=>'form-control'])  ?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>承包人姓名</td>
        <td colspan="5" align='left'><?= $model->farmername ?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>身份证号</td>
        <td colspan="5" align='left'><?= $model->cardid?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>电话号码</td>
        <td colspan="5" align='left'><?= $model->telephone?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>农场位置</td>
        <td colspan="5" align='left'><?= $model->address ?></td>
        </tr>
       <tr>
        <td width="20%" align='right' valign="middle">管理区</td>
        <td colspan="5" align='left' valign="middle"><?= ManagementArea::find()->where(['id'=>$model->management_area])->one()['areaname']?></td>
        </tr>
      <tr>
        <td width="20%" align='right'>宗地</td><?php $model->zongdi = '';?>
        <td colspan="5" align='left'><?= $form->field($model, 'zongdi')->textarea(['readonly' => true])->label(false)->error(false) ?></td>
        </tr>
      <tr>
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
		</tr><?php $model->notclear = '';$model->measure = '';?>
        <td align='right'>面积</td><?= html::hiddenInput('tempmeasure',$model->measure,['id'=>'temp_measure']) ?>
								  <?= html::hiddenInput('tempnotclear',$model->notclear,['id'=>'temp_notclear']) ?>
        <td colspan="5" align='left'><?= $form->field($model, 'measure')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
        </tr>
      <tr>
        <td align='right'>未明确地块面积</td>
        <td colspan="5" align='left'><?= $form->field($model, 'notclear')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
       </tr>
       <tr>
        <td align='right'>转让未明确地块面积</td>
        <td colspan="5" align='left'><?= html::textInput('inputnotclear','',['id'=>'input-notclear','class'=>'form-control']) ?></td>
       </tr>
      <tr>
        <td align='right'>法人签字</td>
        <td colspan="5" align='left'><?= $form->field($model, 'farmersign')->textInput()->label(false)->error(false) ?></td>
        </tr>
    </table></td>
  </tr>
</table>
<div class="form-group">
      <?= Html::submitButton('提交', ['class' =>  'btn btn-success']) ?>
      <?= Html::button('重置', ['class' => 'btn btn-primary','id'=>'reset']) ?>
      <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
</div>

    <?php ActiveFormrdiv::end(); ?>
 <?php } else {?>
    	<h4>此农场因贷款事由现已被冻结，解冻日期为<?= Loan::find()->where(['farms_id'=>$_GET['oldfarms_id']])->one()['enddate'];?></h4>
    <?php }?>   
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
	$('#temp_measure').val(newvalue.toFixed(2));
	var newzongdi = $('#farms-zongdi').val()+'、'+zongdi;
	var first = newzongdi.substr(0,1);
	if(first == '、') {
		$('#farms-zongdi').val(newzongdi.substring(1));
	} else
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
	//alert($('#oldfarm-zongdi').val());
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
$('#input-notclear').blur(function(){
	var input = $(this).val();
	if(input*1 > $('#temp-oldnotclear').val()*1) {
		
		alert('输入的数值不能大于'+$('#temp-oldnotclear').val());
		$('#input-notclear').focus();
	} else {
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
				var oldmeasure = $('#oldfarms-measure').val();
				var oldresult = oldmeasure*1 - cha*1;
				$('#oldfarms-measure').val(oldresult.toFixed(2));
				var oldnotclear = $('#oldfarms-notclear').val();
				var notclearresult = oldnotclear*1 - cha*1;
				$('#oldfarms-notclear').val(notclearresult.toFixed(2));
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
	}
});
$('#input-notclear').keyup(function (event) {
	var input = $(this).val();
	if(/^\d+(\.\d+)?$/.test(input)) {
		if(event.keyCode == 8) {
			$(this).val('');
			
			if($('#temp_notclear').val() !== '') {
				var result = $('#farms-measure').val()-$('#temp_notclear').val();
				$('#farms-measure').val(result.toFixed(2));
				$('#oldfarms-measure').val($('#oldfarms-measure').val()*1+$('#temp_notclear').val()*1);
				$('#oldfarms-notclear').val($('#oldfarms-notclear').val()*1+$('#temp_notclear').val()*1);	
				$('#temp_notclear').val('');
				$('#temp_measure').val('');
			}
		
		}
	} else {
		alert('输入的必须为数字');
		var last = input.substr(input.length-1,1);
		$('#input-notclear').val(input.substring(0,input.length-1));
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
