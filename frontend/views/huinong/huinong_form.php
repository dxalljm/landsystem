<?php
use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
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
		$son = ArrayHelper::map(Plant::find()->where(['father_id'=>$fatherid])->all(),'id','cropname');
		break;
	case 'Goodseed':
		$plantid = Goodseed::find()->where(['id'=>$model->typeid])->one()['plant_id'];
		$fatherid = Plant::find()->where(['id'=>$plantid])->one()['father_id'];
		$sonid = Plant::find()->where(['id'=>$plantid])->one()['id'];
		$son = ArrayHelper::map(Plant::find()->where(['father_id'=>$fatherid])->all(),'id','cropname');
		$goodseeds = ArrayHelper::map(Goodseed::find()->where(['plant_id'=>$sonid])->all(),'id','plant_model');
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
  			<td align='left'><?= html::dropDownList('plant-father',$fatherid,ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'cropname'),['prompt'=>'请选择...','class'=>'form-control','id'=>'goodseedplantfather']) ?></td>
			<td colspan="2" align='right'><?= html::dropDownList('plant',$sonid,$son,['prompt'=>'请选择...','class'=>'form-control','id'=>'goodseedplantson'])?></td>
			<td align="right"><?= html::dropDownList('goodseed',$model->typeid,$goodseeds,['prompt'=>'请选择...','class'=>'form-control','id'=>'goodseedgoodseed']) ?></td>
			<td >&nbsp;</td>
		</tr>
		<tr class="plant">
			<td width=15% align='right'>作物</td>
			<td align='left'><?= html::dropDownList('plant-father',$fatherid,ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'cropname'),['prompt'=>'请选择...','class'=>'form-control','id'=>'plantfather']) ?></td>
			<td colspan="2" align='right'><?= html::dropDownList('plant',$model->typeid,$son,['prompt'=>'请选择...','class'=>'form-control','id'=>'plantson'])?></td>
			<td align='left'>&nbsp;</td>
			<td align='left'>&nbsp;</td>
		</tr>
		<tr>
			<td width=15% align='right'>补贴面积</td>
			<td colspan="6" align='left'><?= $form->field($model, 'subsidiesarea')->textInput()->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>补贴金额</td>
			<td colspan="6" align='left'><?= $form->field($model, 'subsidiesmoney')->textInput()->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>起止日期</td><?php if($model->begindate == '') $model->begindate = date('Y-m-d'); else $model->begindate = date('Y-m-d',$model->begindate)?>
			<td align='center'><?= $form->field($model, 'begindate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
			<td align='center'>至</td><?php if($model->enddate =='') $model->enddate = '';else $model->enddate = date('Y-m-d',$model->enddate);?>
			<td align='center'><?= $form->field($model, 'enddate')->textInput(['maxlength' => 500])->label(false)->widget(
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
			<td align='center'>&nbsp;</td>
			<td colspan="2" align='center'>&nbsp;</td>
		</tr>
	</table>
	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

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