<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\Infrastructuretype;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Infrastructuretype */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="infrastructuretype-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover" 'id'='infrastructuretype'>
<thead id="infrastructuretype-template" class="d-none">
          <tr>
              <td><?= $form->field($model, 'father_id')->dropDownList(['value'=>''])->label(false)->error(false) ?></td>
          </tr>
      </thead>
<tbody>
<tr>
<td width=15% align='right'>类别</td><?php $dropdownValue = Infrastructuretype::find()->where(['father_id'=>1])->all();?>
<td align='left'><?= html::dropDownList('infrastructureFatherPost','',ArrayHelper::map($dropdownValue, 'id', 'typename'),['class'=>'form-control','id'=>'infrastructure-father_id'])?></td>
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
$('#infrastructure-father_id').change(function(){
	var input = $(this).val();
	
	$.getJSON('index.php?r=infrastructuretype/getson', {father_id: input}, function (data) {
		
		if (data.son == 1) {
			var template = $('#infrastructuretype-template').html();
			$('#infrastructuretype > tbody').append(template);
			alert(input);
// 			$('#infrastructuretype-father_id').html(null);
// 			$('#infrastructuretype-father_id').append('<option value="prompt">请选择...</option>');
// 			for(i=0;i<data.data.length;i++) {
// 				$('#infrastructuretype-father_id').append('<option value="'+data.data[i]['id']+'">'+data.data[i]['typename']+'</option>');
// 			}
		}
	});
});
</script>