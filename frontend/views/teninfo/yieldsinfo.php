<?php

use app\models\Tables;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Plant;
use app\models\User;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use frontend\helpers\arraySearch;
use app\models\Yieldbase;
use frontend\helpers\MoneyFormat;
use app\models\Lease;
use app\models\Plantingstructurecheck;
use frontend\helpers\ES;
use frontend\helpers\Tab;
use app\models\Yields;
use frontend\helpers\Echartsdata;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<?php
    //往年情况
    $totalDataLast = clone $dataProviderLast;
    $totalDataLast->pagination = ['pagesize'=>0];
    $dataLast = arraySearch::find($totalDataLast)->search();
    $arrclassLast = explode('\\',$dataProviderLast->query->modelClass);

    $totalDataSalesLast = clone $salesDataLast;
    $totalDataSalesLast->pagination = ['pagesize'=>0];
    $dataSalesLast = arraySearch::find($totalDataSalesLast)->search();
    $arrclassSalesLast = explode('\\',$salesDataLast->query->modelClass);
    //当年情况
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
    $arrclass = explode('\\',$dataProvider->query->modelClass);

    $totalDataSales = clone $salesData;
    $totalDataSales->pagination = ['pagesize'=>0];
    $dataSales = arraySearch::find($totalDataSales)->search();
    $arrclassSales = explode('\\',$salesData->query->modelClass);
?>
 <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php User::tableBegin('农产品统计表')?>
            <ul class="nav nav-pills nav-pills-warning">
                <li class="active" id="plantsLast"><a href="#plantsLastlist" data-toggle="tab" aria-expanded="true">农作物产量统计(<?= User::getLastYear()?>年度)</a></li>
                <li id="salesLast"><a href="#salesLastlist" data-toggle="tab" aria-expanded="false">农作物销量统计(<?= User::getLastYear()?>年度)</a></li>
                <li class="" id="plantEchartsLast"><a href="#plantEchartsLastlist" data-toggle="tab" aria-expanded="false">农作物产量图表(<?= User::getLastYear()?>年度)</a></li>
                <?php if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {?>
                    <li class="" id="goodseedEchartsLast"><a href="#goodseedEchartsLastlist" data-toggle="tab" aria-expanded="false">良种图表(<?= User::getLastYear()?>年度)</a></li>
                <?php }?>

              <li class="" id="plants"><a href="#plantslist" data-toggle="tab" aria-expanded="true">农作物产量统计(<?= User::getYear()?>年度)</a></li>
              <li id="farms"><a href="#farmslist" data-toggle="tab" aria-expanded="false">农作物销量统计(<?= User::getYear()?>年度)</a></li>
              <li class="" id="plantEcharts"><a href="#plantEchartslist" data-toggle="tab" aria-expanded="false">农作物产量图表(<?= User::getYear()?>年度)</a></li>
              <?php if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {?>
              <li class="" id="goodseedEcharts"><a href="#goodseedEchartslist" data-toggle="tab" aria-expanded="false">良种图表(<?= User::getYear()?>年度)</a></li>
              <?php }?>
            </ul>
            <div class="tab-content">
