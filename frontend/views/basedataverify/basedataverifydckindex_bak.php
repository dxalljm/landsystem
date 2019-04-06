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
?>
<div class="farms-index">

    <?php  //echo $this->render('farms_search', ['model' => $searchModel]); ?>
<?= Html::hiddenInput('tempFarmsid','',['id'=>'farmsid'])?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3>&nbsp;&nbsp;&nbsp;&nbsp;基础数据核实录入<font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#dataverify" data-toggle="tab" aria-expanded="true">数据录入</a></li>
                            <li class=""><a href="#plantingstructureinfo" data-toggle="tab" aria-expanded="false">数据汇总查询</a></li>
                            <li class=""><a href="#plantingstructurezhu" data-toggle="tab" aria-expanded="false">柱状图表</a></li>
                            <li class=""><a href="#plantingstructurebing" data-toggle="tab" aria-expanded="false">饼状图表</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="dataverify">
                                <?= GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
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
                                                <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t3"></td>
                                                <td align="left" id="t4"></td>
                                                <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t7"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t8"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t9"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t10"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t11"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t12"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td align="left" id="t13"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                                <td></td>
                                            </tr>',
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
        // 			'id',
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
                                            'attribute' => 'contractnumber',
                                            'options' => ['width'=>'150'],
                                        ],
                                        [
                                            'attribute' => 'contractarea',
                                            'options' => ['width'=>'120'],
                                        ],
                                        [
                                            'label' => '种植面积',
                                            'options' => ['width'=>'100','align'=>'right'],
                                            'value' => function($model) {
                                                $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->id,'year'=>User::getYear()])->sum('area');
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
                                                $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
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
                                                $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
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
                                                $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
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
                                                $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
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
                                                $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
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
                                                $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
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
                                                $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
                                                if($area) {
                                                    return sprintf("%.2f", $area);
                                                } else {
                                                    return 0;
                                                }
                                            }
                                        ],
                                        [
                                            'label' => '其他',
                                            'value' => function($model) {
                                                $plant = Plant::find()->where(['typename'=>'其他'])->one();
                                                $area = Plantingstructurecheck::find()->where(['farms_id'=>$model->id,'year'=>User::getYear(),'plant_id'=>$plant['id']])->sum('area');
                                                if($area) {
                                                    return sprintf("%.2f", $area);
                                                } else {
                                                    return 0;
                                                }
                                            }
                                        ],
                                        [
                                            'label'=>'更多操作',
                                            'format'=>'raw',
                                            'options' => ['width'=>'80'],
                                            //'class' => 'btn btn-primary btn-lg',
                                            'value' => function($model,$key){
                                                $text = '录入数据';
                                                $url = '#';
                                                $option = [
        //                                            'id' => 'moreOperation',
                                                    'class' => 'btn btn-primary btn-xs',
                                                    'onclick' => 'showDialog('.$model->id.')',

                                                ];
                                                if(User::getItemname('地产科')) {
                                                    $now = time();
                                                    $jzdate = strtotime(User::getYear().'-08-31 23:59:59');
                                                    if($now > $jzdate) {
                                                        $option['disabled'] = true;
                                                    }

                                                }
                                                $html = Html::a($text,$url, $option);
                                                return $html;
                                            },
                                            'filter' => ['未完成','已完成'],
                                        ],
                                    ],
                                ]); ?>
                            </div>
                            <div class='tab-pane' id="plantingstructureinfo">
                                <?php
                                $totalData = clone $plantdataProvider;
                                $totalData->pagination = ['pagesize'=>0];
                                $data = arraySearch::find($totalData)->search();
                                $plantarrclass = explode('\\',$plantdataProvider->query->modelClass);
                                if(isset($_GET['plantingstructureSearch']['plant_id'])) {
                                    $goodseedNumber = count(Goodseed::getPlantGoodseed($_GET['plantingstructureSearch']['plant_id']));
                                    $goodseedArray = Goodseed::getPlantGoodseed($_GET['plantingstructureSearch']['plant_id']);
                                } else {
                                    $goodseedNumber = 0;
                                    $goodseedArray = [];
                                }
                                ?>
                                <?= GridView::widget([
                                    'dataProvider' => $plantdataProvider,
                                    'filterModel' => $plantsearchModel,
                                    'total' => '<tr height="40">
                                        <td></td>	
                                        <td align="left" id="pt0"><strong>合计</strong></td>
                                        <td align="left" id="pt1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt3"></td>
                                        <td align="left" id="pt4"><strong></strong></td>
                                        <td align="left" id="pt5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt7"><strong>'.count(Plant::getAllname()).'种</strong></td>
                                        <td align="left" id="pt8"><strong></strong></td>
                                        <td></td>
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
                                            'value' => function($model) {
                                                return Farms::find ()->where ( [
                                                    'id' => $model->farms_id
                                                ] )->one ()['contractnumber'];
                                            }
                                        ],
                                        [
                                            'label' => '合同面积',
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
                                            'label' => '补贴归属(法人)',
                                            'value' => function($model) {
                                                if($model->issame) {
                                                    return '100%';
                                                }
                                                $plant = Plant::find()->where(['id'=>$model->plant_id])->one();
                                                $lease = Lease::find()->where(['id'=>$model->lease_id])->one();
                                                if($plant['typename'] == '大豆') {
                                                    return $lease['ddcj_farmer'];
                                                }
                                                if($plant['typename'] == '玉米') {
                                                    return $lease['ymcj_farmer'];
                                                }
                                                return '';
                                            }
                                        ],
                                        [
                                            'label' => '补贴归属(种植者)',
                                            'value' => function($model) {
                                                if($model->issame) {
                                                    return '0%';
                                                }
                                                $plant = Plant::find()->where(['id'=>$model->plant_id])->one();
                                                $lease = Lease::find()->where(['id'=>$model->lease_id])->one();
                                                if($plant['typename'] == '大豆') {
                                                    return $lease['ddcj_lessee'];
                                                }
                                                if($plant['typename'] == '玉米') {
                                                    return $lease['ymcj_lessee'];
                                                }
                                                return '';
                                            }
                                        ],

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
                                $plants = Plant::find()->andWhere('father_id>1')->all();
                                foreach ($plants as $plant) {
                                    $plantname[] = $plant['typename'];
                                    $plantdata[] = ['value'=>sprintf("%.2f", Plantingstructurecheck::find()->where($plantdataProvider->query->where)->andWhere(['plant_id'=>$plant['id']])->sum('area')),'name'=>$plant['typename']];
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
$management_area = '';
foreach($dataProvider->query->where[2] as $key=>$value) {
    if($key == 'management_area') {
        $management_area = $value;
    }
}
?>
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-id'}, function (data) {
            $('#t1').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t4').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode(['and',['year'=>User::getYear(),'management_area'=>$management_area]])?>',command:'sum-area'}, function (data) {
            $('#t5').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode(['and',['year'=>User::getYear(),'management_area'=>$management_area,'plant_id'=>6]])?>',command:'sum-area'}, function (data) {
            $('#t6').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode(['and',['year'=>User::getYear(),'management_area'=>$management_area,'plant_id'=>3]])?>',command:'sum-area'}, function (data) {
            $('#t7').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode(['and',['year'=>User::getYear(),'management_area'=>$management_area,'plant_id'=>2]])?>',command:'sum-area'}, function (data) {
            $('#t8').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode(['and',['year'=>User::getYear(),'management_area'=>$management_area,'plant_id'=>4]])?>',command:'sum-area'}, function (data) {
            $('#t9').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode(['and',['year'=>User::getYear(),'management_area'=>$management_area,'plant_id'=>14]])?>',command:'sum-area'}, function (data) {
            $('#t10').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode(['and',['year'=>User::getYear(),'management_area'=>$management_area,'plant_id'=>8]])?>',command:'sum-area'}, function (data) {
            $('#t11').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode(['and',['year'=>User::getYear(),'management_area'=>$management_area,'plant_id'=>17]])?>',command:'sum-area'}, function (data) {
            $('#t12').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode(['and',['year'=>User::getYear(),'management_area'=>$management_area,'plant_id'=>16]])?>',command:'sum-area'}, function (data) {
            $('#t13').html(data + '亩');
        });
    });

