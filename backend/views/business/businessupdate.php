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

    <label class="control-label" for="user-username">农场</label>
    <?= html::textInput(['disabled'=>"disabled" ,'maxlength' => 64,'value'=>$str]) ?>
    <label class="control-label" for="user-username"></label>
	<?php $items = Item::find()->where(['type'=>1])->all();$listData=ArrayHelper::map($items,'name','name');?>
	<?= $form->field($assign, 'item_name')->dropDownList($listData,['prompt'=>'请选择...']) ?>

	<?= html::dropDownList(\yii\helpers\ArrayHelper::map($dataFarms,'id','farmname'),
    [
        'prompt'=>'select Company',
        'onchange'=>'
            $.post("index.php?r=business/farmerlists&id='.'"+$(this).val(),function(data){
                $("select#farmerid").html(data);
            });',
    ]
) ?>
 
<?= html::dropDownList(
    \yii\helpers\ArrayHelper::map($dataFarmer,'id','farmername'),
    [
    	'id' => 'farmerid',
        'prompt'=>'Select Branches',
        'onchange'=>'
        		$.post("index.php?r=business/farmeractionlists&id='.'"+$(this).val(),function(data){
        			$("select#farmeraction").html(data);
        		});',
    ]
) ?>

<?= html::dropDownList(
    \yii\helpers\ArrayHelper::map($dataFarmer,'id','farmername'),
    [
    	'id' => 'farmeraction',
        'prompt'=>'Select Branches',
    ]
) ?>

    <?php ActiveForm::end(); ?>

</div>
