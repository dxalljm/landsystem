<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
	.d-none{ display:none }
</style>
<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>
<table class="table table-bordered table-hover" id="temp-table">
<thead id="temp-tr" class="d-none">
  <tr>
    <td><?= html::textInput('itemPost[actionName][]','',['class'=>'form-control','id'=>'action-name']) ?></td>
    <td><?= html::textInput('itemPost[description][]','',['class'=>'form-control','id'=>'item-description']) ?></td>
  </tr>
</thead>
<tbody>
	<tr>
	    <td>类名称</td>
	    <td><?= html::dropDownList('controllerDirList','',ArrayHelper::map($controllerAllDir, 'classname', 'classname'),['prompt'=>'请选择...','class'=>'form-control','id'=>'controllerList']) ?></td>
	</tr>
</tbody>
<tfoot>
	
</tfoot>
</table>
    

	<?= $form->field($model, 'description')->textInput(['maxlength' => 64]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJsFile('js/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
<script>
$('#controllerList').change(function(){
	var input = $(this).val();
	$.getJSON('index.php?r=permission/getactions', {id: input}, function (data) {
			for(i=0;i<data.data.length;i++) {
				var template = $('#temp-tr').html();
		        $('#temp-table > tfoot').append(template);
				$(":text[name='itemPost[actionName][]']").val(data.data[i]);
			}		
	});
});
</script>