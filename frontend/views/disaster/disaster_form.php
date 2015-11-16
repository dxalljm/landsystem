<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Disastertype;
use app\models\Plant;
use app\models\Plantingstructure;
use app\models\Farms;

/* @var $this yii\web\View */
/* @var $model app\models\Disaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="disaster-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover" id="disaster">
<thead id="disaster-temp" class="d-none">
	<tr>
		<td width=15% align='right'>受保面积</td>
		<td align='left'><?= $form->field($model, 'insurancearea')->textInput()->label(false)->error(false) ?></td>
	</tr>
	<tr>
		<td width=15% align='right'>理赔金额</td>
		<td align='left'><?= $form->field($model, 'socmoney')->textInput()->label(false)->error(false) ?></td>
	</tr>
</thead>
<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
<tbody>
<tr>
<td width=15% align='right'>灾害类型</td>
<td align='left'><?= $form->field($model, 'disastertype_id')->dropDownList(ArrayHelper::map(Disastertype::find()->all(), 'id', 'typename'))->label(false)->error(false) ?></td>
</tr>

<tr>
<td width=15% align='right'>受灾作物</td>
<?php 
	$plantingstracutres = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id']])->all();
	$plantValue = '';
	foreach ($plantingstracutres as $value) {
		$plant = Plant::find()->where(['id'=>$value['plant_id']])->one();
		$plantValue[$plant['id']] = [
			'id' => $plant->id,
			'cropname' => $plant->cropname,
		];
		
	}
	sort($plantValue);
?>
<td align='left'><?= $form->field($model, 'disasterplant')->dropDownList(ArrayHelper::map($plantValue, 'id', 'cropname'),['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>受灾面积</td><?= html::hiddenInput('tempArea','',['id'=>'temp-area']) ?>
<td align='left'><?= $form->field($model, 'disasterarea')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>减产量</td>
<td align='left'><?= $form->field($model, 'yieldreduction')->textInput(['checked'=>false])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>是否参加保险</td>
<td align='left'><?= $form->field($model, 'isinsurance')->checkbox()->label(false)->error(false) ?></td>
</tr>
</tbody>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
$('#disaster-disasterplant').change(function(){
	var input = $(this).val();
	$.getJSON('index.php?r=plantingstructure/getplantarea', {farms_id:<?= $_GET['farms_id'] ?>,plant_id: input}, function (data) {	
		if (data.status == 1) {
			$('#disaster-disasterarea').val(data.area);
			$('#temp-area').val(data.area);
		}
	});
});
$('#disaster-disasterarea').blur(function(){
	var input = $(this).val();
	if(input > $('#temp-area').val()) {
		alert('灾害面积不能超过作物种植面积');
		$('#disaster-disasterarea').focus();
	}
});
$('#disaster-isinsurance').click(function(){
	alert($(this).is(":checked"));
//	if($(this).attr("checked")==false) {
		var template = $('#disaster-temp').html();
 		$('#disaster > tbody').append(template);
//	} 
});
// $('#disaster-yieldreduction').blur(function(){
// 	var input = $(this).val();
// // 	var area = $('#disaster-disasterarea').val();
// // 	$('#disaster-yieldreduction').
// });
</script>