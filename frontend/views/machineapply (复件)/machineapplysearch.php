<?php

use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Lease;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Url;
use frontend\helpers\ActiveFormrdiv;
use app\models\Search;
use frontend\helpers\arraySearch;
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
            <div class="box">
                <div class="box-body">

<?= $this->render('..//search/searchindex',['tab'=>$tab,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
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
//			        <td><strong>'.$data->sum('mortgagearea').'亩</strong></td>
//					<td><strong>'.$data->count('mortgagebank').'个</strong></td>
//					<td><strong>'.$data->sum('mortgagemoney').'元</strong></td>
//			        </tr>',
// 	var_dump($tab);exit;
?>
<div class="nav-tabs-custom">
            <ul class="nav nav-pills nav-pills-warning">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
                    'total' => '<tr height="40">
                                        <td></td>	
                                        <td align="left" id="t0"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"><strong></strong></td>
                                        <td align="left" id="t3"><strong></strong></td>
                                        <td align="left" id="t3"><strong></strong></td>
                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"><strong></strong></td>
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
                        'farmername',
//            'age',
//            'sex',
                        // 'domicile',
                        // 'management_area',
                        'cardid',
                        'telephone',
                        [
                            'label' => '农机类型',
                            'value' => function($model) {
                                $offarm = \app\models\Machineoffarm::findOne($model->machineoffarm_id);
                                $type = \app\models\Machinetype::findOne($offarm->machinetype_id);
                                return $type->typename;
                            }
                        ],
                        [
                            'label' => '农机名称',
                            'value' => function($model) {
                                $offarm = \app\models\Machineoffarm::findOne($model->machineoffarm_id);
                                return $offarm->machinename;
                            }
                        ],
                        'subsidymoney',

//                        [
//                            'attribute' => 'state',
//                            'format' => 'raw',
//                            'value' => function($model) {
//                                if($model->state == 1) {
//                                    $result = '<span class="text-green">已完成</span>';
//                                }
//                                if($model->state == 0) {
//                                    $result = '已申请';
//                                }
//                                if($model->state == -1) {
//                                    $result = '<span class="text-red">已撤消</span>';
//                                }
//                                return $result;
//                            },
////                            'filter' => [0=>'已申请',1=>'已完成',-1=>'已撤消'],
//                        ],
                        // 'machineoffarm_id',
                        // 'farmerpinyin',
                    ],
    ]); ?>
              </div>
              <!-- /.tab-pane -->

            </div>
          </div>        

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
            $('#t1').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html(data + '人');
        });
//        $.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>//',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'sum-mortgagearea'}, function (data) {
//            $('#t3').html(data + '亩');
//        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-subsidymoney'}, function (data) {
            $('#t4').html(data + '元');
        });
//        $.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>//',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'sum-mortgagemoney'}, function (data) {
//            $('#t5').html(data + '元');
//        });
    });
</script>