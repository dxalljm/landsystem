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

	<tr>
	    <td>类名称</td>
	    <td><?= html::dropDownList('controllerDirList','',ArrayHelper::map($controllerAllDir, 'classname', 'classname'),['prompt'=>'请选择...','class'=>'form-control','id'=>'controllerList']) ?></td>
	</tr>
<?php foreach($actions as $key => $value) {?>
	<tr>
    <td><?= html::textInput('itemPost[actionName]['.$key.']',$value['action'],['class'=>'form-control','id'=>'action-name']) ?></td>
    <td><?= html::textInput('itemPost[description]['.$key.']',$value['description'],['class'=>'form-control','id'=>'item-description']) ?></td>
 </tr>
 <?php }?>
</table>
    
	<?= $form->field($model, 'cname')->textInput()->label('类名') ?>
	<?= $form->field($model, 'classdescription')->textInput(['maxlength' => 64])->label('描述') ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJsFile('js/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
<script>
$('#controllerList').change(function(){
	var input = $(this).val();
	$.get(
	        'index.php',         
	        {
	            r: 'permission/permissioncreate',
	            classname: input,
	        },
	        function (data) {
	            $('body').html(data);

	        }  
	    );
});
</script>