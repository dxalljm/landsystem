<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Tempprintbill */
/* @var $form yii\widgets\ActiveForm */
?>
<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
       <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<div class="tempprintbill-form">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
          <div class="box-body">
    <?php $form = ActiveFormrdiv::begin(); ?>

<table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="8" align="center"><h3 style="font-size: 30px">大兴安岭岭南宜农林地承包费专用票据</h3><br></td>
  </tr>
  <tr>
    <td width="15%" align="right" style="vertical-align: middle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开票日期：</td><?php $model->create_at = date('Y-m-d');?>
    <td width="12%"><?= $form->field($model, 'create_at')->textInput()->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 2,
        	'maxView' => 3,
            'format' => 'yyyy-mm-dd'
        ]
]);?></td>
    <td align="right" style="vertical-align: middle">账页号：</td>
    <td width="100"><?= $form->field($model, 'accountnumber')->textInput()->label(false)->error(false) ?></td>
    <td align="right" style="vertical-align: middle">管理区：</td>
    <td><?= $form->field($model,'management_area')->dropDownList(ArrayHelper::map(ManagementArea::find()->all(),'id','areaname'))->label(false)->error(false)?></td>
    <td align="right" style="vertical-align: middle">NO:</td><?php $model->nonumber = $nonumber?>
    <td><?= $form->field($model, 'nonumber')->textInput()->label(false)->error(false) ?></td>

  </tr>
