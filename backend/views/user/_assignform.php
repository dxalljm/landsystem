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
    <label class="control-label" for="user-username">用户名</label>
    <?= html::textInput(['disabled'=>"disabled" ,'maxlength' => 64,'value'=>$str]) ?>
	<?php $items = Item::find()->where(['type'=>1])->all();$listData=ArrayHelper::map($items,'name','name');?>
	<?= $form->field($assign, 'item_name')->dropDownList($listData,['prompt'=>'请选择...']) ?>



    <?php ActiveForm::end(); ?>

</div>
