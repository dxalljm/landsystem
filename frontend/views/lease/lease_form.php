<?php
use dosamigos\datetimepicker\DateTimePicker;
use yii\web\View;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Farms;
use app\models\Plant;

use app\models\Farmer;
use app\models\Parcel;
use app\models\Lease;


?>
<style>
    #lease-farmerzb{
        display: none;
    }
    #lease-lesseezb {
        display: none;
    }
</style>
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<div class="lease-form">
<?php //$farms = Farms::find()->where(['id'=>$_GET['farms_id']])->one();?>
    <?php $form = ActiveFormrdiv::begin(); ?>
    <?= html::hiddenInput('farms_id',$_GET['farms_id'],['id'=>'farms_id'])?>
    
    <?= html::hiddenInput('measure',$noarea,['id'=>'measure']);?>

    <table class="table table-bordered table-hover">
  <tr>
    <td width="3%" align="right"><strong>农场名称</strong></td>
    <td colspan="2" align="left"><?= $farm->farmname?></td>
    <td width="" align="right" ><strong>法人</strong></td>
    <td width="" align="left"><?= $farm->farmername?></td>
    <td width="" align="right"><strong>宜农林地面积</strong></td>
    <td colspan="2" align="left"><?= $farm->contractarea.'(已经租凭'.$overarea.')'?></td>
  </tr>
  <tr>
    <td colspan="8" align="center"><h4>承租人基础信息</h4></td>
  </tr>
  <tr>
    <td width="8%" align="right"><strong>身份证号</strong></td>
    <td width=""><?= $form->field($model, 'lessee_cardid')->textInput()->label(false) ?></td>
      <td align="right" width="20%"><strong>承租人姓名</strong></td>
      <td width="15%" align="center"><?= $form->field($model, 'lessee')->textInput()->label(false) ?></td>
    <td align="right"><strong>电话</strong></td>
    <td colspan="3" align="center"><?= $form->field($model, 'lessee_telephone')->textInput()->label(false)->error(false) ?></td>
  </tr>
  <tr>
     <td align="right" width="20%"><strong>所属银行</strong></td><?php $bankModel->bank = '大兴安岭农村商业银行';?>
     <td width="15%" align="center" ><?= $form->field($bankModel, 'bank')->textInput(['readonly'=>true])->label(false) ?></td>
     <td width="8%" align="right" colspan="2"><strong>银行卡号</strong></td>
     <td width="" colspan="4"><?= $form->field($bankModel, 'accountnumber')->textInput()->label(false) ?></td>
  </tr>
  <tr>
    <td align="right"><strong>住址</strong></td>
    <td colspan="7" align="center"><?= $form->field($model, 'address')->textInput()->label(false)->error(false) ?></td>
    </tr>
  <tr><?php if($model->lease_area == '') $model->lease_area = $farm->contractarea - $overarea;?>
    <td align="right"><strong>租赁面积</strong></td>
    <td align="center"><?= $form->field($model, 'lease_area')->textInput()->label(false)->error(false) ?></td>
    <td align="right"><strong>租赁期限</strong></td>
      <?php
        if($model->begindate == '') {
            $model->begindate = $year.'-01-01';
        }
      if($model->enddate == '') {
          $model->enddate = $year.'-12-31';
      }
      ?>
    <td align="center"><?= $form->field($model, 'begindate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 2,
        	'maxView' => 43,
            'format' => 'yyyy-mm-dd'
        ]
]);?></td>
    <td align="center">至</td>
    <td colspan="3" align="center"><?= $form->field($model, 'enddate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 2,
        	'maxView' => 4,
            'format' => 'yyyy-mm-dd'
        ]
]);?></td>
    </tr>
  <tr>
    <td align="right"><strong>租金（万元）</strong></td>
    <td colspan="1" align="center"><?= $form->field($model, 'rent')->textInput(['placeholder'=>"万元"])->label(false)->error(false) ?></td>
    <td align="right"><strong>租金交纳方式</strong></td>
    <td colspan="2" align="center"><?= $form->field($model, 'rentpaymode')->dropDownlist(['现金'=>'现金','转账'=>'转账'])->label(false)->error(false) ?></td>
    <td align="right"><strong>支付时间</strong></td><?php if(empty($model->renttime)) $model->renttime = date('Y-m-d'); else $model->renttime = date('Y-m--d',$model->renttime);?>
    <td colspan="2" align="center"><?= $form->field($model, 'renttime')->textInput()->label(false)->widget(
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
  </tr>
  <tr>
  
    <td colspan="1" align="right"><strong>租金交付方式</strong></td>
    <td colspan="7" align="left"><?= $form->field($model, 'renttype')->checkboxList(['现场指认地界'=>'现场指认地界','现场测量面积'=>'现场测量面积','签订交付收据'=>'签订交付收据','其他'=>'其他'],['prompt'=>'请选择...'])->label(false)->error(false) ?></td>
  </tr>
        <tr>
            <td colspan="1" align="right"><strong>保险情况</strong></td>
            <td align="left" colspan="7"><label><strong><?= Html::checkbox('Lease[isinsurance]',$isinsurance)?>是否参加保险</strong></label></td>
        </tr>
        <tr>
            <td colspan="1" align="right"><strong>补贴分配比率</strong></td>
            <td colspan="6" align="left">
                <table border="1">
                    <?php
//                    var_dump($model);
                    $sub = \app\models\Subsidyratio::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>$model->id])->all();
                    if($sub) {
                        foreach($sub as $key => $value) {
                            $type = \app\models\Subsidytypetofarm::find()->where(['id'=>$value['typeid']])->one();
                            echo '<tr>';
                            echo '<td align="right" style="vertical-align: middle;">';
                            echo $type['typename'].':&nbsp;&nbsp;&nbsp;&nbsp;';
                            echo '</td>';
                            echo '<td  style="vertical-align: middle;">';
                            echo '法人占比:';
                            echo '</td>';
                            echo '<td  style="vertical-align: middle;">';
                            echo Html::textInput('Subsidyratio['.$type['mark'].'-farmer]',$value['farmer'],['class'=>'form-control','id'=>$type['mark'].'_farmer']);
                            echo '</td>';
                            echo '<td  style="vertical-align: middle;">';
                            echo '承租人占比:';
                            echo '</td>';
                            echo '<td  style="vertical-align: middle;">';
                            echo Html::textInput('Subsidyratio['.$type['mark'].'-lessee]',$value['lessee'],['class'=>'form-control','id'=>$type['mark'].'_lessee']);
                            echo '</td>';
                            echo '</tr>';
                            echo '<script>';
                            echo '$("#'.$type['mark'].'_farmer").change(function(){var input = $(this).val();if(input>100) { alert("对不起,不能输入超过100的数值1。"); $(this).val("100%");} else {$(this).val(input+"%");getUNBFB("'.$type['mark'].'_lessee",input);}});';
                            echo '$("#'.$type['mark'].'_lessee").change(function(){var input = $(this).val();if(input>100) { alert("对不起,不能输入超过100的数值2。"); $(this).val("100%");} else {$(this).val(input+"%");getUNBFB("'.$type['mark'].'_farmer",input);}});';
                            echo '</script>';
                        }
                    } else {
                        foreach (\app\models\Subsidytypetofarm::find()->all() as $key => $value) {
                            echo '<tr>';
                            echo '<td align="right">';
                            echo $value['typename'] . ':&nbsp;&nbsp;&nbsp;&nbsp;';
                            echo '</td>';
                            echo '<td>';
                            echo '法人占比:';
                            echo '</td>';
                            echo '<td>';
                            echo Html::textInput('Subsidyratio[' . $value['mark'] . '-farmer]', '100%', ['class' => 'form-control', 'id' => $value['mark'] . '_farmer']);
                            echo '</td>';
                            echo '<td>';
                            echo '承租人占比:';
                            echo '</td>';
                            echo '<td>';
                            echo Html::textInput('Subsidyratio[' . $value['mark'] . '-lessee]', '0%', ['class' => 'form-control', 'id' => $value['mark'] . '_lessee']);
                            echo '</td>';
                            echo '</tr>';
                            echo '<script>';
                            echo '$("#' . $value['mark'] . '_farmer").click(function(){var input = $(this).val();if(input == "100%") $(this).val("");});';
                            echo '$("#' . $value['mark'] . '_farmer").blur(function(){var input = $(this).val();if(input == "") $(this).val("100%");});';
                            echo '$("#' . $value['mark'] . '_farmer").change(function(){var input = $(this).val();if(Number(input)) {if(input>100) { alert("对不起,不能输入超过100的数值1。"); $(this).val("100%");$("#'.$value['mark'] . '_lessee").val("0%");} else {$(this).val(input+"%");getUNBFB("' . $value['mark'] . '_lessee",input);}} else {alert("只能输入数字。");$(this).val("100%");}});';
                            echo '$("#' . $value['mark'] . '_lessee").click(function(){var input = $(this).val();if(input == "0%") $(this).val("");});';
                            echo '$("#' . $value['mark'] . '_lessee").blur(function(){var input = $(this).val();if(input == "") $(this).val("0%");});';
                            echo '$("#' . $value['mark'] . '_lessee").change(function(){var input = $(this).val();if(Number(input)) {if(input>100) { alert("对不起,不能输入超过100的数值2。"); $(this).val("100%");} else {$(this).val(input+"%");getUNBFB("' . $value['mark'] . '_farmer",input);}} else {alert("只能输入数字。");$(this).val("0%");}});';
                            echo '</script>';
                        }
                    }?>
                </table>
            </td>
        </tr>
  <tr>
    <td colspan="1" align="right"><strong>其他约定</strong></td>
    <td colspan="7" align="left"><?= $form->field($model, 'otherassumpsit')->textarea(['rows'=>5])->label(false)->error(false) ?></td>
  </tr>
  </table>