</table>
<?= $form->field($model, 'amountofmoneys')->hiddenInput()->label(false)->error(false)?>
<table width="100%" border="1" class="table table-bordered table-hover">
  <tr>
    <td width="14%" height="31" align="center">&nbsp;收款单位（缴款人）      </td>
    <td height="31" colspan="5"><?= $form->field($model, 'farmername')->textInput()->label(false)->error(false) ?></td>
    </tr>
  <tr>
    <td height="31" colspan="2" align="center">收费项目</td>
    <td width="13%" align="center">单位</td>
    <td width="18%" align="center">数量</td>
    <td width="17%" align="center">标准</td>
    <td width="21%" align="center">金额</td>
  </tr>
  <tr>
    <td height="23" align="center" valign="middle"><?= DateTimePicker::widget([
          'id' => 'year0',
          'name' => 'temp[year][]',//当没有设置model时和attribute时必须设置name
          'language' => 'zh-CN',
          'class' => false,
//          'value' => '承包费收缴',
          'template' => '<div class="well well-sm" style="background-color: #fff; width:100px">{input}</div>',
          'inline' => false,

          'clientOptions' => [
              'autoclose' => true,
              'startView' => 4,
              'minView' => 4,
              'format' => 'yyyy'
          ]
      ]);?></td>
      <td align="center" valign="middle">年度宜农林地承包费</td>
    <td align="center" valign="middle">      元/亩<br /></td>
    <td align="center" valign="middle"><?= Html::textinput('temp[measure][]', '', ['class' => 'form-control','id'=>'measure0']); ?></td>
    <td align="center" valign="middle"><?= Html::textinput('temp[standard][]', '30', ['class' => 'form-control','id'=>'standard0']);?></td>
    <td align="center" valign="middle"><?= Html::textinput('temp[amountofmoney][]', '0', ['class' => 'form-control','readonly'=>'readonly','id'=>'amountofmoney0']);?></td>
  </tr>
  <tr>
   <td height="23" align="center" valign="middle"><?= DateTimePicker::widget([
          'id' => 'year1',
          'name' => 'temp[year][]',//当没有设置model时和attribute时必须设置name
          'language' => 'zh-CN',
          'class' => false,
//          'value' => '承包费收缴',
          'template' => '<div class="well well-sm" style="background-color: #fff; width:100px">{input}</div>',
          'inline' => false,

          'clientOptions' => [
              'autoclose' => true,
              'startView' => 4,
              'minView' => 4,
              'format' => 'yyyy'
          ]
      ]);?></td>
      <td align="center" valign="middle">年度宜农林地承包费</td>
    <td align="center" valign="middle">      元/亩<br /></td>
    <td align="center" valign="middle"><?= Html::textinput('temp[measure][]', '', ['class' => 'form-control','id'=>'measure1']); ?></td>
    <td align="center" valign="middle"><?= Html::textinput('temp[standard][]', '30', ['class' => 'form-control','id'=>'standard1']);?></td>
    <td align="center" valign="middle"><?= Html::textinput('temp[amountofmoney][]', '0', ['class' => 'form-control','readonly'=>'readonly','id'=>'amountofmoney1']);?></td>
  </tr>
  <tr>
    <td height="23" align="center" valign="middle"><?= DateTimePicker::widget([
          'id' => 'year2',
          'name' => 'temp[year][]',//当没有设置model时和attribute时必须设置name
          'language' => 'zh-CN',
          'class' => false,
//          'value' => '承包费收缴',
          'template' => '<div class="well well-sm" style="background-color: #fff; width:100px">{input}</div>',
          'inline' => false,

          'clientOptions' => [
              'autoclose' => true,
              'startView' => 4,
              'minView' => 4,
              'format' => 'yyyy'
          ]
      ]);?></td>
      <td align="center" valign="middle">年度宜农林地承包费</td>
    <td align="center" valign="middle">      元/亩<br /></td>
    <td align="center" valign="middle"><?= Html::textinput('temp[measure][]', '', ['class' => 'form-control','id'=>'measure2']); ?></td>
    <td align="center" valign="middle"><?= Html::textinput('temp[standard][]', '30', ['class' => 'form-control','id'=>'standard2']);?></td>
    <td align="center" valign="middle"><?= Html::textinput('temp[amountofmoney][]', '0', ['class' => 'form-control','readonly'=>'readonly','id'=>'amountofmoney2']);?></td>
  </tr>
  <tr>
    <td align="center">金额合计（大写）</td>
    <td colspan="3"><?= $form->field($model, 'bigamountofmoney')->textInput(['readonly'=>'readonly'])->label(false)->error(false) ?></td>
    <td align="right">￥：</td>
    <td><?= html::textInput('money',$model->amountofmoney,['id'=>'viewmoney','class'=>'form-control','readonly'=>'readonly']) ?>
    </td>
  </tr>
  <tr>
    <td align="right">备注：</td>
    <td colspan="5"><?= $form->field($model, 'remarks')->textInput()->label(false)->error(false) ?></td>
    </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="60%">收款单位（盖章）大兴安岭林业管理局岭南管委会</td>
    <td width="13%">收款人：<?= Yii::$app->getUser()->getIdentity()->realname?></td>
    <td width="27%" align="right">（微机专用 手填无效）</td>
  </tr>
</table>
<br/>
<div class="form-group">
      <?= Html::submitButton('提交打印', ['class' => $model->isNewRecord]) ?>
</div>

    <?php ActiveFormrdiv::end(); ?>
