<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Firepreventionemployee;
/* @var $this yii\web\View */
/* @var $model app\models\Fireprevention */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fireprevention-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
    <?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
<table width="57%" class="table table-striped table-bordered table-hover table-condensed">
		<tr>

<td colspan="3" align='center'><h4>防火、安全、环保合同</h4></td>

<td colspan="7" align='center' valign="middle"><h4>防火设施及火源管理</h4></td>
</tr>

<tr>

<td width=8% align='center'>文火合同</td>

<td width="11%" align='center'>安全生产<br />
  合同</td>
<td width="10%" align='center'>环境保护<br />
  协议</td>
<td width="8%" align='center'>扑火工具</td>
<td width="9%" align='center'>机械设备<br />
  防火罩</td>
<td width="8%" align='center'>烟囱<br />
  防火罩</td>
<td width="9%" align='center'>房屋防火<br />
  隔离带</td>
<td width="9%" align='center'>防火义务<br />
  宣管员</td>
<td width="11%" align='center'>一盒火<br />
  管理员</td>
<td width="17%" align='center'>液化气<br />
  灶具</td>

</tr>

<tr>

<td width=8% align='center'><?php viewModel($model->firecontract);?></td>
<td align='center'><?php if($model->safecontract == '') $model->safecontract = 0;?><?= $form->field($model, 'safecontract')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->environmental_agreement == '') $model->environmental_agreement = 0;?><?= $form->field($model, 'environmental_agreement')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->firetools == '') $model->firetools = 0;?><?= $form->field($model, 'firetools')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->mechanical_fire_cover == '') $model->mechanical_fire_cover = 0;?><?= $form->field($model, 'mechanical_fire_cover')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->chimney_fire_cover == '') $model->chimney_fire_cover = 0;?><?= $form->field($model, 'chimney_fire_cover')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->isolation_belt == '') $model->isolation_belt = 0;?><?= $form->field($model, 'isolation_belt')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->propagandist == '') $model->propagandist = 0;?><?= $form->field($model, 'propagandist')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->fire_administrator == '') $model->fire_administrator = 0;?><?= $form->field($model, 'fire_administrator')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->cooker == '') $model->cooker = 0;?><?= $form->field($model, 'cooker')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>

</tr>

<tr>

<td colspan="5" align='center'><h4>农场防火宣传栏</h4></td>
<td colspan="3" align='center'><h4>防火宣传检查现场取景照</h4></td>
<td align='left'>&nbsp;</td>
<td align='left'>&nbsp;</td>

</tr>

<tr>

<td width=8% align='center'>野外作业许可证</td>
<td align='center'>防火合同</td>
<td align='center'>防火宣传单</td>
<td align='center'>雇工防火合同</td>
<td align='center'>防火检查整改记录</td>
<td align='center'>设备照片</td>
<td align='center'>人员照片</td>
<td align='center'>设施照片</td>
<td align='left'>&nbsp;</td>
<td align='left'>&nbsp;</td>

</tr>

<tr>

<td width=8% align='center'><?php if($model->fieldpermit == '') $model->fieldpermit = 0;?><?= $form->field($model, 'fieldpermit')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->propaganda_firecontract == '') $model->propaganda_firecontract = 0;?><?= $form->field($model, 'propaganda_firecontract')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->leaflets == '') $model->leaflets = 0;?><?= $form->field($model, 'leaflets')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->employee_firecontract == '') $model->employee_firecontract = 0;?><?= $form->field($model, 'employee_firecontract')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->rectification_record == '') $model->rectification_record = 0;?><?= $form->field($model, 'rectification_record')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->equipmentpic == '') $model->equipmentpic = 0;?><?= $form->field($model, 'equipmentpic')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->peoplepic == '') $model->peoplepic = 0;?><?= $form->field($model, 'peoplepic')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?php if($model->facilitiespic == '') $model->facilitiespic = 0;?><?= $form->field($model, 'facilitiespic')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='left'>&nbsp;</td>
<td align='left'>&nbsp;</td>

</tr>
</table>
<table width="57%" class="table table-striped table-bordered table-hover table-condensed">
<tr>

<td colspan="6" align='center'><h3>农场雇工登记</h3></td>

</tr>

<tr>

<td width=8% align='center'>雇工期限</td>

<td align='center'>雇工姓名</td>
<td align='center'>身份证号</td>
<td align='center'>是否吸烟</td>
<td align='center'>智障人员</td>



</tr>
<?php 

$i=0;
foreach($employees as $emp) {
	foreach($emp as $val) {
		$efire = Firepreventionemployee::find()->where(['employee_id'=>$val['id']])->one();
	?>
<tr>
<?php if(!empty($efire)) $value = $efire['id'];else $value = '';?>
<?php echo Html::hiddenInput('ArrEmployeesFire[id][]',$value); ?>
<?php if(!empty($efire)) $value = $efire['employee_id'];else $value = $val['id'];?>
<?php echo Html::hiddenInput('ArrEmployeesFire[employee_id][]', $value); ?>
<td width=8% align='center'><?= $val['employeetype'] ?></td>
<td align='center'><?= $val['employeename'] ?></td>
<td align='center'><?= $val['cardid'] ?></td>
<?php if(!empty($efire)) $value = $efire['is_smoking'];else $value = 0;?>
<td align='center'><?php echo Html::radioList('ArrEmployeesFire[is_smoking]['.$i.']', $value, [1=>'是',0=>'否']); ?></td>
<?php if(!empty($efire)) $value = $efire['is_retarded'];else $value = 0;?>
<td align='center'><?php echo Html::radioList('ArrEmployeesFire[is_retarded]['.$i.']', $value, [1=>'是',0=>'否']); ?></td>
</tr>
<?php $i++;}}?>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>
<?php function viewModel($modelname) {
	if($modelname == 0)
		echo '否';
	else 
		echo '是';
}?>
</div>
