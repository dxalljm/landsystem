<?php
namespace frontend\controllers;
use app\models\Goodseed;
use app\models\Lease;
use app\models\Mainmenu;
use app\models\Plant;
use app\models\Plantingstructurecheck;
use app\models\Tables;
use yii\helpers\Html;
use yii;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Theyear;
use app\models\Dispute;
use app\models\Machineoffarm;
use app\models\User;
use app\models\Farmer;
use yii\helpers\Url;
use frontend\helpers\whereHandle;
use frontend\helpers\arraySearch;
use frontend\helpers\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<?php
//	$totalData = clone $dataProvider;
//	$totalData->pagination = ['pagesize'=>0];
// 	var_dump($totalData->getModels());exit;
//	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
$arrclass2 = explode('\\',$dataProvider2->query->modelClass);
$huinongArray = [];
if(isset($_GET['plantingstructurecheckSearch']['plant_id'])) {
    if($_GET['plantingstructurecheckSearch']['plant_id'])
        $huinongArray = ['farmer'=>'法人:100%','lessee'=>'种植者:100%','f_l'=>'按比例分配'];
}
?>
<div class="farms-index">

    <?php  //echo $this->render('farms_search', ['model' => $searchModel]); ?>
    <?= Html::hiddenInput('tempFarmsid','',['id'=>'farmsid'])?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class=""><a href="#plantingstructureinfo" data-toggle="tab" aria-expanded="false">按农作物统计</a></li>
                                <li class=""><a href="#plantinfo" data-toggle="tab" aria-expanded="false">按农场统计</a></li>
                                <li class=""><a href="#plantingstructurezhu" data-toggle="tab" aria-expanded="false">柱状图表</a></li>
                                <li class=""><a href="#plantingstructurebing" data-toggle="tab" aria-expanded="false">饼状图表</a></li>
                            </ul>
                            <div class="tab-content">
                                
                                <div class='tab-pane active' id="plantingstructureinfo">
                                    <?php
                                    $totalData = clone $dataProvider;
                                    $totalData->pagination = ['pagesize'=>0];
                                    $data = arraySearch::find($totalData)->search();
                                    $plantarrclass = explode('\\',$dataProvider->query->modelClass);
                                    if(isset($_GET['plantingstructureSearch']['plant_id'])) {
                                        $goodseedNumber = count(Goodseed::getPlantGoodseed($_GET['plantingstructureSearch']['plant_id']));
                                        $goodseedArray = Goodseed::getPlantGoodseed($_GET['plantingstructureSearch']['plant_id']);
                                    } else {
                                        $goodseedNumber = 0;
                                        $goodseedArray = [];
                                    }
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
                                                'filter' => Plant::getAllname(),
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
                                <div class='tab-pane' id="plantinfo">
                                    <?= GridView::widget([
                                        'dataProvider' => $dataProvider2,
                                        'filterModel' => $searchModel2,
                                        'pager' => [
                                            'class' => \frontend\helpers\page\LinkPager::className(),
                                            'template' => '{pageButtons} {customPage} {pageSize}', //分页栏布局
                                            'pageSizeList' => [10, 20, 30, 50], //页大小下拉框值
                                            'customPageWidth' => 50,            //自定义跳转文本框宽度
                                            'customPageBefore' => ' 跳转到第 ',
                                            'customPageAfter' => ' 页 ',
                                        ],
                                        'total' => '<tr height="40">
                                                <td></td>	
                                                <td align="left"><strong>合计</strong></td>
                                                <td align="left" id="t21"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t22"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t23"></td>
                                                <td align="left" id="t24"></td>
                                                <td align="left" id="t25"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t26"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t27"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t28"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t29"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t210"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t211"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t212"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t213"><strong></strong></td>
                                                <td align="left" id="t214"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                            </tr>',
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],
//         			'id',
                                            [
                                                'attribute' => 'management_area',
                                                'headerOptions' => ['width' => '160'],
                                                'value'=> function($model) {
                                                    return ManagementArea::getAreanameOne($model->management_area);
                                                },
                                                'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
                                            ],
                                            [
                                                'attribute' => 'farmname',
                                                'options' => ['width'=>'150'],
                                            ],
                                            [
                                                'attribute' => 'farmername',
                                                'options' => ['width'=>'100'],
                                            ],
                                            [
                                                'label' => '合同号',
                                                'attribute' => 'state',
                                                'options' => ['width'=>'150'],
                                                'value' => function($model) {
                                                    return $model->contractnumber;
                                                },
                                                'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同'],
                                            ],
                                            [
                                                'attribute' => 'contractarea',
                                                'options' => ['width'=>'120'],
                                            ],

                                            [
                                                'label' => '种植面积',
                                                'options' => ['width'=>'110','align'=>'right'],
                                                'value' => function($model) {
                                                    $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->farms_id,'year'=>User::getYear()])->sum('area');
                                                    if($area) {
                                                        return sprintf("%.2f", $area);
                                                    } else {
                                                        return 0;
                                                    }
                                                }
                                            ],
                                            [
                                                'label' => '大豆',
                                                'value' => function($model) {
                                                    $plant = Plant::find()->where(['typename'=>'大豆'])->one();
                                                    $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->farms_id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
                                                    if($area) {
                                                        return sprintf("%.2f", $area);
                                                    } else {
                                                        return 0;
                                                    }
                                                }
                                            ],
                                            [
                                                'label' => '玉米',
                                                'value' => function($model) {
                                                    $plant = Plant::find()->where(['typename'=>'玉米'])->one();
                                                    $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->farms_id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
                                                    if($area) {
                                                        return sprintf("%.2f", $area);
                                                    } else {
                                                        return 0;
                                                    }
                                                }
                                            ],
                                            [
                                                'label' => '小麦',
                                                'value' => function($model) {
                                                    $plant = Plant::find()->where(['typename'=>'小麦'])->one();
                                                    $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->farms_id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
                                                    if($area) {
                                                        return sprintf("%.2f", $area);
                                                    } else {
                                                        return 0;
                                                    }
                                                }
                                            ],
                                            [
                                                'label' => '马铃薯',
                                                'value' => function($model) {
                                                    $plant = Plant::find()->where(['typename'=>'马铃薯'])->one();
                                                    $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->farms_id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
                                                    if($area) {
                                                        return sprintf("%.2f", $area);
                                                    } else {
                                                        return 0;
                                                    }
                                                }
                                            ],
                                            [
                                                'label' => '杂豆',
                                                'value' => function($model) {
                                                    $plant = Plant::find()->where(['typename'=>'杂豆'])->one();
                                                    $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->farms_id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
                                                    if($area) {
                                                        return sprintf("%.2f", $area);
                                                    } else {
                                                        return 0;
                                                    }
                                                }
                                            ],
                                            [
                                                'label' => '北药',
                                                'value' => function($model) {
                                                    $plant = Plant::find()->where(['typename'=>'北药'])->one();
                                                    $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->farms_id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
                                                    if($area) {
                                                        return sprintf("%.2f", $area);
                                                    } else {
                                                        return 0;
                                                    }
                                                }
                                            ],
                                            [
                                                'label' => '蓝莓',
                                                'value' => function($model) {
                                                    $plant = Plant::find()->where(['typename'=>'蓝莓'])->one();
                                                    $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->farms_id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
                                                    if($area) {
                                                        return sprintf("%.2f", $area);
                                                    } else {
                                                        return 0;
                                                    }
                                                }
                                            ],
                                            [
                                                'label' => '其它',
                                                'value' => function($model) {
                                                    $plant = Plant::find()->where(['typename'=>'其它'])->one();
                                                    $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->farms_id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
                                                    if($area) {
                                                        return sprintf("%.2f", $area);
                                                    } else {
                                                        return 0;
                                                    }
                                                },
                                            ],
                                            [
                                                'label'=>'完成/户数',
                                                'attribute' => 'isfinished',
//                                                'format'=>'raw',
                                                'options' => ['width'=>'80'],
                                                //'class' => 'btn btn-primary btn-lg',
                                                'value' => function($model,$key){
                                                    if($model->isfinished) {
                                                        $text = '完成';
                                                    } else {
                                                        $text = '未完成';
                                                    }
                                                    return $text;
                                                },
                                                'filter' => [0=>'未完成',1=>'已完成'],
                                            ],

                                        ],
                                    ]); ?>
                                </div>
                                <div class='tab-pane' id="plantingstructurezhu">
                                    <div id="plantingstructuredatazhu" style="width:1000px; height: 600px; margin: 0 auto"></div>
                                </div>
                                <script type="text/javascript">
                                    showAllShadow('plantingstructuredatazhu',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($data->getName('Plant', 'typename', 'plant_id')->typenameList())?>,<?= $data->getName('Plant', 'typename', 'plant_id')->showAllShadow('sum','area');?>,'亩');
                                </script>
                                <div class='tab-pane' id="plantingstructurebing">
                                    <div id="plantingstructuredatabing" style="width:1000px; height: 600px; margin: 0 auto"></div>
                                    <?php
                                    $plantdata = [];
                                    $plantname = [];
                                    $plants = Plant::getAllname();
                                    foreach ($plants as $plant_id => $typename) {
                                        $sum = sprintf("%.2f", Plantingstructurecheck::find()->where($dataProvider->query->where)->andWhere(['plant_id'=>$plant_id])->sum('area'));
                                        $plantname[] = $typename.'('.$sum.')';
                                        $plantdata[] = ['value'=>$sum,'name'=>$typename.'('.$sum.')'];
                                    }
                                    ?>
                                </div>
                                <script type="text/javascript">
                                    showPie('plantingstructuredatabing','种植结构占比',<?= json_encode($plantname)?>,'农作物',<?= json_encode($plantdata)?>,'亩');
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="dialogMsg" title="种植结构数据录入">

