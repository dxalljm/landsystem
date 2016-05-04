<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Farms;
use app\models\Collection;
/* @var $this yii\web\View */
/* @var $model app\models\PlantPrice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plant-price-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'years')->textInput()->widget(DateTimePicker::className(), [
    'language' => 'zh-CN',
    'size' => 'xs',
    'template' => '{input}',
    //'pickButtonIcon' => 'glyphicon-calendar',
    'inline' => false,
    'clientOptions' => [
        'startView' => 'decade',
        'minView' => 4,
        //'maxView' => 2,
        'autoclose' => true,
        //'CustomFormat' => 'yyyy',
        'format' => 'yyyy', // if inline = false
        'todayBtn' => false
    ]
]); ?>
	

    <?= $form->field($model, 'price')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data-toggle'=>"modal", 
   'data-target'=>"#plantprice-modal",'onclick'=> 'createModel("plantprice/plantpricemodel",'.$model->id.')',]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'plantprice-modal',
	'size'=>'modal-lg',

]); 

?>

<?php \yii\bootstrap\Modal::end(); ?>
