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
/* @var $this yii\web\View */
/* @var $model app\models\Plantingstructure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plantingstructure-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
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
<td align='right'>宜农林地面积</td>
<td align='left'><?= $farm->measure.' 亩'?></td>
</tr>
<tr>
  <td align='right'>宗地</td>
  <td colspan="3" align='left'><?= $form->field($model, 'zongdi')->textInput(['data-target' => '#myModal','data-toggle' => 'modal','data-keyboard' => 'false', 'data-backdrop' => 'static',])->label(false)->error(false) ?></td>
  <?php if(isset($_GET['zongdi'])) $value = Parcel::find()->where(['id'=>$_GET['zongdi']->one()['grossarea']]); else $value = 0;?>
  <td colspan="2" align='right'>种植面积</td>
  <td colspan="2" align='right'><?= $form->field($model, 'area')->textInput(['value'=>$value])->label(false)->error(false) ?></td>
  </tr>
<tr>
  <td align='right'>种植作物</td>
  <td align='left'><?= html::dropDownList('plant-father','',ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'typename'),['prompt'=>'请选择...','class'=>'form-control','id'=>'plantfather']) ?></td>
  <td colspan="2" align='right'><?= $form->field($model, 'plant_id')->dropDownList(['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
  <td colspan="2" align="right">良种型号</td>
  <td colspan="2"><?= $form->field($model, 'goodseed_id')->dropDownList(['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
</tr>

</table>
    <div class="form-group">
    <?php ?>
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
    		<td align='center'><?php 
			$zongdiarr = $zongdi;
			//var_dump($zongdiarr);
			$i=0;
    		foreach($zongdiarr as $value) {
    			echo html::button($value,['onclick'=>'toParcellist("'.$value.'","'.Lease::getZongdi($value).'")','value'=>$value,'id'=>Lease::getZongdi($value),'class'=>"btn btn-default"]).'&nbsp;&nbsp;&nbsp;';
    			$i++;
    			if($i%6 == 0)
    				echo '<br><br>';
    		}
    		?></td>

    	</tr>
    </table>
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
function reset()
{
	$('#model-parcellist').val('');
	$('button').attr('disabled',false);
}
function setLeasearea() {
	$('#myModal').modal('hide');
	$('#plantingstructure-zongdi').val($('#model-parcellist').val());
	$.getJSON('index.php?r=plantingstructure/plantingstructuregetarea', {zongdi: $('#model-parcellist').val()}, function (data) {
		if (data.status == 1) {
			$('#plantingstructure-area').val(data.area);
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
$('#plantingstructure-zongdi').change(function(){
	zongdi = $(this).val();
	alert(zongdi);
	$.getJSON('index.php?r=plantingstructure/plantingstructuregetarea', {zongdi: zongdi}, function (data) {
		if (data.status == 1) {
			$('#plantingstructure-area').val(data.area);
		}
	});
});
$('#plantfather').change(function(){
	father_id = $(this).val();
	$.getJSON('index.php?r=plant/plantgetson', {father_id: father_id}, function (data) {
		if (data.status == 1) {
			$('#plantingstructure-plant_id').html(null);
			$('#plantingstructure-plant_id').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.son.length;i++) {
				$('#plantingstructure-plant_id').append('<option value="'+data.son[i]['id']+'">'+data.son[i]['typename']+'</option>');
			}
		}
		else {
			$('#plantingstructure-plant_id').html(null);
			$('#plantingstructure-plant_id').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
$('#plantingstructure-plant_id').change(function(){
	plant_id = $(this).val();
	
	$.getJSON('index.php?r=goodseed/goodseedgetmodel', {plant_id: plant_id}, function (data) {
		
		if (data.status == 1) {
			$('#plantingstructure-goodseed_id').html(null);
			$('#plantingstructure-goodseed_id').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.goodseed.length;i++) {
				$('#plantingstructure-goodseed_id').append('<option value="'+data.goodseed[i]['id']+'">'+data.goodseed[i]['typename']+'</option>');
			}
		}
		else {
			$('#plantingstructure-goodseed_id').html(null);
			$('#plantingstructure-goodseed_id').append('<option value="prompt">请选择...</option>');
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
			$('#plantingstructure-inputproduct_id').html(null);
			$('#plantingstructure-inputproduct_id').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.inputproductson.length;i++) {
				$('#plantingstructure-inputproduct_id').append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
			}
		}
		else {
			$('#plantingstructure-inputproduct_id').html(null);
			$('#plantingstructure-inputproduct_id').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
</script>

</div>