</div>
<?php
$where = whereHandle::toPlantWhere($dataProvider2->query->where);
?>
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#pt1').html(data + '户');
        });

        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id',andwhere:'<?= json_encode(['issame'=>1])?>'}, function (data) {
            $('#pt2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#pt4').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-lease_id',andwhere:'<?= json_encode('lease_id>0')?>'}, function (data) {
            $('#pt6').html('承租者'+ data + '人');
        });


//        $.getJSON('index.php?r=search/search', {modelClass: 'Farms',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'count-plant_id'}, function (data) {
//             $('#t4').html(data + '种');
//         });
        //$.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>',where:'<?//= json_encode($dataProvider->query->where)?>',command:'count-goodseed_id'}, function (data) {
//             $('#t5').html(data + '种');
//         });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-area'}, function (data) {
            $('#pt5').html(data + '亩');
        });

    });

    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass2[2]?>',where:'<?= json_encode($dataProvider2->query->where)?>',command:'count-id'}, function (data) {
            $('#t21').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass2[2]?>',where:'<?= json_encode($dataProvider2->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t22').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass2[2]?>',where:'<?= json_encode($dataProvider2->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t24').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area'}, function (data) {
            $('#t25').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>6])?>'}, function (data) {
            $('#t26').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>3])?>'}, function (data) {
            $('#t27').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>2])?>'}, function (data) {
            $('#t28').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>4])?>'}, function (data) {
            $('#t29').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>14])?>'}, function (data) {
            $('#t210').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>8])?>'}, function (data) {
            $('#t211').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>17])?>'}, function (data) {
            $('#t212').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>16])?>'}, function (data) {
            $('#t213').html(data + '亩');
        });
        $.getJSON('index.php?r=search/plantfinished', {where:'<?= json_encode($dataProvider->query->where)?>'}, function (data) {
            $('#t214').html(data.finished+'/'+data.all);
        });
    });
</script>