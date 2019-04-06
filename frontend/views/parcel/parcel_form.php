<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Parcel */
/* @var $form yii\widgets\ActiveForm */
?>


    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>编号</td>
<td align='left'><?= $form->field($model, 'serialnumber')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>地块暂编号</td>
<td align='left'><?= $form->field($model, 'temporarynumber')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>地块统编号</td>
<td align='left'><?= $form->field($model, 'unifiedserialnumber')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>坡位</td>
<td align='left'><?= $form->field($model, 'powei')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>坡向</td>
<td align='left'><?= $form->field($model, 'poxiang')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>坡度</td>
<td align='left'><?= $form->field($model, 'podu')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>土壤类型</td>
<td align='left'><?= $form->field($model, 'agrotype')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>含石量</td>
<td align='left'><?= $form->field($model, 'stonecontent')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>毛面积</td>
<td align='left'><?= $form->field($model, 'grossarea')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>零星地类面积</td>
<td align='left'><?= $form->field($model, 'piecemealarea')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>净面积</td>
<td align='left'><?= $form->field($model, 'netarea')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>图幅号</td>
<td align='left'><?= $form->field($model, 'figurenumber')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

