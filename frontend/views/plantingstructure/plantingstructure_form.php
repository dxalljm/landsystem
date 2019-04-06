<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Farmer;
use yii\helpers\ArrayHelper;
use app\models\Farms;
use app\models\Help;
use yii\web\View;
use app\models\Plant;
use app\models\Inputproduct;
use app\models\Pesticides;
use app\models\Lease;
use app\models\Goodseed;
use app\models\Inputproductbrandmodel;
use app\models\Plantingstructure;

/* @var $this yii\web\View */
/* @var $model app\models\Plantingstructure */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="plantingstructure-form">
	<?php
//	var_dump($noarea);
	?>
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
<td align='right'>农场面积：<?= $farm->contractarea.' 亩'?></td>
<td align='left'><?= Help::showHelp3('租赁面积','plantingstructure-leaseArea')?>：<?= sprintf('%.2f',$overarea)?>亩</td>
</tr>
<tr>
  <td align='right'>种植面积</td><?php if(bccomp($overarea, $noarea) == 0) $value = sprintf('%.2f',$overarea); else $value = sprintf('%.2f',$noarea);?>
  <td colspan="6" align='left'><?= $form->field($model, 'area')->textInput(['value'=>$value,'help'=>'plantingstructure-PlantArea'])->label(false)->error(false) ?></td>
  </tr>
<tr>
  <td align='right'>种植作物</td><?php $fatherid = Plant::find()->where(['id'=>$model->plant_id])->one()['father_id'];?>
  <td align='left'><?= html::dropDownList('plant-father',$fatherid,ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'typename'),['prompt'=>'请选择...','class'=>'form-control','id'=>'plantfather']) ?></td>
  <td colspan="2" align='right'><?= $form->field($model, 'plant_id')->dropDownList(ArrayHelper::map(Plant::find()->where(['father_id'=>$fatherid])->all(), 'id', 'typename'),['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
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
		foreach ($plantinputproductModel as $value) {?>
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



<?php //$this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
</div>
<script type="text/javascript">

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
<?php //$this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
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
$('#plantingstructure-area').blur(function(){
	var input = $(this).val();
	var mease = <?= $noarea?>;
	if(input > mease) {
		alert('对不起，输入的面积不能大于'+mease+'亩');
		$('#plantingstructure-area').focus();
		$('#plantingstructure-area').val(mease);
	}
})
$('#plantfather').change(function(){
	var father_id = $(this).val();
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
	var plant_id = $(this).val();
	
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
	var father_id = $(this).val();
	
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
<script type="text/javascript" charset="utf-8">
//  var brandjson = <?//= Inputproductbrandmodel::getBrandmodel() ?>//;
//  $('.brandsearch').autocomplete({
//	  lookup: brandjson,
//	  formatResult: function (json) {
//		  return json.data;
//	  },
//	  onSelect: function (suggestion) {
//		  $(this).val(suggestion.data);
//
//	  }
//  });
</script>
</div>
