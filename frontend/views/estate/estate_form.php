<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Estate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="estate-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>双方是否提交申请及保证书</td>
<td align='left'><?= $form->field($model, 'tjsqjbzs')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>情况说明</td>
<td align='left'><?= $form->field($model, 'tjsqjbzscontent')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>是否提交双方复印件</td>
<td align='left'><?= $form->field($model, 'tjsffyj')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>情况说明</td>
<td align='left'><?= $form->field($model, 'tjsffyjcontent')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>宜农林地是否有争议</td>
<td align='left'><?= $form->field($model, 'sfyzy')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>情况说明</td>
<td align='left'><?= $form->field($model, 'sfyzycontent')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>是否明确地块</td>
<td align='left'><?= $form->field($model, 'sfmqzongdi')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>情况说明</td>
<td align='left'><?= $form->field($model, 'sfmqzongdicontent')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>是否有调查报告</td>
<td align='left'><?= $form->field($model, 'sfydcbg')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>情况说明</td>
<td align='left'><?= $form->field($model, 'sfydcbgcontent')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>审核过程ID</td>
<td align='left'><?= $form->field($model, 'reviewprocess_id')->textInput()->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
