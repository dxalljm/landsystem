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
use app\models\ManagementArea;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Collection */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="collection-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;缴费业务<?= $this->title ?><font color="red">(<?= $model->payyear?>年度)</font></h3></div>
                <div class="box-body">
    <?php $form = ActiveFormrdiv::begin(); ?>
	<?php //var_dump($model);exit;?>
    <?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$farm->id])->label(false)->error(false) ?>
 
	<?= $form->field($model, 'isupdate')->hiddenInput()->label(false) ?>
<table class="table table-bordered table-hover">
  <tr>
    <td align="right">农场名称</td>
    <td><?= $farm->farmname?></td>
    <td align="right">法人名称</td>
    <td><?= $farm->farmername?></td>
    <td>身份证</td>
    <td><?= $farm->cardid?></td>
  </tr>
  <tr>
    <td align="right">面积</td>
    <td><?= $farm->contractarea ?>亩</td>
    <td align="right">合同号</td>
    <td><?= $farm->contractnumber?></td>
    <td>管理区</td>
    <td><?= ManagementArea::find()->where(['id'=>$farm->management_area])->one()['areaname']?></td>
  </tr>
  <tr>
    <td align="right">缴费年度</td><?php if(isset($year)) $model->payyear = $year; else $model->payyear = $year;?>
    <td colspan="2"><?= $form->field($model, 'payyear')->textInput(['disabled'=>'disabled','id'=>'collection2-payyear'])->label(false)->error(false); ?></td>
    <td align="right"> 应收金额</td>
    <td colspan="2"><?= $form->field($model, 'amounts_receivable')->textInput(['value'=>Collection::getAR($year,$farm->id),'disabled'=>'disabled'])->label(false)->error(false) ?></td>
    </tr>

  <tr>
    <td align="right">缴费金额</td><?php //if(bcsub(Collection::getAR($year,$farm->id),$model->real_income_amount,2) == 0.0) $realoption = ['value'=>Collection::getYpaymoney($year, $model->real_income_amount,$farm->id),'disabled'=>'disabled']; else $realoption = ['value'=>$model->getYpaymoney($year, $model->real_income_amount,$farm->id)]?>
    <td colspan="2"><?= $form->field($model, 'real_income_amount')->textInput(['value'=>$model->ypaymoney,'id'=>'collection2-real_income_amount'])->label(false)->error(false) ?></td>
    <?php //if(bcsub(Collection::getAR($year,$farm->id),$model->real_income_amount,2) == 0.0) $areaoption = ['class'=>'form-control','disabled'=>'disabled']; else $areaoption = ['class'=>'form-control']?>
    <td align="right">缴费面积</td>
    <td colspan="2"><?= $form->field($model, 'measure')->textInput(['value'=>$model->ypayarea,'id'=>'collection2-measure'])->label(false)->error(false) ?></td>
    </tr>
  <tr>
  	<td align="right">历年陈欠金额</td><?php $model->owe = Collection::getOwe($farms_id,$year);?>
  	<td colspan="5"><?= $form->field($model, 'owe')->textInput(['disabled'=>true])->label(false)->error(false) ?></td>
  </tr>
</table>
<?= $form->field($model, 'ypayyear')->hiddenInput(['value'=>$year])->label(false)->error(false) ?>
<?= $form->field($model, 'ypayarea')->hiddenInput()->label(false)->error(false) ?>
<?= $form->field($model, 'ypaymoney')->hiddenInput()->label(false)->error(false) ?>
<?= $form->field($model, 'owe')->hiddenInput()->label(false)->error(false) ?>
<?= $form->field($model, 'id')->hiddenInput()->label(false)->error(false) ?>
<?php ActiveFormrdiv::end(); ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
    $('#collection2-measure').keyup(function(event){
        var input = $(this).val();
        if(input == 0) {
            alert('缴费面积不能为0。')
            $(this).focus();
            $('#collection2-measure').val(<?= $model->ypayarea?>);
            $('#collection2-real_income_amount').val(<?= $model->ypaymoney?>);
        } else {
            if(input > <?= $model->ypayarea?>) {
                alert('缴费面积不能超过剩余面积。')
                $(this).focus();
                $('#collection2-measure').val(<?= $model->ypayarea?>);
                $('#collection2-real_income_amount').val(<?= $model->ypaymoney?>);
            } else {
                $.getJSON('index.php?r=collection/getplantprice', {formyear: $('#collection2-payyear').val()}, function (data) {
                    $('#collection2-real_income_amount').val(input * data);
                });
            }
        }
    });
    $('#collection2-real_income_amount').keyup(function(event){
        input = $(this).val();
        if(input == 0) {
            alert('对不起，缴费金额不能为0。')
            $(this).focus();
            $('#collection2-measure').val(<?= $model->ypayarea?>);
            $('#collection2-real_income_amount').val(<?= $model->ypaymoney?>);
        } else {
            if(input > <?= $model->ypaymoney?>) {
                alert('缴费金额不能超过剩余金额。')
                $(this).focus();
                $('#collection2-measure').val(<?= $model->ypayarea?>);
                $('#collection2-real_income_amount').val(<?= $model->ypaymoney?>);
            } else {
                $.getJSON('index.php?r=collection/getplantprice', {formyear: $('#collection2-payyear').val()}, function (data) {
                    var result = input / data;
                    $('#collection2-measure').val(result.toFixed(2));
                });
            }
        }
    });

    $('#collection2-measure').blur(function(){
        var input = $(this).val();
        if(input == 0) {
            alert('缴费面积不能为0。')
            $(this).focus();
            $('#collection2-measure').val(<?= $model->ypayarea?>);
            $('#collection2-real_income_amount').val(<?= $model->ypaymoney?>);
        } else {
            if(input > <?= $model->ypayarea?>) {
                alert('缴费面积不能超过剩余面积。')
                $(this).focus();
                $('#collection2-measure').val(<?= $model->ypayarea?>);
                $('#collection2-real_income_amount').val(<?= $model->ypaymoney?>);
            } else {
                $.getJSON('index.php?r=collection/getplantprice', {formyear: $('#collection2-payyear').val()}, function (data) {
                    $('#collection2-real_income_amount').val(input * data);
                });
            }
        }
    });
    $('#collection2-real_income_amount').blur(function(){
        input = $(this).val();
        if(input == 0) {
            alert('对不起，缴费金额不能为0。')
            $(this).focus();
            $('#collection2-measure').val(<?= $model->ypayarea?>);
            $('#collection2-real_income_amount').val(<?= $model->ypaymoney?>);
        } else {
            if(input > <?= $model->ypaymoney?>) {
                alert('缴费金额不能超过剩余金额。')
                $(this).focus();
                $('#collection2-measure').val(<?= $model->ypayarea?>);
                $('#collection2-real_income_amount').val(<?= $model->ypaymoney?>);
            } else {
                $.getJSON('index.php?r=collection/getplantprice', {formyear: $('#collection2-payyear').val()}, function (data) {
                    var result = input / data;
                    $('#collection2-measure').val(result.toFixed(2));
                });
            }
        }
    });
</script>