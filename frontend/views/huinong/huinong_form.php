<?php
use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Subsidiestype;
use app\models\Plant;
use app\models\Goodseed;
/* @var $this yii\web\View */
/* @var $model app\models\Huinong */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
.goodseed {
	display: none
}

.plant {
	display: none
}
</style>
<div class="huinong-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
	<table class="table table-bordered table-hover">
		<tr>
			<td width=15% align='right'>补贴类型</td>
			<td colspan="4" align='left'><?= $form->field($model, 'subsidiestype_id')->dropDownList(ArrayHelper::map(Subsidiestype::find()->all(), 'urladdress', 'typename'),['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
		</tr>
		<tr class="goodseed">
			<td align='right'>良种型号</td>
  			<td align='left'><?= html::dropDownList('plant-father','',ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'cropname'),['prompt'=>'请选择...','class'=>'form-control','id'=>'goodseedplantfather']) ?></td>
			<td align='right'><?= html::dropDownList('plant','',[],['prompt'=>'请选择...','class'=>'form-control','id'=>'goodseedplantson'])?></td>
			<td align="right"><?= html::dropDownList('goodseed','',[],['prompt'=>'请选择...','class'=>'form-control','id'=>'goodseedgoodseed']) ?></td>
			<td >&nbsp;</td>
		</tr>
		<tr class="plant">
			<td width=15% align='right'>作物</td>
			<td align='left'><?= html::dropDownList('plant-father','',ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'cropname'),['prompt'=>'请选择...','class'=>'form-control','id'=>'plantfather']) ?></td>
			<td align='right'><?= html::dropDownList('plant','',[],['prompt'=>'请选择...','class'=>'form-control','id'=>'plantson'])?></td>
			<td align='left'>&nbsp;</td>
			<td align='left'>&nbsp;</td>
		</tr>
		<tr>
			<td width=15% align='right'>补贴面积</td>
			<td colspan="4" align='left'><?= $form->field($model, 'subsidiesarea')->textInput()->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>补贴金额</td>
			<td colspan="4" align='left'><?= $form->field($model, 'subsidiesmoney')->textInput()->label(false)->error(false) ?></td>
		</tr>
	</table>
	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
$('#huinong-subsidiestype_id').change(function(){
	var input = $(this).val();
	if(input == 'goodseed') {
		$('.plant').css('display', 'none')
		$('.goodseed').css('display', 'table-row')
	}
	if(input == 'plant') {
		$('.goodseed').css('display', 'none')
		$('.plant').css('display', 'table-row')
	}
});
$('#plantfather').change(function(){
	father_id = $(this).val();
	$.getJSON('index.php?r=plant/plantgetson', {father_id: father_id}, function (data) {
		if (data.status == 1) {
			$('#plantson').html(null);
			$('#plantson').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.son.length;i++) {
				$('#plantson').append('<option value="'+data.son[i]['id']+'">'+data.son[i]['cropname']+'</option>');
			}
		}
		else {
			$('#plantson').html(null);
			$('#plantson').append('<option value="prompt">请选择...</option>');
		}
			
	});	
});
$('#plantson').change(function(){
	plant_id = $(this).val();
	
	$.getJSON('index.php?r=goodseed/goodseedgetmodel', {plant_id: plant_id}, function (data) {
		
		if (data.status == 1) {
			$('#goodseed').html(null);
			$('#goodseed').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.goodseed.length;i++) {
				$('#goodseed').append('<option value="'+data.goodseed[i]['id']+'">'+data.goodseed[i]['plant_model']+'</option>');
			}
		}
		else {
			$('#goodseed').html(null);
			$('#goodseed').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
$('#goodseedplantfather').change(function(){
	father_id = $(this).val();
	$.getJSON('index.php?r=plant/plantgetson', {father_id: father_id}, function (data) {
		if (data.status == 1) {
			$('#goodseedplantson').html(null);
			$('#goodseedplantson').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.son.length;i++) {
				$('#goodseedplantson').append('<option value="'+data.son[i]['id']+'">'+data.son[i]['cropname']+'</option>');
			}
		}
		else {
			$('#goodseedplantson').html(null);
			$('#goodseedplantson').append('<option value="prompt">请选择...</option>');
		}
			
	});	
});
$('#goodseedplantson').change(function(){
	plant_id = $(this).val();
	
	$.getJSON('index.php?r=goodseed/goodseedgetmodel', {plant_id: plant_id}, function (data) {
		
		if (data.status == 1) {
			$('#goodseedgoodseed').html(null);
			$('#goodseedgoodseed').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.goodseed.length;i++) {
				$('#goodseedgoodseed').append('<option value="'+data.goodseed[i]['id']+'">'+data.goodseed[i]['plant_model']+'</option>');
			}
		}
		else {
			$('#goodseedgoodseed').html(null);
			$('#goodseedgoodseed').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
</script>