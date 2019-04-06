<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
	<?= $form->field($model, 'father_id')->hiddenInput(['value'=>$_GET['father_id']])->label(false)->error(false) ?>
	<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
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
<tr>

<td width=15% align='right'>联系电话</td>

<td align='left'><?= $form->field($model, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>
<?= $form->field($model, 'create_at')->hiddenInput(['value'=>time()])->label(false)->error(false) ?>
<?= $form->field($model, 'update_at')->hiddenInput(['value'=>time()])->label(false)->error(false) ?>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('返回', [Yii::$app->controller->id.'fathers','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
