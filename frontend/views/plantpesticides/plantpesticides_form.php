<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Farms;
use app\models\Lease;
use yii\helpers\ArrayHelper;
use app\models\Pesticides;
use app\models\Plant;
/* @var $this yii\web\View */
/* @var $model app\models\Plantpesticides */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plantpesticides-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>农场名称</td>
<td align='left'><?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?><?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']?></td>
</tr>
<tr>
<td width=15% align='right'>承租人</td>
<td align='left'><?= $form->field($model, 'lessee_id')->hiddenInput(['value'=>$_GET['lease_id']])->label(false)->error(false) ?><?= Lease::find()->where(['id'=>$_GET['lease_id']])->one()['lessee'] ?></td>
</tr>
<tr>
<td width=15% align='right'>作物名称</td>
<td align='left'><?= $form->field($model, 'plant_id')->hiddenInput(['value'=>$_GET['plant_id']])->label(false)->error(false) ?><?= Plant::find()->where(['id'=>$_GET['plant_id']])->one()['typename'] ?></td>
</tr>
<tr>
<td width=15% align='right'>农药使用情况</td>
<td align='left'><?= $form->field($model, 'pesticides_id')->dropDownList(ArrayHelper::map(Pesticides::find()->all(), 'id', 'pesticidename'),['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>农药用量</td>
<td align='left'><?= $form->field($model, 'pconsumption')->textInput()->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
