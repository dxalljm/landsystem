<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Insurance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insurance-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>管理区</td>
<td align='left'><?= $form->field($model, 'management_area')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>年度</td>
<td align='left'><?= $form->field($model, 'year')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>农场ID</td>
<td align='left'><?= $form->field($model, 'farms_id')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>投保人</td>
<td align='left'><?= $form->field($model, 'policyholder')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>法人身份证</td>
<td align='left'><?= $form->field($model, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>联系电话</td>
<td align='left'><?= $form->field($model, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>小麦</td>
<td align='left'><?= $form->field($model, 'wheat')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>大豆</td>
<td align='left'><?= $form->field($model, 'soybean')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>投保面积</td>
<td align='left'><?= $form->field($model, 'insuredarea')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>投保小麦面积</td>
<td align='left'><?= $form->field($model, 'insuredwheat')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>投保大豆面积</td>
<td align='left'><?= $form->field($model, 'insuredsoybean')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>保险公司</td>
<td align='left'><?= $form->field($model, 'company_id')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>创建日期</td>
<td align='left'><?= $form->field($model, 'create_at')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>更新日期</td>
<td align='left'><?= $form->field($model, 'update_at')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>投保人签字日期</td>
<td align='left'><?= $form->field($model, 'policyholdertime')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>管理区提交日期</td>
<td align='left'><?= $form->field($model, 'managemanttime')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>保险负责人提交日期</td>
<td align='left'><?= $form->field($model, 'halltime')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
