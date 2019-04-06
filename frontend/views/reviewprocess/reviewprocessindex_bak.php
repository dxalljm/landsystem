<?php

namespace frontend\controllers;
use app\models\Ttpozongdi;
use dosamigos\datetimepicker\DateTimePicker;
use Yii;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Reviewprocess;
use app\models\Auditprocess;
use app\models\Processname;
use app\models\User;
use app\models\Projectapplication;
use frontend\helpers\ActiveFormrdiv;
use app\models\Infrastructuretype;
use app\models\Tempauditing;
use app\models\Loan;
use app\models\ManagementArea;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ReviewprocessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'reviewprocess';
$this->title = Tables::find ()->where ( [ 
		'tablename' => $this->title 
] )->one ()['Ctablename'];
$this->params ['breadcrumbs'] [] = $this->title;
//$data = Reviewprocess::find()->where(['management_area'=>[1,2,3,4,5,6,7]]);
//var_dump($data->createCommand()->getRawSql());
//var_dump($farmstransfer);exit;

?>
<div class="reviewprocess-index">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"><?= $title?></h3>
						<?php
						if(Tempauditing::is_tempauditing()) echo Html::a('申请延长时间', ['tempauditing/tempauditingextend','id'=>Tempauditing::is_tempauditing()], ['class' => 'btn btn-success','id'=>'extendDate','data' => [
			                'confirm' => '您确定要延长授权时间吗？',
			                'method' => 'post',
			            ]]).'结束日期:'.Date('Y年m月d日',Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->one()['enddate']).'<font color="red"> 注：每次申请可延长3天时间。</font>'; ?>
						
					</div>
					<div class="box-body">
						<?php $form = ActiveFormrdiv::begin(['method'=>'get']); ?>
						<?php
						?>
						<table class="table table-hover">
							<tr>
								<td align="right">自</td>
								<td><?php echo DateTimePicker::widget([
										'name' => 'begindate',
										'language' => 'zh-CN',
										'value' => date('Y-m-d',$begindate),
										'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
										'options' => [
											'readonly' => true
										],
										'clientOptions' => [

                                       'format' => 'yyyy-mm-dd',
											'todayHighlight' => true,
											'autoclose' => true,
                                       'minView' => 2,
                                       'maxView' => 4,
										]
									]);?></td>
								<td>至</td>
								<td><?php echo DateTimePicker::widget([
										'name' => 'enddate',
										'language' => 'zh-CN',
										'value' => date('Y-m-d',$enddate),
										'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
										//'type' => DatePicker::TYPE_COMPONENT_APPEND,
										'options' => [
											'readonly' => true
										],
										'clientOptions' => [
											'language' => 'zh-CN',
                                       'format' => 'yyyy-mm-dd',
											'todayHighlight' => true,
											'autoclose' => true,
                                       'minView' => 2,
                                       'maxView' => 4,
										]
									]);?></td>
								<td>止</td>
								<td><?= html::submitButton('查询',['class'=>'btn btn-success','id'=>'searchButton'])?>&nbsp;<?= html::a('今天',Url::to(['reviewprocess/'.Yii::$app->controller->action->id,'begindate'=>date('Y-m-d'),'enddate'=>date('Y-m-d')]),['class'=>'btn btn-success','id'=>'searchDay'])?></td>
							</tr>
						</table>
						<?php ActiveFormrdiv::end(); ?>
    <?php
	ActiveFormrdiv::begin();
