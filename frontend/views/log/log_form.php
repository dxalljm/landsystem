<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Log */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="log-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>
<td width=15% align='right'>用户ID</td>
<td align='left'><?= $form->field($model, 'user_id')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>用户IP</td>
<td align='left'><?= $form->field($model, 'user_ip')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>动作</td>
<td align='left'><?= $form->field($model, 'action')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>操作类型</td>
<td align='left'><?= $form->field($model, 'action_type')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>对象名称</td>
<td align='left'><?= $form->field($model, 'object_name')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>被操作对象ID</td>
<td align='left'><?= $form->field($model, 'object_id')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>操作内容</td>
<td align='left'><?= $form->field($model, 'operate_desc')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>操作时间</td>
<td align='left'><?= $form->field($model, 'operate_time')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>对象旧数据</td>
<td align='left'><?= $form->field($model, 'object_old_attr')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>对象新数据</td>
<td align='left'><?= $form->field($model, 'object_new_attr')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