<!--                往年信息-->
                <div class="tab-pane active" id="plantsLastlist">
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderLast,
                        'filterModel' => $searchModelLast,
                        'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t0l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t1l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3l"><strong></strong></td>
                                        <td align="left" id="t4l"><strong></strong></td>
                                        <td align="left" id="t5l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t6l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t7l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t8l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t9l"><strong></strong></td>
                                        <td align="left" id="t10l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                    </tr>',
                        'columns' => [
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
                                'options' =>['width'=>180],
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
                            [
                                'label' => '承租人',
                                'attribute' => 'lease_id',
                                'value' => function($model) {
                                    return \app\models\Lease::find()->where(['id'=>$model->lease_id])->one()['lessee'];
                                }
                            ],
                            [
                                'label' => '种植结构',
                                'attribute' => 'plant_id',
                                'value' => function($model) {
                                    return \app\models\Plant::find()->where(['id'=>$model->plant_id])->one()['typename'];
                                },
                                'filter' => \app\models\Plantingstructure::getPlantname($totalDataLast)
                            ],
                            [
                                'label' => '良种',
                                'attribute' => 'goodseed_id',
                                'options' => ['width'=>150],
                                'value' => function($model) {
                                    return \app\models\Goodseed::find()->where(['id'=>$model->goodseed_id])->one()['typename'];
                                },
                                'filter' => \app\models\Plantingstructure::getAllname($totalDataLast)
                            ],
                            'area',
                            [
                                'label' => '单产（每亩）',
                                'options' =>['width'=>150],
                                'value' => function ($model) {
                                    $yield = Yieldbase::find()->where(['plant_id'=>$model->plant_id,'year'=>User::getLastYear()])->one()['yield'];
                                    if(empty($yield)) {
                                        $yield = 0;
                                    }
                                    return $yield . '斤';
                                }
                            ],
                            [
                                'label' => '总产',
                                'options' =>['width'=>200],
                                'value' => function ($model) {
                                    return MoneyFormat::num_format($model->area * Yieldbase::find()->where(['plant_id'=>$model->plant_id,'year'=>User::getLastYear()])->one()['yield']) . '斤';
                                }
                            ],

                        ],
                    ]); ?>
                </div>

                <div class="tab-pane" id="salesLastlist">
                    <?= GridView::widget([
                        'dataProvider' => $salesDataLast,
                        'filterModel' => $salesSearchLast,
                        'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t20l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t21l"><strong></strong></td>
                                        <td align="left" id="t22l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t23l"><strong></strong></td>
                                        <td align="left" id="t24l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t25l"><strong></strong></td>
                                        <td align="left" id="t26l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t27l"><strong></strong></td>
                                        <td align="left" id="t28l"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t29l"><strong></strong></td>
                                    </tr>',
                        'columns' => [
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
                                    $farm = Farms::findOne($model->farms_id);
                                    return $farm->farmname;
                                }
                            ],
                            [
                                'label' => '法人名称',
                                'attribute' => 'farmer_id',
                                'options' =>['width'=>120],
                                'value' => function ($model) {
                                    $farm = Farms::findOne($model->farms_id);
                                    return $farm->farmername;
                                }
                            ],
                            [
                                'label' => '合同号',
                                'attribute' => 'state',
                                'value' => function($model) {
                                    $farm = Farms::findOne($model->farms_id);
                                    return $farm->contractnumber;
                                },
                                'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同'],
                            ],
                            [
                                'label' => '合同面积',
                                'attribute' => 'contractarea',
//                                'options' => ['width'=>100],
                                'value' => function($model) {
                                    $farm = Farms::findOne($model->farms_id);
                                    return $farm->contractarea;
                                }
                            ],
                            [
                                'label' => '农作物',
                                'attribute' => 'plant_id',
                                'value' => function($model) {
                                    $plant = Plant::findOne($model->plant_id);
                                    return $plant->typename;
                                },
                                'filter' => Plant::getSalesAllname(User::getLastYear()),
                            ],
                            [
                                'label' => '销售数量',
                                'attribute' => 'volume',
                                'value' => function($model) {
                                    return $model->volume.'斤';
                                }
                            ],
                            [
                                'label' => '销售单价',
                                'attribute' => 'price',
                                'value' => function($model) {
                                    return $model->price.'元';
                                }
                            ],
                            [
                                'label' => '销售总额',
                                'value' => function($model) {
                                    return sprintf('%.2f',$model->volume*$model->price).'元';
                                }
                            ],
                            [
                                'attribute' => 'whereabouts',
                                'value' => function($model) {
                                    $type = \app\models\Saleswhere::findOne($model->whereabouts);
//                                    var_dump($type);
                                    return $type['wherename'];
                                },
                                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Saleswhere::find()->all(),'id','wherename'),
                            ],
                        ],
                    ]); ?>
                </div>
                <!-- /.tab-pane -->
                <div class='tab-pane' id="plantEchartsLastlist">
                    <div class="row">
                        <div class="col-xs-6" id="plantingstructuredataLast" style="width: 800px; height: 600px; margin: 0 auto;"></div>
                        <div class="col-xs-6" id="saleLast" style="width: 800px; height: 600px; margin: 0 auto;"></div>
                    </div>
                    <?php
