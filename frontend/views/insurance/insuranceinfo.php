<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Plant;
use app\models\Insurancecompany;
use app\models\ManagementArea;
use app\models\Lease;
use frontend\helpers\arraySearch;
use app\models\Insurance;
use app\models\User;
use app\models\Goodseed;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<div class="lease-index">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php User::tableBegin('种植业保险');?>

              
<?php
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
//'total' => '<tr>
//						<td></td>
//		<td></td>
//						<td align="center"><strong>'.$data->where(['state'=>1])->count('farms_id').'户</strong></td>
//						<td><strong>法人'.$data->where(['nameissame'=>1,'state'=>1])->count().'个</strong></td>
//						<td><strong>租赁'.$data->where(['nameissame'=>0,'state'=>1])->count().'个</strong></td>
//						<td><strong>'.$data->where(['state'=>1])->sum('contractarea').'亩</strong></td>
//						<td><strong>'.$data->where(['state'=>1])->sum('insuredarea').'亩</strong></td>
//						<td><strong>'.$data->where(['state'=>1])->sum('insuredsoybean').'亩</strong></td>
//						<td><strong>'.$data->where(['state'=>1])->sum('insuredwheat').'亩</strong></td>
//						<td><strong>'.$data->where(['state'=>1])->sum('insuredother').'亩</strong></td>
//						<td><strong>'.$data->count('company_id').'个</strong></td>
//						<td><strong>完成'.$data->where(['state'=>1])->count().'</strong></td>
//
//
//					</tr>',
?>
            <ul class="nav nav-pills nav-pills-warning">
                <li class="active"><a href="#plan" data-toggle="tab" aria-expanded="true">种植业保险计划统计表</a></li>
                <li class=""><a href="#activity" data-toggle="tab" aria-expanded="true">种植业保险统计表</a></li>
                <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">保险作物图表</a></li>
                <li class=""><a href="#timeline2" data-toggle="tab" aria-expanded="false">承保公司图表</a></li>
                <li class=""><a href="#timeline3" data-toggle="tab" aria-expanded="false">农业保险情况图表（数量 ）</a></li>
                <li class=""><a href="#timeline4" data-toggle="tab" aria-expanded="false">农业保险情况图表（面积）</a></li>
            </ul>
            <div class="tab-content">
                <div class='tab-pane active' id="plan">
                    <?php
                    $totalDataplan = clone $dataProviderplan;
                    $totalDataplan->pagination = ['pagesize'=>0];
                    $data = arraySearch::find($totalDataplan)->search();
                    $plantarrclass = explode('\\',$dataProviderplan->query->modelClass);
                    if(isset($_GET['plantingstructureSearch']['plant_id'])) {
                        $goodseedNumber = count(Goodseed::getPlantGoodseed($_GET['plantingstructureSearch']['plant_id']));
                        $goodseedArray = Goodseed::getPlantGoodseed($_GET['plantingstructureSearch']['plant_id']);
                    } else {
                        $goodseedNumber = 0;
                        $goodseedArray = [];
                    }
                    ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderplan,
                        'filterModel' => $searchPlan,
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
                            [
                                'label' => '保险面积',
                                'attribute' => 'area',
                            ],
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
                                'label' => '保险种类',
                                'attribute' => 'plant_id',
                                'value' => function($model) {
                                    return Plant::find()->where(['id'=>$model->plant_id])->one()['typename'];
                                },
                                'filter' => \app\models\Insurancetype::getTypes(),
                            ],

                            [
                                'label' => '筛选',
                                'attribute' => 'planter',
                                'format' => 'raw',
                                'value' => function($model) {
                                    if($model->planter) {
                                        return '<span class="text-green">承租者种植</span>';
                                    } else {
                                        return '<span class="text-blue">法人种植</span>';
                                    }
                                },
                                'filter' => [1=>'承租者种植',0=>'法人种植'],
                            ],
                        ],

                    ]); ?>
                </div>
              <div class="tab-pane active" id="activity">
                 <?= GridView::widget([
		        'dataProvider' => $dataProvider,
		        'filterModel' => $searchModel,
                     'total' =>  '<tr height="40">
                                        <td></td>		
                                        <td><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t0"><strong></strong></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t7"><strong></strong></td>
                                        <td align="left" id="t10"><strong></strong></td>
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
                'attribute' => 'farmername',
                'options' =>['width'=>120],
                'value' => function ($model) {

                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['farmername'];

                }
            ],
            [
                'label' => '合同号',
                'attribute' => 'farmstate',
                'options' => ['width' => '200'],
                'value' => function($model) {
                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['contractnumber'];
                },
                'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同',5=>'其它'],
            ],
