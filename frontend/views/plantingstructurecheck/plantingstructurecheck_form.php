<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Farmer;
use yii\helpers\ArrayHelper;
use app\models\Farms;
use app\models\Parcel;
use yii\web\View;
use app\models\Plant;
use app\models\Inputproduct;
use app\models\Pesticides;
use app\models\Lease;
use app\models\Goodseed;
use app\models\Inputproductbrandmodel;
use app\models\plantingstructurecheck;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\plantingstructurecheck */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="plantingstructurecheck-form">
	<?php
	$farm = Farms::findOne($_GET['farms_id']);
//	var_dump($noarea);
	?>
    <?php $form = ActiveFormrdiv::begin(); ?>
	<?php
//	Plantingstructurecheck::mergeZongdi($farm['id'],$_GET['id']);
	?>
<table width="61%" class="table table-bordered table-hover">
<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$farm->id])->label(false)->error(false) ?>
<?= $form->field($model, 'lease_id')->hiddenInput(['value'=>$_GET['lease_id']])->label(false)->error(false) ?>
<tr>
<td width=15% align='right'>农场名称</td>
<td align='left'><?= $farm->farmname?></td>
<td align='right'>法人</td>
<?php if($_GET['lease_id'] == 0) {?>
<td colspan="3" align='left'><?= $farm->farmername?></td>
<?php } else {?>
<td align='left'><?= $farm->farmername?></td>
<td align='right'>承租人</td>
<td align='right'><?= Lease::find()->where(['id'=>$_GET['lease_id']])->one()['lessee'] ?></td>
<?php }?>
<td align='right'>农场面积：<?= $farm->contractarea.' 亩'?></td>
<td align='left'>已种植面积：<?= $overarea?>亩</td>
</tr>
<tr>
 	<td align='right'>种植面积</td><?php if($model->isNewRecord) $area = $noarea; else $area = $model->area;?>
  	<td align='left'><?= $form->field($model, 'area')->textInput(['value'=>sprintf('%.2f',$area)])->label(false)->error(false) ?></td>
	<td align='right'>宗地</td>
  	<td colspan="5" align='left'><?= $form->field($model, 'zongdi')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
  </tr>