//                        $s = $dataLast->getName('Plant', 'typename', 'plant_id')->showAllShadow('mulyieldSum',['area', 'plant_id']);
                    $ls = Echartsdata::getYieldsCache($totalDataLast);
                    $lx = Echartsdata::getYieldsTypename($totalDataLast)['typename'];
                    //柱形图,options=['title'=>'测试','tooltip'=>[],'legend'=>['销量'],'xAxis'=>["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"],'yAxis'=>[],'series'=>[5, 20, 36, 10, 10, 20]]
                    echo ES::bar()->DOM('plantingstructuredataLast',false)->options(['title'=>'产量统计表','legend'=>['产量'],'xAxis'=>$lx,'unit'=>'斤','series'=>$ls])->JS();
                    $x = Echartsdata::getSalestypename($totalDataSalesLast,'typename');
                    $s = Echartsdata::getSalesinfo($totalDataSalesLast);
//                    var_dump($s);var_dump($x);
    //饼形图,options=['title'=>'某站点用户访问来源','legend'=>['直接访问','邮件营销','联盟广告','视频广告','搜索引擎'],'series'=>[['value'=>335, 'name'=>'直接访问'], ['value'=>310, 'name'=>'邮件营销'], ['value'=>234, 'name'=>'联盟广告'], ['value'=>135, 'name'=>'视频广告'], ['value'=>1548, 'name'=>'搜索引擎']]]
                    echo ES::pie()->DOM('saleLast',false)->options(['title'=>'销量统计表','name'=>'销售去向','legend'=>$x,'unit'=>'斤','series'=>$s])->JS();
                    ?>

                </div>

                <!-- /.tab-pane -->

                <?php if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {?>
                    <div id="goodseedEchartsLastlist" class='tab-pane'>
                        <div id="goodseedinfoLast" style="width: 1000px; height: 600px; margin: 0 auto;"></div>
                        <?php
                        $gls = Echartsdata::getGoodseedinfo($totalDataLast);
                        $glx = Echartsdata::getGoodseedTypename($totalDataLast,'','typename');
                        echo ES::bar()->DOM('goodseedinfoLast',false)->options(['title'=>'良种产量统计表','legend'=>['产量'],'xAxis'=>$glx,'unit'=>'斤','series'=>$gls])->JS();
                        ?>
                    </div>

                <?php }?>