//            [
//                'label' => '合同面积',
//                'value' => function($model) {
//                    return Farms::find ()->where ( [
//                        'id' => $model->farms_id
//                    ] )->one ()['contractarea'];
//                }
//            ],
            'policyholder',
            'contractarea',
            'insuredarea',
            [
                'label' => '保险种类',
                'attribute' => 'insured',
                'format' => 'raw',
                'value' => function($model) {
                    $result = '';
                    if(!empty($model->insuredsoybean)) {
                        $result .= '<span style="font-size:30px"><strong>大豆：' .$model->insuredsoybean. '亩</strong></span><br>';
                    }
                    if(!empty($model->insuredwheat)) {
                        $result .= '<span style="font-size:30px"><strong>小麦：' .$model->insuredwheat. '亩</strong></span><br>';
                    }
                    if(!empty($model->insuredother)) {
                        $result .= '<span style="font-size:30px"><strong>其它：' .$model->insuredother. '亩</strong></span><br>';
                    }

                    return Html::a('详情','#',['title'=>"保险详细情况" ,'data-container'=>"body", 'data-toggle'=>"popover", 'data-placement'=>"bottom", 'data-content'=>$result]);
                },
                'filter' => Insurance::getTypes(),
            ],
//            'insuredsoybean',
//            'insuredwheat',
//            'insuredother',
            [
                'attribute' => 'company_id',
                'options' =>['width' => '150'],
                'value' => function($model) {
                    $company = Insurancecompany::find()->where(['id'=>$model->company_id])->one();
                    return $company['companynname'];
                },
                'filter' => ArrayHelper::map(Insurancecompany::find()->all(),'id','companynname'),
            ],
            [
                'label' => '操作',
                'format' => 'raw',
            	'options' => ['width' => '100'],
                'value' => function($model) {
                    $option = '查看详情';
                    $title = '';
                    $url = '#';
                    $url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'view','id'=>$model->id,'farms_id'=>$model->farms_id];
                    $html = Html::a($option,$url, [
                        'id' => 'moreOperation',
                        'title' => $title,
                        'class' => 'btn btn-primary btn-xs',
                    ]);
//                    $html .= Html::a('电子信息采集',Url::to(['photograph/photographindex','farms_id'=>$model->id]),['class' => 'btn btn-primary btn-xs',]);
                    return $html;
                }
            ],
        ],
    ]);
                 $plantdata = [
                     ['value'=>Insurance::find()->where($dataProvider->query->where)->sum('insuredsoybean'),'name'=>'大豆'],
                     ['value'=>Insurance::find()->where($dataProvider->query->where)->sum('insuredwheat'),'name'=>'小麦'],
                     ['value'=>Insurance::find()->where($dataProvider->query->where)->sum('insuredother'),'name'=>'其它']
                 ];
