<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Huinonggrant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="huinonggrant-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>农场ID</td>
<td align='left'><?= $form->field($model, 'farms_id')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>惠农政策ID</td>
<td align='left'><?= $form->field($model, 'huinong_id')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>补贴金额</td>
<td align='left'><?= $form->field($model, 'money')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>种植面积</td>
<td align='left'><?= $form->field($model, 'area')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>状态</td>
<td align='left'><?= $form->field($model, 'state')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>备注</td>
<td align='left'><?= $form->field($model, 'note')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
