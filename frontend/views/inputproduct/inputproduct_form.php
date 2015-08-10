<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Inputproduct;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Inputproduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inputproduct-form">

    <?php $form = ActiveForm::begin(); ?>
	<?php $plant = Inputproduct::find()->andWhere('father_id<=1')->all();?>
    <?= html::dropDownList('dalei','',ArrayHelper::map($plant, 'id', 'fertilizer'),['class'=>"form-control",'id'=>'dalei']) ?>
    
    <?php if(isset($_GET['fatherid'])) $two = Inputproduct::find()->where(['father_id'=>$_GET['fatherid']])->all(); else $two = array();?>
	<?= $form->field($model, 'father_id')->dropDownList(ArrayHelper::map($two, 'id', 'fertilizer')) ?>
	
    <?= $form->field($model, 'fertilizer')->textInput(['maxlength' => 500]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<JS
jQuery('#dalei').change(function(){
    var fatherid = $(this).val();
    jQuery.get('index.php?r=inputproduct/inputproductcreate',{fatherid:fatherid},function(data){
        $('body').html(data);
    });
 
});
JS;
$this->registerJs($script);
?>