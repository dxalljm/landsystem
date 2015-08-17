<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
	<?= $form->field($model, 'father_id')->hiddenInput(['value'=>$_GET['father_id']])->label(false)->error(false) ?>

<tr>

<td width=15% align='right'>雇工类型</td>

<td align='left'><?= $form->field($model, 'employeetype')->dropDownList(['长期工'=>'长期工','短期工'=>'短期工','临时工'=>'临时工'])->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>雇工姓名</td>

<td align='left'><?= $form->field($model, 'employeename')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>身份证号</td>

<td align='left'><?= $form->field($model, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>

</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
