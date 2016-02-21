<?php

namespace backend\controllers;
use Yii;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;
use app\models\Reviewprocess;
use app\models\Auditprocess;
use app\models\Processname;
use app\models\User;
use app\models\Projectapplication;
use app\models\Projecttype;
use app\models\Infrastructuretype;
use app\models\Tempauditing;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ReviewprocessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'reviewprocess';
$this->title = Tables::find ()->where ( [ 
		'tablename' => $this->title 
] )->one ()['Ctablename'];
$this->params ['breadcrumbs'] [] = $this->title;
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
    <?php if($farmstransfer) {?>
    <h3>宜农林地承包经营权转让审批</h3>
						<table class="table table-bordered table-hover">
							<tr height="40px">
								<td align="center">原农场名称</td>
								<td align="center">原法人</td>
								<td align="center">原面积</td>
								<td align="center">现农场名称</td>
								<td align="center">现法人</td>
								<td align="center">现面积</td>
								<td align="center">状态</td>
								<td align="center">操作</td>
							</tr>
							<?php foreach ($farmstransfer as $value) {
							$newfarm = Farms::find()->where(['id'=>$value['newfarms_id']])->one();
							$oldfarm = Farms::find()->where(['id'=>$value['oldfarms_id']])->one();
							if(Reviewprocess::isShowProess($value['actionname'])) {
								$field = Reviewprocess::getProcessIdentification();
								?>
							<tr height="40px">
								<td align="center"><?= $oldfarm->farmname?></td>
								<td align="center"><?= $oldfarm->farmername?></td>
								<td align="center"><?= $oldfarm->measure?>亩</td>
								<td align="center"><?= $newfarm->farmname?></td>
								<td align="center"><?= $newfarm->farmername?></td>
								<td align="center"><?= $newfarm->measure?>亩</td>
								
								<td align="center">
								<?php if(User::getItemname() == '地产科科长' or User::getItemname() == '主任' or  User::getItemname() == '副主任' ) {?>
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
								
								foreach ($field as $v) {
									
									if($value[$v] == 2 or $value[$v] == 0)
										$s = true;
								}
								if($s) 
									echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id'],'class'=>'farmstransfer'],['class'=>'btn btn-success']); 
								else {
									echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id']],['class'=>'btn btn-success','disabled'=>'disabled']);
								}?></td>
							</tr>
							
							<?php }}?>
						</table>
<?php }?>
<?php if($projectapplication) {?>
<h3>项目审批</h3>
						<table class="table table-bordered table-hover">
							<tr height="40px">
								<td align="center">农场名称</td>
								<td align="center">法人</td>
								<td align="center">项目名称</td>
								<td align="center">申请时间</td>
								<td align="center">状态</td>
								<td align="center">操作</td>
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
								<?php if(User::getItemname() == '地产科科长' or User::getItemname() == '主任' or  User::getItemname() == '副主任' ) {?>
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
									echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id']],['class'=>'btn btn-success','disabled'=>'disabled']);
								}?></td>
							</tr>
							
							<?php }}?>
						</table>
<?php }?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
