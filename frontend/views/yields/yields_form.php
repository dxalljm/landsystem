<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Yields */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="yields-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
	<?= $form->field($model, 'planting_id')->hiddenInput(['value'=>$_GET['planting_id']])->label(false)->error(false) ?>
	<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
<tr>
<td width=15% align='right'>单产</td>
<td align='left'><?= $form->field($model, 'single')->textInput()->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('返回', [Yii::$app->controller->id.'index','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
