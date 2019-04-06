<?php
namespace frontend\controllers;
use app\models\Goodseed;
use app\models\Lease;
use app\models\Lockstate;
use app\models\Mainmenu;
use app\models\Plant;
use app\models\Plantingstructurecheck;
use app\models\Tables;
use frontend\helpers\whereHandle;
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
                        <h3>&nbsp;&nbsp;&nbsp;&nbsp;种植结构核实录入<font color="red">(<?= User::getYear()?>年度)</font><?= Html::a('生成汇总表',Url::to(['basedataverify/basedataverifylist','where'=>json_encode($dataProvider->query->where)]),['class'=>'btn btn-success'])?></h3></div>
                    <div class="box-body">


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
                                                <td align="left" id="t13"><strong></strong></td>
                                                <td align="left" id="t14"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
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
                                            'format'=>'raw',
                                            'options' => ['width'=>'80'],
                                            //'class' => 'btn btn-primary btn-lg',
                                            'value' => function($model,$key){
                                                if($model->isfinished) {
                                                    $text = '更新数据';
                                                    $url = '#';
                                                    $option = [
                                                        //                                            'id' => 'moreOperation',
                                                        'class' => 'btn btn-success btn-xs',
                                                        'onclick' => 'showDialog('.$model->farms_id.')',

                                                    ];
                                                } else {
                                                    $text = '录入数据';
                                                    $url = '#';
                                                    $option = [
                                                        //                                            'id' => 'moreOperation',
                                                        'class' => 'btn btn-primary btn-xs',
                                                        'onclick' => 'showDialog(' . $model->farms_id . ')',

                                                    ];
                                                }
                                                if(User::getItemname('地产科')) {
                                                    $now = time();
                                                    $lockinfo = Lockstate::findOne(1);
                                                    if($lockinfo->plantstate) {
                                                        $jzdate = strtotime(User::getYear().'-'.$lockinfo->plantstatedate.' 23:59:59');
                                                        $jzdateend = strtotime(User::getYear().'-'.$lockinfo->plantstatedateend.' 23:59:59');
                                                        if($now > $jzdate and $now < $jzdateend) {
                                                            $option['disabled'] = true;
                                                        }
                                                    }

                                                }
                                                $html = Html::a($text,$url, $option);
                                                return $html;
                                            },
                                            'filter' => [0=>'未完成',1=>'已完成'],
                                        ],

                                    ],
                                ]); ?>






                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="dialogMsg" title="种植结构数据录入">

</div>
<?php
//var_dump($dataProvider->query->where);
$where = whereHandle::toPlantWhere($dataProvider->query->where);
//var_dump($where);
//var_dump(Plantingstructurecheck::find()->where(['management_area'=>1,'year'=>'2017','state'=>[1],'plant_id'=>6])->count());
//var_dump(whereHandle::toPlantWhere($dataProvider->query->where));
//var_dump(Plantingstructurecheck::find()->where($where)->sum('area'));
//var_dump(Plantingstructurecheck::find()->where(['farms_id'=>2047,'year'=>'2017','state'=>[1,2,3,4,5],'management_area'=>1])->all());
//var_dump(Plantingstructurecheck::find()->where($where)->all());
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
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area'}, function (data) {
            $('#t5').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>6])?>'}, function (data) {
            $('#t6').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>3])?>'}, function (data) {
            $('#t7').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>2])?>'}, function (data) {
            $('#t8').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>4])?>'}, function (data) {
            $('#t9').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>14])?>'}, function (data) {
            $('#t10').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>8])?>'}, function (data) {
            $('#t11').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>17])?>'}, function (data) {
            $('#t12').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: 'Plantingstructurecheck',where:'<?= json_encode($where)?>',command:'sum-area',andwhere:'<?= json_encode(['plant_id'=>16])?>'}, function (data) {
            $('#t13').html(data + '亩');
        });
        $.getJSON('index.php?r=search/plantfinished', {where:'<?= json_encode($dataProvider->query->where)?>'}, function (data) {
            $('#t14').html(data.finished+'/'+data.all);
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
