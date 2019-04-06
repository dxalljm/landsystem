<?php
use app\models\Tables;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Plant;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use frontend\helpers\arraySearch;
use frontend\helpers\MoneyFormat;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Tempprintbill;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<script type="text/javascript" src="vendor/bower/echarts/build/dist/echarts.js"></script>
<script type="text/javascript" src="vendor/bower/echarts/build/dist/echarts.min.js"></script>
<div class="lease-index">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php User::tableBegin('票据打印');?>
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
// 	var_dump($data->getEchartsData(['real_income_amount','amounts_receivable'],1,'showShadowThermometer'));
// 	var_dump($_GET);
?>
<p><?= Html::a('收费','index.php?r=tempprintbill/tempprintbillnocontract',['class' => 'btn btn-success'])?> <?= Html::a('陈欠追缴','index.php?r=tempprintbill/tempprintbillcq',['class' => 'btn btn-success'])?></p>
<div class="nav-tabs-custom">
<!--            <ul class="nav nav-pills nav-pills-warning">-->
<!--                <li class="active"><a href="#nocontractdata" data-toggle="tab" aria-expanded="false">未更换合同数据表</a></li>-->
<!--                <li class=""><a href="#nocontractecharts" data-toggle="tab" aria-expanded="false">未更换合同占比图表</a></li>-->
<!--            </ul>-->
<!--            <div class="tab-content">-->
<!--                <div class="tab-pane active" id="nocontractdata">-->
                    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
//         'total' => '<tr height="40">
//                                         <td></td>
//                                         <td align="center"><strong>合计</strong></td>
//                                         <td align="center" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                       
//                                         <td></td>
// 										<td align="center" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
//                                         <td align="center" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
//                                         <td align="center"></td>
                                        
//                                     </tr>',
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
//             [
//                 'attribute' => 'farms_id',
//                 'value' => function ($model) {
//                     if($model->farms_id == 0)
//                         return '';
//                     return Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'];
//                 }
//             ],
            [
                'attribute' => 'farmername',
//                'options' => ['width' =>80],
            ],
//             [
//                 'label' => '合同号',
//                 'attribute' => 'contractnumber',
//                 'value' => function ($model) {
//                     if($model->farms_id == 0)
//                         return '';
//                     return Farms::find()->where(['id'=>$model->farms_id])->one()['contractnumber'];
//                 }
//             ],
            //'standard',
            'measure',
            'amountofmoney',
            // 'bigamountofmoney',
            'year',
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
                'attribute' => 'contract',
                'value' => function($model) {
                    if($model->farms_id > 0) {
                        return '<font class="text-green">正常提交</font>';
                    }
                    if($model->farms_id == 0) {
                        return '<font class="text-blue">收费</font>';
                    }
                    if($model->farms_id < 0) {
                    	return '<font class="text-blue">陈欠收缴</font>';
                    }
                },
                'filter' => [0=>'收费',1=>'正常提交','-1'=>'陈欠收缴'],
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
                	$temps = Tempprintbill::find()->where(['farms_id'=>-1,'farmername'=>$model->farmername])->all();
//                 	var_dump($temps);exit;
					$id = [0=>'',1=>'',2=>''];
                	if($temps) {
                		foreach ($temps as $key => $value) {
                			$id[$key] = $value['id'];
                		}
                		$url = Url::to('index.php?r=tempprintbill/tempprintbillcqview&id0='.$id[0].'&id1='.$id[1].'&id2='.$id[2]);
                	}
                	if($model->farms_id == 0)
                		$url = Url::to('index.php?r=tempprintbill/tempprintbillview2&id='.$model->id);
                	if($model->farms_id >0)
                		$url = Url::to('index.php?r=tempprintbill/tempprintbillview&id='.$model->id);
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
               	],
       	 	],
            [
                'label' => '更多操作',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a('重新定位',Url::to(['collection/collectionfarmlist','id'=>$model->id]),['class'=>'btn btn-sm btn-success']);
                }
            ],
        ],
    ]); ?>
<!--                </div>-->
<!--                <!-- /.tab-pane -->
<!--                <div class="tab-pane" id="nocontractecharts">-->
<!--                    --><?php
//                    $echartsData = $data->setEchartsName(['实收金额','应收金额'])->collectionShowShadow();
//                    if(isset($data->where['management_area'])) {
//                        if (is_array($data->where['management_area'])) {
//                            $areaname = Farms::getManagementArea('small')['areaname'];
//                        } else {
//                            $areaname = [Farms::getManagementArea()['areaname'][$data->where['management_area'] - 1]];
//                        }
//                    } else {
//                        $areaname = Farms::getManagementArea('small')['areaname'];
//                    }
//                    ?>
<!--                    <div id="nocontractarea" style="width: 900px; height: 600px; margin: 0 auto"; ></div>-->
<!--                    <script type="text/javascript">-->
<!--                        showPie('nocontractarea','有合同与无合同数量占比',--><?//= json_encode(['有合同','无合同'])?><!--//,'数量',<?//= json_encode([['value'=>$collectionCount,'name'=>'有合同'],['value'=>$data->allcount(),'name'=>'无合同']])?>//,'个');-->
    <!--                   </script>-->

                    </div>
              </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
