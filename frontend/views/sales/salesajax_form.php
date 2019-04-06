<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Sales */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-condensed">
		<?= $form->field($model, 'planting_id')->hiddenInput(['value'=>$_GET['planting_id']])->label(false)->error(false) ?>
		<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
	<?= $form->field($model, 'plant_id')->hiddenInput(['value'=>$plant_id])->label(false)->error(false) ?>
	<tr>
		<td>作物种类</td>
		<td><?= $plant?></td>
		<td>良种</td>
		<td><?= $goodseed?></td>
	</tr>
	<tr>
<td width=15% align='right'>销售去向</td>
<td align='left'>
	<?= $form->field($model, 'whereabouts')->textInput(['maxlength' => 500,'list'=>'selectList'])->label(false)->error(false) ?>
	<datalist id="selectList">
		<?php
		foreach (\app\models\Saleswhere::find()->all() as $value) {
			echo '<option>'.$value['wherename'].'</option>';
		}
		?>
	</datalist>
</td>
	<td width=15% align='right'>销售量</td><?php if(Yii::$app->controller->action->id !== 'salesupdate') $model->volume = $volume;?>
	<td align='left'><?= $form->field($model, 'volume')->textInput()->label(false)->error(false) ?></td>

</tr>
<tr>
</tr>
<tr>
<td width=15% align='right'>单价(斤)</td>
<td align='left'><?= $form->field($model, 'price')->textInput()->label(false)->error(false) ?></td>
	<td width=15% align='right'>总价</td>
	<td align='left'><div class="text-left" id="sum"></div></td>
</tr>
</table>

    <?php ActiveFormrdiv::end(); ?>

</div>

<script>
	$('#Refresh').val(false);
$('#sales-volume').blur(function(){
	input = $(this).val();
	if(input > <?= $volume?>) {
		alert('填写的数值不能大于总产量'+<?= $volume ?>);
		$('#sales-volume').focus();
	}
});
$('#sales-price').blur(function(){
	var sum;
	sum = $('#sales-volume').val()*$(this).val();
	$('#sum').html(sum.toFixed(2) + '元');
});
$('#sales-whereabluts').val();
$(function () {
	//Initialize Select2 Elements
	$(".select2").select2({
		maximumSelectionLength: 1
	});
});

</script>