<!--                当年信息-->
              <div class="tab-pane" id="plantslist">
               <?= GridView::widget([
			        'dataProvider' => $dataProvider,
			        'filterModel' => $searchModel,
                   'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t0"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"><strong></strong></td>
                                        <td align="left" id="t4"><strong></strong></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t7"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t8"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t9"><strong></strong></td>
                                        <td align="left" id="t10"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                    </tr>',
                    'columns' => [
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
                            'options' =>['width'=>180],
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
                        [
                            'label' => '承租人',
                            'attribute' => 'lease_id',
                            'value' => function($model) {
                                return \app\models\Lease::find()->where(['id'=>$model->lease_id])->one()['lessee'];
                            }
                        ],
                        [
                            'label' => '种植结构',
                            'attribute' => 'plant_id',
                            'value' => function($model) {
                                return \app\models\Plant::find()->where(['id'=>$model->plant_id])->one()['typename'];
                            },
                            'filter' => Plant::getAllname(User::getYear()),
                        ],
                        [
                            'label' => '良种',
                            'attribute' => 'goodseed_id',
                            'value' => function($model) {
                                return \app\models\Goodseed::find()->where(['id'=>$model->goodseed_id])->one()['typename'];
                            }
                        ],
                        'area',
                        [
                            'label' => '单产（每亩）',
                            'options' =>['width'=>150],
                            'value' => function ($model) {
                                $yield = Yieldbase::find()->where(['plant_id'=>$model->plant_id,'year'=>User::getYear()])->one()['yield'];
                                if(empty($yield)) {
                                    $yield = 0;
                                }
                                return $yield . '斤';
                            }
                        ],
                        [
                            'label' => '总产',
                            'options' =>['width'=>200],
                            'value' => function ($model) {
                                return MoneyFormat::num_format($model->area * Yieldbase::find()->where(['plant_id'=>$model->plant_id,'year'=>User::getYear()])->one()['yield']) . '斤';
                            }
                        ],

                    ],
			    ]); ?>
              </div>

                <div class="tab-pane" id="farmslist">
                    <?= GridView::widget([
                        'dataProvider' => $salesData,
                        'filterModel' => $salesSearch,
                        'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t20"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t21"><strong></strong></td>
                                        <td align="left" id="t22"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t23"><strong></strong></td>
                                        <td align="left" id="t24"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t25"><strong></strong></td>
                                        <td align="left" id="t26"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t27"><strong></strong></td>
                                        <td align="left" id="t28"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                    </tr>',
                        'columns' => [
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
                                    $farm = Farms::findOne($model->farms_id);
                                    return $farm->farmname;
                                }
                            ],
                            [
                                'label' => '法人名称',
                                'attribute' => 'farmer_id',
                                'options' =>['width'=>120],
                                'value' => function ($model) {
                                    $farm = Farms::findOne($model->farms_id);
                                    return $farm->farmername;
                                }
                            ],
                            [
                                'label' => '合同号',
                                'attribute' => 'state',
                                'value' => function($model) {
                                    $farm = Farms::findOne($model->farms_id);
                                    return $farm->contractnumber;
                                },
                                'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同'],
                            ],
                            [
                                'label' => '合同面积',
                                'attribute' => 'contractarea',
//                                'options' => ['width'=>100],
                                'value' => function($model) {
                                    $farm = Farms::findOne($model->farms_id);
                                    return $farm->contractarea;
                                }
                            ],
                            [
                                'label' => '农作物',
                                'format' => 'raw',
                                'value' => function($model) {
                                    $plant = Plant::findOne($model->plant_id);
                                    return $plant->typename;
                                }
                            ],
                            [
                                'label' => '销售数量',
                                'attribute' => 'volume',
                                'value' => function($model) {
                                    return $model->volume.'斤';
                                }
                            ],
                            [
                                'label' => '销售单价',
                                'attribute' => 'price',
                                'value' => function($model) {
                                    return $model->price.'元';
                                }
                            ],
                            [
                                'label' => '销售总额',
                                'value' => function($model) {
                                    return sprintf('%.2f',$model->volume*$model->price).'元';
                                }
                            ],
                            [
                                'attribute' => 'whereabouts',
                                'value' => function($model) {
                                    $type = \app\models\Saleswhere::findOne($model->whereabouts);
//                                    var_dump($type);
                                    return $type['wherename'];
                                },
                                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Saleswhere::find()->all(),'id','wherename'),
                            ],

                        ],
                    ]); ?>
                </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id="plantEchartslist">
                  <div class="row">
                      <div class="col-xs-6" id="plantingstructuredata" style="width: 800px; height: 600px; margin: 0 auto;"></div>
                      <div class="col-xs-6" id="sale" style="width: 800px; height: 600px; margin: 0 auto;"></div>
                  </div>
				<?php
                $ns = Echartsdata::getYieldsCache($totalData);
                $nx = Echartsdata::getYieldsTypename($totalData)['typename'];
                $ss = Echartsdata::getSalesinfo($totalDataSales);
                $sx = Echartsdata::getSalestypename($totalDataSales,'typename');
                ?>
                  <?php
