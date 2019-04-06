<?php

use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use frontend\helpers\htmlColumn;
use app\models\Plant;
use app\models\Lease;
use app\models\Inputproduct;
use app\models\Pesticides;
use app\models\Plantpesticides;
use app\models\Plantinputproduct;
use app\models\Plantpesticidescheck;
use app\models\Plantinputproductcheck;
use app\models\User;
use app\models\Plantingstructurecheck;
use app\models\Theyear;
use yii\helpers\Url;
use app\models\Goodseed;
use app\models\Goodseedinfo;
use app\models\Goodseedinfocheck;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<style>
	.table-bordered {
		border: 1px solid #dddddd;  /* 整体表格边框 */
	}
</style>
<div class="lease-index">
	<script type="text/javascript">
	function openwindows(url)
	{
		window.open(url,'','width=1200,height=600,top=50,left=80, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');
		self.close();
	}
	</script>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<?php Farms::showRow($_GET['farms_id']);?>
				</div>
			</div>
			<div class="col-md-6" >
				<div class="box" id="jh">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-globe"></i> <?php $farms = Farms::find()->where(['id'=>$_GET['farms_id']])->one();?>
							<?= $farms['farmname']; ?> 的种植结构计划<font color="red">(<?= User::getYear()?>年度)</font></h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered table-hover">
							<tr>
								<td width="" align="center"><strong>种植者</strong></td>
								<td width="" align="center"><strong>姓名</strong></td>
								<td width="" align="center"><strong>种植面积</strong></td>
								<td width="" align="center"><strong>种植作物</strong></td>
								<td width="" align="center"><strong>操作</strong></td>
							</tr>
							<?php
							$sumArea = 0;
							foreach($plantings as $v) {
								?>
								<tr>
									<td align="center"><?php if($v['lease_id'] == 0) echo '法人'; else echo '租赁者'; ?></td>
									<td align="center"><?php if($v['lease_id'] == 0) echo $farms['farmername']; else echo Lease::find()->where(['id'=>$v['lease_id']])->one()['lessee']; ?></td>
									<?php
									$sumArea += (float)$v['area'];
									?>
									<td align="center"><?= $v['area']?>亩</td>
									<td align="center"><?= Plant::find()->where(['id'=>$v['plant_id']])->one()['typename'];?></td>
									<td align="center"><?php
										$check = Plantingstructurecheck::find()->where(['farms_id'=>$v['farms_id'],'year'=>$v['year'],'lease_id'=>$v['lease_id'],'plant_id'=>$v['plant_id'],'area'=>$v['area'],'goodseed_id'=>$v['goodseed_id']])->one();
										if($check) {
											echo Html::a('相同', '#', ['class' => 'btn btn-primary btn-xs','disabled'=>true]);
										} else {
											$sum = Plantingstructurecheck::find()->where(['farms_id'=>$_GET['farms_id'],'year'=>User::getYear(),'lease_id'=>$v['lease_id']])->sum('area');
//											$sum = Lease::findOne($v['lease_id'])->lease_area;
											if($v['lease_id'] == 0) {
												if (bccomp($sum, $farms['contractarea']) == 0) {
													echo Html::a('相同', '#', ['class' => 'btn btn-primary btn-xs', 'disabled' => true]);
												} else {
													$parea = Plantingstructurecheck::find()->where(['farms_id'=>$v['farms_id'],'id'=>$v['id'],'lease_id'=>0,'year'=>User::getYear()])->sum('area');
													if($parea) {
														echo Html::a('相同', '#', ['class' => 'btn btn-primary btn-xs', 'disabled' => true]);
													} else {
														echo Html::a('相同', Url::to(['plantingstructurecheck/plantingstructurechecksame', 'planting_id' => $v['id']]), ['class' => 'btn btn-primary btn-xs', 'help' => 'plantingstructurecheck-samebutton', 'data' => ['confirm' => '您确定复核数据与此数据相同吗？',],]);
													}
												}
											} else {
												if (bccomp($sum, $v['area']) == 0) {
													echo Html::a('相同', '#', ['class' => 'btn btn-primary btn-xs', 'disabled' => true]);
												} else {
													$parea = Plantingstructurecheck::find()->where(['farms_id'=>$v['farms_id'],'id'=>$v['id'],'plant_id'=>$v['plant_id'],'lease_id'=>$v['lease_id'],'year'=>User::getYear()])->sum('area');
//													var_dump($parea);
													if($parea) {
														echo Html::a('相同', '#', ['class' => 'btn btn-primary btn-xs', 'disabled' => true]);
													} else {
														echo Html::a('相同', Url::to(['plantingstructurecheck/plantingstructurechecksame', 'planting_id' => $v['id']]), ['class' => 'btn btn-primary btn-xs', 'help' => 'plantingstructurecheck-samebutton', 'data' => ['confirm' => '您确定复核数据与此数据相同吗？',],]);
													}
												}
											}
										}
										echo '&nbsp;&nbsp;';
										echo Html::a('查看详情', '#inputproduct'.$v['id'], ['class' => 'btn btn-primary btn-xs','data-toggle'=>"collapse", 'aria-expanded'=>false,'id'=>'product']);
										?>
									</td>
								</tr>
								<tr>
									<td colspan="5">
										<div class="collapse" id="inputproduct<?= $v['id']?>" aria-expanded="false" style="height: 0px;">
											<?php
											$goodseedinfo = \app\models\Goodseedinfo::find()->where(['planting_id'=>$v['id'],'year'=>User::getYear()])->all();
											if($goodseedinfo) {?>
												<h5 class="box-title"><strong>良种情况</strong></h5>
												<table class="table table-bordered table-hover">
													<tr>
														<td align="center"><strong>良种类型</strong></td>
														<td align="center"><strong>种植面积</strong></td>
													</tr>
													<?php
													foreach ($goodseedinfo as $value) {?>
														<tr>
															<td align="center"><?php echo Goodseed::find()->where(['id'=>$value['goodseed_id']])->one()['typename'];?></td>
															<td align="center"><?php echo $value['area']; ?></td>
														</tr>
													<?php }
													?>
												</table>
											<?php }
											$plantinput = Plantinputproduct::find()->where(['planting_id'=>$v['id']])->all();
											if($plantinput) {?>
												<h5 class="box-title"><strong>投入品使用情况</strong></h5>
												<table class="table table-bordered table-hover">
													<tr>
														<td align="center"><strong>投入品大类</strong></td>
														<td align="center"><strong>投入品小类</strong></td>
														<td align="center"><strong>投入品</strong></td>
														<td align="center"><strong>用量</strong></td>
													</tr>
													<?php
													foreach ($plantinput as $value) {?>
														<tr>
															<td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['father_id']])->one()['fertilizer'];?></td>
															<td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['son_id']])->one()['fertilizer']; ?></td>
															<td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['inputproduct_id']])->one()['fertilizer']; ?></td>
															<td align="center"><?php echo $value['pconsumption'].'公斤/亩';?></td>
														</tr>
													<?php }
													?>
												</table>
											<?php }?>
											<?php
											$pesticides = Plantpesticides::find()->where(['planting_id'=>$v['id']])->all();
											if($pesticides) {?>
												<h5 class="box-title"><strong>农药使用情况</strong></h5>
												<table class="table table-bordered table-hover">
													<tr>
														<td width=40% align='center'><strong>农药</strong></td>
														<td align='center'><strong>农药用量</strong></td>
													</tr>
													<?php
													foreach ($pesticides as $value) {?>
														<tr>
															<td align="center"><?php echo Pesticides::find()->where(['id'=>$value['pesticides_id']])->one()['pesticidename']; ?></td>
															<td align="center"><?php echo $value['pconsumption'].'公斤/亩'; ?></td>
														</tr>
													<?php }
													?>
												</table>
											<?php }
											?>
										</div>
									</td>
								</tr>
							<?php }?>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<div class="col-md-6">
				<div class="box" id="hs">
					<div class="box-header with-border">
						<h3 class="box-title"><?php $farms = Farms::find()->where(['id'=>$_GET['farms_id']])->one();
							$plantings = Plantingstructurecheck::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>0])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();?>
							<?= $farms['farmname']; ?> 的种植结构复核</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<?php
						$farmerSumArea = 0.0;
						$leaseSumArea = 0.0;
						$farmerArea = 0.0;
						$leaseArea = 0.0;
						$isLeaseViewAdd = 0.0;
						$strArea = '';
						$arrayArea = [];
						$plantingLeaseArea = 0.0;
						$allarea = $farms['contractarea'];
						foreach ($leases as $value) {
							$leaseArea += $value['lease_area'];
							$plantingLeaseArea = Plantingstructurecheck::find()->where(['lease_id'=>$value['id']])->sum('area');
						}

						$farmerArea = (float)bcsub($allarea , $leaseArea,2);
						foreach ($plantings as $value) {
							$farmerSumArea += $value['area'];
						}
						$isLeaseViewAdd = (float)bcsub($leaseArea, $plantingLeaseArea,2);
