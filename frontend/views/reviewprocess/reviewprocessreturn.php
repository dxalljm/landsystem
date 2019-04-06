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
use app\models\Projecttype;
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
var_dump(Processname::getIdentification());
?>
<div class="reviewprocess-index">

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">任务列表 </h3>
						<?php if(Tempauditing::is_tempauditing()) echo Html::a('申请延长时间', ['tempauditing/tempauditingextend','id'=>Tempauditing::is_tempauditing()], ['class' => 'btn btn-success','id'=>'extendDate','data' => [
			                'confirm' => '您确定要延长授权时间吗？',
			                'method' => 'post',
			            ]]).'注：每次申请可延长3天时间。'; ?>
						
					</div>
					<div class="box-body">
    <?php
//     var_dump(Auditprocess::isShowProcess('承包经营权转让'));
    if(Auditprocess::isShowProcess('承包经营权转让')) {
    if($farmstransfer) {?>
    <h3>宜农林地承包经营权转让审批</h3>
						<table class="table table-bordered table-hover">
							<tr height="40px">
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
// 							var_dump($farmstransfer);exit;
							foreach ($farmstransfer as $value) {
//								var_dump($value);exit;
								$newfarm = Farms::find()->where(['id'=>$value['newfarms_id']])->one();
								$oldfarm = Farms::find()->where(['id'=>$value['oldfarms_id']])->one();
// 							var_dump($newfarm);exit;
							if(Reviewprocess::isShowProess($value['operation_id'])) {
								$field = Reviewprocess::getProcessIdentification();
								?>
							<tr height="40px">
								<td align="center"><?= ManagementArea::getAreaname($oldfarm->management_area)?></td>
								<td align="center"><?= $oldfarm->farmname?></td>
								<td align="center"><?= $oldfarm->farmername?></td>
								<td align="center"><?= $oldfarm->contractarea?>亩</td>
								<td align="center"><?= $newfarm->farmname?></td>
								<td align="center"><?= $newfarm->farmername?></td>
								<td align="center"><?= $newfarm->contractarea?>亩</td>
								
								<td align="center">
								<?php 
								
// 								var_dump($useritem);
								if(User::getItemname('地产科') or User::getItemname('主任') or  User::getItemname('副主任')) {?>
									<div class="btn-group">
									<div class="btn dropdown-toggle" 
								      data-toggle="dropdown" data-trigger="hover">
								    	  <?= Reviewprocess::state($value['state']); ?> <span class="caret"></span>
								   </div>
								   <ul class="dropdown-menu" role="menu">
								   <?php 
// 								   var_dump(Reviewprocess::getProcess($value['operation_id']));exit;
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
 								var_dump($field);
								foreach ($field as $v) {
									
									if($value[$v] == 2 or $value[$v] == 0)
										$s = false;
								}
								if($s) {
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
$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
if($temp) {
	$useritem = User::getUserItemname($temp['user_id']);
}

if(Auditprocess::isShowProcess('项目审核')) {
if($projectapplication) {?>
<h3>项目审批</h3>
						<table class="table table-bordered table-hover">
							<tr height="40px">
								<td align="center"><strong>农场名称</strong></td>
								<td align="center"><strong>法人</strong></td>
								<td align="center"><strong>项目名称</strong></td>
								<td align="center"><strong>申请时间</strong></td>
								<td align="center"><strong>状态</strong></td>
								<td align="center"><strong>操作</strong></td>
							</tr>
							<?php foreach ($projectapplication as $value) {
// 								var_dump($value);
							$project = Projectapplication::find()->where(['reviewprocess_id'=>$value['id']])->one();
							$oldfarm = Farms::find()->where(['id'=>$value['oldfarms_id']])->one();
// 							var_dump($value['actionname']);
							if(Reviewprocess::isShowProess($value['actionname'])) {
								$field = Reviewprocess::getProcessIdentification();
							
								?>
							<tr height="40px">
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
if($loan) {?>
<h3>贷款冻结审批</h3>
						<table class="table table-bordered table-hover">
							<tr height="40px">
								<td align="center"><strong>农场名称</strong></td>
								<td align="center"><strong>贷款人</strong></td>
								<td align="center"><strong>抵押银行</strong></td>
								<td align="center"><strong>抵押金额</strong></td>
								<td align="center"><strong>状态</strong></td>
								<td align="center"><strong>操作</strong></td>
							</tr>
							<?php foreach ($loan as $value) {
// 								var_dump($value);
							$loandata = Loan::find()->where(['reviewprocess_id'=>$value['id']])->one();
							$oldfarm = Farms::find()->where(['id'=>$value['oldfarms_id']])->one();
							
							if(Reviewprocess::isShowProess($value['actionname'])) {
								$field = Reviewprocess::getProcessIdentification();
							
								?>
							<tr height="40px">
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
									echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id'],'class'=>'loan'],['class'=>'btn btn-success']); 
								else {
									echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id']],['class'=>'btn btn-success','disabled'=>'disabled']);
								}?></td>
							</tr>
							
							<?php }}?>
						</table>
<?php }}?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
