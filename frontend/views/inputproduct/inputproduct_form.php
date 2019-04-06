<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Inputproduct;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Inputproduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inputproduct-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
	<table class="table table-bordered table-hover">
  <tr >
    <td align='center'>投入品父类</td>
    <td align='center'>投入品子类</td>
    <td align='center'>投入品</td>

  </tr>
  <tr><?php $plant = Inputproduct::find()->andWhere('father_id<=1')->all();?>
    <td><?= html::dropDownList('dalei','',ArrayHelper::map($plant, 'id', 'fertilizer'),['class'=>"form-control",'id'=>'dalei']) ?></td>
    <td>
	<?= $form->field($model, 'father_id')->dropDownList(ArrayHelper::map($plant, 'id', 'fertilizer'))->label(false) ?></td>
    <td width="20%"><?= $form->field($model, 'fertilizer')->textInput()->label(false) ?></td>
  </tr>
</table>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<?php
$script = <<<JS
$('#dalei').change(function(){
	father_id = $(this).val();
	
	$.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
		
		if (data.status == 1) {
			$('#inputproduct-father_id').html(null);
			$('#inputproduct-father_id').append('<option value="'+father_id+'">子类</option>');
			for(i=0;i<data.inputproductson.length;i++) {
				$('#inputproduct-father_id').append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
			}
		}
		else {
			$('#inputproduct-father_id').html(null);
			$('#inputproduct-father_id').append('<option value="'+father_id+'">子类</option>');
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
JS;
$this->registerJs($script);
?>

