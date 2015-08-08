<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\Cooperative;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Farms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farms-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>

<td width=15% align='right'>农场名称</td>

<td align='left'><?= $form->field($model, 'farmname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>农场位置</td>

<td align='left'><?= $form->field($model, 'address')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>管理区</td>

<td align='left'><?= $form->field($model, 'management_area')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>审批年度</td>

<td align='left'><?= $form->field($model, 'spyear')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>是否承包</td>

<td align='left'><?= $form->field($model, 'iscontract')->textInput()->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>承包年限</td>

<td align='left'><?= $form->field($model, 'contractlife')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>面积</td>

<td align='left'><?= $form->field($model, 'measure')->textInput()->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>宗地</td>

<td align='left'><?= $form->field($model, 'zongdi')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>回收站</td>

<td align='left'><?= $form->field($model, 'isdelete')->textInput()->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>合作社</td>

<td align='left'><?= $form->field($model, 'cooperative_id')->dropDownList(ArrayHelper::map(Cooperative::find()->all(), 'id', 'cooperativename'))->label(false)->error(false) ?></td>

</tr>

</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