//                  $series = Yields::get
                  //$html .= ES::barLabel2()->DOM($position,false)->options(['color'=>['#003366', '#e5323e'],'legend'=>['计划','复核'],'xAxis'=>$x,'series'=>[$newdata->plan,$newdata->fact],'unit'=>'亩'])->JS();
                  echo ES::bar()->DOM('plantingstructuredata',false)->options(['title'=>'产量统计表','legend'=>['产量'],'xAxis'=>$nx,'unit'=>'斤','series'=>$ns])->JS();
                  echo ES::pie()->DOM('sale',false)->options(['title'=>'销量统计表','legend'=>$sx,'unit'=>'斤','series'=>$ss])->JS();
                  ?>
              </div>

              <!-- /.tab-pane -->

               <?php if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {?>
                    <div id="goodseedEchartslist" class='tab-pane'>
                    <div id="goodseedinfo" style="width: 1500px; height: 500px; margin: 0 auto;"></div>
                      <?php
                      $ngs = Echartsdata::getGoodseedinfo($totalData);
                      $ngx = Echartsdata::getGoodseedTypename($totalData,'','typename');
                      echo ES::bar()->DOM('goodseedinfo',false)->options(['title'=>'良种产量统计表','legend'=>['产量'],'xAxis'=>$ngx,'unit'=>'斤','series'=>$ngs])->JS();
                      ?>
                    </div>

            <?php }?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
            <?php User::dataListEnd();?>
        </div>
    </div>
