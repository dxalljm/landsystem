<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\Firepreventionemployee;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $model app\models\Fireprevention */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fireprevention-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
    <?php $form = ActiveFormrdiv::begin(); ?>
    <h2><?= Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'] ?></h2>
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
<td align='center'><?php viewModel($model->safecontract);?></td>
<td align='center'><?php viewModel($model->environmental_agreement);?></td>
<td align='center'><?php viewModel($model->firetools);?></td>
<td align='center'><?php viewModel($model->mechanical_fire_cover); ?></td>
<td align='center'><?php viewModel($model->chimney_fire_cover); ?></td>
<td align='center'><?php viewModel($model->isolation_belt); ?></td>
<td align='center'><?php viewModel($model->propagandist); ?></td>
<td align='center'><?php viewModel($model->fire_administrator);?></td>
<td align='center'><?php viewModel($model->cooker);?></td>

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

<td width=8% align='center'><?php viewModel($model->fieldpermit);?></td>
<td align='center'><?php viewModel($model->propaganda_firecontract);?></td>
<td align='center'><?php viewModel($model->leaflets); ?></td>
<td align='center'><?php viewModel($model->employee_firecontract);?></td>
<td align='center'><?php viewModel($model->rectification_record);?></td>
<td align='center'><?php viewModel($model->equipmentpic);?></td>
<td align='center'><?php viewModel($model->peoplepic);?></td>
<td align='center'><?php viewModel($model->facilitiespic);?></td>
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
foreach($employees as $emp) {
	foreach($emp as $val) {
		$efire = Firepreventionemployee::find()->where(['employee_id'=>$val['id']])->one();
	?>
<tr>

<td width=8% align='center'><?= $val['employeetype'] ?></td>
<td align='center'><?= $val['employeename'] ?></td>
<td align='center'><?= $val['cardid'] ?></td>
<td align='center'><?php viewModel($efire['is_smoking']); ?></td>
<td align='center'><?php viewModel($efire['is_retarded']); ?></td>
</tr>
<?php }}?>
</table>

<?= Html::a('返回', Yii::$app->getRequest()->getReferrer(), ['class' => 'btn btn-success'])?>
    <?php ActiveFormrdiv::end(); ?>
<?php function viewModel($modelname) {
	if($modelname == 0)
		echo '否';
	else 
		echo '是';
}?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
