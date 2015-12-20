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
						<h3 class="box-title">
                        任务列表                    </h3>
					</div>
					<div class="box-body">
    <?php ?>
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
							<?php foreach ($data as $value) {
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
								if($value[$field] == 3 or $value[$field] == 0) 
									echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id']],['class'=>'btn btn-success']); 
								else {
									echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id']],['class'=>'btn btn-success','disabled'=>'disabled']);
								}?></td>
							</tr>
							
							<?php }}?>
						</table>

					</div>
				</div>
			</div>
		</div>
	</section>
</div>
