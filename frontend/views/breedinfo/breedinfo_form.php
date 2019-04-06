<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Breedinfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="breedinfo-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>
<td width=15% align='right'>养殖户ID</td>
<td align='left'><?= $form->field($model, 'breed_id')->dr()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>数量</td>
<td align='left'><?= $form->field($model, 'number')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>基础投资</td>
<td align='left'><?= $form->field($model, 'basicinvestment')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>圈舍面积</td>
<td align='left'><?= $form->field($model, 'housingarea')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>养殖种类</td>
<td align='left'><?= $form->field($model, 'breedtype_id')->textInput()->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
