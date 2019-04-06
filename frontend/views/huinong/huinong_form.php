<?php
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Subsidiestype;
use app\models\Plant;
use app\models\Goodseed;
use dosamigos\datetimepicker\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Huinong */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
.goodseed {
	display: none
}
#jdt{
	display: none
}
.plant {
	display: none
}
</style>
<div class="huinong-form">
<?php 
$typename = Subsidiestype::find()->where(['id'=>$model->subsidiestype_id])->one()['urladdress'];
switch ($typename) {
	case 'Plant':
		$fatherid = Plant::find()->where(['id'=>$model->typeid])->one()['father_id'];
		$sonid = '';
		$goodseeds = [];
		$son = ArrayHelper::map(Plant::find()->where(['father_id'=>$fatherid])->all(),'id','typename');
		break;
	case 'Goodseed':
		$plantid = Goodseed::find()->where(['id'=>$model->typeid])->one()['plant_id'];
		$fatherid = Plant::find()->where(['id'=>$plantid])->one()['father_id'];
		$sonid = Plant::find()->where(['id'=>$plantid])->one()['id'];
		$son = ArrayHelper::map(Plant::find()->where(['father_id'=>$fatherid])->all(),'id','typename');
		$goodseeds = ArrayHelper::map(Goodseed::find()->where(['plant_id'=>$sonid])->all(),'id','typename');
		break;
	default:
		$fatherid = '';
		$son=[];
		$sonid = '';
		$goodseeds = [];
}
?>
    <?php $form = ActiveFormrdiv::begin(); ?>
	<table class="table table-bordered table-hover">
		<tr>
			<td width=15% align='right'>补贴类型</td>
			<td colspan="6" align='left'><?= $form->field($model, 'subsidiestype_id')->dropDownList(ArrayHelper::map(Subsidiestype::find()->all(), 'id', 'typename'),['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
		</tr>
		<tr class="goodseed">
			<td align='right'>良种型号</td>
  			<td align='left'><?= html::dropDownList('plant-father',$fatherid,ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'typename'),['prompt'=>'请选择...','class'=>'form-control','id'=>'goodseedplantfather']) ?></td>
			<td colspan="2" align='right'><?= html::dropDownList('plant',$sonid,$son,['prompt'=>'请选择...','class'=>'form-control','id'=>'goodseedplantson'])?></td>
			<td align="right"><?= html::dropDownList('goodseed',$model->typeid,$goodseeds,['prompt'=>'请选择...','class'=>'form-control','id'=>'goodseedgoodseed']) ?></td>
			<td >&nbsp;</td>
		</tr>
		<tr class="plant">
			<td width=15% align='right'>作物</td>
			<td align='left'><?= html::dropDownList('plant-father',$fatherid,ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'typename'),['prompt'=>'请选择...','class'=>'form-control','id'=>'plantfather']) ?></td>
			<td colspan="2" align='right'><?= html::dropDownList('plant',$model->typeid,$son,['prompt'=>'请选择...','class'=>'form-control','id'=>'plantson'])?></td>
			<td align='left'>&nbsp;</td>
			<td align='left'>&nbsp;</td>
		</tr>
		<tr>
			<td width=15% align='right'>补贴比率(%)</td>
			<td colspan="6" align='left'><?= $form->field($model, 'subsidiesarea')->textInput()->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>补贴金额</td>
			<td colspan="6" align='left'><?= $form->field($model, 'subsidiesmoney')->textInput()->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>年度</td><?php if($model->begindate == '') $model->begindate = date('Y');?>
			<td colspan="6" align='center'><?= $form->field($model, 'begindate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
		            'autoclose' => true,
		        	'startView' => 4,
		        	'minView' => 4,
		            'format' => 'yyyy'
        ]]) ?></td>
		</tr>
	</table>
	<div class="progress" id="jdt">
  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
    <span class="sr-only">60% Complete</span>
  </div>
</div>
	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'isSubmit'])?>
    </div>

    <?php ActiveFormrdiv::end(); ?>
<script>
$(document).ready(function () {
	NProgress.start();
	NProgress.set(10)
	NProgress.inc();
//	NProgress.done();
});
</script>
</div>
<script>
if($('#huinong-subsidiestype_id').val() !== '')
	$('.'+$('#huinong-subsidiestype_id').val()).css('display', 'table-row');
$('#huinong-subsidiestype_id').change(function(){
	var input = $(this).val();
	if(input == 1) {
		$('.plant').css('display', 'none');
		$('.goodseed').css('display', 'table-row');
	}
	if(input == 2) {
		$('.goodseed').css('display', 'none');
		$('.plant').css('display', 'table-row');
	}
});
$('#plantfather').change(function(){
	father_id = $(this).val();
	$.getJSON('index.php?r=plant/plantgetson', {father_id: father_id}, function (data) {
		if (data.status == 1) {
			$('#plantson').html(null);
			$('#plantson').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.son.length;i++) {
				$('#plantson').append('<option value="'+data.son[i]['id']+'">'+data.son[i]['typename']+'</option>');
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
				$('#goodseed').append('<option value="'+data.goodseed[i]['id']+'">'+data.goodseed[i]['typename']+'</option>');
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
				$('#goodseedplantson').append('<option value="'+data.son[i]['id']+'">'+data.son[i]['typename']+'</option>');
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
				$('#goodseedgoodseed').append('<option value="'+data.goodseed[i]['id']+'">'+data.goodseed[i]['typename']+'</option>');
			}
		}
		else {
			$('#goodseedgoodseed').html(null);
			$('#goodseedgoodseed').append('<option value="prompt">请选择...</option>');
		}
			
	});
});

</script>