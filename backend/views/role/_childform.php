<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Item;
use yii\helpers\ArrayHelper;
use app\models\ItemChild;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
<style> 
.itemChild-child{height:400px;height:auto;margin:0 auto;}
</style>
<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent')->textInput(['disabled'=>"disabled" ,'maxlength' => 64,'value'=>$model->parent]) ?>
    <?php $parents = ItemChild::find()->where(['parent'=>$model->parent])->all();?>
	<?php 
		
 		$model->child = ArrayHelper::map($parents,'child', 'child');
	?>
	<table class="table table-bordered table-hover">
	<tr>
		<td>按角色分配</td>
	</tr>
	<tr>
		<td><?= html::checkboxList('childPost[child][]',$model->child,ArrayHelper::map(Item::find()->where(['type'=>1])->all(), 'name', 'name'),['class'=>'itemChild-child'])?></td>
	</tr>
	<?php 
		$i=0;
		foreach ($allController as $value) {
		$itemall = Item::find()->where(['cname'=>$value['classname']])->all();
		
		if($itemall) {
			$title =  Item::find()->where(['cname'=>$value['classname']])->one()['classdescription'];
			$items = ArrayHelper::map($itemall,'name', 'description');
			//var_dump($items);
	?>
		<tr>
			<td><input name="" type="checkbox" value="" id=<?= $value['classname']?>  onclick='allClick("<?= $value['classname']?>")' /><?= $title?></td>
		</tr>
		<tr>
			<td><?= html::checkboxList('childPost[child][]',$model->child,$items,['class'=>'itemChild-child'])?></td>
		</tr>
	<?php $i++;}}?>
	</table>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
function allClick(cname)
{
	if($('#'+cname).is(":checked")==true) {
		$.getJSON('index.php?r=permission/jsonaname', {classname: cname}, function (data) {
			if (data.status == 1) {
				for(i=0;i<data.actions.length;i++) {
					$('#'+data.actions[i]).attr('checked',true);
				}
			}	
		});
	} else {
		$.getJSON('index.php?r=permission/jsonaname', {classname: cname}, function (data) {
			if (data.status == 1) {
				for(i=0;i<data.actions.length;i++) {
					$('#'+data.actions[i]).attr('checked',false);
				}
			}	
		});
	}
}
</script>