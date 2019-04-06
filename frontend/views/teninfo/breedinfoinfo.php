<?php

use app\models\Tables;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\User;
use app\models\Plant;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use frontend\helpers\arraySearch;
use app\models\Breedtype;
use yii\helpers\ArrayHelper;
use app\models\Breed;
use frontend\helpers\MoneyFormat;
use app\models\Breedinfo;
use frontend\helpers\Echartsdata;
use frontend\helpers\ES;
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
            <?php User::tableBegin('畜牧业');?>
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
    $arrclass = explode('\\',$dataProvider->query->modelClass);

    $totalDataFarms = clone $farmsData;
    $totalDataFarms->pagination = ['pagesize'=>0];
    $dataFarms = arraySearch::find($totalDataFarms)->search();
    $arrclassFarms = explode('\\',$farmsData->query->modelClass);
//'total' => '<tr>
//	        <td></td>
//	        <td align="center"><strong>合计</strong></td>
//	        <td><strong>'.$data->count('farms_id').'户</strong></td>
//	        <td><strong>'.$data->count('farmer_id').'个</strong></td>
//	        <td></td>
//	        <td></td>
//			<td></td>
//			<td><strong>'.$data->sum('basicinvestment',10000).'万元</strong></td>
//			<td><strong>'.$data->sum('housingarea').'平方米</strong></td>
//	        <td><strong>'.$data->count('breedtype_id').'种</strong></td>
//			<td><strong>'.$data->sum('number').'</strong></td>
//	        </tr>',
?>
            <ul class="nav nav-pills nav-pills-warning">
                <li class="active" id="types"><a href="#typeslist" data-toggle="tab" aria-expanded="true">按畜牧种类统计</a></li>
                <li id="farms"><a href="#farmslist" data-toggle="tab" aria-expanded="true">按农场统计</a></li>
                <li class="" id="typesEcharts"><a href="#typesEchartslist" data-toggle="tab" aria-expanded="false">图表</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="typeslist">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t0"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                        <td></td>
                                        <td align="left" id="t3"><strong></strong></td>
                                        <td align="left" id="t4"><strong></strong></td>
                                        <td align="left" id="t5"><strong></strong></td>
                                        <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t7"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t8"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t9"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                    </tr>',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'label'=>'管理区',
                                'attribute'=>'management_area',
                                'headerOptions' => ['width' => '150'],
                                'value'=> function($model) {
// 				            	var_dump($model);exit;
                                    return ManagementArea::getAreanameOne($model->management_area);
                                },
                                'filter' => ManagementArea::getAreaname(),
                            ],
                            [
                                'label' => '农场名称',
                                'attribute' => 'farms_id',
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
                                'attribute' => 'farmstate',
                                'options' => ['width'=>160],
                                'value' => function($model) {
                                    return Farms::find ()->where ( [
                                        'id' => $model->farms_id
                                    ] )->one ()['contractnumber'];
                                },
                                'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同',5=>'其它'],
                            ],
                            [
                                'label' => '合同面积',
                                'value' => function($model) {
                                    return Farms::find ()->where ( [
                                        'id' => $model->farms_id
                                    ] )->one ()['contractarea'];
                                }
                            ],
                            [
                                'label' => '养殖场名称',
                                'attribute' => 'breedname',
                                'value' => function ($model) {
                                    $breed = Breed::find ()->where ( [
                                        'id' => $model->breed_id
                                    ] )->one ();
                                    return $breed->breedname;
                                }
                            ],
                            [
                                'label' => '养殖场位置',
                                'attribute' => 'breedaddress',
                                'value' => function ($model) {
                                    $breed = Breed::find ()->where ( [
                                        'id' => $model->breed_id
                                    ] )->one ();
                                    return $breed->breedaddress;
                                }
                            ],
                            [
                                'label' => '示范户',
                                'attribute' => 'is_demonstration',
                                'value' => function ($model) {
                                    $breed = Breed::find ()->where ( [
                                        'id' => $model->breed_id
                                    ] )->one ();
                                    return $breed->is_demonstration ? '是' : '否';
                                } ,
                                'filter' => ['否','是'],
                            ],
                            [
                                'attribute' => 'basicinvestment',
                                'options' => ['width' => '150'],
                                'value' => function ($model) {
                                    return MoneyFormat::num_format ( $model->basicinvestment ) . '元';
                                }
                            ],
                            [
                                'attribute' => 'housingarea',
                                'format' => 'raw',
 								'options' =>['width'=>120],
                                'value' => function ($model) {
                                    return $model->housingarea . 'm&sup2;';
                                }
                            ],
                            [
                                'attribute' => 'breedtype_id',
                                'value'=> function($model) {
                                    return Breedtype::find()->where(['id'=>$model->breedtype_id])->one()['typename'];
                                },
                                'filter' => Breedtype::getAllTypename(),

                            ],
                            [
                                'attribute' => 'number',
                                'options' => ['width' => '100'],
                                'value' => function ($model) {
                                    $breedtype = Breedtype::find ()->where ( [
                                        'id' => $model->breedtype_id
                                    ] )->one ();
                                    return $model->number . $breedtype->unit;
                                }
                            ]
                        ],
                    ]); ?>
                </div>

                <div class="tab-pane" id="farmslist">
                    <?= GridView::widget([
                        'dataProvider' => $farmsData,
                        'filterModel' => $farmsSearch,
                        'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t20"><strong>合计</strong></td>
                                        <td align="left" id="t21"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t22"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t23"></td>
                                        <td align="left" id="t24"></td>
                                        <td align="left" id="t25"></td>
                                    </tr>',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn','options'=>['width'=>'40']],
                            [
                                'label'=>'管理区',
                                'attribute'=>'management_area',
                                'headerOptions' => ['width' => '150'],
                                'value'=> function($model) {
// 				            	var_dump($model);exit;
                                    return ManagementArea::getAreanameOne($model->management_area);
                                },
                                'filter' => ManagementArea::getAreaname(),
                            ],
                            [
                                'label' => '农场名称',
                                'attribute' => 'id',
                                'options' => ['width' => '150'],
                                'value' => function ($model) {
                                    return Farms::find ()->where ( [
                                        'id' => $model->id
                                    ] )->one ()['id'];
                                }
                            ],
                            [
                                'label' => '养殖场名称',
//                                'attribute' => 'breedname',
                                'value' => function ($model) {
                                    $breed = Breed::find ()->where ( [
                                        'farms_id' => $model->id,'year'=>User::getYear()
                                    ] )->one ();
//                                    return $breed['id'];
                                    return $breed['breedname'];
                                }
                            ],
                            [
                                'label' => '养殖场位置',
//                                'options' => ['width' => '200'],
//                                'attribute' => 'breedaddress',
                                'value' => function ($model) {
                                    $breed = Breed::find ()->where ( [
                                        'farms_id' => $model->id,'year'=>User::getYear()
                                    ] )->one ();
                                    return $breed['breedaddress'];
                                }
                            ],
                            [
                                'label' => '示范户',
//                                'attribute' => 'is_demonstration',
//                                'options' => ['width' => '80'],
                                'value' => function ($model) {
                                    $breed = Breed::find ()->where ( [
                                        'farms_id' => $model->id,'year'=>User::getYear()
                                    ] )->one ();
                                    return $breed['is_demonstration'] ? '是' : '否';
                                } ,
                                'filter' => ['否','是'],
                            ],
                            [
                                'label' => '养殖情况',
                                'format' => 'raw',
                                'value'=> function($model) {
                                    $breeds =  Breedinfo::find()->where(['farms_id'=>$model->id,'year'=>User::getYear()])->all();
                                    $html = '<table class="table-striped"><tr>';
                                    foreach ($breeds as $breed) {
                                        $breedtype = Breedtype::find()->where(['id'=>$breed['breedtype_id']])->one();
                                        $html.='<td class="text text-right">'.$breedtype['typename'].':</td><td class="text text-left">'.$breed['number'].$breedtype['unit'].'</td><td width="10%"></td>';
                                    }
                                    $html.='</tr></table>';
                                    return $html;
                                },
                                'filter' => Breedtype::getAllTypename(),

                            ],
                        ],
                    ]); ?>
                </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="typesEchartslist">
                  <?php
                  $x = Echartsdata::getBreedinfoTypename($totalData,'typename');
                  $s = Echartsdata::getBreedinfoinfo($totalData);
                  $unit = Echartsdata::getBreedinfoTypename($totalData,'unit');
                  echo ES::barUnit()->DOM('breedinfoEcharts',true,'1500px','500px')->options(['name'=>'畜牧业','xAxis'=>$x,'unit'=>$unit,'series'=>$s])->JS();
                  ?>
            </div>
          </div>
 
                </div>

                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php
$tab = new \frontend\helpers\Tab();
echo $tab->createTab(Yii::$app->controller->action->id,['types','farms','typesEcharts']);
?>
<script>
    $('.shclDefault').shCircleLoader({color: "red"});

        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t1').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-basicinvestment'}, function (data) {
            $('#t6').html(data + '元');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-housingarea'}, function (data) {
            $('#t7').html(data + 'm&sup2;');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'countunique-breedtype_id'}, function (data) {
            $('#t8').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-number'}, function (data) {
            $('#t9').html(data);
        });

    });
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassFarms[2]?>',where:'<?= json_encode($farmsData->query->where)?>',command:'count-id'}, function (data) {
            $('#t21').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassFarms[2]?>',where:'<?= json_encode($farmsData->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t22').html(data + '人');
        });
    });
</script>