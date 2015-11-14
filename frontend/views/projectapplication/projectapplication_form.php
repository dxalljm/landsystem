<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Projectapplication */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projectapplication-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>项目类型</td>
<td align='left'><?= $form->field($model, 'projecttype')->dropDownList([''])->label(false)->error(false) ?></td>
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
<td width=15% align='right'>是否立项</td>
<td align='left'><?= $form->field($model, 'is_agree')->textInput()->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
