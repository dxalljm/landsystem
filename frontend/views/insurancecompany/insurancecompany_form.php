<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Insurancecompany */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insurancecompany-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>保险公司名称</td>
<td align='left'><?= $form->field($model, 'companynname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