//						var_dump($isLeaseViewAdd);
						$isPlantingViewAdd = (float)bcsub($farmerArea , $farmerSumArea,2);
						$sum = $leaseArea + $farmerArea;
						$isView = bcsub($allarea, $sum,2);
						if($isView) {
//			$arrayZongdi = Lease::getNOZongdi($_GET['farms_id']);
//		if(is_array($arrayZongdi))
//			$zongdilist = implode('、',$arrayZongdi);
//		else
//			$zongdilist =  bcsub($farms['measure'] , $arrayZongdi,2);
							//var_dump($arrayZongdi);
							?>
							<?php
							if($farmerArea) {
								?>
								<table class="table table-bordered table-hover">
									<tr bgcolor="">
										<td width="12%" colspan="2" align="center"><strong>法人</strong></td>
										<td colspan="2" align="center"><strong>种植面积</strong></td>
										<td width="22%" align="center"><strong>操作</strong></td>
									</tr>
									<tr>
										<td width="12%" colspan="2" align="center"><?= $farms['farmername'] ?></td>
										<?php

										//     	  var_dump($plantings);
										$sumArea = 0;
										foreach ($plantings as $value) {
											$sumArea += (float)$value['area'];
										}
										?>
										<td colspan="2" align="center"><?= $farmerArea ?>亩</td>
										<td align="center"><?php if ($isPlantingViewAdd) { ?><?= Html::a('添加', 'index.php?r=plantingstructurecheck/plantingstructurecheckcreate&lease_id=0&farms_id=' . $_GET['farms_id'], [
												'id' => 'employeecreate',
												'title' => '给' . $farms['farmername'] . '添加',
												'class' => 'btn btn-primary btn-xs',
											]); ?><?php } ?></td>
									</tr>
									<?php
									$farmerplantings = Plantingstructurecheck::find()->where(['farms_id' => $_GET['farms_id'], 'lease_id' => 0])->andFilterWhere(['between', 'update_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]])->all();
									if ($farmerplantings) {
										foreach ($farmerplantings as $v) {
											?>
											<tr>
												<td colspan="2" align="center">|_</td>

												<td align="center">种植作物面积：<?= $v['area'] ?>亩</td>
												<td align="center">
													作物：<?= Plant::find()->where(['id' => $v['plant_id']])->one()['typename'] ?><span id="is-goodseed_<?= $v['id']?>"><?php
														if(Goodseedinfocheck::isGoodseed($v['id'])) {
															echo '<i class="fa fa-pagelines text-success"></i>';
														}
														?></span></td>
												<td align="center">
													<?php
													echo Html::a('<span class="glyphicon glyphicon-eye-open"></span>','#hsinputproduct'.$v['id'], [
														'title' => Yii::t('yii', '查看'),
														'data-pjax' => '0',
														'data-toggle'=>"collapse", 'aria-expanded'=>false,'id'=>'hsproduct'
													]);
													echo '&nbsp;&nbsp;';
													echo Html::a('<span class="glyphicon glyphicon-pencil"></span>',Url::to(['plantingstructurecheck/plantingstructurecheckupdate','id'=>$v['id'],'lease_id'=>$v['lease_id'],'farms_id'=>$v['farms_id']]), [
													'title' => Yii::t('yii', '更新'),
													'data-pjax' => '0',
													]);
													echo '&nbsp;&nbsp;';
													echo Html::a('<span class="glyphicon glyphicon-trash"></span>',Url::to(['plantingstructurecheck/plantingstructurecheckdelete','id'=>$v['id'],'lease_id'=>$v['lease_id'],'farms_id'=>$v['farms_id']]), [
													'title' => Yii::t('yii', '删除'),
													'data-pjax' => '0',
														'data' => [
															'confirm' => '您确定要删除这项吗？',
														],
													]);
													echo Html::a('良种','#',['class'=>'btn btn-xs btn-success','onClick'=>'showGoodseed('.$v['id'].','.$v['farms_id'].','.$v['plant_id'].')']);
													 //htmlColumn::show(['id' => $v['id'], 'lease_id' => $v['lease_id'], 'farms_id' => $v['farms_id']], 'plantingstructureindex');
													?>
												</td>
											</tr>
											<tr>
												<td colspan="5">
													<div class="collapse" id="hsinputproduct<?= $v['id']?>" aria-expanded="false" style="height: 0px;">
														<?php
														$plantinput = Plantinputproductcheck::find()->where(['planting_id'=>$v['id']])->all();
														if($plantinput) {?>
															<h5 class="box-title"><strong>投入品使用情况</strong></h5>
															<table class="table table-bordered table-hover">
																<tr>
																	<td align="center"><strong>投入品大类</strong></td>
																	<td align="center"><strong>投入品小类</strong></td>
																	<td align="center"><strong>投入品</strong></td>
																	<td align="center"><strong>用量</strong></td>
																</tr>
																<?php
																foreach ($plantinput as $value) {?>
																	<tr>
																		<td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['father_id']])->one()['fertilizer'];?></td>
																		<td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['son_id']])->one()['fertilizer']; ?></td>
																		<td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['inputproduct_id']])->one()['fertilizer']; ?></td>
																		<td align="center"><?php echo $value['pconsumption'].'公斤/亩';?></td>
																	</tr>
																<?php }
																?>
															</table>
														<?php }?>
														<?php
														$pesticides = Plantpesticidescheck::find()->where(['planting_id'=>$v['id']])->all();
														if($pesticides) {?>
															<h5 class="box-title"><strong>农药使用情况</strong></h5>
															<table class="table table-bordered table-hover">
																<tr>
																	<td width=40% align='center'><strong>农药</strong></td>
																	<td align='center'><strong>农药用量</strong></td>
																</tr>
																<?php
																foreach ($pesticides as $value) {?>
																	<tr>
																		<td align="center"><?php echo Pesticides::find()->where(['id'=>$value['pesticides_id']])->one()['pesticidename']; ?></td>
																		<td align="center"><?php echo $value['pconsumption'].'公斤/亩'; ?></td>
																	</tr>
																<?php }
																?>
															</table>
														<?php }
														?>
													</div>
												</td>
											</tr>
										<?php }
									} ?>
								</table>
								<br>
								<?php
							}
								?>
							<?php
							if($leases) {
								?>
								<table class="table table-bordered table-hover">
									<tr bgcolor="">
										<td width="12%" colspan="2" align="center"><strong>承租人</strong></td>
										<td colspan="2" align="center"><strong>承租面积</strong></td>
										<td width="22%" align="center"><strong>操作</strong></td>
									</tr>
									<?php
									foreach($leases as $val) {
										?>
										<tr>
											<td colspan="2" align="center"><?= $val['lessee'] ?></td>
											<td colspan="2"  align="center"><?= $val['lease_area']?>亩</td>
											<?php
											$leaseData = Plantingstructurecheck::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>$val['id'],'year'=>User::getYear()]);
											$aarea = sprintf('%.2f',$leaseData->sum('area'));
											$isViewadd = bccomp($aarea,$val['lease_area']);
											?>
											<td align="center"><?php if($isViewadd) {?><?= Html::a('添加','index.php?r=plantingstructurecheck/plantingstructurecheckcreate&lease_id='.$val['id'].'&farms_id='.$_GET['farms_id'], [
													'id' => 'employeecreate',
													'title' => '给'.$val['lessee'].'添加',
													'class' => 'btn btn-primary btn-xs',
												]);?><?php }?></td>
										</tr>
										<?php

										foreach($leaseData->all() as $v) {
											?>
											<tr>
												<td colspan="2" align="center">|_</td>
												<td align="center">种植作物面积：<?= $v['area']?>亩</td>
												<td align="center">作物：<?= Plant::find()->where(['id'=>$v['plant_id']])->one()['typename']?><span id="is-goodseed_<?= $v['id']?>"><?php
														if(Goodseedinfocheck::isGoodseed($v['id'])) {
															echo '<i class="fa fa-pagelines text-success"></i>';
														}
														?></span></td>
												<td align="center">
													<?php
													echo Html::a('<span class="glyphicon glyphicon-eye-open"></span>','#hsleaseinputproduct'.$v['id'], [
														'title' => Yii::t('yii', '查看'),
														'data-pjax' => '0',
														'data-toggle'=>"collapse", 'aria-expanded'=>false,'id'=>'hsproduct'
													]);
													echo '&nbsp;&nbsp;';
													echo Html::a('<span class="glyphicon glyphicon-pencil"></span>',Url::to(['plantingstructurecheck/plantingstructurecheckupdate','id'=>$v['id'],'lease_id'=>$v['lease_id'],'farms_id'=>$v['farms_id']]), [
														'title' => Yii::t('yii', '更新'),
														'data-pjax' => '0',
													]);
													echo '&nbsp;&nbsp;';
													echo Html::a('<span class="glyphicon glyphicon-trash"></span>',Url::to(['plantingstructurecheck/plantingstructurecheckdelete','id'=>$v['id'],'lease_id'=>$v['lease_id'],'farms_id'=>$v['farms_id']]), [
														'title' => Yii::t('yii', '删除'),
														'data-pjax' => '0',
														'data' => [
															'confirm' => '您确定要删除这项吗？',
														],
													]);
													echo Html::a('良种','#',['class'=>'btn btn-xs btn-success','onClick'=>'showGoodseed('.$v['id'].','.$v['farms_id'].','.$v['plant_id'].')']);
//													htmlColumn::show(['id'=>$v['id'],'lease_id'=>$v['lease_id'],'farms_id'=>$v['farms_id']],'plantingstructureindex');
													?></td>
											</tr>
											<tr>
												<td colspan="5">
													<div class="collapse" id="hsleaseinputproduct<?= $v['id']?>" aria-expanded="false" style="height: 0px;">
														<?php
														$plantinput = Plantinputproductcheck::find()->where(['planting_id'=>$v['id']])->all();
														if($plantinput) {?>
															<h5 class="box-title"><strong>投入品使用情况</strong></h5>
															<table class="table table-bordered table-hover">
																<tr>
																	<td align="center"><strong>投入品大类</strong></td>
																	<td align="center"><strong>投入品小类</strong></td>
																	<td align="center"><strong>投入品</strong></td>
																	<td align="center"><strong>用量</strong></td>
																</tr>
																<?php
																foreach ($plantinput as $value) {?>
																	<tr>
																		<td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['father_id']])->one()['fertilizer'];?></td>
																		<td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['son_id']])->one()['fertilizer']; ?></td>
																		<td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['inputproduct_id']])->one()['fertilizer']; ?></td>
																		<td align="center"><?php echo $value['pconsumption'].'公斤/亩';?></td>
																	</tr>
																<?php }
																?>
															</table>
														<?php }?>
														<?php
														$pesticides = Plantpesticidescheck::find()->where(['planting_id'=>$v['id']])->all();
														if($pesticides) {?>
															<h5 class="box-title"><strong>农药使用情况</strong></h5>
															<table class="table table-bordered table-hover">
																<tr>
																	<td width=40% align='center'><strong>农药</strong></td>
																	<td align='center'><strong>农药用量</strong></td>
																</tr>
																<?php
																foreach ($pesticides as $value) {?>
																	<tr>
																		<td align="center"><?php echo Pesticides::find()->where(['id'=>$value['pesticides_id']])->one()['pesticidename']; ?></td>
																		<td align="center"><?php echo $value['pconsumption'].'公斤/亩'; ?></td>
																	</tr>
																<?php }
																?>
															</table>
														<?php }
														?>
													</div>
												</td>
											</tr>
										<?php }}?>
								</table>
							<?php }}?>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
							<table class="table table-bordered">
								<tr>
									<td align="right" width="15%">农业面源污染治理:</td>
									<td><?php
										$e = \app\models\Environment::find()->where(['farms_id'=>$_GET['farms_id'],'year'=>User::getYear()])->one();
										$ev = '';
										if($e) {
											$ev = $e['isgovernment'];
										}
										echo Html::radioList('is_environment',$ev,[1=>'是',0=>'否'],['id'=>'Environment']);
										?></td>
								</tr>
							</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">
							银行账号管理
							<?php
							if(count(Lease::getLesees($_GET['farms_id']))) {
								echo Html::button('添加', ['class' => 'btn btn-success', 'id' => 'openbank']);
							} else {
								echo Html::button('添加', ['class' => 'btn btn-success', 'disabled' => true]);
							}
							?>
						</h3>
					</div>
					<div class="box-body">
						<?= GridView::widget([
							'dataProvider' => $dataProvider,
//							'filterModel' => $searchModel,
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],
								'lessee',
								'cardid',
								'bank',
								'accountnumber',
								[
									'label'=>'操作',
									'format'=>'raw',
									//'class' => 'btn btn-primary btn-lg',
									'value' => function($model,$key) {
										if ($model->state == 2) {
											$html = Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
												'onclick' => 'updatebank(' . $model->id . ')',
												'title' => Yii::t('yii', '更新'),
												'data-pjax' => '0',
											]);
											$html .= '&nbsp;&nbsp;';
											$html .= Html::button('<span class="glyphicon glyphicon-trash"></span>', [
												'onclick' => 'deletebank(' . $model->id . ')',
												'title' => Yii::t('yii', '删除'),
												'data-pjax' => '0',
											]);
											return $html;
										} else {
											return '审核通过';
										}
									}
								]
							],
						]); ?>
						<?php