//     var_dump(Auditprocess::isAuditing('承包经营权转让'));
    if(Auditprocess::isAuditing('承包经营权转让')) {
    if($farmstransfer) {
// 		var_dump($farmstransfer);
		?>
    <h3>宜农林地承包经营权转让审批</h3>
						<table class="table table-bordered table-hover">
							<tr height="40px">
								<td align="center" width="60"><strong>序号</strong></td>
								<td align="center"><strong>管理区</strong></td>
								<td align="center"><strong>原农场名称</strong></td>
								<td align="center"><strong>原法人</strong></td>
								<td align="center"><strong>原面积</strong></td>
								<td align="center"><strong>现农场名称</strong></td>
								<td align="center"><strong>现法人</strong></td>
								<td align="center"><strong>现面积</strong></td>
								<td align="center" width="150"><strong>申请日期</strong></td>
								<td align="center" width="150"><strong>状态</strong></td>
								<td align="center" width="150"><strong>操作</strong></td>
							</tr>
							<?php
// 							var_dump($params);
							if($params) {
								$managementAreaValue = $params['management_area'];
								$yfarmname = $params['yfarmname'];
								$yfarmername = $params['yfarmername'];
								$ycontractarea = $params['ycontractarea'];
								$xfarmname = $params['xfarmname'];
								$xfarmername = $params['xfarmername'];
								$xcontractarea = $params['xcontractarea'];
							}
							else {
								$managementAreaValue = 0;
								$yfarmname = '';
								$yfarmername = '';
								$ycontractarea = '';
								$xfarmname = '';
								$xfarmername = '';
								$xcontractarea = '';
							}
							?>
							<tr height="40px">
								<td align="center"></td>
								<td align="center"><?= Html::dropDownList('management_area',$managementAreaValue,Farms::managementAreaDropDownList()['areaname'],['class'=>'form-control','id'=>'managementArea'])?></td>
								<td align="center"><?= Html::textInput('yfarmname',$yfarmname,['class'=>'form-control'])?></td>
								<td align="center"><?= Html::textInput('yfarmername',$yfarmername,['class'=>'form-control'])?></td>
								<td align="center"><?= Html::textInput('ycontractarea',$ycontractarea,['class'=>'form-control'])?></td>
								<td align="center"><?= Html::textInput('xfarmname',$xfarmname,['class'=>'form-control'])?></td>
								<td align="center"><?= Html::textInput('xfarmername',$xfarmername,['class'=>'form-control'])?></td>
								<td align="center"><?= Html::textInput('xcontractarea',$xcontractarea,['class'=>'form-control'])?></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="center"></td>
							</tr>
							<?php 
// 							var_dump($farmstransfer);exit;
							$show = true;
							$i = 1;
							foreach ($farmstransfer as $value) {
//								var_dump($value);exit;
								$newfarm = Farms::find()->where(['id'=>$value['newfarms_id']])->one();
//								var_dump(Ttpozongdi::isSplit($value['id']));
								if(Ttpozongdi::isSplit($value['id'])) {
									$oldfarm = Farms::find()->where(['id'=>Ttpozongdi::isSplit($value['id'])])->one();
								} else {
									$oldfarm = Farms::find()->where(['id' => $value['oldfarms_id']])->one();
								}
// 								var_dump(Ttpozongdi::getSameCount($value['id']));
// 							var_dump($value['operation_id']);exit;
							if(Reviewprocess::isShowProess($value['operation_id'])) {
								$field = Reviewprocess::getProcessIdentification('farmstransfer');
								?>
							<tr height="40px">
								<td style='vertical-align: middle;text-align: center;'><?= $i++?></td>
								<?php
//								var_dump(Ttpozongdi::getSameFirstID($value['id']));
									if(Ttpozongdi::getSameFirstID($value['id']) == $value['id']) {
										$show = false;
								?>
									<td rowspan="<?= Ttpozongdi::getSameCount($value['id'])?>" style='vertical-align: middle;text-align: center;'><?= ManagementArea::getAreaname($oldfarm->management_area)?></td>
									<td rowspan="<?= Ttpozongdi::getSameCount($value['id'])?>" style='vertical-align: middle;text-align: center;'><?= $oldfarm->farmname?></td>
									<td rowspan="<?= Ttpozongdi::getSameCount($value['id'])?>" style='vertical-align: middle;text-align: center;'><?= $oldfarm->farmername?></td>
									<td rowspan="<?= Ttpozongdi::getSameCount($value['id'])?>" style='vertical-align: middle;text-align: center;'><?= $oldfarm->contractarea?>亩</td>
								<?php } ?>
								<?php
									if($show and ($xfarmname !== '' or $xfarmername !== '' or $xcontractarea !=='')) {
										?>
										<td style='vertical-align: middle;text-align: center;'><?= ManagementArea::getAreaname($oldfarm->management_area)?></td>
										<td style='vertical-align: middle;text-align: center;'><?= $oldfarm->farmname?></td>
										<td style='vertical-align: middle;text-align: center;'><?= $oldfarm->farmername?></td>
										<td style='vertical-align: middle;text-align: center;'><?= $oldfarm->contractarea?>亩</td>
									<?php }?>
								<td style='vertical-align: middle;text-align: center;'><?= $newfarm->farmname?></td>
								<td style='vertical-align: middle;text-align: center;'><?= $newfarm->farmername?></td>
								<td style='vertical-align: middle;text-align: center;'><?= $newfarm->contractarea?>亩</td>
								<td style='vertical-align: middle;text-align: center;'><?= date('Y-m-d',$value['create_at'])?></td>
								<td align="center">
								<?php 
								$useritem = User::getItemname();
								
// 								var_dump($value);
								if($useritem) {?>
									<div class="btn-group">
									<div class="btn dropdown-toggle" 
								      data-toggle="dropdown" data-trigger="hover">
										<?php
										if($value['state'] == 9) {
											echo '<font color="red"><strong>';
											echo Reviewprocess::state($value['state']);
											echo '<span class="caret"></span>';
											echo '</strong></font>';
										}
										if($value['state'] == 7) {
											echo '<font color="green"><strong>';
											echo Reviewprocess::state($value['state']);
											echo '<span class="caret"></span>';
											echo '</strong></font>';
										}
										if($value['state'] == 4) {
											echo '<font color="fuchsia"><strong>';
											echo Reviewprocess::state($value['state']);
											echo '<span class="caret"></span>';
											echo '</strong></font>';
										}
										if($value['state'] == 8) {
											echo '<font color="blue"><strong>';
											echo Reviewprocess::state($value['state']);
											echo '<span class="caret"></span>';
											echo '</strong></font>';
										}
										if($value['state'] == 6) {
											echo '<font color="black"><strong>';
											echo Reviewprocess::state($value['state']);
											echo '<span class="caret"></span>';
											echo '</strong></font>';
										}
										?>
								   </div>
								   <ul class="dropdown-menu" role="menu">
								   <?php 
// 								   var_dump(Reviewprocess::getProcess($value['operation_id']));exit;
								   foreach (Reviewprocess::getProcess($value['operation_id']) as $val) {
								   	?>
								      <li><a href="#"><?= Processname::find()->where(['Identification'=>$val])->one()['processdepartment'].':'.Reviewprocess::state($value[$val],$value,$val)?></a></li>
								   <?php }?>
								   </ul>
								   </div>
								   <?php } else echo Reviewprocess::state($value['state']); ?>
								</td>
								<td align="center">
								<?php 
								$s = false;
// 								var_dump($field);
								foreach ($field as $v) {
									
									if($value[$v] == 2 or $value[$v] == 8)
										$s = true;
								}
								if($s and Yii::$app->controller->action->id !== 'reviewprocessing' and Yii::$app->controller->action->id !== 'reviewprocessfinished') {
									echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id'],'class'=>'farmstransfer'],['class'=>'btn btn-danger']); 
								}
								else {
									echo  html::a('查看',['reviewprocess/reviewprocessview','id'=>$value['id'],'class'=>'farmstransfer'],['class'=>'btn btn-success']);
								}
								
								?></td>
							</tr>
							
							<?php }}?>
						</table>
<?php }}?>
<?php 
$useritem = User::getItemname();

