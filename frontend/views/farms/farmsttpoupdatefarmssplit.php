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
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?></h3></div>
                <div class="box-body">
  <table width="100%" border="0">
    <tr>
    <td width="46%" valign="top"><table width="104%" height="458px"
		class="table table-bordered table-hover">
      <tr>
        <td width="15%" align='right' valign="middle">农场名称</td>
        <td colspan="5" align='left' valign="middle"><?= $model->farmname?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">承包人姓名</td>
        <td colspan="5" align='left' valign="middle"><?= $model->farmername ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">身份证号</td>
        <td colspan="5" align='left' valign="middle"><?= $model->cardid ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">电话号码</td>
        <td colspan="5" align='left' valign="middle"><?= $model->telephone ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">农场位置</td>
        <td colspan="5" align='left' valign="middle"><?= $model->address?></td>
        </tr>
      <tr>
      <tr>
        <td width="20%" align='right' valign="middle">管理区</td>
        <td colspan="5" align='left' valign="middle"><?= ManagementArea::find()->where(['id'=>$model->management_area])->one()['areaname']?></td>
        </tr>
      <tr>
      <tr>
			<td width=15% align='right'>合同号</td>
			<td colspan="5" align='left'><?php if($ttpoModel->actionname == 'farmstransfer') echo $model->contractnumber; else echo html::textInput('oldcontractnumber',$ttpoModel->oldchangecontractnumber,['id'=>'oldfarms-contractnumber','class'=>'form-control','readonly'=>"readonly"])?></td>
		</tr>
		<tr>
			<td width=15% align='right'>承包年限</td>
			<td align='center'>自</td>
			<td align='center'><?php echo DateTimePicker::widget([
				'name' => 'oldbegindate',
				'value' => $model->begindate,
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
				'value' => $model->enddate,
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
        <td colspan="5" align='left' valign="middle">
        <table width="100%" height="100%" border="0" cellspacing="5">
        <?php  
        if($ttpoModel->actionname !== 'farmstransfer') {
        	if($model->zongdi) {
        $arrayZongdi = explode('、', $model->zongdi);       
        $ttpozongdi = explode('、', $ttpoModel->ttpozongdi);
        $tempArrayZongdi = $ttpozongdi;
		$diff = array_diff($arrayZongdi, $ttpozongdi);
        foreach ($arrayZongdi as $key => $zongdi) {
        	foreach ($ttpozongdi as $tkey => $tzongdi) {
        		if(Lease::getZongdi($zongdi) == Lease::getZongdi($tzongdi)) {
        			unset($tempArrayZongdi[$tkey]);
        			$cha = Lease::getArea($zongdi) - Lease::getArea($tzongdi);     
        			$arrayZongdi[$key] = Lease::getZongdi($zongdi)."(".$cha.")";
        		}
        	}
        }
        for($i = 0;$i<count($arrayZongdi);$i++) {
        	// 			    	echo $i%6;
        	if($i%3 == 0) {
        		echo '<tr height="10">';
        		echo '<td align="center" class="zongdi">';
        		if(Lease::getArea($arrayZongdi[$i]) == 0) {
        			
        			echo '<span>'.html::button($arrayZongdi[$i],['onclick'=>'toZongdi("'.Lease::getZongdi($arrayZongdi[$i]).'","'.Lease::getArea($arrayZongdi[$i]).'")','value'=>$arrayZongdi[$i],'id'=>Lease::getZongdi($arrayZongdi[$i]),'disabled'=>"disabled",'class'=>"btn btn-default"]).'</span>';
        		}
        		else
        			echo html::button($arrayZongdi[$i],['onclick'=>'toZongdi("'.Lease::getZongdi($arrayZongdi[$i]).'","'.Lease::getArea($arrayZongdi[$i]).'")','value'=>$arrayZongdi[$i],'id'=>Lease::getZongdi($arrayZongdi[$i]),'class'=>"btn btn-default"]);
        		echo '</td>';
        	} else {
        		echo '<td align="center" class="zongdi">';
        		if(Lease::getArea($arrayZongdi[$i]) == 0) {
        			
        			echo html::button($arrayZongdi[$i],['onclick'=>'toZongdi("'.Lease::getZongdi($arrayZongdi[$i]).'","'.Lease::getArea($arrayZongdi[$i]).'")','value'=>$arrayZongdi[$i],'id'=>Lease::getZongdi($arrayZongdi[$i]),'disabled'=>"disabled",'class'=>"btn btn-default"]);
        		}
        		else
        			echo html::button($arrayZongdi[$i],['onclick'=>'toZongdi("'.Lease::getZongdi($arrayZongdi[$i]).'","'.Lease::getArea($arrayZongdi[$i]).'")','value'=>$arrayZongdi[$i],'id'=>Lease::getZongdi($arrayZongdi[$i]),'class'=>"btn btn-default"]);
        		echo '</td>';
        	}}
        }} else 
        	echo $ttpoModel->oldzongdi;
        ?>
        </table></td>
        </tr>
        <?php 
        	$ttpozongdi = explode('、', $ttpoModel->ttpozongdi);
        	$ttpozongdistr = '';
        	foreach ($ttpozongdi as $value) {
        		$str = Lease::getZongdi($value);
        		$ttpozongdistr .= $str.'|';
        	}
        ?>
      <tr><?= Html::hiddenInput('oldzongdi',$ttpoModel->oldzongdi,['id'=>'oldfarm-zongdi','class'=>'form-control']) ?>
      <?= Html::hiddenInput('oldzongdichange',$ttpoModel->oldchangezongdi,['id'=>'oldzongdiChange','class'=>'form-control']) ?>
      <?= Html::hiddenInput('ttpozongdi',$ttpoModel->ttpozongdi,['id'=>'ttpozongdi-zongdi']) ?>
      <?= Html::hiddenInput('newzongdi',$ttpozongdistr,['id'=>'new-zongdi']) ?>
      <?= Html::hiddenInput('ttpoarea',$ttpoModel->ttpoarea,['id'=>'ttpozongdi-area']) ?>
        <td align='right' valign="middle">宗地面积</td><?= html::hiddenInput('tempoldmeasure',$model->measure,['id'=>'temp_oldmeasure']) ?>
        										 <?= html::hiddenInput('tempoldnotclear',$model->notclear,['id'=>'temp_oldnotclear']) ?>
        										 <?= html::hiddenInput('tempoldcontractarea',$model->contractarea,['id'=>'temp_oldcontractarea']) ?>
        <td colspan="5" align='left' valign="middle"><?php if($ttpoModel->actionname == 'farmstransfer') echo $model->measure; else echo html::textInput('oldmeasure',$ttpoModel->oldchangemeasure,['readonly' => true,'id'=>'oldfarms-measure','class'=>'form-control']) ?></td>
        </tr>
        <tr>
        <td align='right' valign="middle">合同面积</td>
        <td colspan="5" align='left' valign="middle"><?php if($ttpoModel->actionname == 'farmstransfer') echo $model->contractarea; else echo html::textInput('oldcontractarea',Farms::getContractnumberArea($ttpoModel->oldchangecontractnumber),['readonly' => true,'id'=>'oldfarms-contractarea','class'=>'form-control']) ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">未明确地块面积</td>
        <td colspan="5" align='left' valign="middle"><?php if($ttpoModel->actionname == 'farmstransfer') echo $model->notclear; else echo html::textInput('oldnotclear',$ttpoModel->oldchangenotclear,['readonly' => true,'id'=>'oldfarms-notclear','class'=>'form-control']) ?></td>
        </tr>
         <tr>
        <td align='right' valign="middle">未明确状态面积</td>
        <td colspan="5" align='left' valign="middle"><?php if($ttpoModel->actionname == 'farmstransfer') echo $model->notstate; else echo html::textInput('oldnotstate',$ttpoModel->oldchangenotstate,['readonly' => true,'id'=>'oldfarms-notstate','class'=>'form-control']) ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">备注</td>
        <td colspan="5" align='left' valign="middle"><?= $model->remarks?></td>
        </tr>
    </table></td>
    <td width="4%" align="center"><font size="5"><i class="fa fa-arrow-right"></i></font></td>
    <td width="50%">
    <table width="99%" height="458px" class="table table-bordered table-hover">
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
        <td width="30%" align='right'>农场位置</td>
        <td colspan="5" align='left'><?=  $form->field($nowModel, 'address')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        </tr>
       <tr>
        <td width="30%" align='right' valign="middle">管理区</td>
        <td colspan="5" align='left' valign="middle"><?=  ManagementArea::find()->where(['id'=>$model->management_area])->one()['areaname']?></td>
        </tr>
       <tr>
			<td width=30% align='right'>合同号</td>
			<td colspan="5" align='left'><?= $form->field($nowModel, 'contractnumber')->textInput(['maxlength' => 500,'readonly'=>'readonly'])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=30% align='right'>承包年限</td>
			<td align='center'>自</td><?php if($model->begindate == '') $nowModel->begindate='2010-09-13'; else $nowModel->begindate = $model->begindate;if($model->enddate == '') $nowModel->enddate = '2025-09-13';else $nowModel->enddate = $model->enddate;?>
			<td align='center'><?= $form->field($nowModel, 'begindate')->textInput()->label(false)->error(false)->widget(
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
			<td align='center'><?= $form->field($nowModel, 'enddate')->textInput()->label(false)->error(false)->widget(
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
		  <td align='right'>宗地</td><?= html::hiddenInput('tempzongdi','',['id'=>'temp-zongdi'])?><?= $form->field($nowModel, 'zongdi')->hiddenInput()->label(false)->error(false) ?>
		  <td colspan="5" align='left'>
		  
		  <span id="inputZongdi" class="select2-container select2-container--default select2-container--below" dir="ltr" style="width: 100%; color: #000;">
	<span class="selection">
		<span class="select2-selection select2-selection--multiple" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false" tabindex="0">
			<ul class="select2-selection__rendered">
				<li class="select2-search select2-search--inline">
					<input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" placeholder="" style="width: 0.75em;">
					<?php
					$diff = Farms::diff_zongdi($model->zongdi,$ttpoModel->ttpozongdi);
					$iden = Farms::iden_zongdi($model->zongdi,$ttpoModel->ttpozongdi);
					if($iden) {
						foreach ($iden as $zongdi) {
							echo '<li class="select2-selection__choice" id="new' . Lease::getZongdi($zongdi) . '" title="' . $zongdi . '"><span class="remove text-red" role="presentation" onclick=zongdiRemove("' . Lease::getZongdi($zongdi) . '","' . Lease::getArea($zongdi) . '","dialog")>×</span>' . $zongdi . '</li>';
						}
					}
// 					var_dump($diff);
					if($diff) {
						
							foreach ($diff as $zongdi) {
								echo '<li class="select2-selection__choice" id="new'.Lease::getZongdi($zongdi).'" title="'.$zongdi.'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'.Lease::getZongdi($zongdi).'","'.Lease::getArea($zongdi).'","dialog2")>×</span>'.$zongdi.'</li>';
							}
						}
					
					?>
				</li>
			</ul>
		</span>
	</span>
	<span class="dropdown-wrapper" aria-hidden="true"></span>
</span>

		  </td>
		  </tr>

		<tr>
        <td align='right'>宗地面积</td><?= html::hiddenInput('tempmeasure',$nowModel->measure,['id'=>'temp_measure']) ?>
        							<?= html::hiddenInput('yuanmeasure',$nowModel->measure,['id'=>'ymeasure','value'=>0]) ?>
								  <?= html::hiddenInput('tempnotclear',$nowModel->notclear,['id'=>'temp_notclear']) ?>
        <td colspan="5" align='left'><?= $form->field($nowModel, 'measure')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
        </tr>
        <tr>
        <td align='right'>合同面积</td>
        <td colspan="5" align='left'><?= $form->field($nowModel, 'contractarea')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
       </tr>
      <tr>
        <td align='right'>未明确地块面积</td>
        <td colspan="5" align='left'><?= $form->field($nowModel, 'notclear')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
       </tr>
        <tr>
        <td align='right'>未明确状态面积</td>
        <td colspan="5" align='left'><?= $form->field($nowModel, 'notstate')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
       </tr>
       <tr>
        <td align='right'>转让未明确地块面积</td><?php if($model->notclear) $realonly = false; else $realonly = true;?>
        <td colspan="5" align='left'><?= html::textInput('inputnotclear','',['id'=>'input-notclear','class'=>'form-control','readonly'=>$realonly]) ?></td>
       </tr>
      <tr>
        <td align='right'>备注</td><?php $nowModel->remarks = $model->remarks;?>
        <td colspan="5" align='left'><?= $form->field($nowModel, 'remarks')->textarea(['rows'=>2])->label(false)->error(false) ?></td>
        </tr>
    </table></td>
  </tr>
</table>
<?= html::hiddenInput('actionname',$ttpoModel->actionname,['id'=>'Actionname'])?>
<?= html::hiddenInput('dialogselect','',['id'=>'dialogSelect'])?>
<div class="form-group">
      <?= Html::submitButton('提交', ['class' =>  'btn btn-success']) ?>
      <?= Html::button('重置', ['class' => 'btn btn-primary','id'=>'reset']) ?>
      <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$farms_id], ['class' => 'btn btn-success'])?>
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
function zongdidelete(farms_id,zongdi)
{
	$.get("<?= Url::to(['parcel/parcelzongdioffarmdelete'])?>", {farms_id:farms_id,zongdi: zongdi}, function (data){
//			alert(data.id);
	});
}
function zongdiRemove(zongdi,measure,dialogID)
{
	
	removeZongdiForm(zongdi,measure);
	removeNowZongdi(zongdi);
	oldZongdiChange(zongdi,measure,'back');
	
	var ttpoarea = $('#ttpozongdi-area').val();
	$('#ttpozongdi-area').val(ttpoarea - measure);
	$('#new'+zongdi).remove();
// 	var zongdiarr = zongdi.split('_');
// 	alert($('#'+zongdi).val());
// 	alert($('#oldfarms-notclear').val());
	
	if(dialogID == 'dialog') {
		if($('#'+zongdi).val() != undefined) {	
			$('#'+zongdi).attr('disabled',false);
			var getzongdivalue = $('#'+zongdi).val();	
			var zongdiarea = getArea(getzongdivalue);	
			var zongdivalue = zongdiarea*1 + measure*1;
			$('#'+zongdi).attr('value',zongdi+"("+zongdivalue+")");
			$('#'+zongdi).text(zongdi+"("+zongdivalue+")");
			$('#'+zongdi).attr('onclick',"toZongdi('"+zongdi+"',"+zongdivalue+")");
		}
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
		
// 		alert($('#oldfarms-notclear').val());
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
		zongdidelete(<?= $ttpoModel->newnewfarms_id?>,zongdi);
	}
	//宗地面积计算结束
// 	jisuan();
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
	
	var arr1 = nowzongdi.split('|');
	$.each(arr1, function(i,val){  
	      if(val === zongdi)
	    	  arr1.splice(i,1);	      
	  });   
	var newnewzongdi = arr1.join('|');
	
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
		  						oldZongdiChange(zongdi,measure,'change');					
		  					 	var newzongdi = zongdi+'('+measure+')';
		  					 	var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+measure+'","dialog2")>×</span>'+newzongdi+'</li>';
		  						
		  						$('.select2-selection__rendered').append(newzongdihtml);
		  						$('#'+zongdi).attr('disabled',true);
		  						var value = $('#oldfarms-notclear').val()*1-measure*1;
		  						$('#oldfarms-notclear').val(value.toFixed(2));
		  						var newvalue = $('#farms-measure').val()*1 + measure*1;
		  						$('#farms-measure').val(newvalue.toFixed(2));
		  						$('#temp_measure').val(newvalue.toFixed(2));
// 		  						jisuan();
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
						oldZongdiChange(zongdi,measure,'change');	
					 	var newzongdi = zongdi+'('+measure+')';
					 	var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+measure+'","dialog")>×</span>'+newzongdi+'</li>';
						var oldmeasure = $('#ymeasure').val() - measure;
						var oldzongdi = zongdi+'('+cutZero(oldmeasure.toFixed(2))+')';
// 						alert(oldzongdi);
					 	$('#'+zongdi).text(oldzongdi);
					 	$('#'+zongdi).val(oldzongdi);
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
	var oldfarmsnotclear = <?= $model->notclear?>;
	if(oldfarmsnotclear > 0) {
		$("#dialogSelect").val('dialog2');
		$( "#dialog2" ).dialog( "open" );
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
			$.getJSON("<?= Url::to(['parcel/parcelarea'])?>", {zongdi: input,farms_id:<?= $_GET['id']?>}, function (data) {
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
					$( this ).dialog( "close" );
					zongdiForm(zongdi,measure);
					oldZongdiChange(zongdi,measure,'change');
					var newzongdi = zongdi+'('+measure+')';
					var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+measure+'","dialog2")>×</span>'+newzongdi+'</li>';

					$('.select2-selection__rendered').append(newzongdihtml);
					$('#'+zongdi).attr('disabled',true);
					var value = $('#oldfarms-notclear').val()*1-measure*1;
					$('#oldfarms-notclear').val(value.toFixed(2));
					var newvalue = $('#farms-measure').val()*1 + measure*1;
					$('#farms-measure').val(newvalue.toFixed(2));
					$('#temp_measure').val(newvalue.toFixed(2));
// 		  						jisuan();
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
$('#findZongdi').blur(function (event) {
	var input = $(this).val();
	if(input != '') {
		if(nowZongdiFind(input)){
			alert('您已经输入过此宗地号，请不要重复输入');
			$('#findZongdi').val('');
			$('#findMeasure').val('');
		} else {
			$.getJSON("<?= Url::to(['parcel/parcelarea'])?>", {zongdi: input,farms_id:<?= $_GET['id']?>}, function (data) {
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
// 	alert($('#ymeasure').val());
// 	var oldzongdi = $('#oldfarm-zongdi').val();
// 	var strzongdi = zongdi+"("+area+")";
// 	$('#oldfarm-zongdi').val(oldzongdi.replace(strzongdi, ""));
// 	oldzongdistr = $('#oldfarm-zongdi').val();
// 	var first = oldzongdistr.substr(0,1);
// 	var last = oldzongdistr.substr(oldzongdistr.length-1,1);
// 	if(first == '、') {
// 		$('#oldfarm-zongdi').val(oldzongdistr.substring(1));
// 	}
// 	if(last == '、') {
// 		$('#oldfarm-zongdi').val(oldzongdistr.substring(0,oldzongdistr.length-1));
// 	}
// 	//alert($('#oldfarm-zongdi').val());
// 	var ttpozongdi = $('#ttpozongdi-zongdi').val();
// 	ttpozongdi = ttpozongdi + '、' + zongdi;
// 	var first = ttpozongdi.substr(0,1);
// 	if(first == '、') {
// 		$('#ttpozongdi-zongdi').val(ttpozongdi.substring(1));
// 	}
// 	else
// 		$('#ttpozongdi-zongdi').val(ttpozongdi);
	
// 	var ttpoarea = $('#ttpozongdi-area').val();
// 	ttpoarea = area*1 + ttpoarea*1;
// 	$('#ttpozongdi-area').val(ttpoarea);
// 	toHTH();
	
	
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
	if($('#Actionname').val() !== 'farmstransfer') {
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
		
// 		if(input > $('#temp_notclear').val()) {
// 			alert('>>>>');
// 				var tempmeasure = $('#temp_measure').val();
// 				var farmsmeasure = $('#farms-measure').val();
// 				$('#farms-contractarea').val(tempmeasure*1 + $('#temp_notclear').val()*1);
// 				if(farmsmeasure < tempmeasure) {
// 					var result = farmsmeasure*1 + input*1;
					
// 					$('#farms-notclear').val(input);
// 					$('#temp_notclear').val(input);	
// 				} else {
// 					var oldmeasure = parseFloat($('#oldfarms-measure').val());
// 					var oldcontractarea = parseFloat($('#temp_oldcontractarea').val());
					
// 						var cha = input*1 - $('#temp_notclear').val()*1;
// 						var result = farmsmeasure*1 + cha*1;
						
// 						var oldresult = oldmeasure*1 - cha*1;
// 					if(oldcontractarea != 0) {
// 						var notclearresult = oldcontractarea*1 - cha*1;
// 						$('#oldfarms-notclear').val(notclearresult.toFixed(2));
// 						$('#farms-notclear').val(input);
// 						$('#temp_notclear').val(input);
// 					} else {
// 						if(oldmeasure != 0) {
							
// 							var oldnotclear = $('#oldfarms-notclear').val();
// 							var notclearresult = oldnotclear*1 - cha*1;
// 							$('#oldfarms-notclear').val(notclearresult.toFixed(2));
							
// 							$('#farms-notclear').val(input);
// 							$('#temp_notclear').val(input);	
// 						} else {
// 							$('#farms-contractarea').val(input);
// 							$('#farms-notclear').val(input);
							
// 							var oldnotclear = $('#temp_oldnotclear').val();
	
// 							var notclearresult = oldnotclear*1 - input*1;
							
// 							$('#oldfarms-notclear').val(notclearresult.toFixed(2));
							
// 						}
// 					}					
// 				}
			
// 		} 
// 		if(input < $('#temp_notclear').val()) {
// 			alert('<<<<')
// 			if($('#farms-measure').val() !== 0 ) {
// 				var tempmeasure = $('#temp_measure').val();
// 				var farmsmeasure = $('#farms-measure').val();
// 				if(farmsmeasure < tempmeasure) {
// 					var result = farmsmeasure*1 + input*1;
// 					$('#temp_measure').val(result.toFixed(2));
// 					$('#farms-measure').val(result.toFixed(2));
// 					$('#farms-notclear').val(input);	
// 					$('#temp_notclear').val(input);	
					
// 				} else {
// 					var oldmeasure = $('#oldfarms-measure').val();
// // 					alert(oldmeasure);
// 					if(oldmeasure != 0) {
// 						var cha = $('#temp_notclear').val()*1 - input*1;
// 						var result = farmsmeasure*1 - cha*1;
						
// 						var oldresult = oldmeasure*1 + cha*1;
// 						$('#oldfarms-measure').val(oldresult.toFixed(2));
// 						var oldnotclear = $('#oldfarms-notclear').val();
// 						var notclearresult = oldnotclear*1 + cha*1;
// 						$('#oldfarms-notclear').val(notclearresult.toFixed(2));
// 						$('#temp_measure').val(result.toFixed(2));
// 						$('#farms-measure').val(result.toFixed(2));
// 						$('#farms-notclear').val(input);
// 						$('#temp_notclear').val(input);	
// 					}
// 				}
// 			}
// 		}
// 	}
// 	toHTH();
// });
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

