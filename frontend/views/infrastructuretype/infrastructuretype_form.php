<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\Infrastructuretype;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Infrastructuretype */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
	.temp-tr{ display:none }
	.temp-tr2{ display:none }
	.temp-tr3{ display:none }
</style>
<div class="infrastructuretype-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<?php $dropdownValue = Infrastructuretype::find()->where(['father_id'=>1])->all();?>
<table class="table table-bordered table-hover" id='infrastructuretype-table'>
<tbody>
	<tr>
		<td width=15% align='right'>类别</td>
		<td align='left'><?= html::dropDownList('arrayFatherID[]','',ArrayHelper::map($dropdownValue, 'id', 'typename'),['class'=>'form-control','id'=>'father_id'])?></td>
	</tr>
	<tr class="temp-tr">
        <td align='right'>子类</td>
        <td><?= html::dropDownList('arrayFatherID[]','',['prompt'=>'请选择...'],['class'=>'form-control']) ?></td>
    </tr>
    <tr class="temp-tr2">
        <td align='right'>子类</td>
        <td><?=  html::dropDownList('arrayFatherID[]','',['prompt'=>'请选择...'],['class'=>'form-control']) ?></td>
    </tr>
    <tr class="temp-tr3">
        <td align='right'>子类</td>
        <td><?=  html::dropDownList('arrayFatherID[]','',['prompt'=>'请选择...'],['class'=>'form-control']) ?></td>
    </tr>
</tbody>
<tfoot>
	<tr>
		<td width=15% align='right'>类型名称</td>
		<td align='left'><?= $form->field($model, 'typename')->textInput()->label(false)->error(false) ?></td>
	</tr>
	
</tfoot>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>

$('#father_id').change(function(){
	var input = $(this).val();

	$.getJSON('index.php?r=infrastructuretype/getson', {father_id: input}, function (data) {
		if (data.son == 1) {
			var Child = $("#infrastructuretype-father_id");
			for(i=0;i<data.data.length;i++) {
				Child.append('<option value="'+data.data[i]['id']+'">'+data.data[i]['typename']+'</option>');
			}
			$('.temp-tr').css('display', 'table-row')
		}
	});
});
$('#infrastructuretype-father_id').change(function(){
	var input = $(this).val();
	$.getJSON('index.php?r=infrastructuretype/getson', {father_id: input}, function (data) {
		if (data.son == 1) {
			var Child = $("#infrastructuretype-father_id");
			for(i=0;i<data.data.length;i++) {
				Child.append('<option value="'+data.data[i]['id']+'">'+data.data[i]['typename']+'</option>');
			}
			$('.temp-tr2').css('display', 'table-row')
		}
	});
});
</script>