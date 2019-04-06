<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Plant;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Goodseed */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goodseed-form">
<?php if(isset($_GET['fahter_id'])) echo $_GET['father_id'];?>
    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>种植结构大类</td><?php if(isset($_GET['father_id'])) $fathervalue = $_GET['father_id']; else $fathervalue = 1;?>
<td align='left'><?= html::dropDownList('plant_father',$fathervalue,ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'typename'),['prompt'=>'请选择...','id'=>'plantfather','class'=>'form-control']) ?></td>
</tr>
<tr>
<td width=15% align='right'>种植结构</td><?php if(isset($_GET['father_id'])) {if($_GET['father_id'] == 1) $sonvalue = 0; else $sonvalue = $_GET['father_id'];} else {$sonvalue = 0;}?>
<td align='left'><?= $form->field($model, 'plant_id')->dropDownList(ArrayHelper::map(Plant::find()->where(['father_id'=>$sonvalue])->all(),'id','typename'), ['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>农作物型号</td>
<td align='left'><?= $form->field($model, 'typename')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>
<?php
$script = <<<JS
jQuery('#plantfather').change(function(){
    var father_id = $(this).val();
    jQuery.get('index.php?r=goodseed/goodseedcreate',{father_id:father_id},function(data){
		$('body').html(data);
    });
 
});
JS;
$this->registerJs($script);
?>
</div>
