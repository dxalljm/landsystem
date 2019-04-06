<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
/* @var $this yii\web\View */
/* @var $model app\models\CooperativeOfFarm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cooperative-of-farm-form">
    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">

<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>

<tr>

<td width=15% align='right'>合作社</td>

<td align='left'><?= $form->field($model, 'cooperative_id')->dropDownList(ArrayHelper::map(Cooperative::find()->all(), 'id', 'cooperativename'))->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>注资金额</td>

<td align='left'><?= $form->field($model, 'cia')->textInput()->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>占比</td>

<td align='left'><?= $form->field($model, 'proportion')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>

</tr>

<tr>

<td width=15% align='right'>分红</td>

<td align='left'><?= $form->field($model, 'bonus')->textInput()->label(false)->error(false) ?></td>

</tr>



</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('返回', [Yii::$app->controller->id.'index','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
