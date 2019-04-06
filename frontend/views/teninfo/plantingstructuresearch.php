<?php

use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Lease;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Url;
use frontend\helpers\ActiveFormrdiv;
use app\models\Search;
use frontend\helpers\arraySearch;
use frontend\helpers\Echartsdata;
use frontend\helpers\ES;
use frontend\helpers\Tab;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

   <?= $this->render('..//search/searchindex',['tab'=>$tab,'class'=>$class,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
<?php 

	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
// 	$data->getName('Plant', 'typename', 'plant_id')->getEchartsData('area',10000);
//	$planter = $data->count('farmer_id');
//	$leaseer = $data->count('lease_id');
//	$plantFarmer = $planter - $leaseer;
//var_dump($dataProvider->query->where);
$arrclass = explode('\\',$dataProvider->query->modelClass);
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-pills nav-pills-warning">
              <li class="active" id="plants"><a href="#plantslist" data-toggle="tab" aria-expanded="true">种植结构调查数据表</a></li>
                <li class="" id="plantscheck"><a href="#plantschecklist" data-toggle="tab" aria-expanded="false">复核数据表</a></li>
              <li class="" id="plantscheckEcharts"><a href="#plantscheckEchartslist" data-toggle="tab" aria-expanded="false"> 柱状对比图表</a></li>
               <li class="" id="bing"><a href="#binglist" data-toggle="tab" aria-expanded="false">饼状对比图表</a></li>
              <?php if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {?>
              <li class="" id="goodseedEcharts"><a href="#goodseedEchartslist" data-toggle="tab" aria-expanded="false">良种对比图表</a></li>
              <?php }?>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="plantslist">
               <?= GridView::widget([
			        'dataProvider' => $dataProvider,
			        'filterModel' => $searchModel,
                   'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t0"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
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
                            'options' =>['width'=>150],
                            'value' => function ($model) {

                                return Farms::find ()->where ( [
                                    'id' => $model->farms_id
                                ] )->one ()['farmername'];

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
                            'filter' => Plantingstructure::getPlantname($totalData)
                        ],
                        [
                            'label' => '良种',
                            'attribute' => 'goodseed_id',
                            'value' => function($model) {
                                return \app\models\Goodseed::find()->where(['id'=>$model->goodseed_id])->one()['typename'];
                            },
                            'filter' => \app\models\Plantingstructurecheck::getPlan_checkGoodseedname($totalData,'filter')
                        ],
                        'area',
                    ],
			    ]); ?>
              </div>
                <div class="tab-pane" id="plantschecklist">
                    <?php
                    $totalDataCheck = clone $dataProviderCheck;
                    $totalDataCheck->pagination = ['pagesize'=>0];
                    ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderCheck,
                        'filterModel' => $searchCheckModel,
                        'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="ct0"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="ct1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="ct2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="ct3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="ct4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="ct5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="ct6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
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
                                'filter' => \app\models\Plantingstructurecheck::getPlantname($totalDataCheck)
                            ],
                            [
                                'label' => '良种',
                                'attribute' => 'goodseed_id',
                                'value' => function($model) {
                                    return \app\models\Goodseed::find()->where(['id'=>$model->goodseed_id])->one()['typename'];
                                },
                                'filter' => \app\models\Plantingstructurecheck::getPlan_checkGoodseedname($totalDataCheck,'filter')
                            ],
                            'area',
                            [
                                'attribute' => 'issame',
                                'format' => 'raw',
                                'value' => function($model) {
                                    if($model->issame) {
                                        return '<span class="text-green">一致</span>';
                                    } else {
                                        return '<span class="text-blue">不一致</span>';
                                    }
                                },
                                'filter' => [0=>'不一致',1=>'一致'],
                            ],
                        ],
                    ]); ?>
                </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id="plantscheckEchartslist">
                  <?php
                  $edata = Echartsdata::getPlantingstructureEcharts4($totalDataCheck);
                  $x = Echartsdata::getPlantname($totalDataCheck,'sort');
                  //$html .= ES::barLabel2()->DOM($position,false)->options(['color'=>['#003366', '#e5323e'],'legend'=>['计划','复核'],'xAxis'=>$x,'series'=>[$newdata->plan,$newdata->fact],'unit'=>'亩'])->JS();
                  echo ES::barLabel2()->DOM('plantingstructuredatazhu',true,'1500px','500px')->options(['color'=>['#003366', '#e5323e'],'legend'=>['计划','复核'],'xAxis'=>$x,'series'=>[$edata['plan'],$edata['fact']],'unit'=>'亩'])->JS();
                  ?>
              </div>
                <div class='tab-pane' id="binglist">
                    <?php
                    $edata = Echartsdata::getPlantingstructureInfoEcharts4($totalDataCheck);
                    //                                var_dump($edata);exit;
                    $x = Echartsdata::getPlantname($totalDataCheck,'sort');
                    //饼形图(左右两个),options=['title'=>'南丁格尔玫瑰图','subtext'=>'虚构','legend'=>['rose1','rose2','rose3','rose4','rose5','rose6','rose7','rose8'],'series'=>['name'=>['半径模式','面积模式'],'data'=>[$sdata1,$sdata2]]]
                    echo ES::pie2()->DOM('plantingstructuredatabing',true,'1500px','500px')->options(['title'=>'精准农业计划与复核对比','legend'=>$x,'series'=>['name'=>['计划','复核'],'data'=>[$edata['plan'],$edata['fact']]],'unit'=>'亩'])->JS();
                    ?>
                </div>

              <!-- /.tab-pane -->

               <?php if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {?>
            <div id="goodseedEchartslist" class='tab-pane'>
              <?php
              $gx = Echartsdata::getGoodseedTypename($totalData,$totalDataCheck,'typename');
              $gs = Echartsdata::getGoodseedinfo($totalData,$totalDataCheck);
              echo ES::barLabel2()->DOM('goodseedinfo',true,'1500px','500px')->options(['color'=>['#003366', '#e5323e'],'legend'=>['计划','复核'],'xAxis'=>$gx,'series'=>[$edata['plan'],$edata['fact']],'unit'=>'亩'])->JS();
              ?>
            </div>
            
            <?php }?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php
$tab = new Tab();
echo $tab->createTab(Yii::$app->controller->action->id,['plants','plantscheck','plantscheckEcharts','bing','goodseedEcharts']);
?>

<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t0').html('合计('+ data + ')户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t1').html('种植者'+ data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id',andwhere:'<?= json_encode(['lease_id'=>0])?>'}, function (data) {
            $('#t2').html('法人种植'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-lease_id'}, function (data) {
            $('#t3').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-plant_id'}, function (data) {
            $('#t4').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-goodseed_id'}, function (data) {
            $('#t5').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-area'}, function (data) {
            $('#t6').html(data + '亩');
        });

        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($dataProviderCheck->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#ct0').html('合计('+ data + ')户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($dataProviderCheck->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#ct1').html('种植者'+ data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($dataProviderCheck->query->where)?>',command:'count-farmer_id',andwhere:'<?= json_encode(['lease_id'=>0])?>'}, function (data) {
            $('#ct2').html('法人种植'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($dataProviderCheck->query->where)?>',command:'count-lease_id'}, function (data) {
            $('#ct3').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($dataProviderCheck->query->where)?>',command:'count-plant_id'}, function (data) {
            $('#ct4').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($dataProviderCheck->query->where)?>',command:'count-goodseed_id'}, function (data) {
            $('#ct5').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($dataProviderCheck->query->where)?>',command:'sum-area'}, function (data) {
            $('#ct6').html(data + '亩');
        });
    });
</script>
		