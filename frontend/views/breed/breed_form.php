<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Breed */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="breed-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
		<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
<tr>
<td width=15% align='right'>养殖场名称</td>
<td align='left'><?= $form->field($model, 'breedname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>养殖位置</td>
<td align='left'><?= $form->field($model, 'breedaddress')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>是否示范户</td>
<td align='left'><?= $form->field($model, 'is_demonstration')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
