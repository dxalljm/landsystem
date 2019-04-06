<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\ManagementArea;
use app\models\Farms;
use yii\helpers\ArrayHelper;
use app\models\Insurancecompany;
use app\models\Insurancedck;
use app\models\Insurance;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Insurance */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
.insuranceplan-form h2 {
	text-align: center;
}
</style>
<div class="insuranceplan-form">
<h2><?php echo date('Y');?>年种植业保险申请书</h2>
<?php $farm = Farms::find()->where(['id'=>$farms_id])->one();?>
  <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width="12%" align='right' valign="middle">农场名称</td>
<td align='left' valign="middle"><?= $farm['farmname']?></td>
<?php if(empty($model->policyholder)) $model->policyholder = $farm['farmername'];?>
<td align='right' valign="middle">法人姓名</td>
<td align='left' valign="middle"><?= $farm['farmername']?></td>
<?= Html::hiddenInput('tempcardid',$model->cardid,['id'=>'temp-cardid'])?>
<?= Html::hiddenInput('temptelephone',$model->telephone,['id'=>'temp-telephone'])?>
            <?php
            foreach ($plantArea as $value) {
                echo Html::hiddenInput($value['pinyin'],$value['value'],['id'=>'temp-'.$value['pinyin']]);
            }

            ?>
<?= Html::hiddenInput('tempinsuredarea',$insuredarea,['id'=>'temp-insuredarea'])?>
<td align='right' valign="middle">合同编号</td>
<td align='left' valign="middle"><?= $farm['contractnumber']?></td>
<td align='right' valign="middle">宜农林地面积</td><?php $lastarea = $farm['contractarea'] - Insurance::getOverArea($farms_id);?>
<td align='left' valign="middle"><?= $farm['contractarea']."(".$lastarea.")"?>
  亩</td>