function showDialog(id)
{
    $.get('index.php?r=basedataverify/basedataverifydckinput', {farms_id:id}, function (body) {
        $('#dialogMsg').html(body);
        $( "#dialogMsg" ).dialog( "open" );
    });
}
$( "#dialogMsg" ).dialog({
    autoOpen: false,
    width: 1500,
//    show: "blind",
//    hide: "explode",
    modal: true,//设置背景灰的
    position: { using:function(pos){
        console.log(pos)
        var topOffset = $(this).css(pos).offset().top;
        if (topOffset = 0||topOffset>0) {
            $(this).css('top', 80);
        }
        var leftOffset = $(this).css(pos).offset().left;
        if (leftOffset = 0||leftOffset>0) {
            $(this).css('left', 260);
        }
    }},
    buttons: [
        {
            text: "确定",
            click: function() {
                $( this ).dialog( "close" );
                var form = $('form').serializeArray();
                console.log($.toJSON(form));
                $.getJSON('index.php?r=basedataverify/basedataverifysaveindex',{'value':$.toJSON(form),'farms_id':$('#farmsID').val()},function (data) {
                    if(data.state)
                        window.location.reload();
                });
            }

        },
        {
            text: "取消",
            click: function() {
                $( this ).dialog( "close" );
            }
        }
    ]
});
</script>

<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($plantdataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#pt0').html('合计('+ data + '户)');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($plantdataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#pt6').html('种植者'+ data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($plantdataProvider->query->where)?>',command:'count-farmer_id',andwhere:'<?= json_encode(['lease_id'=>0])?>'}, function (data) {
            $('#pt2').html('法人种植'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($plantdataProvider->query->where)?>',command:'count-lease_id'}, function (data) {
            $('#pt1').html(data + '人');
        });
//        $.getJSON('index.php?r=search/search', {modelClass: 'Farms',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'count-plant_id'}, function (data) {
//             $('#t4').html(data + '种');
//         });
        //$.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>',where:'<?//= json_encode($dataProvider->query->where)?>',command:'count-goodseed_id'}, function (data) {
//             $('#t5').html(data + '种');
//         });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($plantdataProvider->query->where)?>',command:'sum-area'}, function (data) {
            $('#pt5').html(data + '亩');
        });
    });
</script>