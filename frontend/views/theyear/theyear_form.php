<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Theyear */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="theyear-form">

    <?php $form = ActiveForm::begin(); ?>
	<table class="table table-bordered table-hover">
		<tr>
			<td align="right">年度</td>
			<td><?= $form->field($model, 'years')->textInput()->label(false) ?></td>
		</tr>
	</table>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
