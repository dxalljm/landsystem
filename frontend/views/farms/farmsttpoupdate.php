<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Parcel;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Contractnumber;
use app\models\Loan;
use app\models\Lockedinfo;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Farms */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.remove{cursor:pointer}
</style>
<div class="farms-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                       
                    </h3>
                </div>
                <div class="box-body">
<?php if(!Farms::getLocked($farms_id)) {?>
    <?php $form = ActiveFormrdiv::begin(); ?>
  <table width="100%" border="0">
    <tr>
    <td width="47%">
    <table width="100%" height="408px" class="table table-bordered table-hover">
      <tr>
        <td width="20%" align='right' valign="middle">农场名称</td>
        <td width="30%" colspan="5" align='left' valign="middle"><?= $model->farmname?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">承包人姓名</td>
        <td colspan="5" align='left' valign="middle"><?= $model->farmername ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">身份证号</td>
        <td colspan="5" align='left' valign="middle"><?= $model->cardid ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">电话号码</td>
        <td colspan="5" align='left' valign="middle"><?= $model->telephone ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">合同号</td><?php if($model->contractnumber == '') $model->contractnumber = Farms::getContractnumber($_GET['farms_id']);?>
        <td colspan="5" align='left' valign="middle"><?= $model->contractnumber ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">承包年限</td>
        <td align='center'>自</td>
        <td align='center'><?= $model->begindate ?></td>
        <td align='center'>至</td>
        <td align='center'><?= $model->enddate?></td>
        <td align='center'>止</td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">农场位置</td>
        <td colspan="5" align='left' valign="middle"><?= $model->address?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">宗地</td>
        <td colspan="5" align='left' valign="middle"><?= $model->zongdi?></td>
        </tr>
       <tr>
        <td align='right' valign="middle">合同面积</td>
        <td colspan="5" align='left' valign="middle"><?= $model->contractarea ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">宗地面积</td>
        <td colspan="5" align='left' valign="middle"><?= $model->measure ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">未明确地块面积</td>
        <td colspan="5" align='left' valign="middle"><?= $model->notclear?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">未明确状态地块面积</td>
        <td colspan="5" align='left' valign="middle"><?= $model->notstate?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">备注</td>
        <td colspan="5" align='left' valign="middle"><?= $model->remarks ?></td>
        </tr>
    </table></td>
    <td width="4%" align="center"><font size="5"><i class="fa fa-arrow-right"></i></font></td>
    <td width="50%" valign="top">
    <table width="411" class="table table-bordered table-hover">
      <tr>
        <td width="30%" align='right'>农场名称</td>
        <td colspan="4" align='left'><?=  $form->field($nowModel, 'farmname')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchFarms','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>承包人姓名</td>
        <td colspan="4" align='left'><?=  $form->field($nowModel, 'farmername')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchFarmer','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>身份证号</td>
        <td colspan="4" align='left'><?=  $form->field($nowModel, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchCardid','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>电话号码</td>
        <td colspan="4" align='left'><?=  $form->field($nowModel, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchTelephone','class'=>'btn btn-success'])?></td>
        </tr>
        <tr>
			<td width=25% align='right'>合同号</td><?php $nowModel->contractnumber = $model->contractnumber;?>
			<td colspan="5" align='left'><?= $form->field($nowModel, 'contractnumber')->textInput(['maxlength' => 500,'readonly'=>'readonly'])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=25% align='right'>承包年限</td>
			<td align='center'>自</td><?php $nowModel->begindate = '2010-09-13';$nowModel->enddate='2025-09-13';?>
			<td align='center'><?= $form->field($nowModel, 'begindate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
			<td align='center'><?= $form->field($nowModel, 'enddate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
		  <td align='right' valign="middle">农场位置</td> <?= Html::hiddenInput('newzongdi','',['id'=>'new-zongdi']) ?>
		  <td colspan="5" align='left' valign="middle"><?php if(empty($model->address)) echo $form->field($nowModel, 'zongdi')->textInput()->label(false)->error(false); ?></td>
		  </tr>
		<tr>
		  <td align='right' valign="middle">宗地</td>
		  <td colspan="5" align='left' valign="middle">
		  <?php 
		  $nowModel->zongdi = $model->zongdi;
		  echo html::hiddenInput('tempzongdi','',['id'=>'temp-zongdi']);
		  echo $form->field($nowModel, 'zongdi')->hiddenInput()->label(false)->error(false);
		  if($model->notclear) {  ?>
		  <span id="inputZongdi" class="select2-container select2-container--default select2-container--below" dir="ltr" style="width: 100%; color: #000;">
	<span class="selection">
		<span class="select2-selection select2-selection--multiple" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false" tabindex="0">
			<ul class="select2-selection__rendered">
				<li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" placeholder="" style="width: 0.75em;"></li>
			</ul>
		</span>
	</span>
	<span class="dropdown-wrapper" aria-hidden="true"></span>
</span>
<?php } else echo $model->zongdi;?>
</td>
		  </tr>
		<tr>
		  <td align='right' valign="middle">合同面积</td>
		  <td colspan="5" align='left' valign="middle"><?= $model->contractarea ?></td>
		  </tr>
		<tr>
		  <td align='right' valign="middle">宗地面积</td>
		  <td colspan="5" align='left' valign="middle"><?= $form->field($nowModel, 'measure')->textInput(['readonly'=>true])->label(false)->error(false); ?></td>
		  </tr>
		<tr>
		  <td align='right' valign="middle">未明确地块面积</td><?php $nowModel->notclear = $model->notclear;?>
		  <td colspan="5" align='left' valign="middle"><?= $form->field($nowModel, 'notclear')->textInput(['readonly'=>true])->label(false)->error(false);?></td>
		  </tr>
		<tr>
		  <td align='right' valign="middle">未明确状态地块面积</td>
		  <td colspan="5" align='left' valign="middle"><?= $form->field($nowModel, 'notstate')->textInput(['readonly'=>true])->label(false)->error(false);?></td>
		  </tr>
      <tr>
        <td align='right'>备注</td>
        <td  colspan="5" align='left'><?= $form->field($nowModel, 'remarks')->textarea(['rows' => 2])->label(false)->error(false) ?></td>
      </tr>
    </table></td>
  </tr>
</table>
<div class="form-group">
      <?= Html::submitButton('提交申请', ['class' => 'btn btn-success']) ?>
      <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
</div>

    <?php ActiveFormrdiv::end(); ?>
     <?php } else {?>
    	<h4><?= Lockedinfo::find()->where(['farms_id'=>$farms_id])->one()['lockedcontent']?></h4>
    <?php }?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
<div id="dialog2" title="宗地信息">
	<table width=100%>
		<tr>
			<td align="right">宗地号：</td>
			<td><?= html::textInput('findzongdi','',['id'=>'findZongdi'])?></td>
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
	removeZongdiForm(zongdi,measure);
	removeNowZongdi(zongdi);
	$('#new'+zongdi).remove();
// 	var zongdiarr = zongdi.split('_');
	$('#'+zongdi).attr('disabled',false);
	if(dialogID == 'dialog') {
		//宗地面积计算开始
		var value = $('#oldfarms-measure').val()*1+measure*1;
		$('#oldfarms-measure').val(value.toFixed(2));
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
		$('#'+zongdi).text($('#'+zongdi).val());
	}
	if(dialogID == 'dialog2') {
		
		var newvalue = $('#farms-measure').val()*1 - measure*1;
		$('#farms-measure').val(newvalue);
		jisuan();
	}
	//宗地面积计算结束
	toHTH();
}
function removeZongdiForm(zongdi,measure)
{
	var findzongdi = zongdi + "("+measure+")";
	var zongdi = $('#farms-zongdi').val();
	var arr1 = zongdi.split('、');
	$.each(arr1, function(i,val){  
	      if(val === findzongdi)
	    	  arr1.splice(i,1);	      
	  });   
	var newnewzongdi = arr1.join('、');
// 	alert(newnewzongdi);
	$('#farms-zongdi').val(newnewzongdi);
// 	return result;
}
function removeNowZongdi(zongdi)
{
	var nowzongdi = $('#new-zongdi').val();
	var arr1 = nowzongdi.split('|');
	$.each(arr1, function(i,val){  
	      if(val === zongdi)
	    	  arr1.splice(i,1);	      
	  });   
	var newnewzongdi = arr1.join('|');
// 	alert(newnewzongdi);
	$('#new-zongdi').val(newnewzongdi);
// 	return result;
}
function nowZongdiFind(zongdi)
{
	var result = false;
	var newzongdi = $('#new-zongdi').val();
	if(newzongdi != '') {
		var arr1 = newzongdi.split('|');
		$.each(arr1, function(i,val){  
		      if(val === zongdi)
		    	  result = true;	      
		  });   
	}
	return result;
}
function zongdiForm(zongdi,measure)
{
	var newfarmszongdi = $('#farms-zongdi').val();
	var zongdistr = zongdi+"("+measure+")";
	$('#farms-zongdi').val(newfarmszongdi +'、'+ zongdistr);
// 	alert(zongdistr);
	var farmszongdi = $('#farms-zongdi').val();
	var first = farmszongdi.substr(0,1);
	var last = farmszongdi.substr(farmszongdi.length-1,1);
	if(first == '、') {
		$('#farms-zongdi').val(farmszongdi.substring(1));
	}
	if(last == '、') {
		$('#farms-zongdi').val(farmszongdi.substring(0,farmszongdi.length-1));
	}
	
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
		  						zongdiForm(zongdi,measure);		  						
		  					 	var newzongdi = zongdi+'('+measure+')';
		  					 	var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+measure+'","dialog2")>×</span>'+newzongdi+'</li>';
		  					 	var newvalue = $('#farms-measure').val()*1 + measure*1;
		  						$('#farms-measure').val(newvalue.toFixed(2));
		  						$('#temp_measure').val(newvalue.toFixed(2));
		  						$('.select2-selection__rendered').append(newzongdihtml);
		  						jisuan();
		  						
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
						zongdiForm(zongdi,measure);		
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
function jisuan()
{
	var notclaer = $('#farms-notclear').val();
	var notstate = $('#farms-notstate').val();
	var contractarea = <?= $model->contractarea?>;
	var measure = $('#farms-measure').val();

	var result = contractarea - measure;
	if(result > 0) {
		$('#farms-notclear').val(result.toFixed(2));
		$('#farms-notstate').val(0);
	}
	if(result == 0) {
		$('#farms-notclear').val(0);
		$('#farms-notstate').val(0);
	}
	if(result < 0) {
		$('#farms-notclear').val(0);
		$('#farms-notstate').val(Math.abs(result.toFixed(2)));
	}
}
//点击宗地输入框弹出宗地信息查找框
$('#inputZongdi').dblclick(function(){
	var notclear = <?= $model->notclear?>;
	if(Number(notclear) > 0) {
		$("#dialogSelect").val('dialog2');
		$( "#dialog2" ).dialog( "open" );
		$('#findZongdi').focus();
	}
});
$('#findZongdi').keyup(function (event) {
	var input = $(this).val();
	if(event.keyCode == 13) {
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
});
$('#findZongdi').blur(function (event) {
	var input = $(this).val();
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
});
// Link to open the dialog
$( ".dialog-link" ).click(function( event ) {
	$("#dialogSelect").val('dialog1');
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
