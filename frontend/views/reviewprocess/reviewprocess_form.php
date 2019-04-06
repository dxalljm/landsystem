<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reviewprocess-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>农场ID</td>
<td align='left'><?= $form->field($model, 'farms_id')->textInput(['maxlength' => 11])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>创建日期</td>
<td align='left'><?= $form->field($model, 'create_at')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>更新日期</td>
<td align='left'><?= $form->field($model, 'update_at')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>地产科状态</td>
<td align='left'><?= $form->field($model, 'estate')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>财务报表</td>
<td align='left'><?= $form->field($model, 'finance')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>档案审查状态</td>
<td align='left'><?= $form->field($model, 'filereview')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>公安部门状态</td>
<td align='left'><?= $form->field($model, 'publicsecurity')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>分管领导状态</td>
<td align='left'><?= $form->field($model, 'leader')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>抵押贷款审查状态</td>
<td align='left'><?= $form->field($model, 'mortgage')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>领导小组状态</td>
<td align='left'><?= $form->field($model, 'steeringgroup')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>地产科意见</td>
<td align='left'><?= $form->field($model, 'estatecontent')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>财务科意见</td>
<td align='left'><?= $form->field($model, 'financecontent')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>档案审查情况</td>
<td align='left'><?= $form->field($model, 'filereviewcontent')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>公安部门意见</td>
<td align='left'><?= $form->field($model, 'publicsecuritycontent')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>分管领导意见</td>
<td align='left'><?= $form->field($model, 'leadercontent')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>抵押贷款审查</td>
<td align='left'><?= $form->field($model, 'mortgagecontent')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>领导小组意见</td>
<td align='left'><?= $form->field($model, 'steeringgroupcontent')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>地产科审查时间</td>
<td align='left'><?= $form->field($model, 'estatetime')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>财务科审核时间</td>
<td align='left'><?= $form->field($model, 'financetime')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>档案审查时间</td>
<td align='left'><?= $form->field($model, 'filereviewtime')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>公安部门审核时间</td>
<td align='left'><?= $form->field($model, 'publicsecuritytime')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>分管领导审核时间</td>
<td align='left'><?= $form->field($model, 'leadertime')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>抵押贷款审核时间</td>
<td align='left'><?= $form->field($model, 'mortgagetime')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>领导小组审核时间</td>
<td align='left'><?= $form->field($model, 'steeringgrouptime')->textInput()->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
