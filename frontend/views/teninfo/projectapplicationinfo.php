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
use frontend\helpers\Echartsdata;
use frontend\helpers\ES;
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
	$totalFixed = clone $dataFixed;
    $totalFixed->pagination = ['pagesize'=>0];
    $totalMachine = clone $dataMachine;
    $totalMachine->pagination = ['pagesize'=>0];
//	$data = arraySearch::find($totalData)->search();
    $fixedclass = explode('\\',$totalFixed->query->modelClass);
    $machineclass = explode('\\',$totalMachine->query->modelClass);
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
                <li class="active" id="fixed"><a href="#fixedlist" data-toggle="tab" aria-expanded="true">固定资产统计表</a></li>
                <li class="" id="fixedEcharts"><a href="#fixedEchartslist" data-toggle="tab" aria-expanded="false">固定资产图表</a></li>
                <li class="" id="machine"><a href="#machinelist" data-toggle="tab" aria-expanded="false">农机器具统计表</a></li>
                <li class="" id="machineEcharts"><a href="#machineEchartslist" data-toggle="tab" aria-expanded="false">农机图表</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="fixedlist">
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

                <div class="tab-pane" id="fixedEchartslist">
                    <?php //var_dump($data->getName('Infrastructuretype', 'typename', 'projecttype')->typenameList());
                    $fixx = Echartsdata::getFixedtypename($totalFixed,'typename');
                    $fixs = Echartsdata::getFixedinfo($totalFixed);
                    $fixu = Echartsdata::getFixedtypename($totalFixed,'unit');
//                    echo ES::bar()->DOM('fixede',true,'1500px','800px')->options(['title'=>'固定资产统计图表','tooltip'=>[],'legend'=>['固定资产'],'yAxis'=>[],'xAxis'=>$fixx,'series'=>$fixs,'unit'=>$fixu])->JS();
                    echo ES::barUnit()->DOM('breedinfoEcharts',true,'1500px','500px')->options(['name'=>'固定资产','xAxis'=>$fixx,'unit'=>$fixu,'series'=>$fixs])->JS();
                    ?>

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
                <div class="tab-pane" id="machinelist">
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


              <!-- /.tab-pane -->
              <div class="tab-pane" id="machineEchartslist">
              <?php
                    $mx = Echartsdata::getMachinetypename($totalMachine,'typename');
                    $ms = Echartsdata::getMachinenumber($totalMachine);
                    echo ES::bar()->DOM('machineecharts',true,'1500px','500px')->options(['legend'=>['农机'],'xAxis'=>$mx,'unit'=>'台','series'=>$ms])->JS();
              ?>
              </div>
            </div>
            <?php User::dataListEnd();?>
        </div>
    </div>
</section>
<?php
$tab = new \frontend\helpers\Tab();
echo $tab->createTab(Yii::$app->controller->action->id,['fixed','fixedEcharts','machine','machineEcharts']);
?>
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $fixedclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t1').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $fixedclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $fixedclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-projecttype'}, function (data) {
            $('#t3').html(data + '个');
        });
    });
</script>