<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Plantinputproduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plantinputproduct-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>
<td width=15% align='right'>农场ID</td>
<td align='left'><?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$planting->farms_id])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>承租人ID</td>
<td align='left'><?= $form->field($model, 'lessee_id')->hiddenInput(['value'=>$planting->lease_id])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>类别</td>
<td align='left'><?= $form->field($model, 'father_id')->textInput([])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>子类ID</td>
<td align='left'><?= $form->field($model, 'son_id')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>化肥使用情况</td>
<td align='left'><?= $form->field($model, 'inputproduct_id')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>农药用量</td>
<td align='left'><?= $form->field($model, 'pconsumption')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>宗地</td>
<td align='left'><?= $form->field($model, 'zongdi')->hiddenInput(['value'=>$planting->zongdi])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>种植结构</td>
<td align='left'><?= $form->field($model, 'plant_id')->hiddenInput(['value'=>$planting->plant_id])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
