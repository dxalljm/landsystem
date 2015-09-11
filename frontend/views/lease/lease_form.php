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
<?php $farms = Farms::find()->where(['id'=>$_GET['farms_id']])->one();?>
    <?php $form = ActiveFormrdiv::begin(); ?>
    <?= html::hiddenInput('farms_id',$_GET['farms_id'],['id'=>'farms_id'])?>
    
    <?= html::hiddenInput('measure',$noarea,['id'=>'measure']);?>

    <table class="table table-bordered table-hover">
  <tr>
    <td width="20%" align="center">农场名称</td>
    <td colspan="2" align="center" width="20%"><?= $farm->farmname?></td>
    <td align="center" width="10%">法人</td>
    <td colspan="2" align="center"><?= $farmer->farmername?></td>
    <td width="107" align="center">宜农林地面积</td>
    <td width="20%" align="center"><?= $farm->measure.'(已经租凭'.$overarea.')'?></td>
  </tr>
  <tr>
    <td colspan="8" align="center"><h4>承租人基础信息</h4></td>
  </tr>
  <tr>
    <td align="center">承租人姓名</td>
    <td colspan="6" align="center"><?= $form->field($model, 'lessee')->textInput(['value'=>$farmer->farmername])->label(false)->error(false) ?></td>
    <td rowspan="5" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">身份证号</td>
    <td colspan="6" align="center"><?= $form->field($model, 'lessee_cardid')->textInput(['value' =>$farmer->cardid])->label(false)->error(false) ?></td>
    </tr>
  <tr>
    <td align="center">电话</td>
    <td colspan="6" align="center"><?= $form->field($model, 'lessee_telephone')->textInput(['value'=>$farmer->telephone])->label(false)->error(false) ?></td>
    </tr>
  <tr>
    <td align="center">租赁面积</td>
    <td colspan="6" align="center"><?= $form->field($model, 'lease_area')->textInput(['data-target' => '#myModal','data-toggle' => 'modal','data-keyboard' => 'false', 'data-backdrop' => 'static',])->label(false)->error(false) ?></td>
    </tr>
  <tr>
    <td align="center">租赁期限</td>
    <td width="19" align="center">自</td>
    <td width="61" align="center"><?= $form->field($model, 'begindate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
    <td width="22" align="center">至</td>
    <td width="64" align="center"><?= $form->field($model, 'enddate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
    <td width="16" align="center">止</td>
    <td align="center">&nbsp;</td>
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
  </div>
    <?php ActiveFormrdiv::end(); ?>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               请选择宗地（面积），如所租赁地块不是整块，可修改面积数值。
            </h4>
         </div>
         <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed">
    
    	<tr>
    		<td align='center'>租赁面积（宗地）</td>
    	</tr>
    	<tr>
    		<td align='center'><?= Html::textInput('parcellist','',['id'=>'model-parcellist','class'=>'form-control'])?></td>

    	</tr>
    	<tr>
    		<td align='center'><?php 
			$zongdiarr = Lease::scanOverZongdi($_GET['farms_id']);
			//var_dump($zongdiarr);
    		foreach($zongdiarr as $value) {
    			echo html::button($value,['onclick'=>'toParcellist("'.$value.'")','value'=>$value,'id'=>'parcelbutton','class'=>"btn btn-default"]).'&nbsp;&nbsp;&nbsp;';
    		}
    		?></td>

    	</tr>
    </table>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">关闭
            </button>
            <button type="button" class="btn btn-primary" id="getParcellist" onclick="setLeasearea()">
               提交
            </button>
<script type="text/javascript">
function toParcellist(zdarea){
	if($('#model-parcellist').val() == '')
		$('#model-parcellist').val(zdarea);
	else {
		var value = $('#model-parcellist').val()+'、'+zdarea;
		$('#model-parcellist').val(value);
	}
}
function setLeasearea() {
	$('#myModal').modal('hide');
	if($('#lease-lease_area').val() == '')
		$('#lease-lease_area').val($('#model-parcellist').val());
	else {
		alert($('#lease-lease_area').val());
		var value = $('#lease-lease_area').val()+'、'+$('#model-parcellist').val();
		$('#lease-lease_area').val(value);	
	}	
}
</script>
         </div>
      </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>
<?php
    $script = <<<JS
jQuery('#years').change(function(){
    var year = $(this).val();
    $.get('/landsystem/frontend/web/index.php?r=collection/collectionindex',{year:year},function (data) {
              $('body').html(data);
            });
});
JS;
$this->registerJs($script);

$this->registerJsFile('js/vendor/bower/devbridge-autocomplete/dist/jquery.autocomplete.js', ['position' => View::POS_HEAD]);
$this->registerJsFile('js/lease.js', ['position' => View::POS_HEAD]);
?>
<script type="text/javascript">

function setFarmsid(id)
{
    $('#lease-farms_id').val(id);
}
</script>



</div>
