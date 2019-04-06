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
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Collection */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="collection-form">

    <?php $form = ActiveFormrdiv::begin(); ?>

    <?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$farms_id])->label(false) ?>
    <?= $form->field($model, 'cardid')->hiddenInput(['value'=>$cardid])->label(false) ?>
	<?= $form->field($model, 'isupdate')->hiddenInput()->label(false) ?>
<table class="table table-bordered table-hover">
  <tr>
    <td align="right">农场名称</td>
    <td><?= $farms->farmname?></td>
    <td align="right">法人名称</td>
    <td><?= Farmer::find()->where(['farms_id'=>$farms->id])->one()['farmername']?></td>
    <td>面积</td>
    <td><?= $farms->contractarea ?>亩</td>
  </tr>
  <tr>
    <td align="right">缴费年度</td><?php if(isset($_GET['year'])) $model->payyear = $_GET['year']; else $model->payyear = date('Y');?>
    <td><?= $form->field($model, 'payyear')->textInput(['disabled'=>'disabled'])->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'startView' => 4,
        	'minView' => 4,
            'format' => 'yyyy'
        ]
]); ?></td>
    <td align="right">应收金额</td>
    <td> <?= $form->field($model, 'amounts_receivable')->textInput(['value'=>$model->getAR($year),'disabled'=>'disabled'])->label(false)->error(false) ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">已收金额</td>
    <td><?php if($model->real_income_amount == '') echo '0';else echo $model->real_income_amount; ?>元</td>
    <td align="right">已收面积</td>
    <td><?= $farms->measure - $model->getYpayarea($year, $model->real_income_amount)?>亩</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">缴费金额</td><?php if(!($model->getAR($year)-$model->real_income_amount)) $realoption = ['value'=>$model->getYpaymoney($year, $model->real_income_amount),'disabled'=>'disabled']; else $realoption = ['value'=>$model->getYpaymoney($year, $model->real_income_amount)]?>
    <td><?= $form->field($model, 'real_income_amount')->textInput($realoption)->label(false)->error(false) ?></td>
    <td align="right">缴费面积</td><?php if(!($model->getAR($year)-$model->real_income_amount)) $areaoption = ['class'=>'form-control','disabled'=>'disabled']; else $areaoption = ['class'=>'form-control']?>
    <td><?= $form->field($model, 'measure')->textInput(['value'=>$model->getYpayarea($year, $model->real_income_amount),$areaoption])->label(false)->error(false) ?><?//= html::textInput('real_area',$model->getYpayarea($year, $model->real_income_amount),$areaoption)?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
</table>
<?= $form->field($model, 'ypayyear')->hiddenInput(['value'=>$year])->label(false)->error(false) ?>
<?= $form->field($model, 'ypayarea')->hiddenInput()->label(false)->error(false) ?>
<?= $form->field($model, 'ypaymoney')->hiddenInput()->label(false)->error(false) ?>
<?= $form->field($model, 'owe')->hiddenInput()->label(false)->error(false) ?>

<table class="table table-striped table-bordered table-hover table-condensed">
  <tr>
    <td align="center">应追缴年度</td>
    <td align="center">实收金额</td>
    <td align="center">应追缴费面积</td>
    <td align="center">应追缴费金额</td>
    <td align="center">剩余欠缴金额</td>

  </tr>
  <?php if(!$collectiondataProvider) {?>
  <tr>
    <td align="center"><?= $year?></td>
    <td align="center"><?= $model->real_income_amount;?></td>
    <td align="center"><?= $model->getYpayarea($year, $model->real_income_amount)?></td>
    <td align="center"><?= $model->getYpaymoney($year, $model->real_income_amount)?></td>
    <td align="center"><?= $owe+$model->getAR($year)-$model->real_income_amount?></td>

  </tr>
  <?php }?>
  <?php //var_dump($collectiondataProvider);?>
  <?php foreach($collectiondataProvider as $val) {?>
  <tr>
    <td align="center"><?= $val['ypayyear']?></td>
    <td align="center"><?= $val['real_income_amount']?></td>
    <td align="center"><?= $val['ypayarea']?></td>
    <td align="center"><?= $val['ypaymoney']?></td>
    <td align="center"><?= $owe?></td>

  </tr>
  <?php }?>