<tr>
  <td align='right'>种植作物</td><?php $fatherid = Plant::find()->where(['id'=>$model->plant_id])->one()['father_id'];?>
  <td align='left'><?= html::dropDownList('plant-father',$fatherid,ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'typename'),['prompt'=>'请选择...','class'=>'form-control','id'=>'plantfather']) ?></td>
  <td colspan="2" align='right'><?= $form->field($model, 'plant_id')->dropDownList(ArrayHelper::map(Plant::find()->where(['father_id'=>$fatherid])->all(), 'id', 'typename'),['prompt'=>'请选择...'])->label(false) ?></td>
  <td colspan="2" align="right">良种型号</td>
  <td colspan="2"><?= $form->field($model, 'goodseed_id')->dropDownList(ArrayHelper::map(Goodseed::find()->where(['plant_id'=>$model->plant_id])->all(), 'id', 'typename'),['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
</tr>

</table>
    <div class="form-group">
        <?= Html::button('增加投入品', ['class' => 'btn btn-info','title'=>'点击可增加一行投入品', 'id' => 'add-plantinputproduct']) ?>
    </div>
<table class="table table-bordered table-hover" id="plantinputproduct">
	
 <!-- 模板 -->

      <thead id="plantinputdroduct-template" class="d-none">
          <tr>
			  <?php echo Html::hiddenInput('PlantInputproductPost[id][]', '', ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[farms_id][]','', ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[lessee_id][]', '', ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[zongdi][]', '', ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[plant_id][]', '', ['class' => 'form-control']); ?>
              <td><?php echo Html::dropDownList('PlantInputproductPost[father_id][]', '', ArrayHelper::map(Inputproduct::find()->where(['father_id'=>1])->all(), 'id', 'fertilizer'),['prompt'=>'请选择...', 'class'=>'plantinputproduct-father_id','class' => 'form-control']); ?></td>
              <td><?php echo Html::dropDownList('PlantInputproductPost[son_id][]', '',['prompt'=>'请选择...'], ['id'=>'plantinputproduct-son_id', 'class' => 'form-control']); ?></td>
              <td><?php echo Html::dropDownList('PlantInputproductPost[inputproduct_id][]', '',['prompt'=>'请选择...'] ,['id'=>'plantinputproduct-inputproduct_id','class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('BrandmodelPost[brand][]','',['class'=>'form-control brandsearch'])?></td>
              <td valign="middle" align="center"><?php echo Html::textInput('PlantInputproductPost[pconsumption][]', '', ['id'=>'plantinputproduct-pconsumption','class' => 'form-control']); ?></td>
              <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-employee']) ?></td>
          </tr>
      </thead>
	<tbody>
		<tr>
			<td width=15% align='center'>投入品父类</td>
			<td align='center'>投入品子类</td>
			<td align='center'>投入品</td>
            <td align='center'>品牌型号</td>
			<td align='center'>投入品用量</td>
			<td align='center'>操作</td>
		</tr>
		<?php 
		if(is_array($plantinputproductModel)) {
		foreach ($plantinputproductModel as $value) {
			?>
		<tr>
			  <?php echo Html::hiddenInput('PlantInputproductPost[id][]', $value['id'], ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[farms_id][]', $value['farms_id'], ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[lessee_id][]',  $value['lessee_id'], ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[zongdi][]',  $value['zongdi'], ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[plant_id][]', $value['plant_id'], ['class' => 'form-control']); ?>
              <td><?php echo Html::dropDownList('PlantInputproductPost[father_id][]',  $value['father_id'], ArrayHelper::map(Inputproduct::find()->where(['father_id'=>1])->all(), 'id', 'fertilizer'),['prompt'=>'请选择...', 'id'=>'plantinputproduct-father_id','class' => 'form-control']); ?></td>
              <td><?php echo Html::dropDownList('PlantInputproductPost[son_id][]',  $value['son_id'],ArrayHelper::map(Inputproduct::find()->where(['father_id'=>$value['father_id']])->all(), 'id', 'fertilizer'),['prompt'=>'请选择...', 'id'=>'plantinputproduct-son_id', 'class' => 'form-control']); ?></td>
              <td><?php echo Html::dropDownList('PlantInputproductPost[inputproduct_id][]',  $value['inputproduct_id'],ArrayHelper::map(Inputproduct::find()->where(['father_id'=>$value['son_id']])->all(), 'id', 'fertilizer'),['prompt'=>'请选择...','id'=>'plantinputproduct-inputproduct_id','class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('BrandmodelPost[brand][]','',['class'=>'form-control brandsearch'])?></td>
              <td valign="middle" align="center"><?php echo Html::textInput('PlantInputproductPost[pconsumption][]',  $value['pconsumption'], ['id'=>'plantinputproduct-pconsumption','class' => 'form-control']); ?></td>
              <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-employee']) ?></td>
          </tr>
<?php }}?>
		
	</tbody>
</table>
    <div class="form-group">
        <?= Html::button('增加农药', ['class' => 'btn btn-info','title'=>'点击可增加一行农药', 'id' => 'add-plantpesticides']) ?>
    </div>
<table class="table table-bordered table-hover" id="plantpesticides">
	
 <!-- 模板 -->

      <thead id="plantpesticides-template" class="d-none">
          <tr>
				<?php echo Html::hiddenInput('PlantpesticidesPost[id][]', '', ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantpesticidesPost[farms_id][]','', ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantpesticidesPost[lessee_id][]', '', ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantpesticidesPost[plant_id][]', '', ['class' => 'form-control']); ?>
              <td><?php echo Html::dropDownList('PlantpesticidesPost[pesticides_id][]', '', ArrayHelper::map(Pesticides::find()->all(), 'id', 'pesticidename'),['prompt'=>'请选择...', 'id'=>'plantpesticides-id','class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('PlantpesticidesPost[pconsumption][]', '', ['id'=>'plantinputproduct-pconsumption','class' => 'form-control']); ?></td>
              <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-pesticides']) ?></td>
          </tr>
      </thead>
	<tbody>
		<tr>
			<td width=40% align='center'>农药</td>
			<td align='center'>农药用量</td>
			<td align='center'>操作</td>
		</tr>
		<?php 
		if(is_array($plantpesticidesModel)) {
		foreach ($plantpesticidesModel as $value) {?>
		 <tr>
				<?php echo Html::hiddenInput('PlantpesticidesPost[id][]', $value['id'], ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantpesticidesPost[farms_id][]',$value['farms_id'], ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantpesticidesPost[lessee_id][]', $value['lessee_id'], ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantpesticidesPost[plant_id][]', $value['plant_id'], ['class' => 'form-control']); ?>
              <td><?php echo Html::dropDownList('PlantpesticidesPost[pesticides_id][]', $value['pesticides_id'], ArrayHelper::map(Pesticides::find()->all(), 'id', 'pesticidename'),['prompt'=>'请选择...', 'id'=>'plantpesticides-id','class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('PlantpesticidesPost[pconsumption][]', $value['pconsumption'], ['id'=>'plantinputproduct-pconsumption','class' => 'form-control']); ?></td>
              <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-pesticides']) ?></td>
          </tr>
<?php }}?>
		
	</tbody>
</table>

    <div class="form-group">
    <?php ?>
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('返回', [Yii::$app->controller->id.'index','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveFormrdiv::end(); ?>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               请选择宗地（面积），如所租赁地块不是整块，可修改面积数值。
            </h4>
         </div>
         <div class="modal-body">
            <table class="table table-bordered table-hover">
    
    	<tr>
    		<td align='center'>租赁面积（宗地）</td>
    	</tr>
    	<tr>
    		<td align='center'><?= Html::textInput('parcellist','',['id'=>'model-parcellist','class'=>'form-control'])?></td>

    	</tr>
    	<tr>
    		<td align='left'><?php 
			$zongdiarr = $noarea;
			//var_dump($zongdiarr);
			if(is_array($zongdiarr)) {
				echo html::hiddenInput('tempAllZongdi',implode('、', $zongdiarr),['id'=>'temp-allzongdi']);
				$i=0;
				foreach($zongdiarr as $value) {
					echo html::button($value,['onclick'=>'toParcellist("'.$value.'","'.Lease::getZongdi($value).'")','value'=>$value,'id'=>Lease::getZongdi($value),'class'=>"btn btn-default"]).'&nbsp;&nbsp;&nbsp;';
					
					$i++;
					if($i%4 == 0)
						echo '<br><br>';
				}
			} else {
				//var_dump(bcsub($farm->measure,$zongdiarr,2));
				echo html::hiddenInput('tempAllZongdi',bcsub($farm->measure,$zongdiarr,2),['id'=>'temp-allzongdi']);
				echo html::button(bcsub($farm->measure,$zongdiarr,2),['onclick'=>'toParcellist("'.bcsub($farm->measure,$zongdiarr,2).'","'.bcsub($farm->measure,$zongdiarr,2).'")','value'=>bcsub($farm->measure,$zongdiarr,2),'id'=>bcsub($farm->measure,$zongdiarr,2),'class'=>"btn btn-default"]).'&nbsp;&nbsp;&nbsp;';
			}
			
    		echo html::button('全选',['onclick'=>'toAll()','','id'=>'allzongdi','class'=>"btn btn-default"]).'&nbsp;&nbsp;&nbsp;';
    		?></td>

    	</tr>
    </table>

			 <?= Html::hiddenInput('tempZongdi',$model->zongdi,['id'=>'tempzongdi'])?>
			 <div id="dialogZongdi" title="宗地信息">
				 <table width="100%" class="table table-bordered table-hover">
					 <tr>
						 <td align="center" width="10%">现有宗地</td>
						 <td width="40%">

							 <table width="100%" height="100%" border="0" cellspacing="5">
								 <?php
								 if(isset($_GET['id'])) {
									 $check_id = $_GET['id'];
								 } else {
									 $check_id = null;
								 }
//								 Plantingstructurecheck::mergeZongdi($farm['id'],$check_id);
//								 Plantingstructurecheck::unUseZongdi($farm['id'],$check_id);
								 if(!empty($farm->zongdi)) {
									 $arrayZongdi = explode('、', $farm->zongdi);
									 $yzZongdi = [];
									 $ps = Plantingstructurecheck::find()->where(['farms_id'=>$farm['id'],'year'=>User::getYear()])->all();
									 foreach ($ps as $p) {
										 $yzZongdi = array_merge_recursive($yzZongdi,explode('、',$p['zongdi']));
									 }
									 for($i = 0;$i<count($arrayZongdi);$i++) {
										 // 			    	echo $i%6;
										 if($i%4 == 0) {
											 echo '<tr height="10">';
											 echo '<td>';
											 $re = Parcel::in_parcel($arrayZongdi[$i],$yzZongdi,$check_id);
											 echo html::button($re['value'],['onclick'=>'toZongdi("'.Lease::getZongdi($arrayZongdi[$i]).'","'.$re['area'].'")','value'=>$re['area'],'id'=>Lease::getZongdi($arrayZongdi[$i]),'class'=>"btn btn-default",'disabled'=>$re['disabled'],'style'=>'font-size:20px']);
											 echo '</td>';
										 } else {
											 echo '<td>';
											 $re = Parcel::in_parcel($arrayZongdi[$i],$yzZongdi,$check_id);
											 echo html::button($re['value'],['onclick'=>'toZongdi("'.Lease::getZongdi($arrayZongdi[$i]).'","'.$re['area'].'")','value'=>$re['area'],'id'=>Lease::getZongdi($arrayZongdi[$i]),'class'=>"btn btn-default",'disabled'=>$re['disabled'],'style'=>'font-size:20px']);
											 echo '</td>';
										 }
									 }}
								 ?>
							 </table>
						 </td>
						 <td width="8%">
							 <?php
							 echo Html::button('全选',['onclick'=>'allZongdi()','class'=>"btn btn-default"]);
							 ?>
						 </td>
					</tr>
					<tr>
						<td align="center" width="10%">已选择宗地</td>
						 <td width="40%">
	<span id="inputZongdi" class="select2-container select2-container--default select2-container--below" dir="ltr" style="width: 100%; color: #000;">
	<span class="selection">
		<span class="select2-selection select2-selection--multiple" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false" tabindex="0">
			<ul class="select2-selection__rendered">
				<li class="select2-search select2-search--inline">
					<input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" placeholder="" style="width: 0.75em;">
					<?php
					if($model->zongdi) {
						$iden = explode('、',$model->zongdi);
						if($iden) {
							foreach ($iden as $zongdi) {
//							echo '<a href="#" id="zongdiinfo"><li class="select2-selection__choice" id="new' . Lease::getZongdi($zongdi) . '" title="' . $zongdi . '"><span class="remove text-red" role="presentation" onclick=zongdiRemove("' . Lease::getZongdi($zongdi) . '","' . Lease::getArea($zongdi) . '","dialog")>×</span>' . $zongdi . '</li></a>';
								echo '<li class="select2-selection__choice" id="new' . Lease::getZongdi($zongdi) . '" title="' . $zongdi . '"><span class="remove text-red" role="presentation" onclick=zongdiRemove("' . Lease::getZongdi($zongdi) . '","' . Lease::getArea($zongdi) . '","dialog")>×</span>' . $zongdi . '</li>';
							}
						}} else {
						echo '<li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" placeholder="" style="width: 0.75em;"></li>';
					}
					?>
				</li>
			</ul>
		</span>
	</span>
		<?= Html::hiddenInput('area',$model->area,['id'=>'zongdiArea'])?>
		<?= Html::hiddenInput('code','',['id'=>'Code']);?>

	<span class="dropdown-wrapper" aria-hidden="true"></span>
						 </td>
						<td>
							<?php
							echo Html::button('取消',['onclick'=>'cacelZongdi()','class'=>"btn btn-default"]);
							?>
						</td>
					 </tr>
				 	<tr>
						<td align="center" width="10%">已选面积</td>
						<td id="showArea"><?= Lease::getListArea($model->zongdi)?></td>
						<td></td>
					</tr>

				 </table>
			 </div>
			 <div id="dialog" title="宗地信息">
				 <table width=100%>
					 <tr>
						 <td align="right">宗地号：</td>
						 <td><?= html::textInput('zongdinumber','',['id'=>'zongdi','disabled'=>true])?><?= html::hiddenInput('ym','',['id'=>'ymeasure'])?></td>
					 </tr>
					 <tr>
						 <td align="right">面积：</td>
						 <td><?= html::textInput('zongdimeasure','',['id'=>'measure'])?></td>
					 </tr>
				 </table>
			 </div>

<?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
</div>

<script type="text/javascript">
	//点击宗地输入框弹出宗地信息查找框
	$('#plantingstructurecheck-zongdi').dblclick(function(){
//		$("#dialogSelect").val('dialogZongdi');
		var parea = Number($('#plantingstructurecheck-area').val());
		if(parea == '' || parea == 0) {
			alert('清先确定种植面积后再添加宗地信息。');
			$('#plantingstructurecheck-area').focus();
		} else {
			$("#dialogZongdi").dialog("open");
		}
	});
	function toZongdi(zongdi,area) {
		var zongdiarea = Number($('#zongdiArea').val());
		var parea = Number($('#plantingstructurecheck-area').val());
		if(zongdiarea < parea) {
			$("#dialog").dialog("open");
//	event.preventDefault();
			$('#zongdi').val(zongdi);
			$('#measure').val(area);
			$('#ymeasure').val(area);
		} else {
			alert('对不起,选择的宗地面积之和已经达到种植面积上限,不能再添加宗地信息。');
		}
	}
	function allZongdi()
	{
		var allArray = <?= json_encode(Plantingstructurecheck::mergeZongdi($farm['id'],$check_id))?>;
		var allArea = <?= json_encode(Plantingstructurecheck::zongdiAreaSum($farm['id'],$check_id))?>;
		var area = Number($('#plantingstructurecheck-area').val());
		if(allArea > area) {
			alert('对不起,全选时,如果所有宗地面积大于种植面积时,不能用此功能,需要手动选择!');
		} else {
			$("#tempzongdi").val("<?= implode('、', Plantingstructurecheck::mergeZongdi($farm['id'], $check_id))?>");
			var yhtml = $('.select2-selection__rendered').html();
			$('#Code').val(yhtml);
			$('.select2-selection__rendered').html('');
//		var allArray = all.split('、');
			var sum = 0;
			$.each(allArray, function (i, v) {
				var ng = /\([^\)]+?\)/g;
				var zongdi = v.replace(ng, '');
				var mng = /-([\s\S]*)\(([0-9.]+?)\)/;
				var measure = v.match(mng);
//			alert(measure[2]);
				sum = sum * 1 + measure[2] * 1;
				var newzongdihtml = '<li class="select2-selection__choice" id="new' + zongdi + '" title="' + v + '"><span class="remove text-red" role="presentation" onclick=zongdiRemove("' + zongdi + '","' + measure[2] + '")>×</span>' + v + '</li>';
				$('.select2-selection__rendered').append(newzongdihtml);
				var oldzongdi = zongdi + '(0)';
				$('#' + zongdi).text(oldzongdi);
				$('#' + zongdi).val(0);
				$('#' + zongdi).attr('disabled', true);
			});
			$('#zongdiArea').val(sum.toFixed(2));
			$('#showArea').text(sum.toFixed(2));
		}
	}
	function cacelZongdi()
	{
		$("#tempzongdi").val('');
		var allArray = <?= json_encode(Plantingstructurecheck::unUseZongdi($farm['id'],$check_id))?>;
//		console.log(allArray);
		$('.select2-selection__rendered').html('');
		$('#zongdiArea').val(0);
		$('#showArea').text(0);
		$.each(allArray,function (i,v) {
			var ng = /\([^\)]+?\)/g;
			var zongdi = v.replace(ng,'');
			$('#new'+zongdi).remove();
			var mng = /-([\s\S]*)\(([0-9.]+?)\)/;
			var measure = v.match(mng);
			var oldzongdi = zongdi+'('+measure[2]+')';
			$('#'+zongdi).text(oldzongdi);
			$('#'+zongdi).val(measure[2]);
			$('#'+zongdi).attr('disabled',false);
		});
	}
	function zongdiRemove(zongdi,measure)
	{
//		alert(measure);
		var zongdiArea = Number($('#zongdiArea').val());
		var showarea = Number($('#showArea').text());
//		alert(showarea);
		if(zongdiArea > 0) {
			var sub = zongdiArea * 1 - measure * 1;
			var show = showarea * 1 - measure * 1;
			$('#zongdiArea').val(sub.toFixed(2));
			$('#showArea').text(show.toFixed(2));
		}
		removeTempZongdi(zongdi,measure);
		$('#new'+zongdi).remove();
		$('#'+zongdi).attr('disabled',false);
//		alert($('#'+zongdi).val());
		var newmeasure = $('#'+zongdi).val()*1 + measure*1;
		var newzongdi = zongdi + "("+newmeasure.toFixed(2)+")";
//		alert(newzongdi);
		$('#'+zongdi).text(newzongdi);
		$('#'+zongdi).attr('onclick','toZongdi("'+zongdi+'","'+newmeasure.toFixed(2)+'")');
		$('#'+zongdi).val(newmeasure);
	}
	function removeTempZongdi(zongdi,measure)
	{
		var findzongdi = zongdi + "("+measure+")";
//		var zongdi = $('#plantingstructurecheck-zongdi').val();
//
//		var arr1 = zongdi.split('、');
//		$.each(arr1, function(i,val){
//			alert(val);
//			if(val === findzongdi) {
////				alert(val);
//				arr1.splice(i, 1);
//			}
//		});
//		var newnewzongdi = arr1.join('、');
//		$('#plantingstructurecheck-zongdi').val(newnewzongdi);

		var tempzongdi = $('#tempzongdi').val();

		var temparr1 = tempzongdi.split('、');
		$.each(temparr1, function(i,val){
			if(val === findzongdi) {
				temparr1.splice(i, 1);
			}
		});
		var newtempzongdi = temparr1.join('、');
		$('#tempzongdi').val(newtempzongdi);
	}

		$( "#dialogZongdi" ).dialog({
		autoOpen: false,
		width: 1000,
		buttons: [
			{
				text: "确定",
				click: function() {
					var zongdiArea = Number($('#zongdiArea').val());
					if(zongdiArea > Number($('#plantingstructurecheck-area').val())) {
						alert('对不起,宗地面积之和大于种植面积,请检查后重新提交。');
					} else {
						$( this ).dialog( "close" );
						$('#plantingstructurecheck-zongdi').val($("#tempzongdi").val());
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
	$('#dialog').dialog({
		autoOpen: false,
		width:400,

		buttons: [
			{
				text: "确定",
				click: function() {
					var zongdi = $('#zongdi').val();
// 	  				alert(zongdi);
					var measure = Number($('#measure').val());
					var bakmeasure = Number($('#measure').val());
					var ymeasure = Number($('#ymeasure').val());
					if(measure == '' || zongdi == '') {
						alert("对不起，宗地或面积不能为空。");
						$('#measure').val(ymeasure);
					} else {
						if(measure > ymeasure) {
							alert("对不起，您输入的面积不能大于原宗地面积。");
							$('#measure').val(ymeasure);
						} else {
							$( this ).dialog( "close" );

							var zongdiArea = $('#zongdiArea').val();
							var sum = measure*1 + zongdiArea*1;
							$('#zongdiArea').val(sum.toFixed(2));
							$('#showArea').text(sum.toFixed(2));

							var inputarea = Number($('#plantingstructurecheck-area').val());
							var zongdiarea = Number($('#zongdiArea').val());
							if(zongdiarea == 0) {
								zongdiarea = measure;
							}
//							alert('area='+inputarea);
//							alert('zongdiarea='+zongdiarea);

							if(zongdiarea > inputarea) {
								alert('选择的宗地面积之和已经超过种植面积,系统将自动截去多余面积。');
								var chaarea = zongdiarea - inputarea;
								measure = measure - chaarea.toFixed(2);
								var nnarea = sum - ymeasure + measure;
								$('#zongdiArea').val(nnarea.toFixed(2));
								$('#showArea').text(nnarea.toFixed(2));
							}
							var oldmeasure = ymeasure - measure;
							var newzongdi = zongdi+'('+parseFloat(measure.toFixed(2))+')';
							var newzongdihtml = '<li class="select2-selection__choice" id="new'+zongdi+'" title="'+newzongdi+'"><span class="remove text-red" role="presentation" onclick=zongdiRemove("'+zongdi+'","'+parseFloat(measure.toFixed(2))+'")>×</span>'+newzongdi+'</li>';
//							var nnarea = sum - oldmeasure;
//							$('#zongdiArea').val(nnarea.toFixed(2));
//							$('#showArea').text(nnarea.toFixed(2));
//							alert(oldmeasure);
							var oldzongdi = zongdi+'('+cutZero(oldmeasure.toFixed(2))+')';
							$('#'+zongdi).text(oldzongdi);
							$('#'+zongdi).val(oldmeasure.toFixed(2));
							$('.select2-selection__rendered').append(newzongdihtml);
							$('#'+zongdi).attr('disabled',true);
							$('#ymeasure').val(0);
							var temp = $('#tempzongdi').val();
							if(temp == '') {
								$('#tempzongdi').val(newzongdi);
							} else {
								$("#tempzongdi").val(temp+'、'+newzongdi);
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
    // 添加雇工人员
    $('#add-plantinputproduct').click(function () {
        var template = $('#plantinputdroduct-template').html();
        $('#plantinputproduct > tbody').append(template);
    });

    // 删除
    $(document).on("click", ".delete-employee", function () {
        $(this).parent().parent().remove();
    });

    $('#add-plantpesticides').click(function () {
        var template = $('#plantpesticides-template').html();
        $('#plantpesticides > tbody').append(template);
    });

    // 删除
    $(document).on("click", ".delete-pesticides", function () {
        $(this).parent().parent().remove();
    });

	// 投入品子类联动
	$(document).on("change", "select[name='PlantInputproductPost[father_id][]']", function () {
		// 投入品子类，投入品
		var fertilizerChild = $(this).parent().next().children(),
			father_id = $(this).val();

		// 请求二级分类
		$.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
			fertilizerChild.html(null);
			fertilizerChild.append('<option value="prompt">请选择</option>');
			if (data.status == 1 && data.inputproductson !== null) {
				for(i = 0; i < data.inputproductson.length; i++) {
					fertilizerChild.append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
				}
			}
		});
	});

	// 投入品选择
	$(document).on("change", "select[name='PlantInputproductPost[son_id][]']", function () {
		// 投入品子类，投入品
		var product = $(this).parent().next().children(),
		father_id = $(this).val();

		// 请求二级分类
		$.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
			product.html(null);
			product.append('<option value="prompt">请选择</option>');
			if (data.status == 1 && data.inputproductson !== null) {
				for(i = 0; i < data.inputproductson.length; i++) {
					product.append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
				}
			}
		});
	});

</script>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭  </button>
            <button type="button" class="btn btn-primary" id="getParcellist" onclick="reset()">重置 </button>
            <button type="button" class="btn btn-success" id="getParcellist" onclick="setLeasearea()">提交 </button>
<script type="text/javascript">
function toParcellist(zdarea,id){
	if($('#model-parcellist').val() == '') {
		$('#'+id).attr('disabled',true);
		$('#model-parcellist').val(zdarea);
		
	}
	else {
		$('#'+id).attr('disabled',true);
		//alert($('#model-parcellist').val());
		var value = $('#model-parcellist').val()+'、'+zdarea;
		$('#model-parcellist').val(value);
		
	}
}
function toAll() {
	$('#model-parcellist').val($('#temp-allzongdi').val());
}
function reset()
{
	$('#model-parcellist').val('');
	$('button').attr('disabled',false);
}
function setLeasearea() {
	$('#myModal').modal('hide');
	$('#plantingstructurecheck-zongdi').val($('#model-parcellist').val());
	$.getJSON('index.php?r=plantingstructurecheck/plantingstructurecheckgetarea', {zongdi: $('#model-parcellist').val()}, function (data) {
		if (data.status == 1) {
			$('#plantingstructurecheck-area').val(data.area);
		}
	});
}
</script>
         </div>
      </div><!-- /.modal-content -->
</div><!-- /.modal -->
<?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
<script type="text/javascript">
function plantinputproductcreate(lease_id,farms_id) {
			$.get(
			    'index.php',         
			    {
			    	r: 'plantinputproduct/plantinputproductcreate',
			    	lease_id: lease_id,
			    	farms_id: farms_id,
			         
			    },
			    function (data) {
			        $('.modal-body').html(data);
			        
			    }  
			);
}
function plantinputproductview(id,lease_id,farms_id) {
	$.get(
	    'index.php',         
	    {
	    	r: 'plantinputproduct/plantinputproductview',
	    	id: id,
	    	lease_id: lease_id,
	    	farms_id: farms_id,
	         
	    },
	    function (data) {
	        $('.modal-body').html(data);
	        
	    }  
	);
}
function plantinputproductupdate(id,lease_id,farms_id) {
	$.get(
	    'index.php',         
	    {
	    	r: 'plantinputproduct/plantinputproductupdate',
	    	id: id,
	    	lease_id: lease_id,
	    	farms_id: farms_id,
	         
	    },
	    function (data) {
	        $('.modal-body').html(data);
	        
	    }  
	);
}
$('#plantingstructurecheck-zongdi').change(function(){
	zongdi = $(this).val();
	alert(zongdi);
	$.getJSON('index.php?r=plantingstructurecheck/plantingstructurecheckgetarea', {zongdi: zongdi}, function (data) {
		if (data.status == 1) {
			$('#plantingstructurecheck-area').val(data.area);
		}
	});
});
$('#plantingstructurecheck-area').blur(function(){
	var input = $(this).val();
	var mease = <?= sprintf('%.2f',$noarea)?>;
	if(mease == 0) {
		mease = <?= sprintf('%.2f',$area)?>;
	}
	if(input > mease) {
		alert('对不起，输入的面积不能大于'+mease+'亩');
		$('#plantingstructurecheck-area').val(mease);
		$('#plantingstructurecheck-area').focus();
	}
})
$('#plantfather').change(function(){
	father_id = $(this).val();
	$.getJSON('index.php?r=plant/plantgetson', {father_id: father_id}, function (data) {
		if (data.status == 1) {
			$('#plantingstructurecheck-plant_id').html(null);
			$('#plantingstructurecheck-plant_id').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.son.length;i++) {
				$('#plantingstructurecheck-plant_id').append('<option value="'+data.son[i]['id']+'">'+data.son[i]['typename']+'</option>');
			}
		}
		else {
			$('#plantingstructurecheck-plant_id').html(null);
			$('#plantingstructurecheck-plant_id').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
$('#plantingstructurecheck-plant_id').change(function(){
	plant_id = $(this).val();
	
	$.getJSON('index.php?r=goodseed/goodseedgetmodel', {plant_id: plant_id}, function (data) {
		
		if (data.status == 1) {
			$('#plantingstructurecheck-goodseed_id').html(null);
			$('#plantingstructurecheck-goodseed_id').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.goodseed.length;i++) {
				$('#plantingstructurecheck-goodseed_id').append('<option value="'+data.goodseed[i]['id']+'">'+data.goodseed[i]['typename']+'</option>');
			}
		}
		else {
			$('#plantingstructurecheck-goodseed_id').html(null);
			$('#plantingstructurecheck-goodseed_id').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
$('#inputproductfather').change(function(){
	father_id = $(this).val();
	
	$.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
		
		if (data.status == 1) {
			$('#inputproductson').html(null);
			$('#inputproductson').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.inputproductson.length;i++) {
				$('#inputproductson').append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
			}
		}
		else {
			$('#inputproductson').html(null);
			$('#inputproductson').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
$('#inputproductson').change(function(){
	father_id = $(this).val();
	
	$.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
		
		if (data.status == 1) {
			$('#plantingstructurecheck-inputproduct_id').html(null);
			$('#plantingstructurecheck-inputproduct_id').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.inputproductson.length;i++) {
				$('#plantingstructurecheck-inputproduct_id').append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
			}
		}
		else {
			$('#plantingstructurecheck-inputproduct_id').html(null);
			$('#plantingstructurecheck-inputproduct_id').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
</script>
<script type="text/javascript" charset="utf-8">
  var brandjson = <?= Inputproductbrandmodel::getBrandmodel() ?>;
  $('.brandsearch').autocomplete({
	  lookup: brandjson,
	  formatResult: function (json) {
		  return json.data;
	  },
	  onSelect: function (suggestion) {
		  $(this).val(suggestion.data);

	  }
  });
</script>
</div>
