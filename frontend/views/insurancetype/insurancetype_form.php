<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Insurancetype */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insurancetype-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>种植结构</td>
<td align='left'><?= $form->field($model, 'plant_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Plant::find()->where('father_id>1')->all(),'id','typename'))->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
