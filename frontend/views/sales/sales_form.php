<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Sales */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
		<?= $form->field($model, 'planting_id')->hiddenInput(['value'=>$_GET['planting_id']])->label(false)->error(false) ?>
		<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
<tr>
<td width=15% align='right'>销售去向</td>
<td align='left'><?= $form->field($model, 'whereabouts')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>销售量</td><?php if(Yii::$app->controller->action->id !== 'salesupdate') $model->volume = $volume;?>
<td align='left'><?= $form->field($model, 'volume')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>价格</td>
<td align='left'><?= $form->field($model, 'price')->textInput()->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('返回', [Yii::$app->controller->id.'index','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
$('#sales-volume').blur(function(){
	input = $(this).val();
	if(input > <?= $volume?>) {
		alert('填写的数值不能大于总产量'+<?= $volume ?>);
		$('#sales-volume').focus();
	}
})
</script>