</table>
<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data' => [
                'confirm' => '您确定要提交吗？请认真核对后提交，提交后不可更改。',
                'method' => 'post',
            ],]) ?>
        
  </div>
<?php ActiveFormrdiv::end(); ?>
</div>
<script>
function submittype(v) {
	$('#collection-isupdate').val(v);
}


$(document).ready(function () {

// 	$('#collection-payyear').change(function(){
// 		var input = $(this).val();		
		//$.getJSON('index.php?r=collection/getar', {year: input,farms_id: <?= $_GET['farms_id'] ?>}, function (data) {
// 			if(data === 0) {
// 				alert(input);
// 				alert(input+'年度没有缴费基数，请添加缴费基数再试。');
// 				var d = new Date();
// 				$('#collection-payyear').val(d.getFullYear());
				//$.getJSON('index.php?r=collection/getar', {year: d.getFullYear(),farms_id: <?= $_GET['farms_id'] ?>}, function (data) {
// 					$('#collection-amounts_receivable').val(data);
// 				});
// 			}
// 			else
// 				$('#collection-amounts_receivable').val(data);
// 		});
// 	});

jQuery('#collection-payyear').change(function(){
    var year = $(this).val();
    $.getJSON('index.php?r=collection/getar', {year: year,farms_id: <?= $_GET['farms_id'] ?>}, function (data) {
			if(data === 0) {
				alert(year+'年度没有缴费基数，请添加缴费基数再试。');
				var d = new Date();
				$('#collection-payyear').val(d.getFullYear());
			$.getJSON('index.php?r=collection/getar', {year: d.getFullYear(),farms_id: <?= $_GET['farms_id'] ?>}, function (data) {
					$('#collection-amounts_receivable').val(data);
				});
			}
    });
    $.get('/landsystem/frontend/web/index.php?r=collection/collectioncreate',{year:year,farms_id:<?= $_GET['farms_id']?>,cardid:<?= $_GET['cardid'] ?>},function (data) {
		$('body').html(data);
	});
});

	
	$('#collection_realarea').keyup(function(event){
		var input = $(this).val();
		$.getJSON('index.php?r=collection/getplantprice', {formyear: $('#collection-payyear').val()}, function (data) {
			$('#collection-real_income_amount').val(input*data*30);
		});
		
	});
	$('#collection-real_income_amount').keyup(function(event){
		input = $(this).val();
		$.getJSON('index.php?r=collection/getplantprice', {formyear: $('#collection-payyear').val()}, function (data) {
			var result = input/data/30;
			$('#collection_realarea').val(result.toFixed(2));
		});
		
	});
	
  // 实收金额判断
  $('#collection-real_income_amount').blur(function() {

    // 实收金额
    var realPrice = parseFloat($(this).val());

    // 应收金额
    var amountsPrice = parseFloat($('#collection-amounts_receivable').val());

    // 实收金额小于应收金额
    if (realPrice > amountsPrice) {
      alert('实收金额(' + realPrice + ')超过本年度应追缴金额(' + <?= $model->getYpaymoney($year, $model->real_income_amount)?> + ')');
      $(this).focus();
    }

  });

//应收面积判断
  $('#collection_realarea').blur(function() {

    // 实收面积
    var realPrice = parseFloat($(this).val());

    // 应收面积
    var amountsPrice = parseFloat('<?= $model->getYpayarea($year, $model->real_income_amount)?>');

    // 实收金额小于应收金额
    if (realPrice > amountsPrice) {
      alert('实收金额(' + realPrice + ')超过本年度应追缴金额(' + amountsPrice + ')');
      $(this).focus();
    }

  });

});

</script>