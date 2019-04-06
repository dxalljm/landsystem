<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Otherfarms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="otherfarms-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>农场ID</td>
<td align='left'><?= $form->field($model, 'farms_id')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>面积</td>
<td align='left'><?= $form->field($model, 'measure')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>描述</td>
<td align='left'><?= $form->field($model, 'describe')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>合同号</td>
<td align='left'><?= $form->field($model, 'contractnumber')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>宗地</td>
<td align='left'><?= $form->field($model, 'zongdi')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>备注</td>
<td align='left'><?= $form->field($model, 'remarks')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
