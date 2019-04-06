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
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

   <?= $this->render('..//search/searchindex',['tab'=>$tab,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
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
              <li class="active" id="plants"><a href="#plantsTable" data-toggle="tab" aria-expanded="true">种植结构调查数据表</a></li>
                <li class="" id="plantscheck"><a href="#plantscheckTable" data-toggle="tab" aria-expanded="false">复核数据表</a></li>
              <li class="" id="plantscheckEcharts"><a href="#plantscheckData" data-toggle="tab" aria-expanded="false"> 复核种植结构图表</a></li>
               <li class="" id="issameEcharts"><a href="#issameData" data-toggle="tab" aria-expanded="false">与调查数据对比图表</a></li>
              <?php if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {?>
              <li class="" id="goodseedEcharts"><a href="#goodseedData" data-toggle="tab" aria-expanded="false">良种图表</a></li>
              <?php }?>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="plantsTable">
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
                            'filter' => Plantingstructure::getAllname($totalData)
                        ],
                        'area',
                    ],
			    ]); ?>
              </div>
                <div class="tab-pane" id="plantscheckTable">
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
                                'filter' => \app\models\Plantingstructurecheck::getAllname($totalDataCheck)
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
              <div class='tab-pane' id="plantscheckData">
              <div id="plantingstructuredata" style="width:1000px; height: 600px; margin: 0 auto"></div>
				<?php $data->getName('Plant', 'typename', 'plant_id')->typenameList();?>
              </div>
              <script type="text/javascript">
				showAllShadow('plantingstructuredata',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($data->getName('Plant', 'typename', 'plant_id')->typenameList())?>,<?= $data->getName('Plant', 'typename', 'plant_id')->showAllShadow('sum','area');?>,'亩');
			</script>
                <?php
                    $issame = [
                        ['value'=>$data->where(['issame'=>1])->count(),'name'=>'一致'],
                        ['value'=>$data->where(['issame'=>0])->count(),'name'=>'不一致'],
                    ];
                ?>
                <div class='tab-pane' id="issameData">
                    <div id="issamedata" style="width:1000px; height: 600px; margin: 0 auto"></div>
                    <?php ?>
                </div>
                <script type="text/javascript">
                    showPie('issamedata','与调查数据对比图表',<?= json_encode(['一致','不一致'])?>,'数量对比',<?= json_encode($issame)?>,'个');
                </script>
              <!-- /.tab-pane -->

               <?php if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {?>
            <div id="goodseedData" class='tab-pane'>
            <div id="goodseedinfo" style="width: 1000px; height: 600px; margin: 0 auto;"></div>
              <?php //var_dump(Plantingstructure::getGoodseedname($params));?>
            </div>
            <script type="text/javascript">
				showAllShadow('goodseedinfo',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($data->getName('Goodseed', 'typename', 'goodseed_id')->typenameList())?>,<?= $data->getName('Goodseed', 'typename', 'goodseed_id')->showAllShadow('sum','area');?>,'亩');
		</script>
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
<script>
    $(document).ready(function () {
        if($.session.get('<?= Yii::$app->controller->action->id?>') == 'plants') {
            $('#pinfo').attr('class','active');
            $('#pcinfo').attr('class','');
            $('#farm').attr('class','');
            $('#tbzz').attr('class','');
            $('#tbbz').attr('class','');

            $('#pinfo').attr('aria-expanded',true);
            $('#pcinfo').attr('aria-expanded',false);
            $('#farm').attr('aria-expanded',false);
            $('#tbzz').attr('aria-expanded',false);
            $('#tbbz').attr('aria-expanded',false);

            $('#plantingstructureinfo').attr('class','tab-pane active');
            $('#plantingstructurecheckinfo').attr('class','tab-pane');
            $('#plantinfo').attr('class','tab-pane');
            $('#plantingstructurezhu').attr('class','tab-pane')
            $('#plantingstructurebing').attr('class','tab-pane')
        }
        if($.session.get('<?= Yii::$app->controller->action->id?>') == 'plantscheck') {
            $('#pinfo').attr('class','');
            $('#pcinfo').attr('class','active');
            $('#farm').attr('class','');
            $('#tbzz').attr('class','');
            $('#tbbz').attr('class','');

            $('#pinfo').attr('aria-expanded',false);
            $('#pcinfo').attr('aria-expanded',true);
            $('#farm').attr('aria-expanded',false);
            $('#tbzz').attr('aria-expanded',false);
            $('#tbbz').attr('aria-expanded',false);


            $('#plantingstructureinfo').attr('class','tab-pane');
            $('#plantingstructurecheckinfo').attr('class','tab-pane active');
            $('#plantinfo').attr('class','tab-pane');
            $('#plantingstructurezhu').attr('class','tab-pane')
            $('#plantingstructurebing').attr('class','tab-pane')
        }
        if($.session.get('<?= Yii::$app->controller->action->id?>') == 'issameEcharts') {
            $('#pinfo').attr('class','');
            $('#pcinfo').attr('class','');
            $('#farm').attr('class','active');
            $('#tbzz').attr('class','');
            $('#tbbz').attr('class','');

            $('#pinfo').attr('aria-expanded',false);
            $('#pcinfo').attr('aria-expanded',false);
            $('#farm').attr('aria-expanded',true);
            $('#tbzz').attr('aria-expanded',false);
            $('#tbbz').attr('aria-expanded',false);


            $('#plantingstructureinfo').attr('class','tab-pane');
            $('#plantingstructurecheckinfo').attr('class','tab-pane');
            $('#plantinfo').attr('class','tab-pane active');
            $('#plantingstructurezhu').attr('class','tab-pane')
            $('#plantingstructurebing').attr('class','tab-pane')
        }
        if($.session.get('<?= Yii::$app->controller->action->id?>') == 'plantscheckEcharts') {
            $('#pinfo').attr('class','');
            $('#pcinfo').attr('class','');
            $('#farm').attr('class','');
            $('#tbzz').attr('class','active');
            $('#tbbz').attr('class','');

            $('#pinfo').attr('aria-expanded',false);
            $('#pcinfo').attr('aria-expanded',false);
            $('#farm').attr('aria-expanded',false);
            $('#tbzz').attr('aria-expanded',true);
            $('#tbbz').attr('aria-expanded',false);


            $('#plantingstructureinfo').attr('class','tab-pane');
            $('#plantingstructurecheckinfo').attr('class','tab-pane');
            $('#plantinfo').attr('class','tab-pane');
            $('#plantingstructurezhu').attr('class','tab-pane active')
            $('#plantingstructurebing').attr('class','tab-pane')
        }
        if($.session.get('<?= Yii::$app->controller->action->id?>') == 'goodseedEcharts') {
            $('#pinfo').attr('class','');
            $('#pcinfo').attr('class','');
            $('#farm').attr('class','');
            $('#tbzz').attr('class','');
            $('#tbbz').attr('class','active');

            $('#pinfo').attr('aria-expanded',false);
            $('#pcinfo').attr('aria-expanded',false);
            $('#farm').attr('aria-expanded',false);
            $('#tbzz').attr('aria-expanded',false);
            $('#tbbz').attr('aria-expanded',true);

            $('#plantingstructureinfo').attr('class','tab-pane');
            $('#plantingstructurecheckinfo').attr('class','tab-pane');
            $('#plantinfo').attr('class','tab-pane');
            $('#plantingstructurezhu').attr('class','tab-pane')
            $('#plantingstructurebing').attr('class','tab-pane active')
        }
    });

    $('#pinfo').click(function () {
        $.session.set('<?= Yii::$app->controller->action->id?>', 'pinfo');
        $('#pinfo').attr('aria-expanded',true);
        $('#pcinfo').attr('aria-expanded',false);
        $('#farm').attr('aria-expanded',false);
        $('#tbzz').attr('aria-expanded',false);
        $('#tbbz').attr('aria-expanded',false);
        $('#pinfo').attr('class','active');
        $('#pcinfo').attr('class','');
        $('#farm').attr('class','');
        $('#tbzz').attr('class','');
        $('#tbbz').attr('class','');
        $('#plantingstructureinfo').attr('class','tab-pane active');
        $('#plantingstructurecheckinfo').attr('class','tab-pane');
        $('#plantinfo').attr('class','tab-pane');
        $('#plantingstructurezhu').attr('class','tab-pane')
        $('#plantingstructurebing').attr('class','tab-pane')
    });
    $('#pcinfo').click(function () {
        $.session.set('<?= Yii::$app->controller->action->id?>', 'pcinfo');
        $('#pinfo').attr('aria-expanded',false);
        $('#pcinfo').attr('aria-expanded',true);
        $('#farm').attr('aria-expanded',false);
        $('#tbzz').attr('aria-expanded',false);
        $('#tbbz').attr('aria-expanded',false);
        $('#pinfo').attr('class','');
        $('#pcinfo').attr('class','active');
        $('#farm').attr('class','');
        $('#tbzz').attr('class','');
        $('#tbbz').attr('class','');
        $('#plantingstructureinfo').attr('class','tab-pane');
        $('#plantingstructurecheckinfo').attr('class','tab-pane active');
        $('#plantinfo').attr('class','tab-pane');
        $('#plantingstructurezhu').attr('class','tab-pane')
        $('#plantingstructurebing').attr('class','tab-pane')
    });
    $('#farm').click(function () {
        $.session.set('<?= Yii::$app->controller->action->id?>', 'farm');
        $('#pinfo').attr('aria-expanded',false);
        $('#pcinfo').attr('aria-expanded',false);
        $('#farm').attr('aria-expanded',true);
        $('#tbzz').attr('aria-expanded',false);
        $('#tbbz').attr('aria-expanded',false);
        $('#pinfo').attr('class','');
        $('#pcinfo').attr('class','');
        $('#farm').attr('class','active');
        $('#tbzz').attr('class','');
        $('#tbbz').attr('class','');
        $('#plantingstructureinfo').attr('class','tab-pane');
        $('#plantingstructurecheckinfo').attr('class','tab-pane');
        $('#plantinfo').attr('class','tab-pane active');
        $('#plantingstructurezhu').attr('class','tab-pane')
        $('#plantingstructurebing').attr('class','tab-pane')
    });
    $('#tbzz').click(function () {
        $.session.set('<?= Yii::$app->controller->action->id?>', 'tbzz');
        $('#pinfo').attr('aria-expanded',false);
        $('#pcinfo').attr('aria-expanded',false);
        $('#farm').attr('aria-expanded',false);
        $('#tbzz').attr('aria-expanded',true);
        $('#tbbz').attr('aria-expanded',false);
        $('#pinfo').attr('class','');
        $('#pcinfo').attr('class','');
        $('#farm').attr('class','');
        $('#tbzz').attr('class','active');
        $('#tbbz').attr('class','');
        $('#plantingstructureinfo').attr('class','tab-pane');
        $('#plantingstructurecheckinfo').attr('class','tab-pane');
        $('#plantinfo').attr('class','tab-pane');
        $('#plantingstructurezhu').attr('class','tab-pane active')
        $('#plantingstructurebing').attr('class','tab-pane')
    });
    $('#tbbz').click(function () {
        $.session.set('<?= Yii::$app->controller->action->id?>', 'tbbz');
        $('#pinfo').attr('aria-expanded',false);
        $('#pcinfo').attr('aria-expanded',false);
        $('#farm').attr('aria-expanded',false);
        $('#tbzz').attr('aria-expanded',false);
        $('#tbbz').attr('aria-expanded',true);
        $('#pinfo').attr('class','');
        $('#pcinfo').attr('class','');
        $('#farm').attr('class','');
        $('#tbzz').attr('class','');
        $('#tbbz').attr('class','active');
        $('#plantingstructureinfo').attr('class','tab-pane');
        $('#plantingstructurecheckinfo').attr('class','tab-pane');
        $('#plantinfo').attr('class','tab-pane');
        $('#plantingstructurezhu').attr('class','tab-pane')
        $('#plantingstructurebing').attr('class','tab-pane active')
    });
</script>
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
		