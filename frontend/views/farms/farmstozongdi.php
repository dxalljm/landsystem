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
<style>
.remove{cursor:pointer}
</style>
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
      <?= Html::hiddenInput('newzongdi','',['id'=>'new-zongdi']) ?>
      <?= Html::hiddenInput('ttpozongdi','',['id'=>'ttpozongdi-zongdi']) ?>
      <?= Html::hiddenInput('ttpoarea',0,['id'=>'ttpozongdi-area']) ?>
        <td align='right' valign="middle">宗地面积</td><?= html::hiddenInput('tempoldmeasure',$oldFarm->measure,['id'=>'temp_oldmeasure']) ?>
        										 <?= html::hiddenInput('tempoldnotclear',$oldFarm->notclear,['id'=>'temp_oldnotclear']) ?>
        										 <?= html::hiddenInput('tempoldcontractarea',$oldFarm->contractarea,['id'=>'temp_oldcontractarea']) ?>
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
        <td align='right' valign="middle">备注</td>
        <td colspan="5" align='left' valign="middle"><?= $oldFarm->remarks?></td>
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
		  <td align='right'>新宗地</td><?= html::hiddenInput('tempzongdi','',['id'=>'temp-zongdi'])?>
		  <td colspan="5" align='left'><span id="inputZongdi" class="select2-container select2-container--default select2-container--below" dir="ltr" style="width: 100%; color: #000;">
	<span class="selection">
		<span class="select2-selection select2-selection--multiple" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false" tabindex="0">
			<ul class="select2-selection__rendered">
				<li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" placeholder="" style="width: 0.75em;"></li>
			</ul>
		</span>
	</span>
	<span class="dropdown-wrapper" aria-hidden="true"></span>
</span>
		  </td>
		  </tr>
		<tr>
        <td align='right'>宗地面积</td><?= html::hiddenInput('tempmeasure',$newFarm->measure,['id'=>'temp_measure']) ?>
        							<?= html::hiddenInput('measure',$newFarm->measure,['id'=>'ymeasure']) ?>
								  <?= html::hiddenInput('tempnotclear',$newFarm->notclear,['id'=>'temp_notclear']) ?>
								  <?= html::hiddenInput('tempnotstate',$newFarm->notstate,['id'=>'temp_notstate']) ?>
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
        <td align='right'>备注</td>
        <td colspan="5" align='left'><?= $form->field($newFarm, 'remarks')->textarea(['rows'=>'2'])->label(false)->error(false) ?></td>
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
<div id="dialog" title="宗地信息">
	<table width=100%>
		<tr>
			<td align="right">宗地号：</td>
			<td><?= html::textInput('zongdinumber','',['id'=>'zongdi','disabled'=>true])?></td>
		</tr>
		<tr>
			<td align="right">面积：</td>
			<td><?= html::textInput('zongdimeasure','',['id'=>'measure'])?></td>
		</tr>
	</table>
</div>
<div id="dialog2" title="宗地信息">
	<table width=100%>
		<tr>
			<td align="right">宗地号：</td>
			<td><?= html::textInput('findzongdi','',['id'=>'findZongdi'])?>回车进行查询</td>
		</tr>
		<tr>
			<td align="right">面积：</td>
			<td><?= html::textInput('findmeasure','',['id'=>'findMeasure'])?></td>
		</tr>
	</table>
