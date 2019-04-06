<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Breedinfo;

/* @var $this yii\web\View */
/* @var $model app\models\Prevention */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prevention-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
	<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
	<?= $form->field($model, 'breedinfo_id')->hiddenInput(['value'=>$_GET['id']])->label(false)->error(false) ?>

<tr>
<td width=15% align='right'>免疫数量</td>
<td align='left'><?= $form->field($model, 'preventionnumber')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>应免数量</td>
<td align='left'><?= $form->field($model, 'breedinfonumber')->textInput(['readonly'=>true,'value'=>Breedinfo::find()->where(['id'=>$_GET['id']])->one()['number']])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>免疫率</td>
<td align='left'><?= $form->field($model, 'preventionrate')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>有无疫情</td><?php $model->isepidemic = '无';?>
<td align='left'><?= $form->field($model, 'isepidemic')->radioList(['无'=>'无','有'=>'有'])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>疫苗接种情况</td>
<td align='left'><?= $form->field($model, 'vaccine')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
$('#prevention-preventionnumber').blur(function(){
	var input = $(this).val();
	var bfb = input/$('#prevention-breedinfonumber').val()*100;
	$('#prevention-preventionrate').val(bfb.toFixed(2) + '%');
	
});
</script>