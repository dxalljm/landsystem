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
use yii\helpers\Url;

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
    <td width="46%" valign="top"><table width="104%" height="458px"
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
			<td width=15% align='right'>合同号</td><?php if($oldFarm->contractnumber == '') $oldFarm->contractnumber = Farms::getContractnumber($_GET['farms_id']);?>
			<td colspan="5" align='left'><?= html::textInput('oldcontractnumber',$oldFarm->contractnumber,['id'=>'oldfarms-contractnumber','class'=>'form-control'])?></td>
		</tr>
		<tr>
			<td width=15% align='right'>承包年限</td>
			<td align='center'>自</td>
			<td align='center'><?php echo DateTimePicker::widget([
				'name' => 'oldbegindate',
				'value' => $oldFarm->begindate,
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
				'value' => $oldFarm->enddate,
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
		<tr>
        <td width="20%" align='right' valign="middle">宗地</td>
        <td colspan="5" align='left' valign="middle"><?php $arrayZongdi = explode('、', $oldFarm->zongdi);
        $i=0;
        foreach($arrayZongdi as $value) {
        	if($value !== '') {
	        	echo html::button($value,['onclick'=>'toZongdi("'.Lease::getZongdi($value).'","'.Lease::getArea($value).'")','value'=>$value,'id'=>Lease::getZongdi($value),'class'=>"btn btn-default"]).'&nbsp;&nbsp;&nbsp;';
	        	$i++;
	        	if($i%5 == 0)
	        		echo '<br><br>';
        	}
        }
        ?></td>
        </tr>
      <tr><?= Html::hiddenInput('oldzongdi',$oldFarm->zongdi,['id'=>'oldfarm-zongdi']) ?>
      <?= Html::hiddenInput('ttpozongdi','',['id'=>'ttpozongdi-zongdi']) ?>
      <?= Html::hiddenInput('ttpoarea',0,['id'=>'ttpozongdi-area']) ?>
        <td align='right' valign="middle">宗地面积</td><?= html::textInput('tempoldmeasure',$oldFarm->measure,['id'=>'temp_oldmeasure']) ?>
        										 <?= html::textInput('tempoldnotclear',$oldFarm->notclear,['id'=>'temp_oldnotclear']) ?>
        										 <?= html::textInput('tempoldnotstate',$oldFarm->notstate,['id'=>'temp_oldnotstate']) ?>
        <td colspan="5" align='left' valign="middle"><?= html::textInput('oldmeasure',$oldFarm->measure,['readonly' => true,'id'=>'oldfarms-measure','class'=>'form-control']) ?></td>
        </tr>
        <tr>
        <td align='right' valign="middle">合同面积</td>
        <td colspan="5" align='left' valign="middle"><?= html::textInput('oldcontractarea',$oldFarm->contractarea,['readonly' => true,'id'=>'oldfarms-contractarea','class'=>'form-control']) ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">未明确地块面积</td>
        <td colspan="5" align='left' valign="middle"><?= html::textInput('oldnotclear',$oldFarm->notclear,['readonly' => true,'id'=>'oldfarms-notclear','class'=>'form-control']) ?></td>
        </tr>
         <tr>
        <td align='right' valign="middle">未明确状态面积</td>
        <td colspan="5" align='left' valign="middle"><?= html::textInput('oldnotstate',$oldFarm->notstate,['readonly' => true,'id'=>'oldfarms-notstate','class'=>'form-control']) ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">法人签字</td>
        <td colspan="5" align='left' valign="middle"><?= html::textInput('oldfarmersign', $oldFarm->farmersign ,['class'=>'form-control'])?></td>
        </tr>
    </table></td>
    <td width="4%" align="center"><font size="5"><i class="fa fa-arrow-right"></i></font></td>
    <td width="50%">
    <table width="99%" height="458px" class="table table-bordered table-hover">
      <tr>
        <td width="30%" align='right'>农场名称</td>
        <td colspan="4" align='left'><?=  $form->field($newFarm, 'farmname')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchFarms','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>承包人姓名</td>
        <td colspan="4" align='left'><?=  $form->field($newFarm, 'farmername')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchFarmer','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>身份证号</td>
        <td colspan="4" align='left'><?=  $form->field($newFarm, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchCardid','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>电话号码</td>
        <td colspan="4" align='left'><?=  $form->field($newFarm, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchTelephone','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>农场位置</td>
        <td colspan="5" align='left'><?=  $oldFarm->address?></td>
        </tr>
       <tr>
        <td width="30%" align='right' valign="middle">管理区</td>
        <td colspan="5" align='left' valign="middle"><?=  ManagementArea::find()->where(['id'=>$oldFarm->management_area])->one()['areaname']?></td>
        </tr>
       <tr>
			<td width=30% align='right'>合同号</td><?php $newFarm->contractnumber = Farms::getContractnumber($_GET['farms_id'],'new');?>
			<td colspan="5" align='left'><?= $form->field($newFarm, 'contractnumber')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=30% align='right'>承包年限</td>
			<td align='center'>自</td><?php if($oldFarm->begindate == '') $newFarm->begindate='2010-09-13'; else $newFarm->begindate = $oldFarm->begindate;if($oldFarm->enddate == '') $newFarm->enddate = '2025-09-13';else $newFarm->enddate = $oldFarm->enddate;?>
			<td align='center'><?= $form->field($newFarm, 'begindate')->textInput()->label(false)->error(false)->widget(
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
			<td align='center'><?= $form->field($newFarm, 'enddate')->textInput()->label(false)->error(false)->widget(
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
		  <td align='right'>宗地</td><?= html::hiddenInput('tempzongdi','',['id'=>'temp-zongdi'])?>
		  <td colspan="5" align='left'><?= $form->field($newFarm, 'zongdi')->textarea(['readonly' => false])->label(false)->error(false) ?></td>
		  </tr>
		<?php $newFarm->notclear = 0;$newFarm->measure = 0;?>
		<tr>
        <td align='right'>宗地面积</td><?= html::textInput('tempmeasure',$newFarm->measure,['id'=>'temp_measure']) ?>
								  <?= html::textInput('tempnotclear',$newFarm->notclear,['id'=>'temp_notclear']) ?>
        <td colspan="5" align='left'><?= $form->field($newFarm, 'measure')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
        </tr>
        <tr>
        <td align='right'>合同面积</td>
        <td colspan="5" align='left'><?= $form->field($newFarm, 'contractarea')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
       </tr>
      <tr>
        <td align='right'>未明确地块面积</td>
        <td colspan="5" align='left'><?= $form->field($newFarm, 'notclear')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
       </tr>
        <tr>
        <td align='right'>未明确状态面积</td>
        <td colspan="5" align='left'><?= $form->field($newFarm, 'notstate')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
       </tr>
       <tr>
        <td align='right'>转让未明确地块面积</td>
        <td colspan="5" align='left'><?= html::textInput('inputnotclear','',['id'=>'input-notclear','class'=>'form-control']) ?></td>
       </tr>
      <tr>
        <td align='right'>法人签字</td>
        <td colspan="5" align='left'><?= $form->field($newFarm, 'farmersign')->textInput()->label(false)->error(false) ?></td>
        </tr>
    </table></td>
  </tr>
</table>
<div class="form-group">
      <?= Html::submitButton('提交', ['class' =>  'btn btn-success']) ?>
      <?= Html::button('重置', ['class' => 'btn btn-primary','id'=>'reset']) ?>
      <?= Html::submitButton('继续转让', ['class' =>  'btn btn-success']) ?>
      <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
</div>

    <?php ActiveFormrdiv::end(); ?>
    
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
	var newzongdi = $('#farms-zongdi').val()+'、'+zongdi+'('+area+')';
	var first = newzongdi.substr(0,1);
	if(first == '、') {
		$('#farms-zongdi').val(newzongdi.substring(1));
	} else
		$('#farms-zongdi').val(newzongdi);
	var oldzongdi = $('#oldfarm-zongdi').val();
	var strzongdi = zongdi+"("+area+")";
	$('#oldfarm-zongdi').val(oldzongdi.replace(strzongdi, ""));
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
	toHTH();
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
	
	var hth = $('#oldfarms-contractnumber').val();
	var arrayhth = hth.split('-');
	var oldcontractarea = $('#oldfarms-measure').val()*1 + $('#oldfarms-notclear').val()*1 - $('#oldfarms-notstate').val()*1;
	arrayhth[2] = cutZero(oldcontractarea.toFixed(2));
	$('#oldfarms-contractarea').val(arrayhth[2]);
	$('#oldfarms-contractnumber').val(arrayhth.join('-'));
}
$('#input-notclear').blur(function(){
	var input = $(this).val();
	if(input*1 > $('#temp_oldnotclear').val()*1) {
		
		alert('输入的数值不能大于'+$('#temp_oldnotclear').val());
		$(this).val(0);
		$(this).focus();
	} else {
		
		if(input > $('#temp_notclear').val()) {
			
				var tempmeasure = $('#temp_measure').val();
				var farmsmeasure = $('#farms-measure').val();
				if(farmsmeasure < tempmeasure) {
					var result = farmsmeasure*1 + input*1;
					
					$('#farms-notclear').val(input);
					$('#temp_notclear').val(input);	
				} else {
					var oldmeasure = parseFloat($('#oldfarms-measure').val());
					
					
						var cha = input*1 - $('#temp_notclear').val()*1;
						var result = farmsmeasure*1 + cha*1;
						
						var oldresult = oldmeasure*1 - cha*1;
					if(oldmeasure != 0) {
						
						var oldnotclear = $('#oldfarms-notclear').val();
						var notclearresult = oldnotclear*1 - cha*1;
						$('#oldfarms-notclear').val(notclearresult.toFixed(2));
						
						$('#farms-notclear').val(input);
						$('#temp_notclear').val(input);	
					} else {
						$('#farms-contractarea').val(input);
						$('#farms-notclear').val(input);
						
						var oldnotclear = $('#temp_oldnotclear').val();

						var notclearresult = oldnotclear*1 - input*1;
						
						$('#oldfarms-notclear').val(notclearresult.toFixed(2));
						
					}
				}
			
		} 
		if(input < $('#temp_notclear').val()) {
			if($('#farms-measure').val() !== 0 ) {
				var tempmeasure = $('#temp_measure').val();
				var farmsmeasure = $('#farms-measure').val();
				if(farmsmeasure < tempmeasure) {
					var result = farmsmeasure*1 + input*1;
					$('#temp_measure').val(result.toFixed(2));
					$('#farms-measure').val(result.toFixed(2));
					$('#farms-notclear').val(input);	
					$('#temp_notclear').val(input);	
					
				} else {
					var oldmeasure = $('#oldfarms-measure').val();
// 					alert(oldmeasure);
					if(oldmeasure != 0) {
						var cha = $('#temp_notclear').val()*1 - input*1;
						var result = farmsmeasure*1 - cha*1;
						
						var oldresult = oldmeasure*1 + cha*1;
						$('#oldfarms-measure').val(oldresult.toFixed(2));
						var oldnotclear = $('#oldfarms-notclear').val();
						var notclearresult = oldnotclear*1 + cha*1;
						$('#oldfarms-notclear').val(notclearresult.toFixed(2));
						$('#temp_measure').val(result.toFixed(2));
						$('#farms-measure').val(result.toFixed(2));
						$('#farms-notclear').val(input);
						$('#temp_notclear').val(input);	
					}
				}
			}
		}
	}
	toHTH();
});
$('#input-notclear').keyup(function (event) {
	var input = $(this).val();
	if(event.keyCode == 8) {
		$(this).val('');
		$('#farms-notclear').val(0);
		var result = $('#farms-measure').val()-$('#temp_notclear').val();
		$('#farms-contractarea').val(result.toFixed(2));
		$('#oldfarms-notclear').val($('#temp_oldnotclear').val());	
		$('#temp_notclear').val(0);
		$('#temp_measure').val(0);
		toHTH();
	} else {
		if(/^\d+(\.\d+)?$/.test(input)) {
			if($('#temp_notclear').val() !== '') {
				var result = $('#farms-measure').val()-$('#temp_notclear').val();
				$('#farms-measure').val(result.toFixed(2));
				$('#oldfarms-measure').val($('#oldfarms-measure').val()*1+$('#temp_notclear').val()*1);
				$('#oldfarms-notclear').val($('#oldfarms-notclear').val()*1+$('#temp_notclear').val()*1);	
				$('#temp_notclear').val(0);
				$('#temp_measure').val(0);
			}
		} else {
			alert('输入的必须为数字');
			var last = input.substr(input.length-1,1);
			$('#input-notclear').val(input.substring(0,input.length-1));
		}
	}
});
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
<?php

$script = <<<JS


$("#farms-zongdi").keyup(function (event) {

    var input = $(this).val();
	if (event.keyCode == 32) {
		
		input = $.trim(input);
		$.getJSON('index.php?r=parcel/parcelarea', {zongdi: input}, function (data) {
			//alert(data.area);
			if (data.status == 1) {
				var oldfarmsmeasure = parseFloat($('#oldfarms-measure').val());
				var value = $('#farms-measure').val()*1+data.area*1;
				$('#farms-measure').val(value.toFixed(2));
				$('#temp_measure').val(value.toFixed(2));
				$('#temp-zongdi').val($.trim(input)+'、');
				$("#farms-zongdi").val($.trim(input)+'、');
				if(oldfarmsmeasure == 0) {
					var notclear = $('#oldfarms-notclear').val()*1 - data.area*1;
					$('#oldfarms-notclear').val(notclear.toFixed(2));
				}
				$('#oldfarms-notclear')
				var measure = $("#farms-measure").val()*1;
				if(measure < contractarea) {
					var cha = contractarea - measure;
					$("#farms-notclear").val(cha.toFixed(2));
				} else {
					$("#farms-notclear").val(0);
					$("#farms-contractarea").val(value.toFixed(2));
				}
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
					var contractarea = $("#farms-contractarea").val()*1;
					var measure = $("#farms-measure").val()*1;
					if(measure < contractarea) {
						$("#farms-notstate").val(0);
						var cha = contractarea - measure;
						$("#farms-notclear").val(cha.toFixed(2));
					} else {
						$("#farms-notclear").val(0);
						var cha =measure - contractarea;
						$("#farms-notstate").val(cha.toFixed(2));
					}
					toHTH();
				}	
			});
		} else {
			$("#farms-measure").val(0);
			$("#farms-notclear").val($("#farms-contractarea").val());
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
				var oldfarmsmeasure = parseFloat($('#oldfarms-measure').val());
				$("#farms-zongdi").val($.trim(data.formatzongdi));	
				$("#farms-measure").val(data.sum);
				var contractarea = $("#farms-contractarea").val()*1;
					var measure = $("#farms-measure").val()*1;
				if(oldfarmsmeasure == 0) {
					var notclear = $('#oldfarms-notclear').val()*1 - data.sum*1;
					$('#oldfarms-notclear').val(notclear.toFixed(2));
				}
					if(measure < contractarea) {
						$("#farms-notstate").val(0);
						var cha = contractarea - measure;
						$("#farms-notclear").val(cha.toFixed(2));
					} else {
						$("#farms-notclear").val(0);
						var cha =measure - contractarea;
						
					}
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
