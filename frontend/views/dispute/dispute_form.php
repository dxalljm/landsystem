<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Disputetype;
/* @var $this yii\web\View */
/* @var $model app\models\Dispute */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dispute-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
	<tr>
		<td width=15% align='right'>纠纷类型</td>
		<td align='left'><?= $form->field($model, 'disputetype_id')->dropDownList(ArrayHelper::map(Disputetype::find()->all(), 'id', 'typename'))->label(false)->error(false) ?></td>
	</tr>
	<tr>
		<td width=15% align='right'>备注</td>
		<td align='left'><?= $form->field($model, 'content')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
	</tr>
</table>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('返回', [Yii::$app->controller->id.'index','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
