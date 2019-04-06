<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\help;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Lockedinfo;
use yii\helpers\Url;
use app\models\Numberlock;
/* @var $this yii\web\View */
/* @var $model app\models\Farms */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
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
                       整体转让(新建)
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
        <td width="30%" align='left' valign="middle"><?= $model->farmname?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">承包人姓名</td>
        <td align='left' valign="middle"><?= $model->farmername ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">身份证号</td>
        <td align='left' valign="middle"><?= $model->cardid ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">电话号码</td>
        <td align='left' valign="middle"><?= $model->telephone ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">合同号</td><?php if($model->contractnumber == '') $model->contractnumber = Farms::getContractnumber($_GET['farms_id']);?>
        <td align='left' valign="middle"><?= $model->contractnumber ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">承包年限</td>
        <td align='center'>自 <?= $model->begindate ?> 至 <?= $model->enddate?> 止</td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">农场位置</td>
        <td align='left' valign="middle"><?= $model->address?></td>
        </tr>
       <tr>
        <td width="20%" align='right' valign="middle">地理坐标</td>
        <td align='left' valign="middle"><?= $model->longitude.'  '.$model->latitude?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">宗地</td>
        <td align='left' valign="middle"><?= $model->zongdi?></td>
        </tr>
       <tr>
        <td align='right' valign="middle">合同面积</td>
        <td align='left' valign="middle"><?= $model->contractarea ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">宗地面积</td>
        <td align='left' valign="middle"><?= $model->measure ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">未明确地块面积</td>
        <td align='left' valign="middle"><?= $model->notclear?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">未明确状态地块面积</td>
        <td align='left' valign="middle"><?= $model->notstate?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">备注</td>
        <td align='left' valign="middle"><?= $model->remarks ?></td>
        </tr>
    </table></td>
    <td width="4%" align="center"><font size="5"><i class="fa fa-arrow-right"></i></font></td>
    <td width="50%" valign="top">
    <table width="411" class="table table-bordered table-hover">
      <tr>
        <td width="30%" align='right'>农场名称</td>
        <td align='left' width="60%" colspan="2"><?=  $form->field($newmodel, 'farmname')->textInput(['maxlength' => 500])->label(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchFarms','class'=>'btn btn-success','help'=>'ttpo-searchButton'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>承包人姓名</td>
        <td align='left' colspan="2"><?=  $form->field($newmodel, 'farmername')->textInput(['maxlength' => 500])->label(false) ?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchFarmer','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>身份证号</td>
        <td align='left' colspan="2"><?=  $form->field($newmodel, 'cardid')->textInput(['maxlength' => 500])->label(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchCardid','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>电话号码</td>
        <td align='left' colspan="2"><?=  $form->field($newmodel, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchTelephone','class'=>'btn btn-success'])?></td>
        </tr>
        <tr>
			<td width=30% align='right'><?= Help::showHelp3('合同号','ttpo-contract')?></td>
			<?php
			if(Farms::getContractstate($_GET['farms_id']) == 'W' or Farms::getContractstate($_GET['farms_id']) == 'M'  or Farms::getContractstate($_GET['farms_id']) == 'L') {
				$newmodel->contractnumber = Numberlock::getNumber($_GET['farms_id'],'transfer');
			} else {
				$newmodel->contractnumber = $model->contractnumber;
			}

			?>
			<td align='left' colspan="4"><?= $form->field($newmodel, 'contractnumber')->textInput(['maxlength' => 500,'readonly'=>true])->label(false)->error(false) ?></td>
		</tr>
		<tr><?php $newmodel->begindate = date('Y-m-d');$newmodel->enddate='2025-09-13';?>
			<td width=30% align='right'>承包年限</td>
			<td align='center' colspan="4">自 <?= $newmodel->begindate?> 至 <?= $newmodel->enddate?> 止</td>
		</tr>
		<tr>
		  <td align='right' valign="middle">农场位置</td> <?= Html::hiddenInput('newzongdi','',['id'=>'new-zongdi']) ?>
		  <td align='left' valign="middle" colspan="4"><?php echo $form->field($model, 'address')->textInput()->label(false)->error(false);?></td>
		</tr>
		<tr>
        <td width="30%" align='right' valign="middle">地理坐标</td>
        
        	<td align='left' valign="middle" colspan="1" width="37%"><?php echo $form->field($model, 'longitude')->textInput(['data-inputmask'=>'"mask": "E999°99′99.99″"', 'data-mask'=>""])->label(false)->error(false); ?></td>
       		<td align='left' valign="middle" colspan="3" ><?php echo $form->field($model, 'latitude')->textInput(['data-inputmask'=>'"mask": "N99°99′99.99″"', 'data-mask'=>""])->label(false)->error(false); ?></td>
       		
        </tr>
		<?php if($model->zongdi !== '') {?>
		<tr>
		  <td align='right'>原宗地</td><?= html::hiddenInput('tempzongdi','',['id'=>'temp-zongdi'])?>
		  <td align='left' colspan="4">
		  <?= $model->zongdi;?>
		  </td>
		  </tr>
		  <?php }?>
		<tr>
		  <td align='right' valign="middle"><?= Help::showHelp3('现宗地','ttpo-newzongdi')?></td>
		  <td align='left' valign="middle" colspan="3">
		  <?php 		  
		  $newmodel->zongdi = $model->zongdi;
		  echo html::hiddenInput('tempzongdi','',['id'=>'temp-zongdi']);
		  echo Html::hiddenInput('ttpozongdi','',['id'=>'ttpozongdi-zongdi']);
	      echo Html::hiddenInput('ttpoarea',0,['id'=>'ttpozongdi-area']);
		  echo $form->field($newmodel, 'zongdi')->hiddenInput()->label(false)->error(false);
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
		  <td align='right' valign="middle">合同面积</td><?php $newmodel->contractarea = $model->contractarea;?>
		  <td align='left' valign="middle" colspan="4"><?= $form->field($newmodel, 'contractarea')->textInput(['readonly'=>true])->label(false)->error(false); ?></td>
		  </tr>
		<tr>
		  <td align='right' valign="middle">宗地面积</td><?php $newmodel->measure = $model->measure;?>
		  <td align='left' valign="middle" colspan="4"><?= $form->field($newmodel, 'measure')->textInput(['readonly'=>true])->label(false)->error(false); ?></td>
		  </tr>
		<tr>
		  <td align='right' valign="middle">未明确地块面积</td><?php $newmodel->notclear = $model->notclear;?>
		  <td align='left' valign="middle" colspan="4"><?= $form->field($newmodel, 'notclear')->textInput(['readonly'=>true])->label(false)->error(false);?></td>
		  </tr>
		<tr>
		  <td align='right' valign="middle">未明确状态地块面积</td><?php $newmodel->notstate = $model->notstate;?>
		  <td align='left' valign="middle" colspan="4"><?= $form->field($newmodel, 'notstate')->textInput(['readonly'=>false])->label(false)->error(false);?></td>
		  </tr>
      <tr>
        <td align='right'>备注</td>
        <td  align='left' colspan="4"><?= $form->field($newmodel, 'remarks')->textarea(['rows' => 2])->label(false)->error(false) ?></td>
      </tr>
    </table></td>
  </tr>
</table>
<div class="form-group">
      <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
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
<div id="dialogMsg" title="信息">
<div id="msg" data-info=""></div>
</div>
<script>
$('#farms-notstate').blur(function(){
    var input = $(this).val();
    var notclear = $('#farms-notclear').val();
    var contractarea = $('#farms-contractarea').val();
    var newarea = contractarea*1 - input*1;
	$('#farms-contractarea').val(newarea.toFixed(2));
// 	if($('#farms-notclear').val()*1 > 0) {
// 		var newnotclear = notclear*1 - input*1;
// 		$('#farms-notclear').val(newnotclear.toFixed(2));
// 	}
	toHTH();
});
function zongdiRemove(zongdi,measure,dialogID)
{
	removeZongdiForm(zongdi,measure);
	removeNowZongdi(zongdi);
// 	oldZongdiChange(zongdi,measure,'back');
	var ttpoarea = $('#ttpozongdi-area').val();
	$('#ttpozongdi-area').val(ttpoarea - measure);
	$('#new'+zongdi).remove();
// 	var zongdiarr = zongdi.split('_');
	$('#'+zongdi).attr('disabled',false);
	if(dialogID == 'dialog') {
		//宗地面积计算开始
		var value = $('#oldfarms-measure').val()*1+measure*1;
		$('#oldfarms-measure').val(value.toFixed(2));
		//如果存在未明确状态面积，那么先减未明确状态面积
		var notstate = Number($('#farms-notstate').val());
		
		if(notstate > 0) {
			if(notstate >= Number(measure)) {
				$('#farms-notstate').val(notstate - Number(measure));
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
	//宗地面积计算结束
	jisuan()
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
	$('#farms-zongdi').val(newnewzongdi);

	var ttpozongdi = $('#ttpozongdi-zongdi').val();
	var arr2 = ttpozongdi.split('、');
	$.each(arr2, function(i,val){  
	      if(val === findzongdi)
	    	  arr2.splice(i,1);	      
	  });   
	var newttpozongdi = arr2.join('、');
	
	$('#ttpozongdi-zongdi').val(newttpozongdi);
}
function removeNowZongdi(zongdi)
{
	var nowzongdi = $('#new-zongdi').val();
	var arr1 = zongdi.split('|');
	$.each(arr1, function(i,val){  
	      if(val === zongdi)
	    	  arr1.splice(i,1);	      
	  });   
	var newnewzongdi = arr1.join('|');
// 	alert(newnewzongdi);
	$('#new-zongdi').val(newnewzongdi);
// 	return result;
}
function oldZongdiChange(zongdi,measure,state)
{
	var yzongdi = $('#oldzongdiChange').val();
// 	alert(yzongdi);
	$.getJSON("<?= Url::to(['farms/oldzongdichange'])?>", {yzongdi: yzongdi, zongdi:zongdi,measure:measure,state:state}, function (data) {
		$('#oldzongdiChange').val(data.zongdi);
	});
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

								oldZongdiChange(zongdi,measure,'change');
								var oldnotclear = Number($('#farms-notclear').val());
								if(measure > oldnotclear) {
									var cha = measure - oldnotclear;
									var result = measure - cha;
									alert('已经超过原合同面积，将自动截取为剩余面积。');
									var newzongdi = zongdi+'('+result.toFixed(2)+')';
									var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+result.toFixed(2)+'","dialog2")>×</span>'+newzongdi+'</li>';
									$('.select2-selection__rendered').append(newzongdihtml);
									zongdiForm(zongdi,result.toFixed(2));
									var newvalue = $('#farms-measure').val()*1 + result.toFixed(2)*1;

									$('#farms-measure').val(newvalue.toFixed(2));
									$('#temp_measure').val(newvalue.toFixed(2));
									$('#farms-notclear').val(0);

									var ttpozongdi = $('#ttpozongdi-zongdi').val();
									var zongdistr = zongdi+"("+result.toFixed(2)+")";
									$('#ttpozongdi-zongdi').val(zongdistr+'、'+ttpozongdi);
									var ttpozongdi = $('#ttpozongdi-zongdi').val();
									var last = ttpozongdi.substr(ttpozongdi.length-1,1);
									if(last == '、') {
										$('#ttpozongdi-zongdi').val(ttpozongdi.substring(0,ttpozongdi.length-1));
									}
									var newtempzongdi = $('#new-zongdi').val();
									$("#new-zongdi").val(zongdi+'|'+newtempzongdi);

									$('#ymeasure').val(0);
									var ttpoarea = $('#ttpozongdi-area').val();

									$('#ttpozongdi-area').val(ttpoarea*1 + result*1);
								} else {
									var newzongdi = zongdi+'('+measure+')';
									var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+measure+'","dialog2")>×</span>'+newzongdi+'</li>';
									zongdiForm(zongdi,measure);
									$('.select2-selection__rendered').append(newzongdihtml);
									var value = $('#farms-notclear').val()*1-measure*1;
									$('#farms-notclear').val(value.toFixed(2));
									var newvalue = $('#farms-measure').val()*1 + measure*1;
									$('#farms-measure').val(newvalue.toFixed(2));
									$('#temp_measure').val(newvalue.toFixed(2));
									$('#findZongdi').val('');
									$('#findMeasure').val('');
									var ttpozongdi = $('#ttpozongdi-zongdi').val();
									var zongdistr = zongdi+"("+measure+")";
									$('#ttpozongdi-zongdi').val(zongdistr+'、'+ttpozongdi);
									var ttpozongdi = $('#ttpozongdi-zongdi').val();
									var last = ttpozongdi.substr(ttpozongdi.length-1,1);
									if(last == '、') {
										$('#ttpozongdi-zongdi').val(ttpozongdi.substring(0,ttpozongdi.length-1));
									}
									var newtempzongdi = $('#new-zongdi').val();
									$("#new-zongdi").val(zongdi+'|'+newtempzongdi);

									$('#ymeasure').val(0);
									var ttpoarea = $('#ttpozongdi-area').val();

									$('#ttpozongdi-area').val(ttpoarea*1 + measure*1);
								}
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
						
						var ycontractarea = parseFloat($('#farms-contractarea').val());
						var oldcontractarea = parseFloat($('#oldfarms-contractarea').val());
						
						if(oldcontractarea < 0 && ycontractarea > 0) {
							alert('宗地面积已经大于合同面积，多出面积自动加入未明确状态面积');
						}
						if(oldcontractarea < 0) {
							$('#farms-notstate').val(Math.abs(oldcontractarea));
							
						}
						$('#ymeasure').val(0);	
						jisuan()
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
	$('#findZongdi').val('');
	$('#findMeasure').val('');
	var notclear = <?= $model->notclear?>;
	if(Number(notclear) > 0) {
		$("#dialogSelect").val('dialog2');
		$( "#dialog2" ).dialog( "open" );
		$('#findZongdi').focus();
	}
});
$('#measure').keyup(function (event) {
	if(event.keyCode == 13) {
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
				$( '#dialog' ).dialog( "close" );
				zongdiForm(zongdi,measure);
				oldZongdiChange(zongdi,measure,'change');
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
				var ttpozongdi = $('#ttpozongdi-zongdi').val();
				var zongdistr = zongdi+"("+measure+")";
				$('#ttpozongdi-zongdi').val(zongdistr+'、'+ttpozongdi);
				var ttpozongdi = $('#ttpozongdi-zongdi').val();
				var last = ttpozongdi.substr(ttpozongdi.length-1,1);
				if(last == '、') {
					$('#ttpozongdi-zongdi').val(ttpozongdi.substring(0,ttpozongdi.length-1));
				}
				var newtempzongdi = $('#new-zongdi').val();
				$("#new-zongdi").val(zongdi+'|'+newtempzongdi);
				$('#findZongdi').val('');
				$('#findMeasure').val('');
				$('#ymeasure').val(0);
				var ttpoarea = $('#ttpozongdi-area').val();

				$('#ttpozongdi-area').val(ttpoarea*1 + measure*1);
			}
		}
	}
});
$('#findMeasure').keyup(function (event) {
	if(event.keyCode == 13) {
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
					$( '#dialog2' ).dialog( "close" );

					oldZongdiChange(zongdi,measure,'change');
					var oldnotclear = Number($('#farms-notclear').val());
					if(measure > oldnotclear) {
						var cha = measure - oldnotclear;
						var result = measure - cha;
						alert('已经超过原合同面积，将自动截取为剩余面积。');
						var newzongdi = zongdi+'('+result.toFixed(2)+')';
						var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+result.toFixed(2)+'","dialog2")>×</span>'+newzongdi+'</li>';
						$('.select2-selection__rendered').append(newzongdihtml);
						zongdiForm(zongdi,result.toFixed(2));
						var newvalue = $('#farms-measure').val()*1 + result.toFixed(2)*1;

						$('#farms-measure').val(newvalue.toFixed(2));
						$('#temp_measure').val(newvalue.toFixed(2));
						$('#farms-notclear').val(0);

						var ttpozongdi = $('#ttpozongdi-zongdi').val();
						var zongdistr = zongdi+"("+result.toFixed(2)+")";
						$('#ttpozongdi-zongdi').val(zongdistr+'、'+ttpozongdi);
						var ttpozongdi = $('#ttpozongdi-zongdi').val();
						var last = ttpozongdi.substr(ttpozongdi.length-1,1);
						if(last == '、') {
							$('#ttpozongdi-zongdi').val(ttpozongdi.substring(0,ttpozongdi.length-1));
						}
						var newtempzongdi = $('#new-zongdi').val();
						$("#new-zongdi").val(zongdi+'|'+newtempzongdi);

						$('#ymeasure').val(0);
						var ttpoarea = $('#ttpozongdi-area').val();

						$('#ttpozongdi-area').val(ttpoarea*1 + result*1);
					} else {
						var newzongdi = zongdi+'('+measure+')';
						var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+measure+'","dialog2")>×</span>'+newzongdi+'</li>';
						zongdiForm(zongdi,measure);
						$('.select2-selection__rendered').append(newzongdihtml);
						var value = $('#farms-notclear').val()*1-measure*1;
						$('#farms-notclear').val(value.toFixed(2));
						var newvalue = $('#farms-measure').val()*1 + measure*1;
						$('#farms-measure').val(newvalue.toFixed(2));
						$('#temp_measure').val(newvalue.toFixed(2));
						$('#findZongdi').val('');
						$('#findMeasure').val('');
						var ttpozongdi = $('#ttpozongdi-zongdi').val();
						var zongdistr = zongdi+"("+measure+")";
						$('#ttpozongdi-zongdi').val(zongdistr+'、'+ttpozongdi);
						var ttpozongdi = $('#ttpozongdi-zongdi').val();
						var last = ttpozongdi.substr(ttpozongdi.length-1,1);
						if(last == '、') {
							$('#ttpozongdi-zongdi').val(ttpozongdi.substring(0,ttpozongdi.length-1));
						}
						var newtempzongdi = $('#new-zongdi').val();
						$("#new-zongdi").val(zongdi+'|'+newtempzongdi);

						$('#ymeasure').val(0);
						var ttpoarea = $('#ttpozongdi-area').val();

						$('#ttpozongdi-area').val(ttpoarea*1 + measure*1);
					}
				}

			}

		}
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
			$.getJSON("<?= Url::to(['parcel/parcelarea'])?>", {zongdi: input,farms_id:<?= $_GET['farms_id']?>}, function (data) {
				if (data.status == 1) {
// 					if(data.showmsg) {
// 						$("#msg").text("");
// 						$('#msg').append(data.message);
// 						$("#dialogMsg").dialog("open");
// 					}
					$('#findMeasure').val(data.area);
					$('#ymeasure').val(data.area);
					$("#findMeasure").focus();
				}
				else {
					if(input != '') {
						if(data.showmsg) {
							$("#msg").text("");
							$('#msg').append(data.message);
							$("#dialogMsg").dialog("open");
						}
						$("#findZongdi").val('');
						$('#findMeasure').val('');
						$("#findZongdi").focus();
					}
				}
			});
		}
	}
});
$('#findZongdi').blur(function (event) {
	var input = $(this).val();
	if(input != '') {
		if(nowZongdiFind(input)){
			alert('您已经输入过此宗地号，请不要重复输入');
			$('#findZongdi').val('');
				$('#findMeasure').val('');
		} else {
			$.getJSON("<?= Url::to(['parcel/parcelarea'])?>", {zongdi: input,farms_id:<?= $_GET['farms_id']?>}, function (data) {
				if (data.status == 1) {
					if(data.showmsg) {
						$("#msg").text("");
						$('#msg').append(data.message);
						$("#dialogMsg").dialog("open");
					}
					$('#findMeasure').val(data.area);
					$('#ymeasure').val(data.area);
					
				}
				else {
					if(input != '') {
						if(data.showmsg) {
							$("#msg").text("");
							$('#msg').append(data.message);
							$("#dialogMsg").dialog("open");
						}
						$("#findZongdi").val('');
						$('#findMeasure').val('');
						$("#findZongdi").focus();
					}
				}
			});
		}
	}
});
$( "#dialogMsg" ).dialog({
	autoOpen: false,
	width: 400,
	buttons: [
		{
			text: "确定",
			click: function() {
				$( this ).dialog( "close" );
			}

		},

	]
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

$('#farms-cardid').blur(function(){
	var input = $(this).val();
	if(input !== '') {
		if(input.length > 18 || input.length <18 ) {
			alert('身份证号不正确，请重新检查。');
			$('#farms-cardid').focus();
		}
	}
});
$('#farms-telephone').blur(function(){
	var input = $(this).val();
	if(input !== '') {
		if(input.length > 11 || input.length <11 ) {
			alert('手机号码不正确，请重新检查。');
			$('#farms-telephone').focus();
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
<script>
		$(function () {
			//Initialize Select2 Elements
			$(".select2").select2();

			//Datemask dd/mm/yyyy
			$("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
			//Datemask2 mm/dd/yyyy
			$("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
			//Money Euro
			$("[data-mask]").inputmask();
		});
	</script>