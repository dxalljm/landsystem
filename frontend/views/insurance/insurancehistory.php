<?php
namespace frontend\controllers;use app\models\User;
use app\models\Insurance;
use app\models\Lease;
use app\models\ManagementArea;
use app\models\Tables;
use frontend\models\electronicarchivesSearch;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\insuranceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Insurance';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<div class="insurance-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('种植业保险申请', ['insurancehistorycreate'], ['class' => 'btn btn-success']) ?>
        <button type="button" class="btn btn-success" id="years"><?php if(empty($year)) echo User::getYear();else echo $year;?>年度</button>
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <?php
        $plantprice = \app\models\PlantPrice::find()->all();
        ?>
        <ul class="dropdown-menu" role="menu">
        <?php
            foreach ($plantprice as $price) {
        ?>
            <li><a href="<?= Url::to(['collection/collectioninfo','year'=>$price['years']])?>" id="selectYear"><?= $price['years']?>年度</a></li>
        <?php }?>
        </ul>
    </p>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">种植业保险数据</a></li>
                        <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">未更换合同数据</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,

                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    [
                                        'attribute' => 'management_area',
                                        'headerOptions' => ['width' => '200'],
                                        'value'=> function($model) {
                                            return ManagementArea::getAreanameOne($model->management_area);
                                        },
                                        'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
                                    ],
                                    [
                                        'attribute' => 'farmname',

                                    ],
                                    'farmername',
//             [
//             	'label' => '管理区',
//               	'attribute' => 'areaname',
//             	'value' => 'managementarea.areaname',
//             ],
                                    //'management_area',
                                    'contractarea',
                                    'contractnumber',

                                    ['class' => 'frontend\helpers\eActionColumn'],
                                    [

                                        'format'=>'raw',
                                        //'class' => 'btn btn-primary btn-lg',
                                        'value' => function($model,$key){
                                            $options = [
                                                'class'=>'btn-sm btn-primary',
                                            ];
                                            $url = Url::to(['insurance/insurancehistorycreate','farms_id'=>$model->id]);
                                            $html = Html::a('申请', $url, $options);
                                            $html .= ' ';
                                            $printoptions = [
                                                'title' => Yii::t('yii', '查看并打印'),
                                                'aria-label' => Yii::t('yii', 'View'),
                                                'data-pjax' => '0',
                                            ];

                                            $html .= Html::a('<span class="fa fa-print"></span>', Url::to(['insurance/insuranceprint','id'=>$model->id]), $printoptions);
//                $html .= Html::a('查看并打印',Url::to(['insurance/insuranceprint','id'=>$model->id]),['class'=>'btn btn-primary btn-xs']);
                                            if(!Insurance::find()->where(['farms_id'=>$model->id])->count())
                                                return $html;

                                        }
                                    ],
                                ],
                            ]); ?>
                            <?php
                            //            [
                            //                'label'=>'操作',
                            //                'format'=>'raw',
                            //                //'class' => 'btn btn-primary btn-lg',
                            //                'value' => function($model){
                            //                    if(!$model->state)
                            //                        return Html::a('撤消申请',Url::to(['insurance/insurancedelete','id'=>$model->id]),[
                            //                        		'class'=>'btn btn-danger btn-xs',
                            //                        		'data' => [
                            //                        				'confirm' => '您确定要撤消申请吗？',
                            //                        				'method' => 'post',
                            //                        		],
                            //                    ]);
                            //                    else
                            //                        return Html::a('撤消申请','#',['class'=>'btn btn-danger btn-xs','disabled'=>true]);
                            //                }
                            //            ],
                            //            [
                            //            'format'=>'raw',
                            //            //'class' => 'btn btn-primary btn-lg',
                            //            'value' => function($model){
                            //                if($model->state == -1) {
                            //                    return Html::a('查看', Url::to(['insurance/insurancechiefview', 'id' => $model->id]), ['class' => 'btn btn-danger btn-xs']);
                            //                }
                            //            	if($model->fwdtstate)
                            //                	return Html::a('查看并打印',Url::to(['insurance/insuranceprint','id'=>$model->id]),['class'=>'btn btn-primary btn-xs']);
                            //            	else
                            //            		return Html::a('查看',Url::to(['insurance/insurancetableview','id'=>$model->id]),['class'=>'btn btn-primary btn-xs']);
                            //            }
                            //        ]
                            ?>
                        </div>
                        <div class="tab-pane" id="timeline">
                            <?= GridView::widget([
                                'dataProvider' => $historyData,
                                'filterModel' => $historyModel,

                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    [
                                        'attribute' => 'management_area',
                                        'headerOptions' => ['width' => '200'],
                                        'value'=> function($model) {
                                            return ManagementArea::getAreanameOne($model->management_area);
                                        },
                                        'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
                                    ],
                                    [
                                        'attribute' => 'farmname',

                                    ],
                                    'farmername',
//             [
//             	'label' => '管理区',
//               	'attribute' => 'areaname',
//             	'value' => 'managementarea.areaname',
//             ],
                                    //'management_area',
                                    'contractarea',
                                    'contractnumber',

//                                    ['class' => 'frontend\helpers\eActionColumn'],
                                    [

                                        'format'=>'raw',
                                        //'class' => 'btn btn-primary btn-lg',
                                        'value' => function($model,$key){
                                            $options = [
                                                'class'=>'btn-sm btn-primary',
                                            ];
//                                            $url = Url::to(['insurance/insurancehistorycreate','farms_id'=>$model->id]);
//                                            $html = Html::a('申请', $url, $options);
//                                            $html .= ' ';
                                            $printoptions = [
                                                'title' => Yii::t('yii', '查看并打印'),
                                                'aria-label' => Yii::t('yii', 'View'),
                                                'data-pjax' => '0',
                                            ];

                                            $html = Html::a('<span class="fa fa-print"></span>', Url::to(['insurance/insuranceprint','id'=>$model->id]), $printoptions);
//                $html .= Html::a('查看并打印',Url::to(['insurance/insuranceprint','id'=>$model->id]),['class'=>'btn btn-primary btn-xs']);
//                                            if(!Insurance::find()->where(['farms_id'=>$model->id])->count())
                                                return $html;

                                        }
                                    ],
                                ],
                            ]); ?>


                        </div>
                        <!-- /.tab-pane -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
</div>
