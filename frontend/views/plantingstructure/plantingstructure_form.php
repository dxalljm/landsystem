<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
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
<table width="61%" class="table table-striped table-bordered table-hover table-condensed">
<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$farm->id])->label(false)->error(false) ?>
<?= $form->field($model, 'lease_id')->hiddenInput(['value'=>$_GET['lease_id']])->label(false)->error(false) ?>
<tr>
<td width=15% align='right'>农场名称</td>
<td align='left'><?= $farm->farmname?></td>
<td align='right'>法人</td>
<td colspan="2" align='left'><?= Farmer::find()->where(['farms_id'=>$farm->id])->one()['farmername']?></td>
<td align='right'>承租人</td>
<td align='right'><?= Lease::find()->where(['id'=>$_GET['lease_id']])->one()['lessee'] ?></td>
<td align='right'>宜农林地面积</td>
<td align='left'><?= $farm->measure.' 亩'?></td>
</tr>
<tr>
  <td align='right'>宗地</td>
  <td colspan="4" align='left'><?= $form->field($model, 'zongdi')->dropDownList(ArrayHelper::map($zongdi, 'unifiedserialnumber', 'unifiedserialnumber'), ['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
  <?php if(isset($_GET['zongdi'])) $value = Parcel::find()->where(['id'=>$_GET['zongdi']->one()['grossarea']]); else $value = 0;?>
  <td colspan="2" align='right'>种植面积</td>
  <td colspan="2" align='right'><?= $form->field($model, 'area')->textInput(['value'=>$value])->label(false)->error(false) ?></td>
  </tr>
<tr>
  <td align='right'>种植作物</td>
  <td align='left'><?= html::dropDownList('plant-father','',ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'cropname'),['prompt'=>'请选择...','class'=>'form-control','id'=>'plantfather']) ?></td>
  <td colspan="3" align='right'><?= $form->field($model, 'plant_id')->dropDownList(['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
  <td colspan="2" align="right">良种型号</td>
  <td colspan="2"><?= $form->field($model, 'goodseed_id')->dropDownList(['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
</tr>
<tr>
  <td colspan="9" align='center'>投入品使用情况</td>
</tr>
<tr>
  <td colspan="5" align='center'>投入品名称</td>
  <td colspan="3" align='center'>投入品用量</td>
  <td align='center'><?= Html::a('添加','#', [
            			'id' => 'employeecreate',
            			'title' => '添加',
            			'class' => 'btn btn-primary',
            			'data-target' => '#plantinputproductcreate-modal',
            			'data-toggle' => 'modal',
            			'data-keyboard' => 'false', 
            			'data-backdrop' => 'static',
						'onclick'=> 'plantinputproductcreate('.$_GET['lease_id'].','.$_GET['farms_id'].')',
            			]);?></td>
</tr>
<?php foreach ($plantinputproductData as $value) {?>
<tr>
  <td colspan="5" align='center'><?= Inputproduct::find()->where(['id'=>$value['father_id']])->one()['fertilizer'].'>'.Inputproduct::find()->where(['id'=>$value['son_id']])->one()['fertilizer'].'>'.Inputproduct::find()->where(['id'=>$value['inputproduct_id']])->one()['fertilizer']?></td>
  <td colspan="3" align='center'><?= $value['pconsumption'].'斤/亩'?></td>
  <td align='center'><?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '#', [
                    'title' => Yii::t('yii', '查看'),
                    'data-pjax' => '0',
                    'data-target' => '#plantinputproductview-modal',
                    'data-toggle' => 'modal',
                    'data-keyboard' => 'false',
                    
                    'onclick'=> 'plantinputproductview('.$value['id'].','.$_GET['lease_id'].','.$_GET['farms_id'].')',
                ]);?>&nbsp;&nbsp;<?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                    'title' => Yii::t('yii', '更新'),
                    'data-pjax' => '0',
                    'data-target' => '#plantinputproductupdate-modal',
                    'data-toggle' => 'modal',
                    'data-keyboard' => 'false',
                    'data-backdrop' => 'static',
                    'onclick'=> 'plantinputproductupdate('.$value['id'].','.$_GET['lease_id'].','.$_GET['farms_id'].')',
                ]);?>&nbsp;&nbsp;<?= Html::a('<span class="glyphicon glyphicon-trash"></span>', 'index.php?r=plantingstructure/plantingstructuredelete&id='.$value['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', '删除'),
                    'data-pjax' => '0',
                    'data' => [
		                'confirm' => '您确定要删除这项吗？',
		                //'method' => 'post',
           			 ],
                ]);?></td>
</tr>
<?php }?>
<tr>
  <td colspan="9" align='center'>农药使用情况</td>
  </tr>
<tr>
  <td colspan="5" align='center'>农药名称</td>
  <td colspan="3" align='center'>农药用量</td>
  <td align='center'>&nbsp;</td>
</tr>
<tr>
  <td colspan="5" align='center'>&nbsp;</td>
  <td colspan="3" align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

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
				$('#plantingstructure-plant_id').append('<option value="'+data.son[i]['id']+'">'+data.son[i]['cropname']+'</option>');
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
				$('#plantingstructure-goodseed_id').append('<option value="'+data.goodseed[i]['id']+'">'+data.goodseed[i]['plant_model']+'</option>');
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
<?php \yii\bootstrap\Modal::begin([
    'id' => 'plantinputproductcreate-modal',
	'size'=>'modal-lg',
	'options' => ['data-keyboard' => 'false', 'data-backdrop' => 'static']
]); 
?>
<?php \yii\bootstrap\Modal::end(); ?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'plantinputproductview-modal',
	'size'=>'modal-lg',
	'options' => ['data-keyboard' => 'true', 'data-backdrop' => 'true']
]); 
?>
<?php \yii\bootstrap\Modal::end(); ?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'plantinputproductupdate-modal',
	'size'=>'modal-lg',
]); 
?>
<?php \yii\bootstrap\Modal::end(); ?>
</div>
