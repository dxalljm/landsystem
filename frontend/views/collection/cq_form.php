<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\ArrayHelper;
use app\models\Farms;
use app\models\Theyear;
use app\models\Farmer;
use app\models\PlantPrice;
use app\models\Collection;
/* @var $this yii\web\View */
/* @var $model app\models\Collection */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="collection-form">

    <?php $form = ActiveFormrdiv::begin(); ?>

    <?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$farmsid])->label(false) ?>
    <?= $form->field($model, 'cardid')->hiddenInput(['value'=>$cardid])->label(false) ?>
	<?= $form->field($model, 'isupdate')->hiddenInput()->label(false) ?>
<table class="table table-bordered table-hover">
  <tr>
    <td align="right">缴费年度：</td>
    <td><?= $form->field($model, 'payyear')->textInput(['value'=>$year,'disabled'=>'disabled'])->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 3,
        	'maxView' => 3,
            'format' => 'yyyy-mm-dd'
        ]
]); ?></td>
    <td align="right">开票时间：</td>
    <td><?= $form->field($model, 'billingtime')->hiddenInput(['maxlength' => 500,'value'=>0])->label(false)->error(false) ?></td>
  </tr>
  <tr>
    <td align="right">应收金额：</td>
    <td> <?= $form->field($model, 'amounts_receivable')->textInput(['value'=>$model->getAR($year),'disabled'=>'disabled'])->label(false)->error(false) ?></td>
    <td align="right">实收金额：</td>
    <td><?= $form->field($model, 'real_income_amount')->textInput()->label(false)->error(false) ?></td>
  </tr>
</table>
<?= $form->field($model, 'ypayyear')->hiddenInput(['value'=>$year])->label(false)->error(false) ?>
<?= $form->field($model, 'ypayarea')->hiddenInput()->label(false)->error(false) ?>
<?= $form->field($model, 'ypaymoney')->hiddenInput()->label(false)->error(false) ?>
<?= $form->field($model, 'owe')->hiddenInput()->label(false)->error(false) ?>

<table class="table table-striped table-bordered table-hover table-condensed">
  <tr>
    <td align="center">应追缴年度</td>
    <td align="center">应追缴费面积</td>
    <td align="center">应追缴费金额</td>
    <td align="center">剩余欠缴金额</td>

  </tr>
  <tr>
    <td align="center"><?= $year?></td>
    <td align="center"><?= $model->getYpayarea($year, $model->real_income_amount)?></td>
    <td align="center"><?= $model->getYpaymoney($year, $model->real_income_amount)?></td>
    <td align="center"><?= $owe+$model->getAR($year)-$model->real_income_amount?></td>

  </tr>
  <?php foreach($collectiondataProvider as $val) {?>
  <tr>
    <td align="center"><?= $val['ypayyear']?></td>
    <td align="center"><?= $val['ypayarea']?></td>
    <td align="center"><?= $val['ypaymoney']?></td>
    <td align="center"><?= $owe?></td>

  </tr>
  <?php }?>
</table>
<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','onclick'=>'submittype(1)']) ?>
        <?= Html::submitButton('保存', ['class' => 'btn btn-primary','onclick'=>'submittype(0)']) ?>
    </div>
<?php ActiveFormrdiv::end(); ?>
</div>
<script>
function submittype(v) {
	$('#collection-isupdate').val(v);
}

$(document).ready(function () {

  // 实收金额判断
  $('#collection-real_income_amount').blur(function() {

    // 实收金额
    var realPrice = parseFloat($(this).val());

    // 应收金额
    var amountsPrice = parseFloat($('#collection-amounts_receivable').val());

    // 实收金额小于应收金额
    if (realPrice > amountsPrice) {
      alert('实收金额(' + realPrice + ')超过本年度应追缴金额(' + amountsPrice + ')');
      $(this).focus();
    }

  });

});

</script>