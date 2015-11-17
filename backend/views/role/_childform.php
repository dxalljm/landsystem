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

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent')->textInput(['disabled'=>"disabled" ,'maxlength' => 64,'value'=>$model->parent]) ?>
    <?php $parents = ItemChild::find()->where(['parent'=>$model->parent])->all();?>
	<?php 
		
 		$model->child = ArrayHelper::map($parents,'child', 'child');
	?>
	<table class="table table-bordered table-hover">
	<?php foreach ($allController as $value) {
		$itemall = Item::find()->where(['rule_name'=>$value['classname']])->all();
		if($itemall) {
			$title =  Item::find()->where(['rule_name'=>$value['classname']])->one()['data'];
			$items =ArrayHelper::map($itemall,'name', 'description');
	?>
		<tr>
			<td><?= $title?></td>
		</tr>
		<tr>
			<td><?= html::checkboxList('childPost[child][]',$model->child,$items,['class'=>'form-control'])?></td>
		</tr>
	<?php }}?>
	</table>
    
	
	

    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
