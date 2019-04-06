<?php

namespace frontend\controllers;
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
use app\models\Ttpozongdi;
use app\models\Infrastructuretype;
use app\models\Tempauditing;
use app\models\Loan;
use app\models\ManagementArea;

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
    <?php
//     var_dump(Auditprocess::isShowProcess('承包经营权转让'));
    if(Auditprocess::isShowProcess('承包经营权转让')) {
    if($farmstransfer) {?>
    <h3>宜农林地承包经营权转让审批</h3>
						<table class="table table-bordered table-hover">
							<tr height="40px">
								<td align="center"><strong>序号</strong></td>
								<td align="center"><strong>管理区</strong></td>
								<td align="center"><strong>原农场名称</strong></td>
								<td align="center"><strong>原法人</strong></td>
								<td align="center"><strong>原面积</strong></td>
								<td align="center"><strong>现农场名称</strong></td>
								<td align="center"><strong>现法人</strong></td>
								<td align="center"><strong>现面积</strong></td>
								<td align="center"><strong>状态</strong></td>
								<td align="center"><strong>操作</strong></td>
							</tr>
							<?php
							//							var_dump($params);
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
							</tr>
							<?php
							$show = true;
// 							var_dump($farmstransfer);exit;
							$i = 1;
							foreach ($farmstransfer as $value) {
//								var_dump($value);exit;
								$newfarm = Farms::find()->where(['id'=>$value['newfarms_id']])->one();
//								var_dump($newfarm);
								if(Ttpozongdi::isSplit($value['id'])) {
									$oldfarm = Farms::find()->where(['id'=>Ttpozongdi::isSplit($value['id'])])->one();
								} else {
									$oldfarm = Farms::find()->where(['id' => $value['oldfarms_id']])->one();
								}
// 							var_dump($newfarm);exit;
							if(Reviewprocess::isShowProess($value['operation_id'])) {
								$field = Reviewprocess::getProcessIdentification('承包经营权转让');
//								var_dump($value['id']);
								?>
							<tr height="40px">
								<td style='vertical-align: middle;text-align: center;'><?= $i++?></td>
								<?php
								if(Ttpozongdi::getSameCacleFirstID($value['id']) == $value['id']) {
									$show = false;
									?>
									<td rowspan="<?= Ttpozongdi::getSameCacleCount($value['id'])?>" style='vertical-align: middle;text-align: center;'><?= ManagementArea::getAreaname($oldfarm->management_area)?></td>
									<td rowspan="<?= Ttpozongdi::getSameCacleCount($value['id'])?>" style='vertical-align: middle;text-align: center;'><?= $oldfarm->farmname?></td>
									<td rowspan="<?= Ttpozongdi::getSameCacleCount($value['id'])?>" style='vertical-align: middle;text-align: center;'><?= $oldfarm->farmername?></td>
									<td rowspan="<?= Ttpozongdi::getSameCacleCount($value['id'])?>" style='vertical-align: middle;text-align: center;'><?= $oldfarm->contractarea?>亩</td>
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
								
								<td align="center">
								<?php 
								$useritem = User::getItemname();
								
// 								var_dump($useritem);
								if($useritem) {
//									var_dump($value);
									?>
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
									echo  html::a('查看',['reviewprocess/reviewprocessview','id'=>$value['id'],'class'=>'farmstransfer'],['class'=>'btn btn-success']);
								
								?></td>
							</tr>
							
							<?php }}?>
						</table>
<?php }}?>
<?php 
$useritem = User::getItemname();

if(Auditprocess::isShowProcess('项目审核')) {
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
							if(Reviewprocess::isShowProess($value['actionname'])) {
								$field = Reviewprocess::getProcessIdentification('项目审核');
							
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
								   <?php foreach (Reviewprocess::getProcess($value['actionname']) as $val) {?>
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
									echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id'],'class'=>'projectapplication'],['class'=>'btn btn-success']); 
								else {
									echo  html::a('审核','#',['class'=>'btn btn-success','disabled'=>'disabled']);
								}?></td>
							</tr>
							
							<?php }}?>
						</table>
<?php }}?>
<?php 
$useritem = User::getItemname();

if(Auditprocess::isShowProcess('贷款冻结审批')) {
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
//							var_dump(Reviewprocess::isShowProess($value['actionname']));exit;
//							if(Reviewprocess::isShowProess($value['actionname'])) {
//								$field = Reviewprocess::getProcessIdentification('贷款冻结审批');
							
								?>
							<tr height="40px">
								<td align="center"><?= $i++?></td>
								<td align="center"><?= $oldfarm->farmname?></td>
								<td align="center"><?= $oldfarm->farmername?></td>
								<td align="center"><?= $loandata['mortgagebank']?></td>
								<td align="center"><?= $loandata['mortgagemoney']?></td>
								<td align="center">
								<?php if(User::getItemname('服务大厅') or User::getItemname('主任') or  User::getItemname('副主任')) {?>
									<div class="btn-group">
									<div class="btn dropdown-toggle" 
								      data-toggle="dropdown" data-trigger="hover">
								    	  <?= Reviewprocess::state($value['state']); ?> <span class="caret"></span>
								   </div>
								   <ul class="dropdown-menu" role="menu">
								   <?php
//								   var_dump($value);exit;
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
									echo  html::a('查看',['reviewprocess/reviewprocessview','id'=>$value['id'],'class'=>'loan'],['class'=>'btn btn-success']);
								?></td>
							</tr>
							
							<?php }?>
						</table>
<?php }}?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