</div>
<script>
function zongdiRemove(zongdi,measure,dialogID)
{
	$('#new'+zongdi).remove();
// 	var zongdiarr = zongdi.split('_');
	$('#'+zongdi).attr('disabled',false);
	if(dialogID == 'dialog') {
		//宗地面积计算开始
		var value = $('#oldfarms-measure').val()*1+measure*1;
		$('#oldfarms-measure').val(value.toFixed(2));
		//如果存在未明确状态面积，那么先减未明确状态面积
		var notstate = Number($('#farms-notstate').val());
		var tempnotstate = Number($('#temp_notstate').val());
		if(notstate > tempnotstate) {
			if(measure <= tempnotstate) {
				$('#farms-notstate').val(notstate - measure);
			} else {
				$('#farms-notstate').val(tempnotstate);
			}
		}
		var newvalue = $('#farms-measure').val()*1 - measure*1;
		$('#farms-measure').val(newvalue.toFixed(2));
		$('#temp_measure').val(newvalue.toFixed(2));
		$('#'+zongdi).text($('#'+zongdi).val());
	}
	if(dialogID == 'dialog2') {
		var value = $('#oldfarms-notclear').val()*1+measure*1;
		$('#oldfarms-notclear').val(value.toFixed(2));
		//如果存在未明确状态面积，那么先减未明确状态面积
		var notstate = $('#farms-notstate').val();
		if(notstate > 0) {
			if(notstate >= measure) {
				$('#farms-notstate').val(notstate - measure);
			} else {
				$('#farms-notstate').val(0);
			}
		}
		var newvalue = $('#farms-measure').val()*1 - measure*1;
		$('#farms-measure').val(newvalue.toFixed(2));
		$('#temp_measure').val(newvalue.toFixed(2));
	}
	removeTempZongdi(zongdi);
	//宗地面积计算结束
	toHTH();
}
//在输入过宗地列表中删除输入过的宗地
function removeTempZongdi(zongdi)
{
	var result = false;
	var newzongdi = $('#new-zongdi').val();
	var arr1 = newzongdi.split('|');
	$.each(arr1, function(i,val){  
	      if(val === zongdi)
	    	  arr1.splice(i,1);	      
	  });   
	var newnewzongdi = arr1.join('|');
	$('#new-zongdi').val(newnewzongdi);
}
//判断是否是已经输入过的宗地
function nowZongdiFind(zongdi)
{
	var result = false;
	var newzongdi = $('#new-zongdi').val();
	var arr1 = newzongdi.split('|');
	$.each(arr1, function(i,val){  
	      if(val === zongdi)
	    	  result = true;	      
	  });   
	return result;
}
$('#dialog2').dialog({
	autoOpen: false,
	width:400,
	
	buttons: [
	  		{
	  			text: "确定",
	  			click: function() { 
	  				var zongdi = $('#findZongdi').val();
// 	  				alert(zongdi);
	  				var measure = Number($('#findMeasure').val());
	  				var ymeasure = Number($('#ymeasure').val());
	  				if(measure == '' || zongdi == '') {
	  					alert("对不起，宗地或面积不能为空。");
	  					$('#findMeasure').val();
	  				} else {
	  					if(measure > ymeasure) {
	  						alert("对不起，您输入的面积不能大于原宗地面积。");
	  						$('#findMeasure').val(ymeasure);	  						
	  					} else {
	  						if(measure > $('#oldfarms-notclear').val()) {
	  							alert("对不起，您输入的面积不能大于原农场未明确地块面积。");
		  					} else {
		  						$( this ).dialog( "close" );
		  						
		  					 	var newzongdi = zongdi+'('+measure+')';
		  					 	var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+measure+'","dialog2")>×</span>'+newzongdi+'</li>';
		  						
		  						$('.select2-selection__rendered').append(newzongdihtml);
		  						$('#'+zongdi).attr('disabled',true);
		  						var value = $('#oldfarms-notclear').val()*1-measure*1;
		  						$('#oldfarms-notclear').val(value.toFixed(2));
		  						var newvalue = $('#farms-measure').val()*1 + measure*1;
		  						$('#farms-measure').val(newvalue.toFixed(2));
		  						$('#temp_measure').val(newvalue.toFixed(2));
		  						toHTH();
		  						var ycontractarea = parseFloat($('#farms-contractarea').val());
		  						var oldcontractarea = parseFloat($('#oldfarms-contractarea').val());
		  						
		  						if(oldcontractarea < 0 && ycontractarea > 0) {
		  							alert('宗地面积已经大于合同面积，多出面积自动加入未明确状态面积');
		  						}
		  						if(oldcontractarea < 0) {
		  							$('#farms-notstate').val(Math.abs(oldcontractarea));
		  							toHTH();
		  						}
		  						var newtempzongdi = $('#new-zongdi').val();
		  						$("#new-zongdi").val(zongdi+'|'+newtempzongdi);
		  						$('#findZongdi').val('');
					  			$('#findMeasure').val('');
					  			$('#ymeasure').val(0);	
		  					}
	  						
		  				}
	  					
	  				}
	  			}
	  		},
	  		{
	  			text: "取消",
	  			click: function() {
		  			$('#findZongdi').val('');
		  			$('#findMeasure').val('');
	  				$( this ).dialog( "close" );
	  			}
	  		}
	  	]
});
$( "#dialog" ).dialog({
	autoOpen: false,
	width: 400,
	buttons: [
		{
			text: "确定",
			click: function() { 
				var zongdi = $('#zongdi').val();
				var measure = Number($('#measure').val());
				var ymeasure = Number($('#ymeasure').val());
				if(measure == '') {
					alert("对不起，您面积不能为空。");
					$('#measure').val(ymeasure);
				} else {
					if(measure > ymeasure) {
						
						alert("对不起，您输入的面积不能大于原宗地面积。");
						$('#measure').val(ymeasure);
					} else {
						$( this ).dialog( "close" );
							
					 	var newzongdi = zongdi+'('+measure+')';
					 	var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+measure+'","dialog")>×</span>'+newzongdi+'</li>';
						var oldmeasure = $('#ymeasure').val() - measure;
						var oldzongdi = zongdi+'('+cutZero(oldmeasure.toFixed(2))+')';
// 						alert(oldzongdi);
					 	$('#'+zongdi).text(oldzongdi);
// 					 	alert($('#zongdi').attr('value'));
						$('.select2-selection__rendered').append(newzongdihtml);
						$('#'+zongdi).attr('disabled',true);
						var value = $('#oldfarms-measure').val()*1-measure*1;
						$('#oldfarms-measure').val(value.toFixed(2));
						var newvalue = $('#farms-measure').val()*1 + measure*1;
						$('#farms-measure').val(newvalue.toFixed(2));
						$('#temp_measure').val(newvalue.toFixed(2));
						toHTH();
						var ycontractarea = parseFloat($('#farms-contractarea').val());
						var oldcontractarea = parseFloat($('#oldfarms-contractarea').val());
						
						if(oldcontractarea < 0 && ycontractarea > 0) {
							alert('宗地面积已经大于合同面积，多出面积自动加入未明确状态面积');
						}
						if(oldcontractarea < 0) {
							$('#farms-notstate').val(Math.abs(oldcontractarea));
							toHTH();
						}
						$('#ymeasure').val(0);	
					}
				}
			}
		},
		{
			text: "取消",
			click: function() {
				$( this ).dialog( "close" );
			}
		}
	]
});
//点击宗地输入框弹出宗地信息查找框
$('#inputZongdi').dblclick(function(){
	if($('#oldfarms-notclear').val() > 0) {
		$("#dialogSelect").val('dialog2');
		$( "#dialog2" ).dialog( "open" );
	}
});
$('#findZongdi').keyup(function (event) {
	var input = $(this).val();
	if(event.keyCode == 13) {
		if(input == '') {
			alert('宗地不能为空');
		} else {
			if(nowZongdiFind(input)){
				alert('您已经输入过此宗地号，请不要重复输入');
				$('#findZongdi').val('');
	  			$('#findMeasure').val('');
			} else {
				$.getJSON("<?= Url::to(['parcel/parcelarea'])?>", {zongdi: input}, function (data) {
					if (data.status == 1) {
						$('#findMeasure').val(data.area);
						$('#ymeasure').val(data.area);
					}
					else {
						if(input != '') {
							alert(data.message);
							$("#findZongdi").val('');
							$("#findZongdi").focus();
						}
					}
				});
			}
		}
	}
});
// Link to open the dialog
$( ".dialog-link" ).click(function( event ) {
	$( "#dialog" ).dialog( "open" );

	event.preventDefault();
});
function resetZongdi(zongdi,area)
{
	$('#'+zongdi).attr('disabled',false);
	var oldmeasure = $('#oldfarms-measure').val()*1 + area*1;
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
	$( "#dialog" ).dialog( "open" );
	event.preventDefault();
	$('#zongdi').val(zongdi);
	$('#measure').val(area);
	$('#ymeasure').val(area);	
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
		$('#oldfarms-notclear').val($('#temp_oldnotclear').val());
		
		$('#temp_oldcontractarea').val($('#oldfarms-contractarea').val());
	} else {
		if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
			if($('#temp_notclear').val() != '') {
				var result = $('#temp_oldnotclear').val()*1 - input*1;
				$('#oldfarms-notclear').val(result.toFixed(2));	
				$('#farms-notclear').val(input);
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
