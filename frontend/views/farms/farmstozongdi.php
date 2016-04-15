<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
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
  <table width="100%" height="100%" border="0">
    <tr>
    <td width="46%" valign="top"><table width="100%" height="100%"
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
			<td colspan="5" align='left'><?= html::textInput('oldcontractnumber',$oldFarm->contractnumber,['id'=>'oldfarms-contractnumber','class'=>'form-control','readonly'=>true])?></td>
		</tr>
		<tr>
			<td width=15% align='right'>承包年限</td>
			<td align='center'>自</td>
			<td align='center'><?php echo $oldFarm->begindate;?></td>
			<td align='center'>至</td>
			<td align='center'><?php echo $oldFarm->enddate;?></td>
			<td align='center'>止</td>
		</tr>
		<tr>
        <td width="20%" align='right' valign="middle">宗地</td>
        <td colspan="5" align='left' valign="middle">
        <table width="100%" height="100%" border="0" cellspacing="5">
        <?php  
        if(!empty($oldFarm->zongdi)) {
        $arrayZongdi = explode('、', $oldFarm->zongdi);
        for($i = 0;$i<count($arrayZongdi);$i++) {
        	// 			    	echo $i%6;
        	if($i%5 == 0) {
        		echo '<tr height="10">';
        		echo '<td>';
        			echo html::button($arrayZongdi[$i],['onclick'=>'toZongdi("'.Lease::getZongdi($arrayZongdi[$i]).'","'.Lease::getArea($arrayZongdi[$i]).'")','value'=>$arrayZongdi[$i],'id'=>Lease::getZongdi($arrayZongdi[$i]),'class'=>"btn btn-default"]);
        		echo '</td>';
        	} else {
        		echo '<td>';
        			echo html::button($arrayZongdi[$i],['onclick'=>'toZongdi("'.Lease::getZongdi($arrayZongdi[$i]).'","'.Lease::getArea($arrayZongdi[$i]).'")','value'=>$arrayZongdi[$i],'id'=>Lease::getZongdi($arrayZongdi[$i]),'class'=>"btn btn-default"]);
        		echo '</td>';
        	}
        }}
        ?>
        </table>
        </td>
        </tr>
      <tr><?= Html::hiddenInput('oldzongdi',$oldFarm->zongdi,['id'=>'oldfarm-zongdi']) ?>
      <?= Html::hiddenInput('ttpozongdi','',['id'=>'ttpozongdi-zongdi']) ?>
      <?= Html::hiddenInput('ttpoarea',0,['id'=>'ttpozongdi-area']) ?>
        <td align='right' valign="middle">宗地面积</td><?= html::textInput('tempoldmeasure',$oldFarm->measure,['id'=>'temp_oldmeasure']) ?>
        										 <?= html::textInput('tempoldnotclear',$oldFarm->notclear,['id'=>'temp_oldnotclear']) ?>
        										 <?= html::textInput('tempoldcontractarea',$oldFarm->contractarea,['id'=>'temp_oldcontractarea']) ?>
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
    <table width="99%" height="100%" class="table table-bordered table-hover">
      <tr>
        <td width="30%" align='right'>农场名称</td>
        <td colspan="5" align='left'><?=  $newFarm->farmname?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>承包人姓名</td>
        <td colspan="5" align='left'><?=  $newFarm->farmername?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>身份证号</td>
        <td colspan="5" align='left'><?=  $newFarm->cardid?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>电话号码</td>
        <td colspan="5" align='left'><?=  $newFarm->telephone?></td>
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
			<td width=30% align='right'>合同号</td>
			<td colspan="5" align='left'><?= $form->field($newFarm, 'contractnumber')->textInput(['maxlength' => 500,'readonly'=>true])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=30% align='right'>承包年限</td>
			<td align='center'>自</td>
			<td align='center'><?php echo $newFarm->begindate;?></td>
			<td align='center'>至</td>
			<td align='center'><?php echo $newFarm->enddate;?></td>
			<td align='center'>止</td>
		</tr>
		<tr>
		  <td align='right'>原宗地</td>
		  <td colspan="5" align='left'><?= $newFarm->zongdi?></td>
		  </tr>
		<tr>
		  <td align='right'>新宗地</td>
		  <td colspan="5" align='left'><?= html::textarea('newFarm-newzongdi','',['class'=>'form-control','id'=>'newzongdi'])?></td>
		  </tr>
		<tr>
        <td align='right'>宗地面积</td><?= html::textInput('tempmeasure',$newFarm->measure,['id'=>'temp_measure']) ?>
        							<?= html::textInput('measure',$newFarm->measure,['id'=>'ymeasure']) ?>
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
        <td align='right'>转让未明确地块面积</td><?php if($oldFarm->notclear) $realonly = false; else $realonly = true;?>
        <td colspan="5" align='left'><?= html::textInput('inputnotclear','',['id'=>'input-notclear','class'=>'form-control','readonly'=>$realonly]) ?></td>
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
if($('#oldfarms-notclear').val() == 0) {
	$('#input-notclear').attr("readonly",true); 
}
function resetZongdi(zongdi,area)
{
	$('#'+zongdi).attr('disabled',false);
	var oldmeasure = $('#oldfarms-measure').val()*1 + area*1;
	var oldfarmsmeasure = parseFloat($('#temp_oldmeasure').val());
	if(oldfarmsmeasure == 0) {
		$('#oldfarms-notclear').val(oldmeasure.toFixed(2));
	} else
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
	$('#'+zongdi).attr('disabled',true);
	var ycontractarea = parseFloat($('#oldfarms-contractarea').val());
	var value = $('#oldfarms-measure').val()*1-area*1;
// 	var oldcontractarea = $('#oldfarms-contractarea').val()*1 - area*1;
	$('#oldfarms-measure').val(value.toFixed(2));
// 	$('#temp_oldmeasure').val(value.toFixed(2));
// 	$('#temp_oldcontractarea').val(oldcontractarea.toFixed(2));
	var newvalue = $('#farms-measure').val()*1 + area*1;
	$('#farms-measure').val(newvalue.toFixed(2));
	$('#temp_measure').val(newvalue.toFixed(2));
	var newzongdi = $('#newzongdi').val()+'、'+zongdi+'('+area+')';
	var first = newzongdi.substr(0,1);
	if(first == '、') {
		$('#newzongdi').val(newzongdi.substring(1));
		$('#temp-zongdi').val($('#newzongdi').val());
	} else {
		$('#newzongdi').val(newzongdi);
		$('#temp-zongdi').val($('#newzongdi').val());
	}
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
	var oldcontractarea = parseFloat($('#oldfarms-contractarea').val());
	
	if(oldcontractarea < 0 && ycontractarea > 0) {
		alert('宗地面积已经大于合同面积，多出面积自动加入未明确状态面积');
	}
	if(oldcontractarea < 0) {
		$('#farms-notstate').val(Math.abs(oldcontractarea));
		toHTH();
	}
	
}
$('#reset').click(function() {
	 
    location.reload();

});
function toHTH()
{
	//生成合同号
	var hth = $('#farms-contractnumber').val();
	var arrayhth = hth.split('-');
	$.each(arrayhth,function(n,value) { 
		if(value == '')
			arrayhth.splice(n,1);
	});
	var contractarea = $('#farms-measure').val()*1 + $('#farms-notclear').val()*1 - $('#farms-notstate').val()*1;
	arrayhth[2] = cutZero(contractarea.toFixed(2));
	$('#farms-contractnumber').val(arrayhth.join('-'));
	$('#farms-contractarea').val(arrayhth[2]);
	
	var hth = $('#oldfarms-contractnumber').val();
	var arrayhth = hth.split('-');
	$.each(arrayhth,function(n,value) { 
		if(value == '')
			arrayhth.splice(n,1);
	});
	var oldcontractarea = $('#oldfarms-measure').val()*1 + $('#oldfarms-notclear').val()*1 - $('#oldfarms-notstate').val()*1;
	arrayhth[2] = cutZero(oldcontractarea.toFixed(2));
	$('#oldfarms-contractarea').val(arrayhth[2]);
	$('#oldfarms-contractnumber').val(arrayhth.join('-'));
}
$('#input-notclear').blur(function(){
	var input = $(this).val();
	if(input*1 > $('#temp_oldnotclear').val()*1) {
		
		alert('输入的数值不能大于'+$('#temp_oldnotclear').val());
		$('#oldfarms-notclear').val($('#temp_oldnotclear').val());
		$(this).val(0);
		$('#farms-notclear').val(0);
		$(this).focus();		
		toHTH();
}});
$('#input-notclear').keyup(function (event) {
	var input = $(this).val();
	if(event.keyCode == 8) {
		$(this).val('');
		$('#farms-notclear').val($('#temp_notclear').val());
		var oldmeasure = $('#oldfarms-measure').val();
		$('#oldfarms-notclear').val($('#temp_oldnotclear').val());	
		
		toHTH();
	} else {
		if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
			if($('#temp_notclear').val() != '') {
				var result = $('#temp_oldnotclear').val()*1 - input*1;
				
				var measure = $('#farms-measure').val();
				if(measure > 0) {
					var newresult = result - measure;
					
					$('#oldfarms-notclear').val(result.toFixed(2));					
				} else {
					$('#oldfarms-notclear').val(result.toFixed(2));	
				}
				var ymeasure = $('#ymeasure').val()*1;
				if(ymeasure > 0) {
					
				}
				var notclear = $('#temp_notclear').val();
				var notclearresult = notclear*1 + input*1;
				$('#farms-notclear').val(notclearresult.toFixed(2));
				toHTH();
			} else {
				var result = $('#temp_oldcontractarea').val()*1 - input*1				
				$('#oldfarms-notclear').val(result.toFixed(2));	
				$('#farms-notclear').val(input);
				toHTH();
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
</script>
<?php

$script = <<<JS
$("#newzongdi").keyup(function (event) {

    var input = $(this).val();
	if (event.keyCode == 32) {
		
		input = $.trim(input);
		$.getJSON('index.php?r=parcel/parcelarea', {zongdi: input}, function (data) {
			//alert(data.area);
			if (data.status == 1) {
				var oldfarmsmeasure = parseFloat($('#oldfarms-measure').val());
				var notclear = parseFloat($('#farms-notclear').val());
				var value = $('#farms-measure').val()*1+data.area*1;
				$('#farms-measure').val(value.toFixed(2));
				$('#temp_measure').val(value.toFixed(2));
				
				$('#temp-zongdi').val($.trim(input)+'、');
				$("#newzongdi").val($.trim(input)+'、');
				if(oldfarmsmeasure == 0) {
					var notclear = $('#oldfarms-notclear').val()*1 - data.area*1;
					$('#oldfarms-notclear').val(notclear.toFixed(2));					
					toHTH();
					$('#temp_oldcontractarea').val($('#oldfarms-contractarea').val());
				} else {
// 					var notclear
				}
				
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
				$("#newzongdi").val($('#temp-zongdi').val());
				toHTH();
			}
		});
	}
	if (event.keyCode == 8) {
		var zongdi = $('#newzongdi').val();
		var arrayZongdi = zongdi.split('、');
		var rows = arrayZongdi.length*1 - 1;
		var delZongdi = arrayZongdi[rows];
		var zongdiNumber = delZongdi.split('(');
		resetZongdi(zongdiNumber[0],zongdiNumber[1]);
		arrayZongdi.splice(rows,1); 
		$('#newzongdi').val(arrayZongdi.join('、'));
		var input = $(this).val();
		var oldfarmsmeasure = parseFloat($('#temp_oldmeasure').val());
		if(input) {
		    input = $.trim(input);
			$.getJSON('index.php?r=parcel/getformatzongdi', {zongdi: input}, function (data) {
				if (data.status == 1) {
					
					$("#newzongdi").val($.trim(data.formatzongdi));	
					
					if(oldfarmsmeasure == 0) {
						
						var notclear = $('#temp_oldnotclear').val()*1 - data.sum*1 - $('#input-notclear').val()*1;
						alert(notclear);
						$('#oldfarms-notclear').val(notclear.toFixed(2));
						$('#temp_oldcontractarea').val(notclear.toFixed(2));
						toHTH();
					} else {
						$('#oldfarms-measure').val(oldfarmsmeasure*1 - data.sum*1);
					}
					var ymeasure = $('#ymeasure').val()*1;
					$('#farms-measure').val(ymeasure + data.sum*1);
					var contractarea = $('#farms-contra ctarea').val()*1;
					var measure = $('#farms-measure').val()*1;
					if(measure > contractarea) {
						$('#farms-notstate').val(measure - contractarea);
					}
				toHTH();
				}	
			});
		} else {
			$("#farms-measure").val(0);
			var inputnotclear = $('#input-notclear').val();
			if(oldfarmsmeasure == 0) {
				if(inputnotclear > 0) {
					
					$('#oldfarms-notclear').val($('#temp_oldnotclear').val()*1 - inputnotclear*1);
					$('#temp_oldcontractarea').val($('#temp_oldnotclear').val() - inputnotclear*1);
				} else {
					
					$('#oldfarms-notclear').val($('#temp_oldnotclear').val());
					$('#temp_oldcontractarea').val($('#temp_oldnotclear').val());
				}
			} else {
// 				var notclear = $('temp_oldcontractarea').val()*1 - $('#farms-notclear').val()*1;
				$('#oldfarms-measure').val($('#temp_oldmeasure').val());
				var tempmeasure = $('#temp_measure').val();
					if(tempmeasure > 0) {
						$('#farms-measure').val($('#ymeasure').val());
					}
			}
			
			
			toHTH();
		}
	}
 });
$('#newzongdi').blur(function(){
	var input = $(this).val();
	if(input) {
	    input = $.trim(input);
		
		$.getJSON('index.php?r=parcel/getformatzongdi', {zongdi: input}, function (data) {
			if (data.status == 1) {
				
				$("#newzongdi").val($.trim(data.formatzongdi));	
				$("#farms-measure").val(data.sum);
				$('#temp_measure').val(data.sum);
				var oldfarmsmeasure = parseFloat($('#temp_oldmeasure').val());
				toHTH();
				var measure = $("#farms-measure").val()*1;
				var input = $('#input-notclear').val()*1;
				if(oldfarmsmeasure == 0) {
					toHTH();
					
					var result = $('#temp_oldnotclear').val()*1 - data.sum*1;
					if(input > 0) {
						var newresult = result - input;
						$('#oldfarms-notclear').val(newresult.toFixed(2));
					} else {
						$('#oldfarms-notclear').val(result.toFixed(2));
						$('#temp_oldcontractarea').val(result.toFixed(2));
					}
					toHTH();
				} else {
					
// 					var tempzongdi = $('#temp-zongdi').val();
// 					var arrayTempZongdi = tempzongdi.split('、');
					var zongdi = $('#newzongdi').val();
					var arrayZongdi = zongdi.split('、');
					var sum = 0.0;
					$.each(arrayZongdi,function(n,value) { 
						sum +=  getArea(value)*1;
					});
					
					var result = $('#temp_oldmeasure').val() *1 - sum*1;
					var ymeasure = $('#ymeasure').val()*1;
					if(ymeasure > 0) {
						var lresult = ymeasure + sum*1;
						$('#farms-measure').val(lresult.toFixed(2));
					}
						
					$('#oldfarms-measure').val(result.toFixed(2));
					var contractarea = $("#farms-contractarea").val()*1;
					var tempoldcontractarea = $('#temp_oldcontractarea').val()*1;
					if(contractarea < tempoldcontractarea) {
						$('#farms-notstate').val(0);
					}
					toHTH();
				}
			}	
		});
	} else {
		var ymeasure = $('#ymeasure').val()*1;
		if(ymeasure > 0) {
			$("#farms-measure").val(ymeasure);
		} else {
			$("#farms-measure").val(0);
		}
		
		toHTH();
	}
});
JS;
$this->registerJs($script);
?>
