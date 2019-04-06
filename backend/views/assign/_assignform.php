<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Item;
use yii\helpers\ArrayHelper;
use app\models\AssignmentForm;
/* @var $this yii\web\View */
/* @var $model app\models\user */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php $user_item = AssignmentForm::find()->where(['user_id'=>$user_id])->all();
    	$arr = [];
    	foreach($user_item as $val)
    	{
    		$arr[] = $val['item_name'];
    	}
    	$str = implode(',', $arr);
    ?>
    <label class="control-label" for="user-username">所属角色</label>
    <?= Html::textInput('roles',$str,['class'=>"form-control",'disabled'=>"disabled" ,'maxlength' => '255']); ?>
	<?php $items = Item::find()->where(['type'=>1])->all();$listData=ArrayHelper::map($items,'name','name');?>
	<?= $form->field($model, 'item_name')->dropDownList($listData,['prompt'=>'请选择...']) ?>

	<div class="form-group">
        <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
         <?= Html::submitButton('删除', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