if( Auditprocess::isAuditing('项目审核')) {
if($projectapplication) {?>
<h3>项目审批</h3>
						<table class="table table-bordered table-hover">
							<tr height="40px">
								<td align="center"><strong>序号</strong></td>
								<td align="center"><strong>农场名称</strong></td>
								<td align="center"><strong>法人</strong></td>
								<td align="center"><strong>项目名称</strong></td>
								<td align="center"><strong>申请时间</strong></td>
								<td align="center"><strong>状态</strong></td>
								<td align="center"><strong>操作</strong></td>
							</tr>
							<?php
							$i = 1;
							foreach ($projectapplication as $value) {
// 								var_dump($value);
							$project = Projectapplication::find()->where(['reviewprocess_id'=>$value['id']])->one();
							$oldfarm = Farms::find()->where(['id'=>$value['oldfarms_id']])->one();
// 							var_dump($value['actionname']);
							if(Reviewprocess::isShowProess($value['operation_id'])) {
								$field = Reviewprocess::getProcessIdentification('projectapplication');
							
								?>
							<tr height="40px">
								<td align="center"><?= $i++?></td>
								<td align="center"><?= $oldfarm->farmname?></td>
								<td align="center"><?= $oldfarm->farmername?></td>
								<td align="center"><?= Infrastructuretype::find()->where(['id'=>$project['projecttype']])->one()['typename'].'建设'?></td>
								<td align="center"><?= date('Y-m-d',$project['create_at'])?></td>
								<td align="center">
								<?php if(User::getItemname('地产科') or User::getItemname('主任') or  User::getItemname('副主任')) {?>
									<div class="btn-group">
									<div class="btn dropdown-toggle" 
								      data-toggle="dropdown" data-trigger="hover">
								    	  <?= Reviewprocess::state($value['state']); ?> <span class="caret"></span>
								   </div>
								   <ul class="dropdown-menu" role="menu">
								   <?php foreach (Reviewprocess::getProcess($value['operation_id']) as $val) {?>
								      <li><a href="#"><?= Processname::find()->where(['Identification'=>$val])->one()['processdepartment'].':'.Reviewprocess::state($value[$val])?></a></li>
								   <?php }?>
								   </ul>
								   </div>
								   <?php } else echo Reviewprocess::state($value['state']); ?>
								</td>
								<td align="center">
								<?php 
								$s = false;
// 								var_dump(($field));
								foreach ($field as $v) {
									if($value[$v] == 2 or $value[$v] == 0)
										$s = true;
								}
								if($s) 
									echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id'],'class'=>'projectapplication'],['class'=>'btn btn-danger']);
								else {
									echo  html::a('审核','#',['class'=>'btn btn-danger','disabled'=>'disabled']);
								}?></td>
							</tr>
							
							<?php }}?>
						</table>
<?php }}?>
<?php 
$useritem = User::getItemname();

