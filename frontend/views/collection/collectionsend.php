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
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;缴费业务<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
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
    <td colspan="2"><?= $form->field($model, 'payyear')->textInput(['disabled'=>'disabled'])->label(false)->error(false)->widget(
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
    <td align="right"> 应收金额</td>
    <td colspan="2"><?= $form->field($model, 'amounts_receivable')->textInput(['value'=>Collection::getAR($year,$farm->id),'disabled'=>'disabled'])->label(false)->error(false) ?></td>
    </tr>

  <tr>
    <td align="right">缴费金额</td><?php //if(bcsub(Collection::getAR($year,$farm->id),$model->real_income_amount,2) == 0.0) $realoption = ['value'=>Collection::getYpaymoney($year, $model->real_income_amount,$farm->id),'disabled'=>'disabled']; else $realoption = ['value'=>$model->getYpaymoney($year, $model->real_income_amount,$farm->id)]?>
    <td colspan="2"><?= $form->field($model, 'real_income_amount')->textInput(['value'=>$model->ypaymoney])->label(false)->error(false) ?></td>
    <?php //if(bcsub(Collection::getAR($year,$farm->id),$model->real_income_amount,2) == 0.0) $areaoption = ['class'=>'form-control','disabled'=>'disabled']; else $areaoption = ['class'=>'form-control']?>
    <td align="right">缴费面积</td>
    <td colspan="2"><?= $form->field($model, 'measure')->textInput(['value'=>$model->ypayarea])->label(false)->error(false) ?></td>
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

<table class="table table2-bordered table-hover">
  <tr>
    <td align="center">应追缴年度</td>
    <td align="center">实收金额</td>
    <td align="center">应追缴费面积</td>
    <td align="center">应追缴费金额</td>
    <td align="center">剩余欠缴金额</td>
    <td align="center">状态</td>
      <td align="center">操作</td>
  </tr>
<!--  --><?php //var_dump($collectiondataProvider);?>
  <?php foreach($collectiondataProvider as $val) {?>
  <tr>
    <td align="center"><?= $val['payyear']?></td>
    <td align="center"><?= $val['real_income_amount']?></td>
    <td align="center"><?= $val['ypayarea']?></td>
    <td align="center"><?= $val['ypaymoney']?></td>
    <td align="center"><?= $val['owe']?></td>
    <td align="center"><?php if($val['state'] == 0 and $val['dckpay'] == 1) echo '已提交'; if($val['state'] == 0 and $val['dckpay'] == 0) echo '未缴纳';if($val['state'] == 1 and $val['dckpay'] == 1) echo '已缴纳';if($val['state'] == 2) echo '部分缴纳';?></td>
      <td align="center"><?php
          $collection = Collection::find()->where(['id'=>$val['id']])->one();
//          var_dump($collection);
//          if($collection['owe'] > 0) {
//          var_dump($val['state']);
//              if ($val['state'] == 0) {
//                  var_dump($val['payyear']);var_dump(date('Y'));
                  if($val['payyear'] !== (int)date('Y') and $val['dckpay'] == 0) {

//                      echo Html::a('陈欠追缴', 'index.php?r=collection/collectionrecovered&id=' . $val['id'], [
//                          'id' => 'collectionreset',
//                          'class' => 'btn btn-xs btn-danger',
//                          'data' => [
//                              'confirm' => '您确定要缴费吗？',
//                              'method' => 'post',
//                          ],
//                      ]);
                      echo Html::a('陈欠追缴','#',['id'=>'cq','class' => 'btn btn-xs btn-danger','onclick'=>'cq('.$val['id'].')']);
                      echo '&nbsp;';
                  }

//              }
//          }
              if ($val['state'] == 0 and $val['dckpay'] == 1) {
                  echo Html::a('撤消', 'index.php?r=collection/collectionreset&id=' . $val['id'], [
                      'id' => 'collectionreset',
                      'class' => 'btn btn-xs btn-danger',
                      'data' => [
                          'confirm' => '您确定要撤消这项吗？',
                          'method' => 'post',
                      ],
                  ]);
              }
          ?></td>
  </tr>
  <?php }?>
</table>
                    <div id="dialogWait" title="正在生成数据...">
                        <?= Html::img('images/wait.gif')?>
                    </div>
<div class="form-group">
	<?php
    if($model->ypaymoney > 0)
        echo Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        
  </div>
<?php ActiveFormrdiv::end(); ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
<div id="collectionMsg" title="陈欠追缴"></div>
<script>

    $( "#collectionMsg" ).dialog({
        autoOpen: false,
        width: 1200,
        modal:true,
        closeOnEscape:false,
        open:function(event,ui){$(".ui-dialog-titlebar-close").hide();},
        buttons: [
            {
                text: "确定",
                click: function() {
                    $( this ).dialog( "close" );
                    var form = $('form').serializeArray();
                    console.log($.toJSON(form));
                    $.getJSON('index.php?r=collection/collectionsendsave',{'value':$.toJSON(form),'id':$('#collection-id').val()},function (data) {
                        console.log(data);
                        if(data.state ) {
                            location.href = "<?= Url::to(['collection/collectionfinished','farms_id'=>$farm->id])?>";
                        }
                    });
                }
            },
            {
                text: "取消",
                class:'btn btn-danger',
                click: function() {
                    $( this ).dialog( "close" );
                }
            }
        ]
    });
    function cq(id) {
        $.get('index.php?r=collection/collectionsendajax', {id: id}, function (body) {
            $('#collectionMsg').html(body);
            $("#collectionMsg").dialog("open");
        });
    }
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
    $( "#dialogWait" ).dialog( "open" );
    $.getJSON('index.php?r=collection/getar', {year: year,farms_id: <?= $_GET['farms_id'] ?>}, function (data) {
			if(data === 0) {
				alert(year+'年度没有缴费基数，请添加缴费基数再试。');
				$('#collection-payyear').val(<?= User::getYear()?>);
			} else {
				$.get('index.php?r=collection/collectionsend',{farms_id:<?= $_GET['farms_id']?>,year:year},function (data) {
					$('body').html(data);
				});
			}
    });
    
});

	
	$('#collection-measure').keyup(function(event){
        var input = $(this).val();
//        if(input == 0) {
//            alert('缴费面积不能为0。')
//            $(this).focus();
//            $('#collection-measure').val(<?//= $model->ypayarea?>//);
//            $('#collection-real_income_amount').val(<?//= $model->ypaymoney?>//);
//        } else {
            if(input > <?= $model->ypayarea?>) {
                alert('缴费面积不能超过剩余面积。')
                $(this).focus();
                $('#collection-measure').val(<?= $model->ypayarea?>);
                $('#collection-real_income_amount').val(<?= $model->ypaymoney?>);
            } else {
                $.getJSON('index.php?r=collection/getplantprice', {formyear: $('#collection-payyear').val()}, function (data) {
                    $('#collection-real_income_amount').val(input * data);
                });
            }
//        }
	});
	$('#collection-real_income_amount').keyup(function(event){
		input = $(this).val();
//        if(input == 0) {
//            alert('对不起，缴费金额不能为0。')
//            $(this).focus();
//            $('#collection-measure').val(<?//= $model->ypayarea?>//);
//            $('#collection-real_income_amount').val(<?//= $model->ypaymoney?>//);
//        } else {
            if(input > <?= $model->ypaymoney?>) {
                alert('缴费金额不能超过剩余金额。')
                $(this).focus();
                $('#collection-measure').val(<?= $model->ypayarea?>);
                $('#collection-real_income_amount').val(<?= $model->ypaymoney?>);
            } else {
                $.getJSON('index.php?r=collection/getplantprice', {formyear: $('#collection-payyear').val()}, function (data) {
                    var result = input / data;
                    $('#collection-measure').val(result.toFixed(2));
                });
            }
//        }
	});

    $('#collection-measure').blur(function(){
        var input = $(this).val();
        if(input == 0) {
            alert('缴费面积不能为0。')
            $(this).focus();
            $('#collection-measure').val(<?= $model->ypayarea?>);
            $('#collection-real_income_amount').val(<?= $model->ypaymoney?>);
        } else {
            if(input > <?= $model->ypayarea?>) {
                alert('缴费面积不能超过剩余面积。')
                $(this).focus();
                $('#collection-measure').val(<?= $model->ypayarea?>);
                $('#collection-real_income_amount').val(<?= $model->ypaymoney?>);
            } else {
                $.getJSON('index.php?r=collection/getplantprice', {formyear: $('#collection-payyear').val()}, function (data) {
                    $('#collection-real_income_amount').val(input * data);
                });
            }
        }
    });
    $('#collection-real_income_amount').blur(function(){
        input = $(this).val();
        if(input == 0) {
            alert('对不起，缴费金额不能为0。')
            $(this).focus();
            $('#collection-measure').val(<?= $model->ypayarea?>);
            $('#collection-real_income_amount').val(<?= $model->ypaymoney?>);
        } else {
            if(input > <?= $model->ypaymoney?>) {
                alert('缴费金额不能超过剩余金额。')
                $(this).focus();
                $('#collection-measure').val(<?= $model->ypayarea?>);
                $('#collection-real_income_amount').val(<?= $model->ypaymoney?>);
            } else {
                $.getJSON('index.php?r=collection/getplantprice', {formyear: $('#collection-payyear').val()}, function (data) {
                    var result = input / data;
                    $('#collection-measure').val(result.toFixed(2));
                });
            }
        }
    });
  // 实收金额判断
