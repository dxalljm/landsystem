<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Machinesubsidy */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="machinesubsidy-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>农机ID</td>
<td align='left'><?= $form->field($model, 'machine_id')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>分档名称</td>
<td align='left'><?= $form->field($model, 'filename')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>基本配置和参数</td>
<td align='left'><?= $form->field($model, 'parameter')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>中央财政补贴额</td>
<td align='left'><?= $form->field($model, 'subsidymoney')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
