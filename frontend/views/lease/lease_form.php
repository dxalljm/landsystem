<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Farms;
use app\models\Plant;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Farmer;
use app\models\Parcel;
use app\models\Lease;


?>

<div class="lease-form">
<?php //$farms = Farms::find()->where(['id'=>$_GET['farms_id']])->one();?>
    <?php $form = ActiveFormrdiv::begin(); ?>
    <?= html::hiddenInput('farms_id',$_GET['farms_id'],['id'=>'farms_id'])?>
    
    <?= html::hiddenInput('measure',$noarea,['id'=>'measure']);?>

    <table class="table table-bordered table-hover">
  <tr>
    <td align="center">农场名称</td>
    <td colspan="2" align="center"><?= $farm->farmname?></td>
    <td align="center" >法人</td>
    <td align="center"><?= $farm->farmername?></td>
    <td align="center">宜农林地面积</td>
    <td colspan="2" align="center"><?= $farm->measure.'(已经租凭'.$overarea.')'?></td>
  </tr>
  <tr>
    <td colspan="8" align="center"><h4>承租人基础信息</h4></td>
  </tr>
  <tr>
    <td align="center">承租人姓名</td>
    <td colspan="7" align="center"><?= $form->field($model, 'lessee')->textInput()->label(false)->error(false) ?></td>
    </tr>
  <tr>
    <td align="center">身份证号</td>
    <td colspan="7" align="center"><?= $form->field($model, 'lessee_cardid')->textInput()->label(false)->error(false) ?></td>
    </tr>
  <tr>
    <td align="center">电话</td>
    <td colspan="7" align="center"><?= $form->field($model, 'lessee_telephone')->textInput()->label(false)->error(false) ?></td>
    </tr>
  <tr>
    <td align="center">住址</td>
    <td colspan="7" align="center"><?= $form->field($model, 'address')->textInput()->label(false)->error(false) ?></td>
  </tr>
  <tr>
    <td align="center">租赁面积</td><?php if($farm->measure == $overarea) $leaseareaValue = $farm->measure;else $leaseareaValue = $farm->measure - $overarea;?>
    <td colspan="7" align="center"><?php //= $form->field($model, 'lease_area')->textInput(['data-target' => '#myModal','data-toggle' => 'modal','data-keyboard' => 'false', 'data-backdrop' => 'static',])->label(false)->error(false) ?>
    <?= $form->field($model, 'lease_area')->textInput(['value'=>$leaseareaValue])->label(false)->error(false) ?></td>
    </tr>
  <tr>
    <td align="center">租赁期限</td>
    <td align="center">自</td>
    <td align="center"><?= $form->field($model, 'begindate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
        	'minView' => 3,
        	'maxView' => 3,
            'format' => 'yyyy-mm-dd'
        ]
]);?></td>
    <td align="center">止</td>
  </tr>
  <tr>
    <td align="center">租金</td>
    <td colspan="2" align="center"><?= $form->field($model, 'rent')->textInput()->label(false)->error(false) ?></td>
    <td align="center">租金交纳方式</td>
    <td colspan="4" align="center"><?= $form->field($model, 'rentpaymode')->textInput()->label(false)->error(false) ?></td>
    </tr>
  <tr>
    <td align="center">投保人</td>
    <td colspan="2" align="center"><?= $form->field($model, 'policyholder')->textInput()->label(false)->error(false) ?></td>
    <td align="center">被保险人</td>
    <td colspan="4" align="center"><?= $form->field($model, 'insured')->textInput()->label(false)->error(false) ?></td>
    </tr>
  <tr>
    <td align="center">惠农补贴归属</td>
    <td colspan="2" align="center"><?= $form->field($model, 'huinongascription')->textInput()->label(false)->error(false) ?></td>
    <td align="center">&nbsp;</td>
    <td colspan="4" align="center">&nbsp;</td>
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
$this->registerJsFile('js/vendor/bower/devbridge-autocomplete/dist/jquery.autocomplete.js', ['position' => View::POS_HEAD]);
$this->registerJsFile('js/lease.js', ['position' => View::POS_HEAD]);
?>
<script type="text/javascript">
$('#lease-lease_area').blur(function(){
	var input = $(this).val();
	//alert(input);
	var measure = <?= $leaseareaValue?>;
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
</script>



</div>