<datalist id="parcellist">
<?php $parcel = explode('、', $farm->zongdi);
	foreach($parcel as $val) {
?>
    <option label="宗地号（亩）" value="<?= $val.'('.Parcel::find()->where(['unifiedserialnumber'=>$val])->one()['grossarea'].')'?>" />
<?php }?>
</datalist>
<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['onclick'=>'setFarmsid('.$_GET['farms_id'].')','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('返回', [Yii::$app->controller->id.'index','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
  </div>
    <?php ActiveFormrdiv::end(); ?>

</div>
<?php
//$this->registerJsFile('js/vendor/bower/devbridge-autocomplete/dist/jquery.autocomplete.js', ['position' => View::POS_HEAD]);
//$this->registerJsFile('js/lease.js', ['position' => View::POS_HEAD]);
?>
<script type="text/javascript">
$('#lease-lease_area').blur(function(){
	var input = $(this).val();
	//alert(input);
	var measure = <?= $model->lease_area?>;
	if(input > measure) {
		alert('输入的面积不能大于当前农场总面积'+measure+'亩');
		$('#model-parcellist').focus();
	}
});
function setFarmsid(id)
{
    $('#lease-farms_id').val(id);
}
// $('#model-parcellist').blur(function(){
// 	var input = $(this).val();
// 	$.getJSON('/landsystem/frontend/web/index.php?r=lease/getarea',{zongdiarea:input},function (data) {
		//var measure = <?//= $farm->measure?>;
//         if(data.area > measure) {
// 			alert('输入的面积不能大于地块面积  '+measure);
// 			$('#model-parcellist').val(data.zongdi + '(' + measure +')');
//         }
//     });
// });
$('#lease-lessee').blur(function () {
    var input = $(this).val();
    if(input == '') {
//         alert($('#lease-policyholder').length);
    	if($('#lease-policyholder').length > 1) {
	    	$('#lease-policyholder option:last').remove();
		    $('#lease-insured option:last').remove();
    	}
    } else {
	    $('#lease-policyholder').append('<option value="'+input+'" selected>'+input+'</option>');
	    $('#lease-insured').append('<option value="'+input+'" selected>'+input+'</option>');
    }
})
$('#lease-huinongascription').change(function () {
    var input = $(this).val();
    if(input == 'proportion') {
        $('#farmer').append('法人占比');
        $('#lease-farmerzb').show();
        $('#lessee').append('承租人占比');
        $('#lease-lesseezb').show();
    } else {
        $('#farmer').html(null);
        $('#lease-farmerzb').hide();
        $('#lessee').html(null);
        $('#lease-lesseezb').hide();
    }
})
$('#lease-farmerzb').blur(function () {
    var input = $(this).val();
    var num = input.substr(0,2);
    $('#lease-lesseezb').val(100-num);
})
$('#lease-lesseezb').blur(function () {
    var input = $(this).val();
    var num = input.substr(0,2);
    $('#lease-farmerzb').val(100-num);
})
$(document).ready(function(){
    if($('#lease-lessee').val() !== '') {
        var input = $('#lease-lessee').val();
        $('#lease-policyholder').append('<option value="'+input+'" selected>'+input+'</option>');
        $('#lease-insured').append('<option value="'+input+'" selected>'+input+'</option>');
    }
    if($('#lease-huinongascription').val() == 'proportion') {
        $('#farmer').append('法人占比');
        $('#lease-farmerzb').show();
        $('#lessee').append('承租人占比');
        $('#lease-lesseezb').show();
    } else {
        $('#farmer').html(null);
        $('#lease-farmerzb').hide();
        $('#lessee').html(null);
        $('#lease-lesseezb').hide();
    }
});

//$(function () {
//    $("[data-mask]").inputmask();
//});
function getUNBFB(id,str) {
//    var bfb = {'100%':'0%','90%':'10%','80%':'20%','70%':'30%','60%':'40%','50%':'50%','40%':'60%','30%':'70%','20%':'80%','10%':'90%','0%':'100%'};
//    var reg = /\d+/g;
    var reg = /^[0-9]+(.[0-9]{1,2})?$/;
    var ms = str.match(reg);
    var bfb = 100 - ms[0];
    $("#"+id).val(bfb+'%');
}
$(function () {
    //Money Euro
    $("[data-mask]").inputmask();
});
    $('#lease-lessee_cardid').change(function () {
        $.getJSON('index.php?r=bankaccount/scancardid',{cardid:$(this).val()},function (data) {
            console.log(data);
            if(data.state) {
                $('#bankaccount-accountnumber').val(data.number);
            }
        });

        $.getJSON('index.php?r=lease/scancardid',{cardid:$(this).val()},function (data) {
            if(data.state) {
                $('#lease-lessee').val(data.info.lessee);
                $('#lease-lessee_telephone').val(data.info.telephone);
                $('#lease-address').val(data.info.address);
            }
        });
    });
</script>



</div>
