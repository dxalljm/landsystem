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
	<?php $plant = Inputproduct::findAll(['father_id'=>1]);?>
    <?= $form->field($model, 'father_id')->dropDownList(ArrayHelper::map($plant, 'id', 'fertilizer')) ?>

    <?= $form->field($model, 'fertilizer')->textInput(['maxlength' => 500]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<JS
jQuery('#zipCode').change(function(){
    var zipId = $(this).val();
    jQuery.get('index.php?r=locations/get-city-province',{zipId:zipId},function(data){
        var data = jQuery.parseJSON(data);
        jQuery("#customers-city").attr("value",data.city);
        jQuery("#customers-province").attr("value",data.province);
    });
 
});
JS;
$this->registerJs($script);
?>