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
		//print_r($parents);
		$data = Item::find()->all();
		$items =ArrayHelper::map($data,'name', 'name');
		$model->child = ArrayHelper::map($parents,'child', 'child');
	?>
    <?= $form->field($model, 'child')->checkboxList($items); ?>
	
	

    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