</section>
</div>
<?php
$tab = new Tab();
$array = ['plantsLast','salesLast','plantEchartsLast','plants','farms','plantEcharts'];
//var_dump($data->getName('Goodseed', 'typename', 'goodseed_id')->getList());
if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {
    $array = ['plantsLast','salesLast','plantEchartsLast','goodseedEchartsLast','plants','farms','plantEcharts','goodseedEcharts'];
}
echo $tab->createTab(Yii::$app->controller->action->id,$array);
?>
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
//        if($.session.get('<?//= Yii::$app->controller->action->id?>//') == 'plants') {
//            $('#plants').attr('class','active');
//            $('#farms').attr('class','');
//            $('#plantEcharts').attr('class','');
//            $('#goodseedEcharts').attr('class','');
//
//            $('#plants').attr('aria-expanded',true);
//            $('#farms').attr('aria-expanded',false);
//            $('#plantEcharts').attr('aria-expanded',false);
//            $('#goodseedEcharts').attr('aria-expanded',false);
//
//            $('#plantsTable').attr('class','tab-pane active');
//            $('#farmsTable').attr('class','tab-pane');
//            $('#plantingstructureData').attr('class','tab-pane');
//            $('#goodseedData').attr('class','tab-pane')
//        }
//        if($.session.get('<?//= Yii::$app->controller->action->id?>//') == 'farms') {
//            $('#plants').attr('class','');
//            $('#farms').attr('class','active');
//            $('#plantEcharts').attr('class','');
//            $('#goodseedEcharts').attr('class','');
//
//            $('#plants').attr('aria-expanded',false);
//            $('#farms').attr('aria-expanded',true);
//            $('#plantEcharts').attr('aria-expanded',false);
//            $('#goodseedEcharts').attr('aria-expanded',false);
//
//            $('#plantsTable').attr('class','tab-pane');
//            $('#farmsTable').attr('class','tab-pane active');
//            $('#plantingstructureData').attr('class','tab-pane');
//            $('#goodseedData').attr('class','tab-pane')
//        }
//        if($.session.get('<?//= Yii::$app->controller->action->id?>//') == 'plantEcharts') {
//            $('#plants').attr('class','');
//            $('#farms').attr('class','');
//            $('#plantEcharts').attr('class','active');
//            $('#goodseedEcharts').attr('class','');
//
//            $('#plants').attr('aria-expanded',false);
//            $('#farms').attr('aria-expanded',false);
//            $('#plantEcharts').attr('aria-expanded',true);
//            $('#goodseedEcharts').attr('aria-expanded',false);
//
//            $('#plantsTable').attr('class','tab-pane');
//            $('#farmsTable').attr('class','tab-pane');
//            $('#plantingstructureData').attr('class','tab-pane active');
//            $('#goodseedData').attr('class','tab-pane')
//        }
//        if($.session.get('<?//= Yii::$app->controller->action->id?>//') == 'goodseedEcharts') {
//            $('#plants').attr('class','');
//            $('#farms').attr('class','');
//            $('#plantEcharts').attr('class','');
//            $('#goodseedEcharts').attr('class','active');
//
//            $('#plants').attr('aria-expanded',false);
//            $('#farms').attr('aria-expanded',false);
//            $('#plantEcharts').attr('aria-expanded',false);
//            $('#goodseedEcharts').attr('aria-expanded',true);
//
//            $('#plantsTable').attr('class','tab-pane');
//            $('#farmsTable').attr('class','tab-pane');
//            $('#plantingstructureData').attr('class','tab-pane');
//            $('#goodseedData').attr('class','tab-pane active')
//        }
//
//    $('#plants').click(function () {
//        $.session.set('<?//= Yii::$app->controller->action->id?>//', 'plants');
//        $('#plants').attr('aria-expanded',true);
//        $('#farms').attr('aria-expanded',false);
//        $('#plantEcharts').attr('aria-expanded',false);
//        $('#goodseedEcharts').attr('aria-expanded',false);
//        $('#plants').attr('class','active');
//        $('#farms').attr('class','');
//        $('#plantEcharts').attr('class','');
//        $('#goodseedEcharts').attr('class','');
//        $('#plantsTable').attr('class','tab-pane active');
//        $('#farmsTable').attr('class','tab-pane');
//        $('#plantingstructureData').attr('class','tab-pane');
//        $('#goodseedData').attr('class','tab-pane')
//    });
//    $('#farms').click(function () {
//        $.session.set('<?//= Yii::$app->controller->action->id?>//', 'farms');
//        $('#plants').attr('aria-expanded',false);
//        $('#farms').attr('aria-expanded',true);
//        $('#plantEcharts').attr('aria-expanded',false);
//        $('#goodseedEcharts').attr('aria-expanded',false);
//        $('#plants').attr('class','');
//        $('#farms').attr('class','active');
//        $('#plantEcharts').attr('class','');
//        $('#goodseedEcharts').attr('class','');
//        $('#plantsTable').attr('class','tab-pane');
//        $('#farmsTable').attr('class','tab-pane active');
//        $('#plantingstructureData').attr('class','tab-pane');
//        $('#goodseedData').attr('class','tab-pane')
//    });
//    $('#plantEcharts').click(function () {
//        $.session.set('<?//= Yii::$app->controller->action->id?>//', 'plantEcharts');
//        $('#plants').attr('aria-expanded',false);
//        $('#farms').attr('aria-expanded',false);
//        $('#plantEcharts').attr('aria-expanded',true);
//        $('#goodseedEcharts').attr('aria-expanded',false);
//        $('#plants').attr('class','');
//        $('#farms').attr('class','');
//        $('#plantEcharts').attr('class','active');
//        $('#goodseedEcharts').attr('class','');
//        $('#plantsTable').attr('class','tab-pane');
//        $('#farmsTable').attr('class','tab-pane');
//        $('#plantingstructureData').attr('class','tab-pane active');
//        $('#goodseedData').attr('class','tab-pane')
//    });
//    $('#goodseedEcharts').click(function () {
//        $.session.set('<?//= Yii::$app->controller->action->id?>//', 'goodseedEcharts');
//        $('#plants').attr('aria-expanded',false);
//        $('#farms').attr('aria-expanded',false);
//        $('#plantEcharts').attr('aria-expanded',false);
//        $('#goodseedEcharts').attr('aria-expanded',true);
//        $('#plants').attr('class','');
//        $('#farms').attr('class','');
//        $('#plantEcharts').attr('class','');
//        $('#goodseedEcharts').attr('class','active');
//        $('#plantsTable').attr('class','tab-pane');
//        $('#farmsTable').attr('class','tab-pane');
//        $('#plantingstructureData').attr('class','tab-pane');
//        $('#goodseedData').attr('class','tab-pane active')
//    });

        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t0').html('合计('+ data + ')户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-lease_id'}, function (data) {
            $('#t1').html('种植者'+ data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html('法人种植'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t4').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-lease_id',andwhere:'<?= json_encode('lease_id>0')?>'}, function (data) {
            $('#t5').html('承租人'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-plant_id',andwhere:'',orderby:'',groupby:'plant_id'}, function (data) {
            $('#t6').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-goodseed_id',andwhere:'<?= json_encode('goodseed_id>0')?>'}, function (data) {
            $('#t7').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-area'}, function (data) {
            $('#t8').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassLast[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'mulyieldSum-plant_id'}, function (data) {
            $('#t10').html(data + '斤');
        });

        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassLast[2]?>',where:'<?= json_encode($dataProviderLast->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t0l').html('合计('+ data + ')户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassLast[2]?>',where:'<?= json_encode($dataProviderLast->query->where)?>',command:'count-lease_id'}, function (data) {
            $('#t1l').html('种植者'+ data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassLast[2]?>',where:'<?= json_encode($dataProviderLast->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2l').html('法人种植'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassLast[2]?>',where:'<?= json_encode($dataProviderLast->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t4l').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassLast[2]?>',where:'<?= json_encode($dataProviderLast->query->where)?>',command:'count-lease_id',andwhere:'<?= json_encode('lease_id>0')?>'}, function (data) {
            $('#t5l').html('承租人'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassLast[2]?>',where:'<?= json_encode($dataProviderLast->query->where)?>',command:'count-plant_id',andwhere:'',orderby:'',groupby:'plant_id'}, function (data) {
            $('#t6l').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassLast[2]?>',where:'<?= json_encode($dataProviderLast->query->where)?>',command:'count-goodseed_id',andwhere:'<?= json_encode('goodseed_id>0')?>'}, function (data) {
            $('#t7l').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassLast[2]?>',where:'<?= json_encode($dataProviderLast->query->where)?>',command:'sum-area'}, function (data) {
            $('#t8l').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassLast[2]?>',where:'<?= json_encode($dataProviderLast->query->where)?>',command:'mulyieldSum-plant_id'}, function (data) {
            $('#t10l').html(data + '斤');
        });
    });

    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassSales[2]?>',where:'<?= json_encode($salesData->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t20').html('合计('+ data + ')户');
        });
