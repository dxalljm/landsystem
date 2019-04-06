<?php

use app\models\Tables;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\User;
use app\models\Plant;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use app\models\Projectapplication;
use frontend\helpers\arraySearch;
use app\models\Infrastructuretype;
use app\models\Machinetype;
use app\models\Machine;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php User::tableBegin('项目');?>
<?php
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
//'total' => '<tr>
//			        <td></td>
//			        <td align="center"><strong>合计</strong></td>
//			        <td><strong>'.$data->count('farms_id').'户</strong></td>
//			        <td><strong>'.$data->count('farmer_id').'个</strong></td>
//			        <td><strong>'.$data->count('projecttype').'个</strong></td>
//			        <td><strong></strong></td>
//			        </tr>',
?>
            <ul class="nav nav-pills nav-pills-warning">
                <li class="active" id="fixed"><a href="#fixedinfo" data-toggle="tab" aria-expanded="true">固定资产统计表</a></li>
                <li class="" id="machine"><a href="#machineinfo" data-toggle="tab" aria-expanded="true">农机器具统计表</a></li>
              <li class=""><a href="#activity" data-toggle="tab" aria-expanded="true">项目统计表</a></li>
              <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">图表</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="fixedinfo">
                    <?= GridView::widget([
                        'dataProvider' => $dataFixed,
                        'filterModel' => $fixedSearch,
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
//                                'attribute' => 'farmer_id',
                                'options' =>['width'=>120],
                                'value' => function ($model) {

                                    return Farms::find ()->where ( [
                                        'id' => $model->farms_id
                                    ] )->one ()['farmername'];

                                }
                            ],
                            [
                                'label' => '合同号',
//                                'attribute' => 'farmstate',
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
                                'label' => '名称',
                                'attribute' => 'name',
                            ],
                            'unit',
                            [
                                'label' => '数量',
                                'attribute' => 'number',
                            ],
                            [
                                'label' => '状态',
                                'attribute' => 'state'
                            ],
                            // 'remarks:ntext',

//                            ['class' => 'frontend\helpers\eActionColumn'],
                        ],
                    ]); ?>
                </div>
                <?php
                if(isset($_GET['bigclass'])) {
                    $bigid = $_GET['bigclass'];
                } else {
                    $bigid = '';
                }
                if(isset($_GET['smallclass'])) {
                    $smallid = $_GET['smallclass'];
                } else {
                    $smallid = '';
                }
                ?>
                <div class="tab-pane" id="machineinfo">
                    <?= GridView::widget([
                        'dataProvider' => $dataMachine,
                        'filterModel' => $machineSearch,
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
                                'label' => '法人名称',
//                                'attribute' => 'farmer_id',
                                'options' =>['width'=>120],
                                'value' => function ($model) {

                                    return Farms::find ()->where ( [
                                        'id' => $model->farms_id
                                    ] )->one ()['farmername'];

                                }
                            ],
                            [
                                'label' => '农机大类',
                                'attribute' => 'bisclass',
                                'value' => function ($model) {
                                    return Machinetype::getClass($model->machinetype_id)[0];
                                },
                                'filter' => Machinetype::getBigclass(),
                            ],
                            [
                                'label' => '农机小类',
                                'attribute' => 'smallclass',
                                'value' => function ($model) {
                                    return Machinetype::getClass($model->machinetype_id)[1];
                                },
                                'filter' => Machinetype::getSmallclass($bigid)
                            ],
                            [
                                'label' => '农机品目',
                                'attribute' => 'machinetype_id',
                                'value' => function ($model) {
                                    return Machinetype::getClass($model->machinetype_id)[2];
                                },
                                'filter' => Machinetype::getMachinetype($smallid),
                            ],
                            [
                                'label' => '型号',
                                'value' => function ($model) {
                                    return Machine::find()->where(['id'=>$model->machine_id])->one()['implementmodel'];
                                }
                            ],
                            [
                                'label' => '生产厂商',
                                'value' => function ($model) {
                                    return Machine::find()->where(['id'=>$model->machine_id])->one()['enterprisename'];
                                }
                            ],
                            [
                                'label' => '购置年份',
                                'attribute' => 'acquisitiontime',
                            ],
//             'machinetype_id',
//             'machine_id',
                            'machinename',
                        ],
                    ]); ?>
                </div>
              <div class="tab-pane" id="activity">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t0"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t0"><strong></strong></td>
                                        <td align="left" id="t0"><strong></strong></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
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
                'label' => '合同号',
                'attribute' => 'farmstate',
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
                'attribute' => 'projecttype',
                'value'=> function($model,$params) {
                    return Infrastructuretype::find()->where(['id'=>$model->projecttype])->one()['typename'];
                },
                'filter' => Infrastructuretype::getAllname($params),
            ],
            [
                'attribute' => 'projectdata',
                'value' => function ($model) {
                    return $model->projectdata.$model->unit;
                }
            ],
            [
                'label' => '工程情况',
                'value' => function ($model) {
                    $plan = Projectplan::find()->where(['project_id'=>$model->id])->one();
                    if($plan) {
                        $now = time();
                        if($now<=$plan['begindate'])
                            return '未开始';
                        if($now<=$plan['enddate'] and $now >= $plan['begindate'])
                            return '施工中';
                        if($now >= $plan['enddate'])
                            return '工程结束';
                    } else {
                        return '还没有工程计划';
                    }
                }
            ],
        ],            
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php //var_dump($data->getName('Infrastructuretype', 'typename', 'projecttype')->typenameList());?>
                <div id="projectapplication" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				showAllShadowProject('projectapplication',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($data->getName('Infrastructuretype', 'typename', 'projecttype')->typenameList())?>,<?= $data->getName('Infrastructuretype', 'typename', 'projecttype')->showAllShadow('sum','projectdata');?>,<?= json_encode(Projectapplication::getTypenamelist($params)['unit'])?>);
				//showStacked('collection','应收：<?php //echo Collection::totalAmounts()?> 实收：<?php //echo Collection::totalReal()?>',<?php //echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php //echo Collection::getCollection()?>,'万元');
		</script>
<?php User::dataListEnd();?>
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
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-projecttype'}, function (data) {
            $('#t3').html(data + '个');
        });
    });
</script>