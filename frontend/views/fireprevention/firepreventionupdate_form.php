<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;

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

<td width=8% align='center'><?= $form->field($model, 'firecontract')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'safecontract')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'environmental_agreement')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'firetools')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'mechanical_fire_cover')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'chimney_fire_cover')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'isolation_belt')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'propagandist')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'fire_administrator')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'cooker')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>

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

<td width=8% align='center'><?= $form->field($model, 'fieldpermit')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'propaganda_firecontract')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'leaflets')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'employee_firecontract')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'rectification_record')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'equipmentpic')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'peoplepic')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
<td align='center'><?= $form->field($model, 'facilitiespic')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
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
		$i++;
	?>
<tr>
<?php echo Html::hiddenInput('ArrEmployeesFire[employees_id][]', $val['id']); ?>
<td width=8% align='center'><?= $val['employeetype'] ?></td>

<td align='center'><?= $val['employeename'] ?></td>
<td align='center'><?= $val['cardid'] ?></td>
<td align='center'><?php echo Html::radioList('ArrEmployeesFire[is_smoking]['.$i.']', $val['is_smoking'], [1=>'是',0=>'否']); ?></td>
<td align='center'><?php echo Html::radioList('ArrEmployeesFire[is_retarded]['.$i.']', $val['is_retarded'], [1=>'是',0=>'否']); ?></td>



</tr>
<?php }}?>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
