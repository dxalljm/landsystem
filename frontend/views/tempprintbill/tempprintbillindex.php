<?php
namespace frontend\controllers;use app\models\User;

use app\models\Farms;
use Yii;
use frontend\helpers\ActiveFormrdiv;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use frontend\helpers\MoneyFormat;
use app\models\ManagementArea;
use yii\helpers\Url;
use dosamigos\datetimepicker\DateTimePicker;
use frontend\helpers\arraySearch;
use app\models\Tempprintbill;
use app\models\PlantPrice;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\tempprintbillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
// $totalData = clone $dataProvider;
// $totalData->pagination = ['pagesize'=>0];
// $data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
// 'total' => '<tr>
// 			        <td></td>
// 			        <td align="center"><strong>合计</strong></td>
// 			        <td align="center"><strong>'.$data->count('farms_id').'户</strong></td>
// 			        <td align="center"><strong>'.$data->count('farmer_id').'个</strong></td>
// 			        <td align="center"><strong></strong></td>
// 			        <td align="center"><strong>'.$data->sum('measure').'亩</strong></td>
// 					<td align="center"><strong>'.$data->sum('amountofmoney').'元</strong></td>
// 					<td align="center"></td>
// 			        </tr>',
// var_dump($begindate);
if(date('Y-m-d',$begindate) == date('Y-m-d',$enddate)) {
	$begindate = strtotime(date('Y').'-01-01');
}
//var_dump(Tempprintbill::find()->where(['management_area'=>1,'state'=>0])->andFilterWhere(['between','update_at',strtotime('2016-01-01 00:00:01'),strtotime('2016-12-31 23:59:59')])->sum('amountofmoneys'));
//var_dump(Tempprintbill::find()->where(['management_area'=>1,'state'=>0])->andFilterWhere(['between','update_at',strtotime('2016-01-01 00:00:01'),strtotime('2016-12-31 23:59:59')])->count());
?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<div class="tempprintbill-index">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        承包费收缴明细
                    </h3>
                </div>
                <div class="box-body">
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
                            <td><?= html::submitButton('查询',['class'=>'btn btn-success','id'=>'searchButton'])?>&nbsp;<?= html::a('今天',Url::to(['tempprintbill/tempprintbillindex','begindate'=>date('Y-m-d'),'enddate'=>date('Y-m-d')]),['class'=>'btn btn-success','id'=>'searchDay'])?>
                                <?= Html::a('导出XLS',Url::to(['tempprintbill/tempprintbilltoxls','begindate'=>$begindate,'enddate'=>$enddate]),['class'=>'btn btn-success'])?>
                            </td>
                        </tr>
                    </table>
                    <?php ActiveFormrdiv::end(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
										<td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div><?= ?></strong></td>
                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left"></td>
                                        
                                    </tr>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute'=>'management_area',
                'value' => function($model) {
                    return ManagementArea::getAreanameOne($model->management_area);
                },
                'filter' => ManagementArea::getAreaname(),
            ],
            [
                'attribute' => 'farms_id',
                'value' => function ($model) {
                    if($model->farms_id == 0)
                        return '';
                    return Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'];
                }
            ],
            [
                'attribute' => 'farmername',
//                'options' => ['width' =>80],
            ],
            [
                'label' => '合同号',
                'attribute' => 'contractnumber',
                'value' => function ($model) {
                    if($model->farms_id == 0)
                        return '';
                    return Farms::find()->where(['id'=>$model->farms_id])->one()['contractnumber'];
                }
            ],
            //'standard',
            'measure',
            [
            	'attribute' => 'amountofmoney',
            	'options' => ['width' => 150],
            	'value' => function($model) {
            		return MoneyFormat::toNumber($model->amountofmoney);
            	}
            ],
            
            // 'bigamountofmoney',
            [
            	'attribute' => 'year',
            	'options' => ['width'=>100],
            	'value' => function($model) {
            		return $model->year;
            },
            	'filter' => ArrayHelper::map(PlantPrice::find()->all(),'years','years'),
            ],
//             'year',
            'nonumber',
            [
                'attribute' => 'update_at',
                'value' => function($model) {
                    return date('Y-m-d H:i:s',$model->update_at);
                }
            ],
            [
                'format' => 'raw',
                'label' => '状态',
                'attribute' => 'farmstate',
                'value' => function($model) {
                    return Farms::getStateInfo($model->farmstate);
                },
                'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同',5=>'其它','-1'=>'陈欠收缴'],
            ],
            //'state',
// 			[
// 				'attribute' => 'create_at',
// 				'label' => '开票时间',
// 				'value' => function($model)
// 				{
// 					return date('Y-m-d',$model->create_at);
// 				}
// 			],
            [
            'class' => 'yii\grid\ActionColumn',
            'options' => ['width'=>100],
            'template' => '{view} {print} {modfiy} {update}',
            'buttons' => [
            		'view' => function ($url, $model, $key) {
            		$url = Url::to('index.php?r=tempprintbill/tempprintbillsview&id='.$model->id);
            			$options = [
            					'title' => Yii::t('yii', '查看'),
            					'aria-label' => Yii::t('yii', 'View'),
            					'data-pjax' => '0',
            					'target' => '_blank',
            			];
            		
            			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
            		},
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'print' => function ($url, $model, $key) {
               
                if($model->farms_id > 0) {
                	$url = Url::to('index.php?r=tempprintbill/tempprintbillview&id='.$model->id);
                }
                if($model->farms_id == 0){
                	$url = Url::to('index.php?r=tempprintbill/tempprintbillview2&id='.$model->id);
                }
                if($model->farms_id < 0){
                	$temps = Tempprintbill::find()->where(['farms_id'=>-1,'farmername'=>$model->farmername])->all();
                	$id = [0=>'',1=>'',2=>''];
                	if($temps) {
                		foreach ($temps as $key => $value) {
                			$id[$key] = $value['id'];
                		}
                		$url = Url::to('index.php?r=tempprintbill/tempprintbillcqview&id0='.$id[0].'&id1='.$id[1].'&id2='.$id[2]);
                	}                	
                }
                          		
                    $options = [
                        'title' => Yii::t('yii', '查看并打印'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    	'target' => '_blank',
                    ];
                    
                    return Html::a('<span class="fa fa-print"></span>', $url, $options);
                },

                'update' => function ($url, $model, $key) {
                    $url = Url::to('index.php?r=tempprintbill/tempprintbillupdate&id='.$model->id);
                	$options = [
                			'title' => Yii::t('yii', '报废'),
                			'aria-label' => Yii::t('yii', 'delete'),
                			'data-pjax' => '0',
                	];
                
                	return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                },
               	]
       	 	],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t1').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-measure'}, function (data) {
            $('#t3').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-amountofmoney'}, function (data) {
            $('#t4').html(data + '元');
        });
    });
</script>