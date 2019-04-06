<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Firepreventionemployee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="firepreventionemployee-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>

<td width=15% align='right'>雇工人员ID</td>

<td align='left'><?= $form->field($model, 'employee_id')->textInput()->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>是否吸烟</td>

<td align='left'><?= $form->field($model, 'is_smoking')->textInput()->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>是否智障</td>

<td align='left'><?= $form->field($model, 'is_retarded')->textInput()->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>创建日期</td>

<td align='left'><?= $form->field($model, 'create_at')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>更新日期</td>

<td align='left'><?= $form->field($model, 'update_at')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>

</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
