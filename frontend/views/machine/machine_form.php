<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Machine */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="machine-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>产品名称</td>
<td align='left'><?= $form->field($model, 'productname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>机具型号</td>
<td align='left'><?= $form->field($model, 'implementmodel')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>分档名称</td>
<td align='left'><?= $form->field($model, 'filename')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>企业所属省份</td>
<td align='left'><?= $form->field($model, 'province')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>企业名称</td>
<td align='left'><?= $form->field($model, 'enterprisename')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>基本配置及参数</td>
<td align='left'><?= $form->field($model, 'parameter')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
