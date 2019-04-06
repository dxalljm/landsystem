<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Inputproduct;
use app\models\Inputproductbrandmodel;
/* @var $this yii\web\View */
/* @var $model app\models\Inputproductbrandmodel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inputproductbrandmodel-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
<tr>
	<td align='right'>投入品大类</td><?php $sonid = Inputproduct::find()->where(['id'=>$model->inputproduct_id])->one()['father_id'];$fatherid = Inputproduct::find()->where(['id'=>$sonid])->one()['father_id'];?>
	<td><?php echo Html::dropDownList('PlantInputproductPost[father_id][]', $fatherid, ArrayHelper::map(Inputproduct::find()->where(['father_id'=>1])->all(), 'id', 'fertilizer'),['prompt'=>'请选择...', 'id'=>'inputproductfather','class' => 'form-control']); ?></td>
</tr>
<tr>
	<td align='right'>投入品子类</td><?php $sonvalue = ArrayHelper::map(Inputproduct::find()->where(['father_id'=>$fatherid])->all(),'id','fertilizer');?>
	<td><?php echo Html::dropDownList('PlantInputproductPost[son_id][]', $sonid,$sonvalue, ['id'=>'inputproductson', 'class' => 'form-control']); ?></td>
</tr>
<tr>
	<td align='right'>投入品名称	</td><?php $inputproduct = ArrayHelper::map(Inputproduct::find()->where(['father_id'=>$sonid])->all(),'id','fertilizer');?>
	<td><?= $form->field($model, 'inputproduct_id')->dropDownList($inputproduct,['prompt'=>'请选择...'])->label(false)->error(false)?></td>
</tr>
<tr>
<td width=15% align='right'>品牌</td>
<td align='left'><?= $form->field($model, 'brand')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>型号</td>
<td align='left'><?= $form->field($model, 'model')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
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
			$('#inputproductbrandmodel-inputproduct_id').html(null);
			$('#inputproductbrandmodel-inputproduct_id').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.inputproductson.length;i++) {
				$('#inputproductbrandmodel-inputproduct_id').append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
			}
		}
		else {
			$('#inputproductbrandmodel-inputproduct_id').html(null);
			$('#inputproductbrandmodel-inputproduct_id').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
</script>