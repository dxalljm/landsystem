<?php

use app\models\Tables;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Plant;
use app\models\User;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use frontend\helpers\arraySearch;
use app\models\Huinong;
use app\models\Subsidiestype;
use app\models\Huinonggrant;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
$arrclassFarms = explode('\\',$farmsData->query->modelClass);
	$namelist = $data->getName('Subsidiestype', 'typename', ['Huinong','huinong_id','subsidiestype_id'])->getList();
//'total' => '<tr>
//						        <td></td>
//						        <td align="center"><strong>合计</strong></td>
//						        <td><strong>'.$data->count('farms_id').'个</strong></td>
//						        <td><strong>'.$data->count('farmer_id').'个</strong></td>
//						        <td><strong>'.$data->count('lease_id').'个</strong></td>
//								<td><strong></strong></td>
//								<td><strong>'.$data->count('huinong_id').'个</strong></td>
//						        <td><strong>'.$data->sum('money').'元</strong></td>
//						        <td><strong>'.$data->sum('area').'亩</strong></td>
//						        </tr>',
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php User::tableBegin('惠农政策');?>
            <ul class="nav nav-pills nav-pills-warning">
              <li class="active" id="types"><a href="#typesTable" data-toggle="tab" aria-expanded="true">按补贴种类统计</a></li>
                <li id="farms"><a href="#farmsTable" data-toggle="tab" aria-expanded="true">按农场统计</a></li>
              <?php foreach(Huinong::getTypename() as $key => $value) {
//               	var_dump($value);exit;
              			echo '<li class=""><a href="#huinongview'.$key.'" data-toggle="tab" aria-expanded="false">'.$value.'图表</a></li>';
			  		}
			  	?>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="typesTable">
               <?= GridView::widget([
			        'dataProvider' => $dataProvider,
			        'filterModel' => $searchModel,
                   'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t0"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                        
                                    </tr>',
//			        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','lease_id','subsidiestype_id','typeid','money','area'],$totalData),
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
                            'label' => '承租人',
                            'attribute' => 'lease_id',
                            'value' => function($model) {
                                return \app\models\Lease::find()->where(['id'=>$model->lease_id])->one()['lessee'];
                            }
                        ],
                        [
                            'label' => '补贴种类',
                            'attribute' => 'subsidiestype_id',
                            'value' => function ($model) {
                                return Subsidiestype::find()->where(['id'=>$model->subsidiestype_id])->one()['typename'];
                            },
                            'filter' => Subsidiestype::getTypelist(),

                        ],
                        [
                            'label' => '作物',
                            'attribute' => 'typeid',
                            'value' => function($model) {
                                $sub = Subsidiestype::find()->where(['id'=>$model->subsidiestype_id])->one()['urladdress'];

                                $classFile = 'app\\models\\'. $sub;
                                $data = $classFile::find()->where(['id'=>$model->typeid])->one();
                                if($sub == 'Plant')
                                    return $data['typename'];
                                if($sub == 'Goodseed') {
                                    $plant = Plant::find()->where(['id'=>$data['plant_id']])->one();
                                    return $plant['typename'].'/'.$data['typename'];
                                }
                            },
                            'filter' => Plant::getCheckAllname($totalData),
                        ],
                        'money',
                        'area',
                    ],

			    ]); ?>
              </div>
                <div class="tab-pane" id="farmsTable">
                    <?= GridView::widget([
                        'dataProvider' => $farmsData,
                        'filterModel' => $farmsSearch,
                        'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t20"><strong>合计</strong></td>
                                        <td align="left" id="t21"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t22"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t23"><strong></strong></td>
                                        <td align="left" id="t24"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t25"><strong></strong></td>
                                    </tr>',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn','options'=>['width'=>'40']],
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
                                    return $model->farmname;
                                }
                            ],
                            [
                                'label' => '法人名称',
                                'attribute' => 'farmer_id',
                                'options' =>['width'=>120],
                                'value' => function ($model) {
                                    return $model->farmername;
                                }
                            ],
                            [
                                'label' => '合同号',
                                'attribute' => 'state',
                                'value' => function($model) {
                                    return $model->contractnumber;
                                },
                                'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同'],
                            ],
                            [
                                'label' => '合同面积',
                                'attribute' => 'contractarea',
                                'value' => function($model) {
                                    return $model->contractarea;
                                }
                            ],
                            [
                                'label' => '补贴金额',
                                'value' => function($model) {
                                    $money = Huinonggrant::find()->where(['farms_id'=>$model->id,'year'=>User::getLastYear()])->sum('money');
                                    if(empty($money)) {
                                        $money = 0;
                                    }
                                    return $money.'元';
                                }
                            ],
                            [
                                'label' => '补贴明细',
                                'format' => 'raw',
                                'value' => function($model) {
                                    $huinonggrants = Huinonggrant::find()->where(['farms_id'=>$model->id,'year'=>User::getLastYear()])->all();
                                    $html = '<table class="table-striped"><tr>';
                                    foreach ($huinonggrants as $huinonggrant) {
                                        $plant = Plant::find()->where(['id'=>$huinonggrant['typeid']])->one();
                                        $html.='<td class="text text-right">'.$plant['typename'].':</td><td class="text text-left">'.$huinonggrant['money'].'</td><td width="10%"></td>';
                                    }
                                    $html.='</tr></table>';
                                    return $html;
                                }
                            ],
                            [
                                'label' => '是否补贴',
                                'value' => function($model) {
                                    $huinonggrants = Huinonggrant::find()->where(['farms_id'=>$model->id,'year'=>User::getLastYear()])->all();
                                    if($huinonggrants) {
                                        return '有补贴';
                                    } else {
                                        return '无补贴';
                                    }
                                }
                            ],
                        ],
                    ]); ?>
                </div>
              <?php foreach(Huinong::getTypename() as $key => $value) {
              $classname = 'huinong'.$key;
              	?>
              <!-- /.tab-pane -->
              <div class='tab-pane' id="huinongview<?= $key?>">
              <div id="<?= $classname?>" style="width:1000px; height: 600px; margin: 0 auto"></div>
				<?php $echartsData = $data->getName('Subsidiestype', 'typename', 'subsidiestype_id')->huinongShowShadow($key);?>
              </div>
              <script type="text/javascript">
              wdjShowEchart('<?= $classname?>',<?= json_encode(['应发金额','实发金额'])?>,<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($echartsData['all'])?>,<?= json_encode($echartsData['real'])?>,'元');
			</script>
              <!-- /.tab-pane -->
            <!-- /.tab-content -->
            <?php }?>
         <?php User::dataListEnd();?>
        </div>
    </div>
