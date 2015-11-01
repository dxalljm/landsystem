<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Parcel;
use app\models\ManagementArea;
use app\models\Lease;
/* @var $this yii\web\View */
/* @var $model app\models\Farms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farms-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                       分户
                    </h3>
                </div>
                <div class="box-body">
    <?php $form = ActiveFormrdiv::begin(); ?>
  <table width="100%" border="0">
    <tr>
    <td width="47%"><table width="104%" height="458px"
		class="table table-bordered table-hover">
      <tr>
        <td width="15%" align='right' valign="middle">农场名称</td>
        <td align='left' valign="middle"><?= $oldFarm->farmname?></td>
      </tr>
      <tr>
        <td width="20%" align='right' valign="middle">承包人姓名</td>
        <td align='left' valign="middle"><?= $oldFarm->farmername ?></td>
      </tr>
      <tr>
        <td width="20%" align='right' valign="middle">身份证号</td>
        <td align='left' valign="middle"><?= $oldFarm->cardid ?></td>
      </tr>
      <tr>
        <td width="20%" align='right' valign="middle">电话号码</td>
        <td align='left' valign="middle"><?= $oldFarm->telephone ?></td>
      </tr>
      <tr>
        <td width="20%" align='right' valign="middle">农场位置</td>
        <td align='left' valign="middle"><?= $oldFarm->address?></td>
      </tr>
      <tr>
        <td width="20%" align='right' valign="middle">宗地</td>
        <td align='left' valign="middle"><?php $arrayZongdi = explode('、', $oldFarm->zongdi);
        $i=0;
        foreach($arrayZongdi as $value) {
        	echo html::button($value,['onclick'=>'toZongdi("'.$value.'","'.Lease::zongdiToArea($value).'")','value'=>$value,'id'=>$value,'class'=>"btn btn-default"]).'&nbsp;&nbsp;&nbsp;';
        	$i++;
        	if($i%5 == 0)
        		echo '<br><br>';
        }
        ?></td>
      </tr>
      <tr>
        <?= Html::hiddenInput('oldzongdi',$oldFarm->zongdi,['id'=>'oldfarm-zongdi']) ?>
        <?= Html::hiddenInput('ttpozongdi','',['id'=>'ttpozongdi-zongdi']) ?>
        <?= Html::hiddenInput('ttpoarea',0,['id'=>'ttpozongdi-area']) ?>
        <td align='right' valign="middle">面积</td>
        <td align='left' valign="middle"><?= html::textInput('oldmeasure',$oldFarm->measure,['readonly' => true,'id'=>'oldfarms-measure','class'=>'form-control']) ?></td>
      </tr>
      <tr>
        <td align='right' valign="middle">未明确地块</td>
        <td align='left' valign="middle"><?= html::textInput('oldnotclear',$oldFarm->notclear,['readonly' => true,'id'=>'oldfarms-notclear','class'=>'form-control']) ?></td>
      </tr>
      <tr>
        <td align='right' valign="middle">法人签字</td>
        <td align='left' valign="middle"><?= $oldFarm->farmersign ?></td>
      </tr>
    </table></td>
    <td width="4%" align="center"><font size="5"><i class="fa fa-arrow-right"></i></font></td>
    <td width="50%" valign="top"><table class="table table-bordered table-hover">
      <tr>
        <td width="25%" align='right'>农场名称</td>
        <td align='left'>
          <?= $form->field($model, 'farmname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
      </tr>
      <tr>
        <td width="25%" align='right'>承包人姓名</td>
        <td align='left'><?= $form->field($model, 'farmername')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
      </tr>
      <tr>
        <td width="25%" align='right'>身份证号</td>
        <td align='left'><?= $form->field($model, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
      </tr>
      <tr>
        <td align='right'>电话号码</td>
        <td align='left'><?= $form->field($model, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
      </tr>
      <tr>
        <td align='right'>宗地</td>
        <td align='left'><?= $form->field($model, 'zongdi')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
      </tr>
      <tr>
        <td align='right'>面积</td>
        <td align='left'><?= $form->field($model, 'measure')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
      </tr>
      <tr>
        <td align='right'>未明确地块</td>
        <td align='left'><?= $form->field($model, 'notclear')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
      </tr>
      <tr>
        <td align='right'>法人签字</td>
        <td align='left'><?= $form->field($model, 'farmersign')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
      </tr>
    </table></td>
  </tr>
</table>
<div class="form-group">
      <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      <?= Html::button('重置', ['class' => 'btn btn-primary','id'=>'reset']) ?>
      <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
</div>

    <?php ActiveFormrdiv::end(); ?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
function toZongdi(zongdi,area){
	$('#'+zongdi).attr('disabled',true);
	var value = $('#oldfarms-measure').val()*1-area*1;
	$('#oldfarms-measure').val(value.toFixed(2));
	var newvalue = $('#farms-measure').val()*1 + area*1;
	$('#farms-measure').val(newvalue.toFixed(2));
	var newzongdi = $('#farms-zongdi').val()+'、'+zongdi;
	var first = newzongdi.substr(0,1);
	if(first == '、') {
		$('#farms-zongdi').val(newzongdi.substring(1));
	}
	else {
		$('#farms-zongdi').val(newzongdi);
	}
	//$('#farms-zongdi').val(newzongdi);
	var oldzongdi = $('#oldfarm-zongdi').val();
	$('#oldfarm-zongdi').val(oldzongdi.replace(zongdi, ""));
	oldzongdistr = $('#oldfarm-zongdi').val();
	var first = oldzongdistr.substr(0,1);
	var last = oldzongdistr.substr(oldzongdistr.length-1,1);
	if(first == '、') {
		$('#oldfarm-zongdi').val(oldzongdistr.substring(1));
	}
	if(last == '、') {
		$('#oldfarm-zongdi').val(oldzongdistr.substring(0,oldzongdistr.length-1));
	}
	
	var ttpozongdi = $('#ttpozongdi-zongdi').val();
	ttpozongdi = ttpozongdi + '、' + zongdi;
	var first = ttpozongdi.substr(0,1);
	if(first == '、') {
		$('#ttpozongdi-zongdi').val(ttpozongdi.substring(1));
	}
	else {
		$('#ttpozongdi-zongdi').val(ttpozongdi);
	}
	
	var ttpoarea = $('#ttpozongdi-area').val();
	ttpoarea = area*1 + ttpoarea*1;
	$('#ttpozongdi-area').val(ttpoarea);
	
}
$('#reset').click(function() {
	 
    location.reload();

});
</script>

