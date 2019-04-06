<?php

namespace backend\controllers;
use app\models\Farmer;
use dosamigos\datetimepicker\DateTimePicker;
use Yii;
use yii\helpers\Url;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Reviewprocess;
use app\models\Auditprocess;
use app\models\Processname;
use app\models\User;
use app\models\ManagementArea;
use frontend\helpers\ActiveFormrdiv;
use app\models\Loan;
use yii\web\Session;
use app\models\Ttpozongdi;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ReviewprocessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'reviewprocess';
$this->title = Tables::find ()->where ( [
	'tablename' => $this->title
] )->one ()['Ctablename'];
$this->params ['breadcrumbs'] [] = $this->title;
?>
<script src="/js/jquery.cookie.js"></script>
<div class="reviewprocess-index">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<?php User::tableBegin($title);?>
						<?php $form = ActiveFormrdiv::begin(['method'=>'get']); ?>
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
							<ul class="nav nav-pills nav-pills-warning">
							<?php if( Auditprocess::isAuditing('承包经营权转让')) {?>
								<li class="active" id="farmactive"><a href="#activity" data-toggle="tab" aria-expanded="true" id="farmstransfer"><span class="box-tools">承包经营权转让审核&nbsp;&nbsp;<?= Reviewprocess::getRows('farmstransfer',$begindate,$enddate)?></span></a></li>
							<?php } if( Auditprocess::isAuditing('项目审核')) {?>
								<li class="" id="projectactive"><a href="#project" data-toggle="tab" aria-expanded="false" id="projectapplication"><span class="box-tools">项目审核&nbsp;&nbsp;<?= Reviewprocess::getRows('projectapplication',$begindate,$enddate)?></span></a></li>
							<?php } if( Auditprocess::isAuditing('贷款冻结审批')) {?>	
								<li class="" id="loanactive"><a href="#loan" data-toggle="tab" aria-expanded="false" id="loancreate"><span class="box-tools">贷款审核&nbsp;&nbsp;<?= Reviewprocess::getRows('loancreate',$begindate,$enddate)?></span></a></li>
							<?php }?>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="activity">

									<?php
									if(Auditprocess::isAuditing('承包经营权转让')) {
										?>
										<h3 class="box-title">合同流转审批列表</h3>
										<?= GridView::widget([
											'dataProvider' => $dataFarmstransfer,
											'filterModel' => $searchFarmstransfer,
											'columns' => [
												['class' => 'yii\grid\SerialColumn'],
												[
													'attribute' => 'management_area',
													'headerOptions' => ['width' => '200'],
													'value' => function ($model) {
														return ManagementArea::getAreanameOne($model->management_area);
													},
													'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
												],
												[
													'label' => '出让方农场名称',
													'attribute' => 'oldfarmname',
													'value' => function ($model) {
														return Farms::find()->where(['id' => $model->oldfarms_id])->one()['farmname'];
													}
												],
												[
													'label' => '出让方法人',
													'attribute' => 'oldfarmername',
													'value' => function ($model) {
														return Farms::find()->where(['id' => $model->oldfarms_id])->one()['farmername'];
													}
												],
												[
													'label' => '出让方面积',
													'attribute' => 'oldcontractarea',
													'value' => function ($model) {
										                if($model->samefarms_id) {
										                    return Farms::find()->where(['id' => $model->samefarms_id])->one()['contractarea'];
                                                        }
														return Farms::find()->where(['id' => $model->oldfarms_id])->one()['contractarea'];
													}
												],
												[
													'label' => '受让方农场名称',
													'attribute' => 'farmname',
													'value' => function ($model) {
														return Farms::find()->where(['id' => $model->newfarms_id])->one()['farmname'];
													}
												],
												[
													'label' => '受让方法人',
													'attribute' => 'farmername',
													'value' => function ($model) {
														return Farms::find()->where(['id' => $model->newfarms_id])->one()['farmername'];
													}
												],
												[
													'label' => '受让方面积',
													'attribute' => 'contractarea',
													'value' => function ($model) {
														return Farms::find()->where(['id' => $model->newfarms_id])->one()['contractarea'];
													}
												],
												[
													'label' => '申请日期',
													'options' => ['width'=>'120'],
													'attribute' => 'create_at',
													'value' => function($model) {
														return date('Y-m-d',$model->create_at);
													}
												],
//												[
//													'label' => '审核日期',
//													'attribute' => 'update_at',
//													'value' => function($model) {
//														return date('Y-m-d',$model->update_at);
//													}
//												],
												[
													'label' => '转让形式',
//									'attribute' => 'state',
													'format' => 'raw',
													'value' => function ($model) {
														$result = '';
														$action = Ttpozongdi::findOne($model->ttpozongdi_id);
														if ($action->actionname == 'farmstransfer')
															$result = '整体转让';
														if ($action->actionname == 'farmstransfermergecontract')
															$result = '整体合并';
														if ($action->actionname == 'farmssplit')
															$result = '部分转让';
														if ($action->actionname == 'farmstozongdi')
															$result = '部分合并';
														if ($action->actionname == 'farmssplitcontinue')
															$result = '分户';
														return $result;
													}
												],
//												[
//													'label' => '创建日期',
//													'value' => function ($model) {
//														return date('Y-m-d',$model->create_at);
//													}
//												],
												[
													'label' => '状态',
//									'attribute' => 'state',
													'format' => 'raw',
													'options' => ['width'=>100],
													'value' => function ($model) {
														return Reviewprocess::state($model->state);
													}
												],
//								[
//									'label' => '领取日期',
//									'value' => function($model) {
//										if($model->receivetime)
//											return date('Y-m-d',$model->receivetime);
//									}
//								],
												// 'breedtype_id',

												[
													'format' => 'raw',
													'value' => function ($model) {
														return Reviewprocess::getBtn($model->id);
													}
												],
											],
										]);
									}?>
								</div>
								<div class="tab-pane " id="project">

									<?php
									if( Auditprocess::isAuditing('项目审批')) {
										?>
										<h3 class="box-title">贷款冻结审批列表</h3>
										<?= GridView::widget([
											'dataProvider' => $dataProject,
											'filterModel' => $searchProject,
											'columns' => [
												['class' => 'yii\grid\SerialColumn'],
												[
													'attribute' => 'management_area',
													'headerOptions' => ['width' => '200'],
													'value'=> function($model) {
														return ManagementArea::getAreanameOne($model->management_area);
													},
													'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
												],
												[
													'label' => '农场名称',
													'attribute' => 'farmname',
													'value' => function($model) {
														return Farms::find()->where(['id'=>$model->oldfarms_id])->one()['farmname'];
													}
												],
												[
													'label' => '贷款人',
													'attribute' => 'farmername',
													'value' => function($model) {
														return Farms::find()->where(['id'=>$model->oldfarms_id])->one()['farmername'];
													}
												],
												[
													'label' => '贷款面积',
													'attribute' => 'loanarea',
													'value' => function($model) {
//										var_dump($model->id);exit;
//										var_dump(Loan::find()->where(['reviewprocess_id'=>$model->id])->one());exit;
														return Loan::find()->where(['reviewprocess_id'=>$model->id])->one()['mortgagearea'];
													}
												],
												[
													'label' => '贷款金额',
													'attribute' => 'loanmoney',
													'value' => function($model) {
														return Loan::find()->where(['reviewprocess_id'=>$model->id])->one()['mortgagemoney'];
													}
												],
												[
													'label' => '贷款银行',
													'attribute' => 'loanbank',
													'value' => function($model) {
													var_dump(Loan::getBankName());
														return Loan::find()->where(['reviewprocess_id'=>$model->id])->one()['mortgagebank'];
													},
													'filter' => Loan::getBankName(),
												],
												[
													'label' => '创建日期',
													'value' => function ($model) {
														return date('Y-m-d',$model->create_at);
													}
												],
												[
													'label' => '状态',
//									'attribute' => 'state',
													'format' => 'raw',
													'value' => function($model) {
														return Reviewprocess::state($model->state);
													}
												],
//								[
//									'label' => '领取日期',
//									'value' => function($model) {
//										if($model->receivetime)
//											return date('Y-m-d',$model->receivetime);
//									}
//								],
												// 'breedtype_id',

												[
													'format' => 'raw',
													'value' => function($model) {
//														$html =  html::a('查看',['reviewprocess/reviewprocessview','id'=>$model->id,'class'=>'loan'],['class'=>'btn btn-success']);

														return Reviewprocess::getBtn($model->id);;
													}
												],
											],
										]); }?>

								</div>
								<!-- /.tab-pane -->
								<div class="tab-pane " id="loan">

									<?php
									if( Auditprocess::isAuditing('贷款冻结审批')) {
										
										?>
										<h3 class="box-title">贷款冻结审批列表</h3>
										<?= GridView::widget([
											'dataProvider' => $dataLoan,
											'filterModel' => $searchLoan,
											'columns' => [
												['class' => 'yii\grid\SerialColumn'],
												[
													'attribute' => 'management_area',
													'headerOptions' => ['width' => '200'],
													'value'=> function($model) {
// 														return $model->management_area;
														return ManagementArea::getAreanameOne($model->management_area);
													},
													'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
												],
												[
													'label' => '农场名称',
													'attribute' => 'loanfarmname',
													'value' => function($model) {
														return Farms::find()->where(['id'=>$model->oldfarms_id])->one()['farmname'];
													}
												],
												[
													'label' => '贷款人',
													'attribute' => 'loanfarmername',
													'value' => function($model) {
														return Farms::find()->where(['id'=>$model->oldfarms_id])->one()['farmername'];
													}
												],
												[
													'label' => '贷款面积',
													'attribute' => 'loanarea',
													'value' => function($model) {
//										var_dump($model->id);exit;
//										var_dump(Loan::find()->where(['reviewprocess_id'=>$model->id])->one());exit;
														return Loan::find()->where(['reviewprocess_id'=>$model->id])->one()['mortgagearea'];
													}
												],
												[
													'label' => '贷款金额',
													'attribute' => 'loanmoney',
													'value' => function($model) {
														return Loan::find()->where(['reviewprocess_id'=>$model->id])->one()['mortgagemoney'];
													}
												],
												[
													'label' => '贷款银行',
													'attribute' => 'loanbank',
													'value' => function($model) {
														return Loan::find()->where(['reviewprocess_id'=>$model->id])->one()['mortgagebank'];
													},
													'filter' => Loan::getBankName(),
												],
// 												[
// 													'attribute' => 'create_at',
// 													'value' => function($model) {
// 														return date('Y-m-d',$model->create_at);
// 													}
// 												],
												[
													'label' => '审核状态',
//									'attribute' => 'state',
													'format' => 'raw',
													'value' => function($model) {
														return Reviewprocess::state($model->state);
													}
												],
												[
												'label' => '创建日期',
												'value' => function ($model) {
													return date('Y-m-d',$model->create_at);
												}
												],
												[
												'label' => '贷款状态',
												//									'attribute' => 'state',
												'format' => 'raw',
												'value' => function($model) {
													$loan = Loan::find()->where(['reviewprocess_id'=>$model->id])->one();
													$farm = Farms::find()->where(['id'=>$loan['farms_id']])->one();
													if($farm['locked']) {
														return '已冻结';
													} else {
														return '已解冻';
													}
												}
												],
//								[
//									'label' => '领取日期',
//									'value' => function($model) {
//										if($model->receivetime)
//											return date('Y-m-d',$model->receivetime);
//									}
//								],
												// 'breedtype_id',

												[
													'format' => 'raw',
													'value' => function($model) {
//														$html =  html::a('查看',['reviewprocess/reviewprocessview','id'=>$model->id,'class'=>'loan'],['class'=>'btn btn-success']);

														return Reviewprocess::getBtn($model->id);;
													}
												],
											],
										]); }?>

								<?php User::dataListEnd();?>

					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function () {
		if($.session.get('tabname') == 'farmactive') {
			$('#farmactive').attr('class','active');
			$('#projectactive').attr('class','');
			$('#loanactive').attr('class','');
			$('#activity').attr('class','tab-pane active')
			$('#project').attr('class','tab-pane')
			$('#loan').attr('class','tab-pane')
		}
		if($.session.get('tabname') == 'projectactive') {
			$('#projectactive').attr('class','active');
			$('#loanactive').attr('class','');
			$('#farmactive').attr('class','');
			$('#activity').attr('class','tab-pane')
			$('#project').attr('class','tab-pane active')
			$('#loan').attr('class','tab-pane')
		}
		if($.session.get('tabname') == 'loanactive') {
			$('#loanactive').attr('class','active');
			$('#projectactive').attr('class','');
			$('#farmactive').attr('class','');
			$('#activity').attr('class','tab-pane')
			$('#project').attr('class','tab-pane')
			$('#loan').attr('class','tab-pane active')
		}
	})
	$('#farmactive').click(function () {

		$.session.set('tabname', 'farmactive');

	});
	$('#projectactive').click(function () {
		$.session.set('tabname', 'projectactive');

	});
	$('#loanactive').click(function () {
		$.session.set('tabname', 'loanactive');

	});
</script>