//						var_dump(Lease::getLesees($_GET['farms_id']));exit;
						?>
						<div id="bankdialog" title="银行账号管理">
							<table class="table table-bordered table-hover" width=100% height="120px">
								<tr>
									<td align="right">持卡人:</td>
									<td>
										<?= html::dropDownList('lessee','',[],['id'=>'Lessee','readonly'=>true,'class'=>'form-control'])?>
										<span class="text text-red"><?php if(count(Lease::getLesees($_GET['farms_id'])>1)) echo '注:此农场有'.count(Lease::getLesees($_GET['farms_id'])).'人需要填写银行卡账号!'?></span>
									</td>
									<td align="right">是否委托:</td>
									<td><?= Html::radioList('isEntrust',0,[1=>'是',0=>'否'],['id'=>'is_Entrust','class'=>'form-control'])?></td>
								</tr>
								<tr>
									<td align="right">身份证号:</td>
									<td colspan="3">
										<?= html::textInput('cardid','',['id'=>'Cardid','readonly'=>true,'class'=>'form-control'])?>
									</td>
								</tr>
								<tr>
									<td align="right">银行:</td>
									<td colspan="3"><?= html::textInput('bank','大兴安岭农村商业银行',['id'=>'Bank','readonly'=>true,'class'=>'form-control'])?></td>
								</tr>
								<tr>
									<td align="right">卡号：</td>
									<td colspan="3">
										<input type="text" id="Accountnumber" class="form-control" name="actTransaction.opbankacntnoShow" size="26", maxlength="50" value="" onkeyup="Keystroke();"/>
								</tr>
							</table>
							<div id="copybankacntno">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="bankacntnoEm" size="30" style="font-size:30px;color:blue;" value="" readonly="readonly"/>
							</div>
						</div>
						<div id="bankdel" title="银行账号管理">
							<?= Html::hiddenInput('id','',['id'=>'bankid'])?>
							是否删除些项操作?
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div id="goodseed" title="良种">
</div>
<?php
//var_dump(Lease::getLesees($_GET['farms_id']));
?>
<input type="hidden" id="hdnvalue" runat="server" value="0" />
	<script>
		$('#Lessee').change(function () {
			var array = <?= json_encode(Lease::getLeseesArray($_GET{'farms_id'}))?>;
			console.log(array);
			var input = $(this).val();
			$('#Cardid').val(array[input]['cardid']);
			$('#Accountnumber').val(array[input]['accountnumber']);
			var v = $('#Accountnumber').val();
			var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
//			$('#Accountnumber').val(v.replace(/\s/g, '').replace(/(.{4})/g, "$1 "));
			$("#bankacntnoEm").val(vnew);
		});