<script>
$('#measure0').keyup(function(event){
	var input = $(this).val();
	var result = input*$('#standard0').val();
	$('#amountofmoney0').val(result.toFixed(2));
	var sum = $('#amountofmoney0').val()*1 + $('#amountofmoney1').val()*1 + $('#amountofmoney2').val()*1;
	$.getJSON('index.php?r=tempprintbill/format', {number: sum.toFixed(2)}, function (data) {
		$('#tempprintbill-bigamountofmoney').val(data.cny);
		$('#tempprintbill-amountofmoneys').val(sum);
		$('#viewmoney').val(data.num);
	});
});
$('#measure1').keyup(function(event){
	input = $(this).val();
	result = input*$('#standard1').val();
	$('#amountofmoney1').val(result.toFixed(2));
	var sum = $('#amountofmoney0').val()*1 + $('#amountofmoney1').val()*1 + $('#amountofmoney2').val()*1;
	$.getJSON('index.php?r=tempprintbill/format', {number: sum.toFixed(2)}, function (data) {
		$('#tempprintbill-bigamountofmoney').val(data.cny);
		$('#tempprintbill-amountofmoneys').val(sum);
		$('#viewmoney').val(data.num);
	});
});
$('#measure2').keyup(function(event){
	input = $(this).val();
	result = input*$('#standard2').val();
	$('#amountofmoney2').val(result.toFixed(2));
	var sum = $('#amountofmoney0').val()*1 + $('#amountofmoney1').val()*1 + $('#amountofmoney2').val()*1;
	$.getJSON('index.php?r=tempprintbill/format', {number: sum.toFixed(2)}, function (data) {
		$('#tempprintbill-bigamountofmoney').val(data.cny);
		$('#tempprintbill-amountofmoneys').val(sum);
		$('#viewmoney').val(data.num);
	});
});
$('#standard0').keyup(function(event){
	var input = $(this).val();
	var result = input*$('#measure0').val();
	$('#amountofmoney0').val(result.toFixed(2));
	var sum = $('#amountofmoney0').val()*1 + $('#amountofmoney1').val()*1 + $('#amountofmoney2').val()*1;
	$.getJSON('index.php?r=tempprintbill/format', {number: sum.toFixed(2)}, function (data) {
		$('#tempprintbill-bigamountofmoney').val(data.cny);
		$('#tempprintbill-amountofmoneys').val(sum);
		$('#viewmoney').val(data.num);
	});
});
$('#standard1').keyup(function(event){
	input = $(this).val();
	result = input*$('#measure1').val();
	$('#amountofmoney1').val(result.toFixed(2));
	var sum = $('#amountofmoney0').val()*1 + $('#amountofmoney1').val()*1 + $('#amountofmoney2').val()*1;
	$.getJSON('index.php?r=tempprintbill/format', {number: sum.toFixed(2)}, function (data) {
		$('#tempprintbill-bigamountofmoney').val(data.cny);
		$('#tempprintbill-amountofmoneys').val(sum);
		$('#viewmoney').val(data.num);
	});
});
$('#standard2').keyup(function(event){
	input = $(this).val();
	result = input*$('#measure2').val();
	$('#amountofmoney2').val(result.toFixed(2));
	var sum = $('#amountofmoney0').val()*1 + $('#amountofmoney1').val()*1 + $('#amountofmoney2').val()*1;
	$.getJSON('index.php?r=tempprintbill/format', {number: sum.toFixed(2)}, function (data) {
		$('#tempprintbill-bigamountofmoney').val(data.cny);
		$('#tempprintbill-amountofmoneys').val(sum);
		$('#viewmoney').val(data.num);
	});
});
$('#year1').click(function(){
	if($('#year0').val() == '') {
		alert('请按顺序填写数据');
		$('#year0').focus();
		$('#year1').datetimepicker('hide');
		$('#year1').val('');
	}
});
$('#year2').click(function(){
	if($('#year0').val() == '') {
		
		alert('请按顺序填写数据');
		$('#year0').focus();
// 		$('#year1').datetimepicker('hide');
		$('#year2').datetimepicker('hide');
// 		$('#year2').val('');
	} else {
		if($('#year1').val() == '') {
			alert('请按顺序填写数据');
			$('#year1').focus();
			$('#year2').datetimepicker('hide');
// 			$('#year2').val('');
		}
	}
});

// $('#year').change(function () {
//   var year = $(this).val();
//   $.getJSON('index.php?r=plantprice/getprice', {year: year}, function (data) {
//     $('#tempprintbill-standard').val(data);
//     var result = $('#tempprintbill-measure').val()*$('#tempprintbill-standard').val();
//       $.getJSON('index.php?r=tempprintbill/format', {number: result.toFixed(2)}, function (data) {
//           $('#tempprintbill-bigamountofmoney').val(data.cny);
//           $('#tempprintbill-amountofmoney').val(data.num);
//           $('#viewmoney').val(data.num);
//       });
//   });
// });
</script>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
