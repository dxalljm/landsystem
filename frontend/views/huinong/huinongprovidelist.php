<?php
namespace frontend\controllers;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Plant;
use app\models\Subsidiestype;
use yii\helpers\Url;
use app\models\Huinonggrant;
use dosamigos\datetimepicker\DateTimePicker;
use frontend\helpers\ActiveFormrdiv;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Lease;
use app\models\Huinong;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\HuinongSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'huinong';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
// var_dump($huinongs);
?>
<div class="huinong-index">
	<?php $form = ActiveFormrdiv::begin(); ?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<div class="box-body">
							<div class="nav-tabs-custom">
							<ul class="nav nav-pills nav-pills-warning">
								<li class="active" id="pinfo"><a href="#plantingstructureinfo" data-toggle="tab" aria-expanded="true">惠农补贴审核</a></li>
			<!--					<li class="" id="pcinfo"><a href="#plantingstructurecheckinfo" data-toggle="tab" aria-expanded="false">农作物复核数据统计</a></li>-->
							</ul>
							<div class="tab-content">

								<div class='tab-pane active' id="plantingstructureinfo">
									<?php
									$totalData = clone $dataProvider;
									$totalData->pagination = ['pagesize'=>0];
									?>
									<?= GridView::widget([
										'dataProvider' => $dataProvider,
										'filterModel' => $searchModel,
										'total' => '<tr height="40">
													<td></td>	
													<td align="left" id="pt0"><strong>合计</strong></td>
													<td align="left" id="pt1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="pt2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="pt3"></td>
													<td align="left" id="pt4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="pt5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="pt6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="pt7"><strong>'.count(Plant::getAllname()).'种</strong></td>
													<td align="left" id="pt8"><strong></strong></td>
													<td align="left" id="pt9"><strong></strong></td>
												</tr>',
										'columns' =>[
											['class' => 'yii\grid\SerialColumn'],
											[
												'label'=>'管理区',
												'attribute'=>'management_area',
												'headerOptions' => ['width' => '130'],
												'value'=> function($model) {
			// 				            	var_dump($model);exit;
													return ManagementArea::getAreanameOne($model->management_area);
												},
												'filter' => ManagementArea::getAreaname(),
											],
											[
												'label' => '农场名称',
												'attribute' => 'farms_id',
												'options' =>['width'=>120],
												'value' => function ($model) {

													return Farms::find ()->where ( [
														'id' => $model->farms_id
													] )->one ()['farmname'];

												}
											],
											[
												'label' => '法人名称',
												'attribute' => 'farmer_id',
												'options' =>['width'=>120],
												'value' => function ($model) {

													return Farms::find ()->where ( [
														'id' => $model->farms_id
													] )->one ()['farmername'];

												}
											],
											[
												'label' => '合同号',
												'attribute' => 'state',
												'value' => function($model) {
													return Farms::find ()->where ( [
														'id' => $model->farms_id
													] )->one ()['contractnumber'];
												},
												'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同'],
											],
											[
												'label' => '合同面积',
												'attribute' => 'contractarea',
												'value' => function($model) {
													return Farms::find ()->where ( [
														'id' => $model->farms_id
													] )->one ()['contractarea'];
												}
											],
											'area',
											[
												'label' => '种植者',
												'attribute' => 'lease_id',
												'value' => function($model) {
													$lessee =  \app\models\Lease::find()->where(['id'=>$model->lease_id])->one()['lessee'];
													if($lessee) {
														return $lessee;
													} else {
														return Farms::find ()->where ( [
															'id' => $model->farms_id
														] )->one ()['farmername'];
													}
												}
											],
											[
												'label' => '种植结构',
												'attribute' => 'plant_id',
												'value' => function($model) {
													return Plant::find()->where(['id'=>$model->plant_id])->one()['typename'];
												},
												'filter' => Huinong::getPlant(),
											],
											[
												'label' => '补贴归属',
			//                                                'attribute' => 'huinong',
												'format' => 'raw',
												'value' => function($model) {
													$plant = Plant::find()->where(['id'=>$model->plant_id])->one();
													if($model->lease_id == 0) {
														if ($plant['typename'] == '大豆') {
															return '<span class="text-green">法人:100%</span>';
														}
														if($plant['typename'] == '玉米') {
															return '<span class="text-blue">种植者:100%</span>';
														}
													} else {
														$lease = Lease::find()->where(['id'=>$model->lease_id])->one();
														if ($plant['typename'] == '大豆') {
															if ($lease['ddcj_farmer'] == '100%') {
																return '<span class="text-green">法人:100%</span>';
															}
															if ($lease['ddcj_lessee'] == '100%') {
																return '<span class="text-blue">种植者:100%</span>';
															}
															return '<span class="text-red">法人:' . $lease['ddcj_farmer'] . ' 种植者:' . $lease['ddcj_lessee'].'</span>';
														}
														if ($plant['typename'] == '玉米') {
															if ($lease['ymcj_farmer'] == '100%') {
																return '<span class="text-green">法人:100%</span>';
															}
															if ($lease['ymcj_lessee'] == '100%') {
																return '<span class="text-blue">种植者:100%</span>';
															}
															return '<span class="text-red">法人:' . $lease['ymcj_farmer'] . ' 种植者:' . $lease['ymcj_lessee'].'</span>';
														}
													}
													return '';
												},
			//                                                'filter' => $huinongArray,
											],
			//                                            [
			//                                                'label' => '补贴归属(种植者)',
			//                                                'value' => function($model) {
			//                                                    if($model->issame) {
			//                                                        return '0%';
			//                                                    }
			//                                                    $plant = Plant::find()->where(['id'=>$model->plant_id])->one();
			//                                                    $lease = Lease::find()->where(['id'=>$model->lease_id])->one();
			//                                                    if($plant['typename'] == '大豆') {
			//                                                        return $lease['ddcj_lessee'];
			//                                                    }
			//                                                    if($plant['typename'] == '玉米') {
			//                                                        return $lease['ymcj_lessee'];
			//                                                    }
			//                                                    return '';
			//                                                }
			//                                            ],

											[
												'label' => '筛选',
												'attribute' => 'issame',
												'format' => 'raw',
												'value' => function($model) {
													if($model->issame) {
														return '<span class="text-green">法人种植</span>';
													} else {
														return '<span class="text-blue">承租者种植</span>';
													}
												},
												'filter' => [0=>'承租者种植',1=>'法人种植'],
											],
										],

									]); ?>
								</div>
							</div>
							</div>
						</div>
					</div>
				</div>
	</section>
	<?php ActiveFormrdiv::end(); ?>
</div>