//		$('#Accountnumber').focus(function () {
//			$("#copybankacntno").show();
//		});
		function Keystroke(){

			var v = $('#Accountnumber').val();
			var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
//			$('#Accountnumber').val(v.replace(/\s/g, '').replace(/(.{4})/g, "$1 "));
			$("#bankacntnoEm").val(vnew);
//			$("#Accountnumber").blur(function(){
//				$("#copybankacntno").hide();
//			});
//			$("#Accountnumber").mouseout(function(){
//				$("#copybankacntno").hide();
//			});
		}
		$('#rowjump').keyup(function(event){
		input = $(this).val();
		$.getJSON('index.php?r=farms/getfarmid', {id: input}, function (data) {
			$('#setFarmsid').val(data.farmsid);
		});
	});
	$("#Environment").click(function () {
		input = $("input[name='is_environment']:checked").val();
		$.getJSON('index.php?r=environment/setenvironment', {farms_id: "<?= $_GET['farms_id']?>",value:input}, function (data) {
			if(data.state) {
//				alert('保存成功');
			}
		});
	});
	$('#openbank').click(function () {
		var select = <?= json_encode(Lease::getLesees($_GET['farms_id']))?>;
		$('#Lessee').html(null);
		for(i=0;i<select.length;i++) {
			$('#Lessee').append('<option value="'+select[i]['name']+'">'+select[i]['name']+'</option>');
		}
		$('#Cardid').val(select[0]['cardid']);
		var ischeck = <?= Plantingstructurecheck::find()->where(['farms_id'=>$_GET['farms_id'],'year'=>User::getYear()])->count()?>;
		if(ischeck > 0) {
			$("#bankdialog").dialog("open");
			var cardid = $('#Cardid').val();
			$.getJSON('index.php?r=bankaccount/getone', {cardid: cardid}, function (data) {
				if(data.state) {
					alert('此人已经填写过银行卡号,将自动生成,如果需要另外填写请重新输入。');
					$('#Accountnumber').val(data.accountnumber);
					var v = data.accountnumber;
					var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
					$('#bankacntnoEm').val(vnew);
				}
			});
		} else {
			alert('填写完种植结构复核数据后,方可填写银行账号信息');
		}

	});
	function updatebank(id) {
		$.getJSON('index.php?r=bankaccount/bankaccountgetbank',{id:id,farms_id: "<?= $_GET['farms_id']?>"},function (data) {
			$('#bankid').val(id);
			$('#Lessee').append('<option value="'+data.lessee+'">'+data.lessee+'</option>');
			$('#Cardid').val(data.cardid);
			$('#Accountnumber').val(data.accountnumber);
			var v = $('#Accountnumber').val();
			var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
//			$('#Accountnumber').val(v.replace(/\s/g, '').replace(/(.{4})/g, "$1 "));
			$("#bankacntnoEm").val(vnew);
		});

		$( "#bankdialog" ).dialog( "open" );
	}
	function deletebank(id) {
		$('#bankid').val(id);
		$( "#bankdel" ).dialog( "open" );
	}
	$('#bankdel').dialog({
		autoOpen: false,
		width:400,
		buttons: [
			{
				text: "确定",
				click: function() {
					$.getJSON('index.php?r=bankaccount/bankaccountdelbank',{id:$('#bankid').val(),farms_id: "<?= $_GET['farms_id']?>"},function (data) {
						if(data.state) {
							window.location.reload();
						}
					});
				}
			},
			{
				text: "取消",
				click: function() {
					$('#bankacntnoEm').val('');
					$('#Accountnumber').val('');
					$( this ).dialog( "close" );
				}
			}
		]
	});
	$('#bankdialog').dialog({
		autoOpen: false,
		width:700,

		buttons: [
			{
				text: "确定",
				click: function() {
					var bank = $('#Bank').val();
					var number = $('#Accountnumber').val();
					var lessee = $("#Lessee").val();
					var cardid = $('#Cardid').val();
					$.getJSON('index.php?r=bankaccount/bankaccountsave',{'lessee':lessee,'cardid':cardid,'bank':bank,'accountnumber':number,'farms_id':"<?= $_GET['farms_id']?>",id:$('#bankid').val()},function (data) {
						if(data.state) {
							window.location.reload();
						}
					});
				}
			},
			{
				text: "取消",
				click: function() {
					$('#bankacntnoEm').val('');
					$('#Accountnumber').val('');
					$( this ).dialog( "close" );
				}
			}
		]
	});