//  $('#collection-real_income_amount').blur(function() {
//
//    // 实收金额
//    var realPrice = parseFloat($(this).val());
//
//    // 应收金额
//    var amountsPrice = parseFloat($('#collection-amounts_receivable').val());
//
//    // 实收金额小于应收金额
//    if (realPrice > amountsPrice) {
//      alert('实收金额(' + realPrice + ')超过本年度应追缴金额(' + <?//= $model->getYpaymoney($year, $model->real_income_amount,$farm->id)?>// + ')');
//      $(this).focus();
//    }
//
//  });

//应收面积判断
//  $('#collection-measure').blur(function() {
//
//    // 实收面积
//    var realPrice = parseFloat($(this).val());
//
//    // 应收面积
//    var amountsPrice = parseFloat('<?//= $model->getYpayarea($year, $model->real_income_amount,$farm->id)?>//');
//
//    // 实收金额小于应收金额
//    if (realPrice > amountsPrice) {
//      alert('实收金额(' + realPrice + ')超过本年度应追缴金额(' + amountsPrice + ')');
//      $(this).focus();
//    }
//
//  });

});

</script>
<script>
    $( "#dialogWait" ).dialog({
        autoOpen: false,
        width: 300,
        open: function (event, ui) {
            $(".ui-dialog-titlebar-close", $(this).parent()).hide();
        }
    });
//    window.onbeforeunload=function (){
////	alert("===onbeforeunload===");
//        if(event.clientX>document.body.clientWidth && event.clientY < 0 || event.altKey){
////		alert("你关闭了浏览器");
//        }else{
//            $( "#dialogWait" ).dialog( "open" );
//        }
//    }
</script>