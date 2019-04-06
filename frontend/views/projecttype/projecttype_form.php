<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Projecttype */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projecttype-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>类别</td>
<td align='left'><?= $form->field($model, 'father_id')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>类型名称</td>
<td align='left'><?= $form->field($model, 'typename')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
