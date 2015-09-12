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
use app\models\Goodseed;
/* @var $this yii\web\View */
/* @var $model app\models\Plantingstructure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plantingstructure-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table width="61%" class="table table-bordered table-hover">
<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$farm->id])->label(false)->error(false) ?>
<tr>
<td width=15% align='right'>农场名称</td>
<td align='left'><?= $farm->farmname?></td>
<td align='right'>法人</td>
<td align='left'><?= Farmer::find()->where(['farms_id'=>$farm->id])->one()['farmername']?></td>
<td colspan="2" align='right'>宜农林地面积</td>
<td align='left'><?= $farm->measure.' 亩'?></td>
</tr>
<tr>
  <td align='right'>宗地</td>
  <td align='left'><?= $form->field($model, 'zongdi')->dropDownList(ArrayHelper::map($zongdi, 'unifiedserialnumber', 'unifiedserialnumber'), ['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
  <td align='right'>种植作物</td><?php $plantfatherValue = Plant::find()->where(['id'=>$model->plant_id])->one()['father_id'];?>
  <td align='left'><?= html::dropDownList('plant-father',$plantfatherValue,ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'cropname'),['prompt'=>'请选择...','class'=>'form-control','id'=>'plantfather']) ?></td>
  <td align='left'><?php if($plantfatherValue) $plantSonValue = ArrayHelper::map(Plant::find()->where(['father_id'=>$plantfatherValue])->all(),'id','cropname'); else $plantSonValue = ['prompt'=>'请选择...']?>
  <?= $form->field($model, 'plant_id')->dropDownList($plantSonValue)->label(false)->error(false) ?></td>
  <td align='right'>良种型号</td><?php $goodseedValue = ArrayHelper::map(Goodseed::find()->where(['plant_id'=>$model->plant_id])->all(),'id','plant_model');?>
  <td align='left'><?= $form->field($model, 'goodseed_id')->dropDownList($goodseedValue,['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
</tr>
<tr>
  <td align='right'>种植面积</td><?php if(isset($_GET['zongdi'])) $value = Parcel::find()->where(['id'=>$_GET['zongdi']->one()['grossarea']]); else $value = $model->area;?>
  <td align='left'><?= $form->field($model, 'area')->textInput(['value'=>$value])->label(false)->error(false) ?></td>
  <td align='right'>化肥使用情况</td><?php if($model->inputproduct_id) $inputproductFatherValue = Inputproduct::find()->where(['id'=>Inputproduct::find()->where(['id'=>$model->inputproduct_id])->one()['father_id']])->one()['father_id']; else $inputproductFatherValue = '';?>
  <td align='left'><?= html::dropDownList('inputproduct-father',$inputproductFatherValue,ArrayHelper::map(Inputproduct::find()->where(['father_id'=>1])->all(), 'id', 'fertilizer'),['prompt'=>'请选择...','class'=>'form-control','id'=>'inputproductfather']) ?></td>
  <td><?php if($model->inputproduct_id) $inputproductSonValue = Inputproduct::find()->where(['id'=>$model->inputproduct_id])->one()['father_id']; else $inputproductSonValue = '';?>
  <?php $inputproductSonOption = ArrayHelper::map(Inputproduct::find()->where(['father_id'=>$inputproductFatherValue])->all(),'id','fertilizer');?>
  <?= html::dropDownList('inputproduct-son',$inputproductSonValue,$inputproductSonOption,['prompt'=>'请选择...','class'=>'form-control','id'=>'inputproductson']) ?></td>
  <?php $inputproductIdOption = ArrayHelper::map(Inputproduct::find()->where(['father_id'=>$inputproductSonValue])->all(),'id','fertilizer');?>
  <td><?= $form->field($model, 'inputproduct_id')->dropDownList($inputproductIdOption,['prompt'=>'请选择...','value'=>$model->inputproduct_id])->label(false)->error(false) ?></td>
  <td align='left'>&nbsp;</td>
</tr>
<tr>
  <td align='right'>农药使用情况</td>
  <td align='left'><?= $form->field($model, 'pesticides_id')->dropDownList(ArrayHelper::map(Pesticides::find()->all(), 'id', 'pesticidename'),['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
  <td align='right'>农药用量</td>
  <td align='left'><?= $form->field($model, 'pconsumption')->textInput()->label(false)->error(false)?></td>
  <td colspan="2" align='right'>&nbsp;</td>
  <td align='left'>&nbsp;</td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>
<?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
<script type="text/javascript">
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

</div>