//		$(function() {
//			var hs = $('#hs').height();
//			var jh = $('#jh').height();
//			if(hs > jh) {
//				$('#jh').height(hs);
//			} else {
//				$('#hs').height(jh);
//			}
//		});
//	$('#inputproduct').resize(function () {
//		var h = $(this).height();
//		var jh = $('#jh').height();
//		var hs = $('#hs').height();
//		if(h > 0) {
//			if(hs <= jh)
//				$('#jh').height(jh+h);
//		} else {
//			$('#jh').height(jh - h);
//		}
//	});
//	$('#jh').resize(function(){
//		$('#hs').height($(this).height());
//	});

		function showGoodseed(planting_id,farms_id,plant_id) {
			$.get('index.php?r=goodseedinfocheck/goodseedinfochecklistajax', {'farms_id':farms_id,'planting_id':planting_id,'plant_id':plant_id}, function (body) {
				$('#goodseed').html(body);
				$("#goodseed").dialog("open");
			});
		}

		$( "#goodseed" ).dialog({
			autoOpen: false,
			width: 800,
			height:600,
			modal:true,
//        closeOnEscape:false,
//        open:function(event,ui){$(".ui-dialog-titlebar-close").hide();},
			buttons: [
				{
					text: "确定",
					click: function() {
						$( this ).dialog( "close" );
						var tempid = $('#temp-id').val();
						var planting_id = $('#planting-id').val();
						var type_id = new Array();
						var area_num = new Array();
						for(i=1;i<=tempid;i++) {
							var t = $('#type_'+i).val();
							if(t !== undefined || t !== '') {
								type_id[i] = t;
							}
							var area = $('#area_'+i).val();
							if(area !== undefined || area !== '') {
								area_num[i] = area;
							}
						}
						$.getJSON('index.php?r=goodseedinfocheck/goodseedinfochecksave2',{'typename':type_id.join(','),'area':area_num.join(','),'planting_id':planting_id},function (data) {
							if(data.save > 0) {
								$('#is-goodseed_' + planting_id).html('<i class="fa fa-pagelines text-success"></i>');
							} else {
								$('#is-goodseed_' + planting_id).html('');
							}
						});
					}
				},
				{
					text: "取消",
					class:'btn btn-danger',
					click: function() {
						$( this ).dialog( "close" );
					}
				}
			]
		});
</script>