</tr>
    <tr>
        <td align='right' valign="middle">被保险人姓名</td><?php echo Html::hiddenInput('overarea',Insurance::getOverArea($farms_id),['id'=>'OverArea'])?>
        <td width="12%" align='left' valign="middle"><?= $form->field($model, 'policyholder')->dropDownList($people)->label(false)->error(false) ?></td>
        <td align='center' valign="middle"><label><?php  echo Html::checkbox('Insuranceplan[isAll]',false,['id'=>'is-all']).'是否法人全保';?></label></td>
        <td align='right' valign="middle">被保险人身份证</td>
        <?php
        if(empty($model->cardid)) $model->cardid = $farm['cardid'];
        if(empty($model->telephone)) $model->telephone = $farm['telephone'];
        ?>
        <td colspan="2" align='left' valign="middle"><?= $form->field($model, 'cardid')->textInput(['maxlength' => 500])->label(false)?></td>
        <td align='right' valign="middle">联系电话</td>
        <td align='left' valign="middle"><?= $form->field($model, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    </tr>
    <?php
    if($farmerZZ) {
    ?>
    <tr>
    <?php if(isset($_GET['btn']) and $_GET['btn'] == 'first') {
    	$readonly = true;
    } else {
    	$readonly = false;
    }?>
  <td width=12% solspan="2" align='right' valign="middle">种植结构</td><?php if(!$model->contractarea) $model->contractarea = $farm->contractarea;?>
  <td width="12%" align='left' valign="middle"><?= $form->field($model, 'contractarea')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
        <td width="76%" colspan="6">
            <table width="100%" border="1" class="">
                <tr valign="middle">
                    <?php
                    foreach ($plantArea as $value) {
                        echo '<td style="vertical-align: middle;" align="right">'.$value['name'].':</td>';
                        echo '<td valign="middle">'.Html::textInput('Insuranceplan['.$value['pinyin'].']',$value['value'],['class'=>'form-control','readonly'=>true,'id'=>'plan-'.$value['pinyin']]).'</td>';
                    }
                    ?>
                </tr>
            </table>
        </td>
</tr>
<tr>
  <td width=12% align='right' valign="middle">保险面积</td><?php if(!$model->insuredarea) $model->insuredarea = $insuredarea;?>
  <td align='left' valign="middle"><?= $form->field($model, 'insuredarea')->textInput(['readonly'=>true])->label(false)->error(false) ?></td>
    <td width="76%" colspan="6">
        <table width="100%" border="1" class="">
            <tr valign="middle">
                <?php
                foreach ($plantArea as $value) {
                    echo '<td style="vertical-align: middle;" align="right">'.$value['name'].':</td>';
                    echo '<td valign="middle">'.Html::textInput('insured'.$value['pinyin'],$value['value'],['class'=>'form-control','readonly'=>true,'id'=>'insured-'.$value['pinyin']]).'</td>';
                }
                ?>
            </tr>
        </table>
    </td>
</tr>
<?php  }?>
</table>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>

    $('#is-all').click(function () {

        if($(this).is(":checked") == true) {
            $.getJSON('index.php?r=insurance/getall', {farms_id: <?= $farm['id']?>}, function (data) {
                console.log(data);
                $('#insuranceplan-cardid').val(data.cardid);
                $('#insuranceplan-telephone').val(data.telephone);
                $.each(data.plantArea,function (index,value) {
                    $('#plan-'+value['pinyin']).val(value['value']);
                    $('#insured-'+value['pinyin']).val(value['value']);
                });
                $('#insuranceplan-insuredarea').val(data.insuredarea);
                $('#insuranceplan-policyholder').html('');
                $('#insuranceplan-policyholder').append("<option value=0 selected><?= $farm['farmername']?></option>");
            });
        } else {
            var plantArea = <?= json_encode($plantArea)?>;
            var people = <?= json_encode($people)?>;
            $('#insuranceplan-cardid').val($('#temp-cardid').val());
            $('#insuranceplan-telephone').val($('#temp-telephone').val());
            $.each(plantArea,function (index,value) {
                $('#plan-'+value['pinyin']).val($('#temp-'+value['pinyin']).val());
                $('#insured-'+value['pinyin']).val($('#temp-'+value['pinyin']).val());
            });
            $('#insuranceplan-insuredarea').val($('#temp-insuredarea').val());
            $('#insuranceplan-policyholder').html('');
            $.each(people,function (i,v) {
                $('#insuranceplan-policyholder').append("<option value="+i+">"+v+"</option>");
            })
        }
    });

    $('#insuranceplan-policyholder').change(function(){
        if($(this).val() == 0) {
            $('#insuranceplan-cardid').val($('#temp-cardid').val());
            $('#insuranceplan-telephone').val($('#temp-telephone').val());
            var plantArea = <?= json_encode($plantArea)?>;

            $.each(plantArea,function (index,value) {
                $('#plan-'+value.pinyin).val($('#temp-'+value.pinyin).val());
            });
            $.each(plantArea,function (index,value) {
                $('#insured-'+value.pinyin).val($('#temp-'+value.pinyin).val());
            });
            $('#insuranceplan-insuredarea').val($('#temp-insuredarea').val());
        } else {
            $.getJSON('index.php?r=lease/getlessee', {lease_id: $(this).val()}, function (data) {
                console.log(data);
                $('#insuranceplan-cardid').val(data.cardid);
                $('#insuranceplan-telephone').val(data.telephone);
                $.each(data.plantArea,function (index,value) {
                    $('#plan-'+value.pinyin).val(value.value);
                });
                $.each(data.plantArea,function (index,value) {
                    $('#insured-'+value.pinyin).val(value.value);
                });
                $('#insuranceplan-insuredarea').val(data.insuredarea);
            });
        }
    });
    function radioListState()
    {
        var arr = new Array();
        var str = "<?= implode(',',Insurancedck::attributesKey())?>";
        arr = str.split(',');
        var state = false
        $.each(arr,function(){
            if ($('input:radio[name="iswt"]').prop('checked') == false) {
                state = true;
            } else {
                if(this != 'isoneself') {
                    if ($('input:radio[name="' + this + '"]').prop('checked') == false) {
                        state = true;
                    }
                }
            }
            if ($('input:radio[name="islease"]').prop('checked') == false) {
                state = true;
            } 
        });
        return state;
    }
    function radioCheck(name) {
        var state = false;
        if(name == 'isoneself') {
            if($('input:radio[name="isoneself"]:checked').val() == 0) {
//                $('#submitButton').attr('disabled',true);
                var html = '<tr id="tr-iswt"><td align="right"><div id="iswt-id" onclick=radioCheck("iswt")><label><input type="radio" name="iswt" value="1"> 是</label><label><input type="radio" name="iswt" value="0"> 否</label></div></td><td>提供委托书及委托人身份证</td></tr>';
                if($('#tr-iswt').val() == undefined)
                    $('#isTable tr:eq(0)').after(html);
//
            } else {
                $('#tr-iswt').remove();
            }
        }
        state = radioListState();
   	 	$('#submitButton').attr('disabled',state);
    }

//    $('#insuranceplan-cardid').change(function(){
//    	var cardid = "<?//= $farm['cardid']?>//";
//		if($(this).val() != cardid) {
//			 var html = '<tr id="tr-islease"><td><div id="iswt-id" onclick=radioCheck("islease")><label><input type="radio" name="islease" value="1"> 是</label> <label><input type="radio" name="islease" value="0"> 否</label></div></td><td>提供租赁合同或租赁协议书</td></tr>';
//			 if($('#tr-islease').val() == undefined)
//                 $('#isTable tr:eq(3)').after(html);
//		}
//    });
$('#insuranceplan-wheat').focus(function(){
	if($(this).val() == 0) {
		$(this).val('');
	}
});
$('#insuranceplan-wheat').blur(function(){
	if($(this).val() == '') {
		$(this).val(0);
		$('#insuranceplan-insuredwheat').val(0);
	}
});
$('#insuranceplan-soybean').focus(function(){
	if($(this).val() == 0)
		$(this).val('');
});
$('#insuranceplan-soybean').blur(function(){
	if($(this).val() == '') {
		$(this).val(0);
		$('#insuranceplan-insuredsoybean').val(0);
	}
});
$('#insuranceplan-other').focus(function(){
	if($(this).val() == 0)
		$(this).val('');
});
$('#insuranceplan-other').blur(function(){
	if($(this).val() == '') {
		$(this).val(0);
		$('#insuranceplan-insuredother').val(0);
	}
});

$('#insuranceplan-insuredwheat').focus(function(){
	if($(this).val() == 0)
		$(this).val('');
});
$('#insuranceplan-insuredwheat').blur(function(){
	if($(this).val() == '')
		$(this).val(0);
});
$('#insuranceplan-insuredsoybean').focus(function(){
	if($(this).val() == 0)
		$(this).val('');
});
$('#insuranceplan-insuredsoybean').blur(function(){
	if($(this).val() == '')
		$(this).val(0);
});
$('#insuranceplan-insuredother').focus(function(){
	if($(this).val() == 0)
		$(this).val('');
});
$('#insuranceplan-insuredother').blur(function(){
	if($(this).val() == '')
		$(this).val(0);
});
$('#insuranceplan-wheat').change(function(){
	var sum = $(this).val()*1 + $('#insuranceplan-soybean').val()*1 + $('#insuranceplan-other').val()*1;
    var area = $(this).val()*1 + $('#insuranceplan-soybean').val()*1;
	var ycontractarea = <?= $farm['contractarea']?>*1;
	var overarea = $('#OverArea').val()*1;
	if(overarea > 0.0)
		var contractarea = ycontractarea - overarea;
	else
		var contractarea = ycontractarea;
	if(sum > contractarea) {
		alert('对不起，已经超过当前农场总面积，请重新填写。');
		$('#insuranceplan-wheat').focus();
		$('#insuranceplan-wheat').val('');
        sum = $(this).val()*1 + $('#insuranceplan-soybean').val()*1 + $('#insuranceplan-other').val()*1;
	}
	$('#insuranceplan-insuredwheat').val($(this).val());
	$('#insuranceplan-contractarea').val(sum.toFixed(2));
    $('#insuranceplan-insuredarea').val(area.toFixed(2));
});


$('#insuranceplan-soybean').change(function(){
	var sum = $(this).val()*1 + $('#insuranceplan-wheat').val()*1 + $('#insuranceplan-other').val()*1;
    var area = $(this).val()*1 + $('#insuranceplan-wheat').val()*1;
	var ycontractarea = <?= $farm['contractarea']?>*1;
	var overarea = $('#OverArea').val()*1;
	if(overarea > 0.0)
		var contractarea = ycontractarea - overarea;
	else
		var contractarea = ycontractarea;
	if(sum > contractarea) {
		alert('对不起，已经超过当前农场总面积，请重新填写。');
		$('#insuranceplan-soybean').focus();
		$('#insuranceplan-soybean').val('');
        sum = $(this).val()*1 + $('#insuranceplan-wheat').val()*1;
	}
	$('#insuranceplan-insuredsoybean').val($(this).val());
	$('#insuranceplan-contractarea').val(sum.toFixed(2));
    $('#insuranceplan-insuredarea').val(area.toFixed(2));
});
$('#insuranceplan-other').change(function(){
	var sum = $(this).val()*1 + $('#insuranceplan-soybean').val()*1 + $('#insuranceplan-wheat').val()*1;
	var isum = $('#insuranceplan-soybean').val()*1 + $('#insuranceplan-wheat').val()*1;
	var ycontractarea = <?= $farm['contractarea']?>*1;
// 	var overarea = $('#OverArea').val()*1;
// 	if(overarea > 0.0)
// 		var contractarea = ycontractarea - overarea;
// 	else
// 		var contractarea = ycontractarea;
	if(sum > ycontractarea) {
		alert('对不起，已经超过当前农场总面积，请重新填写。');
		$('#insuranceplan-other').focus();
		$('#insuranceplan-other').val('');
        sum = $('#insuranceplan-soybean').val()*1 + $('#insuranceplan-wheat').val()*1;
	}
    $('#insuranceplan-insuredother').val($(this).val());
    $('#insuranceplan-contractarea').val(sum.toFixed(2));
    $('#insuranceplan-insuredarea').val(isum.toFixed(2));
});
$('#insuranceplan-insuredwheat').change(function(){
    var sum = $(this).val()*1 + $('#insuranceplan-insuredsoybean').val()*1;
    var ycontractarea = <?= $farm['contractarea']?>*1;
// 	var overarea = $('#OverArea').val()*1;
// 	if(overarea > 0.0)
// 		var contractarea = ycontractarea - overarea;
// 	else
// 		var contractarea = ycontractarea;
	if(sum > ycontractarea) {
        alert('对不起，已经超过当前种植结构面积，请重新填写。');
        $('#insuranceplan-insuredwheat').focus();
        $('#insuranceplan-insuredwheat').val('');
//         $('#insuranceplan-insuredsoybean').val('');
//         $('#insuranceplan-insuredother').val('');
        sum = $(this).val()*1 + $('#insuranceplan-insuredsoybean').val()*1;
    }
    $('#insuranceplan-insuredarea').val(sum.toFixed(2));
});
$('#insuranceplan-insuredsoybean').change(function(){
    var sum = $(this).val()*1 + $('#insuranceplan-insuredwheat').val()*1;

    var ycontractarea = <?= $farm['contractarea']?>*1;
// 	var overarea = $('#OverArea').val()*1;
// 	if(overarea > 0.0)
// 		var contractarea = ycontractarea - overarea;
// 	else
// 		var contractarea = ycontractarea;

    if(sum > ycontractarea) {
        alert('对不起，已经超过当前植结构面积，请重新填写。');
        $('#insuranceplan-insuredsoybean').focus();
//         $('#insuranceplan-insuredwheat').val('');
        $('#insuranceplan-insuredsoybean').val('');
//         $('#insuranceplan-insuredother').val('');
        sum = $(this).val()*1 + $('#insuranceplan-insuredwheat').val()*1;
    }
    $('#insuranceplan-insuredarea').val(sum.toFixed(2));
});

// $('#insuranceplan-insuredother').change(function(){
//     var sum = $(this).val()*1 + $('#insuranceplan-insuredsoybean').val()*1 + $('#insuranceplan-insuredwheat').val()*1;
    //var ycontractarea = <//= $farm['contractarea']?>*1;
// // 	var overarea = $('#OverArea').val()*1;
// // 	if(overarea > 0.0)
// // 		var contractarea = ycontractarea - overarea;
// // 	else
// // 		var contractarea = ycontractarea;
//     if(sum > ycontractarea) {
//         alert('对不起，已经超过当前植结构面积，请重新填写。');
//         $('#insuranceplan-insuredother').focus();
// //         $('#insuranceplan-insuredwheat').val('');
// //         $('#insuranceplan-insuredsoybean').val('');
//         $('#insuranceplan-insuredother').val('');
//         sum = $(this).val()*1 + $('#insuranceplan-insuredsoybean').val()*1 + $('#insuranceplan-insuredwheat').val()*1;
//     }
//     $('#insuranceplan-insuredarea').val(sum.toFixed(2));
// });
</script>