//var_dump(Auditprocess::isAuditing('贷款冻结审批'));
if(Auditprocess::isAuditing('贷款冻结审批') or User::getItemname('地产科')) {
//	var_dump($loan);
if($loan) {?>
<h3>贷款冻结审批</h3>
						<table class="table table-bordered table-hover">
							<tr height="40px">
								<td align="center"><strong>序号</strong></td>
								<td align="center"><strong>农场名称</strong></td>
								<td align="center"><strong>贷款人</strong></td>
								<td align="center"><strong>抵押银行</strong></td>
								<td align="center"><strong>抵押金额</strong></td>
								<td align="center"><strong>状态</strong></td>
								<td align="center"><strong>操作</strong></td>
							</tr>
							<?php
							$i = 1;
							foreach ($loan as $value) {
// 								var_dump($value);
							$loandata = Loan::find()->where(['reviewprocess_id'=>$value['id']])->one();
							$oldfarm = Farms::find()->where(['id'=>$value['oldfarms_id']])->one();
							if(Reviewprocess::isShowProess($value['operation_id']) or User::getItemname('地产科')) {
								$field = Reviewprocess::getProcessIdentification('loancreate');
							
								?>
							<tr height="40px">
								<td align="center"><?= $i++?></td>
								<td align="center"><?= $oldfarm->farmname?></td>
								<td align="center"><?= $oldfarm->farmername?></td>
								<td align="center"><?= $loandata['mortgagebank']?></td>
								<td align="center"><?= $loandata['mortgagemoney']?>元</td>
								<td align="center">
								<?php if(User::getItemname('服务大厅') or User::getItemname('主任') or  User::getItemname('副主任')) {?>
									<div class="btn-group">
									<div class="btn dropdown-toggle" 
								      data-toggle="dropdown" data-trigger="hover">
								    	  <?= Reviewprocess::state($value['state']); ?> <span class="caret"></span>
								   </div>
								   <ul class="dropdown-menu" role="menu">
								   <?php
								   foreach (Reviewprocess::getProcess($value['operation_id']) as $val) {
									   ?>
										   <li><a href="#"><?= Processname::find()->where(['Identification'=>$val])->one()['processdepartment'].':'.Reviewprocess::state($value[$val])?></a></li>
									   <?php }?>
								   </ul>
								   </div>
								   <?php } else echo Reviewprocess::state($value['state']); ?>
								</td>
								<td align="center">
								<?php 
								$s = false;
// 								var_dump(($field));
								foreach ($field as $v) {
									if($value[$v] == 2 or $value[$v] == 8)
										$s = true;
								}
								if($s) 
									echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id'],'class'=>'loan'],['class'=>'btn btn-danger']);
								else {
									echo  html::a('查看',['reviewprocess/reviewprocessview','id'=>$value['id'],'class'=>'loan'],['class'=>'btn btn-success']);
								}?></td>
							</tr>
							
							<?php }}?>
						</table>
<?php }}?>
						<?= Html::submitButton('更新', ['style' => 'display:none;',]) ?>
						<?php ActiveFormrdiv::end(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$('#managementArea').change(function(){
		document.getElementById('w0').submit();
	});
</script>