//        $.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclassFarms[2]?>//',where:'<?//= json_encode($farmsData->query->where)?>//',command:'count-lease_id'}, function (data) {
//            $('#t21').html('种植者'+ data + '人');
//        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassSales[2]?>',where:'<?= json_encode($salesData->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t22').html('法人'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassSales[2]?>',where:'<?= json_encode($salesData->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t24').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassSales[2]?>',where:'<?= json_encode($salesData->query->where)?>',command:'sum-volume'}, function (data) {
            $('#t26').html(data + '斤');
        });

        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassSales[2]?>',where:'<?= json_encode($salesData->query->where)?>',command:'mulsum-volume*price'}, function (data) {
            $('#t28').html(data + '元');
        });

    });
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassSalesLast[2]?>',where:'<?= json_encode($salesDataLast->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t20l').html('合计('+ data + ')户');
        });
//        $.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclassFarms[2]?>//',where:'<?//= json_encode($farmsData->query->where)?>//',command:'count-lease_id'}, function (data) {
//            $('#t21').html('种植者'+ data + '人');
//        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassSalesLast[2]?>',where:'<?= json_encode($salesDataLast->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t22l').html('法人'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassSalesLast[2]?>',where:'<?= json_encode($salesDataLast->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t24l').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassSalesLast[2]?>',where:'<?= json_encode($salesDataLast->query->where)?>',command:'sum-volume'}, function (data) {
            $('#t26l').html(data + '斤');
        });

        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassSalesLast[2]?>',where:'<?= json_encode($salesDataLast->query->where)?>',command:'mulsum-volume*price'}, function (data) {
            $('#t28l').html(data + '元');
        });
    });
</script>
		