</section>
</div>
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        if($.session.get('<?= Yii::$app->controller->action->id?>') == 'types') {
            $('#types').attr('class','active');
            $('#farms').attr('class','');

            $('#types').attr('aria-expanded',true);
            $('#farms').attr('aria-expanded',false);

            $('#typesTable').attr('class','tab-pane active');
            $('#farmsTable').attr('class','tab-pane');
        }
        if($.session.get('<?= Yii::$app->controller->action->id?>') == 'farms') {
            $('#types').attr('class','');
            $('#farms').attr('class','active');

            $('#types').attr('aria-expanded',false);
            $('#farms').attr('aria-expanded',true);

            $('#typesTable').attr('class','tab-pane');
            $('#farmsTable').attr('class','tab-pane active');
        }

        $('#types').click(function () {
            $.session.set('<?= Yii::$app->controller->action->id?>', 'types');
            $('#types').attr('aria-expanded',true);
            $('#farms').attr('aria-expanded',false);
            $('#types').attr('class','active');
            $('#farms').attr('class','');
            $('#typesTable').attr('class','tab-pane active');
            $('#farmsTable').attr('class','tab-pane');
        });
        $('#farms').click(function () {
            $.session.set('<?= Yii::$app->controller->action->id?>', 'farms');
            $('#types').attr('aria-expanded',false);
            $('#farms').attr('aria-expanded',true);
            $('#types').attr('class','');
            $('#farms').attr('class','active');
            $('#typesTable').attr('class','tab-pane');
            $('#farmsTable').attr('class','tab-pane active');
        });
        
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t1').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-lease_id'}, function (data) {
            $('#t2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-lease_id'}, function (data) {
            $('#t3').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-huinong_id'}, function (data) {
            $('#t4').html(data + '个');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-money'}, function (data) {
            $('#t5').html(data + '元');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-area'}, function (data) {
            $('#t6').html(data + '亩');
        });

    });

    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassFarms[2]?>',where:'<?= json_encode($farmsData->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t21').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassFarms[2]?>',where:'<?= json_encode($farmsData->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t22').html(data + '人');
        });

        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclassFarms[2]?>',where:'<?= json_encode($farmsData->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t24').html(data + '亩');
        });

    });
</script>
		