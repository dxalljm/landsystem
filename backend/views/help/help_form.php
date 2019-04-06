<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Help */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="help-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>标识</td>
<td align='left'><?= $form->field($model, 'mark')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>备注</td>
<td align='left'><?= $form->field($model, 'content')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>标题</td>
<td align='left'><?= $form->field($model, 'title')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