//                 var_dump($dataProvider->query->where);exit;
                 $companydata = [];
                 $companys = Insurancecompany::getCompanyList();
                 foreach ($companys as $key => $company) {
                     $companydata[] = [
                        'value' => Insurance::find()->andFilterWhere($dataProvider->query->where)->andFilterWhere(['company_id'=>$key])->count(),
                         'name' => $company,
                     ];
                 }
                $insuranceCount = Insurance::find()->andFilterWhere($dataProvider->query->where)->andFilterWhere(['state'=>1])->count('farms_id');
                $insuranctArea = Insurance::find()->andFilterWhere($dataProvider->query->where)->andFilterWhere(['state'=>1,'year'=>User::getYear()])->sum('insuredarea');
                $farmsCount = Farms::find()->andFilterWhere(['management_area'=>Farms::getManagementArea()['id']])->andFilterWhere(['state'=>[1,2,3,4,5]])->count();
                $farmsArea = Farms::find()->andFilterWhere(['management_area'=>Farms::getManagementArea()['id']])->andFilterWhere(['state'=>[1,2,3,4,5]])->sum('contractarea');
                $noCount = $farmsCount - $insuranceCount;
                $noArea = $farmsArea - $insuranctArea;
                 ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <div id="insuranceplant" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				showPie('insuranceplant','种植业保险农作物图表占比信息',<?= json_encode(['大豆','小麦','其它'])?>,'保险作物',<?= json_encode($plantdata)?>,'亩');
		</script>

            </div>
                <div class="tab-pane" id="timeline2">
                    <div id="insurancecompany" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
                    <script type="text/javascript">
                        showPie('insurancecompany','种植业保险承保公司图表占比信息',<?= json_encode($data->getName('Insurancecompany','companynname','company_id')->typenameList())?>,'承保公司',<?= json_encode($companydata)?>,'个');
                    </script>

                </div>
                <div class="tab-pane" id="timeline3">
                    <div id="farmsinsuranceinfo" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
                    <script type="text/javascript">
                        showPie('farmsinsuranceinfo','种植业保险数量占比信息',<?= json_encode(['已参加保险','未参加保险'])?>,'保险数量',<?= json_encode([['value'=>$insuranceCount,'name'=>'已参加保险'],['value'=>$noCount,'name'=>'未参加保险']])?>,'个');
                    </script>

                </div>
                <div class="tab-pane" id="timeline4">
                    <div id="farmsinsuranceinfoarea" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
                    <script type="text/javascript">
                        showPie('farmsinsuranceinfoarea','种植业保险面积占比信息',<?= json_encode(['已参加保险','未参加保险'])?>,'保险面积',<?= json_encode([['value'=>$insuranctArea,'name'=>'已参加保险'],['value'=>$noArea,'name'=>'未参加保险']])?>,'亩');
                    </script>

                <?php User::dataListEnd();?>
                    
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<script>
    $('.shclDefault').shCircleLoader();
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
//            if(data)
            $('#t1').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id',andwhere:'<?= json_encode(['nameissame'=>1])?>'}, function (data) {
            $('#t2').html('法人'+data + '个');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-id',andwhere:'<?= json_encode(['nameissame'=>0])?>'}, function (data) {
            $('#t3').html('租赁'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t4').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-insuredarea'}, function (data) {
            $('#t5').html(data + '亩');
        });
//        $.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>//',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'sum-insuredsoybean'}, function (data) {
//            $('#t6').html(data + '亩');
//        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-insured'}, function (data) {
            $('#t6').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-insuredother'}, function (data) {
            $('#t8').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-company_id'}, function (data) {
            $('#t9').html(data + '个');
        });
//        $.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>//',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'count-state',andwhere:'<?//= json_encode(['state'=>1])?>//'}, function (data) {
//            $('#t10').html('完成'+data+'个');
//        });
    });

    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($dataProviderplan->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#pt1').html(data + '户');
        });

        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($dataProviderplan->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#pt2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($dataProviderplan->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#pt4').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($dataProviderplan->query->where)?>',command:'count-lease_id',andwhere:'<?= json_encode('lease_id>0')?>'}, function (data) {
            $('#pt6').html('承租者'+ data + '人');
        });


//        $.getJSON('index.php?r=search/search', {modelClass: 'Farms',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'count-plant_id'}, function (data) {
//             $('#t4').html(data + '种');
//         });
        //$.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>',where:'<?//= json_encode($dataProvider->query->where)?>',command:'count-goodseed_id'}, function (data) {
//             $('#t5').html(data + '种');
//         });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $plantarrclass[2]?>',where:'<?= json_encode($dataProviderplan->query->where)?>',command:'sum-area'}, function (data) {
            $('#pt5').html(data + '亩');
        });

    });

</script>