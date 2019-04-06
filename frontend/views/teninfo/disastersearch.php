<?php

use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Disastertype;
use app\models\Plant;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use frontend\helpers\arraySearch;
use frontend\helpers\Echartsdata;
use frontend\helpers\ES;
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

              
<?= $this->render('..//search/searchindex',['tab'=>$tab,'class'=>$class,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
// 	var_dump($data->);exit;
	if($data->is_data() and $data->sum('yieldreduction') !== 0.0)
		$yieldreduction = sprintf("%.2f",$data->sum('yieldreduction')/$data->allcount())*100;
	else 
		$yieldreduction = 0;
// 	var_dump($tab);exit;
$arrclass = explode('\\',$dataProvider->query->modelClass);
//'total' => '<tr>
//			        <td></td>
//			        <td align="center"><strong>合计</strong></td>
//			        <td><strong>'.$data->count('farms_id').'户</strong></td>
//			        <td><strong>'.$data->count('farmer_id').'个</strong></td>
//			        <td><strong>'.$data->count('disastertype_id').'种</strong></td>
//					<td><strong>'.$data->sum('disasterarea').'亩</strong></td>
//					<td><strong>'.$data->count('disasterplant').'种</strong></td>
//					<td></td>
//        			<td><strong>'.$yieldreduction.'%</strong></td>
//			        </tr>',
?>
<div class="nav-tabs-custom">
            <ul class="nav nav-pills nav-pills-warning">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">图表</a></li>
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
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                        <td align="center" id="t6"></strong></td>
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
                'attribute' => 'disastertype_id',
                'value' => function ($model) {
                    return Disastertype::find()->where(['id'=>$model->disastertype_id])->one()['typename'];
                },
                'filter' => Disastertype::getAlltypename()
            ],
            'disasterarea',
            [
                'attribute' => 'disasterplant',
                'value' => function ($model) {
                    return Plant::find()->where(['id'=>$model->disasterplant])->one()['typename'];
                },
                'filter' => Echartsdata::getPlantname($totalData)
            ],
            'isinsurance',
            'yieldreduction'
        ],            
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php //var_dump($data->getName('self', 'mortgagebank', 'mortgagebank')->typenameList());?>
              <?php
              $data = Echartsdata::getDisasterEcharts4($totalData);
              //                  var_dump($data);exit;
              $l = Echartsdata::getDisasterPlant($totalData,'typename');
              $x = Farms::getManagementArea()['areaname'];
              echo ES::barDiudie()->DOM('disasterEchart',true,'1500px','600px')->options(['legend'=>$l,'xAxis'=>$x,'unit'=>'元','yAxis'=>[],'series'=>$data])->JS();
              ?>
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
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-disastertype_id'}, function (data) {
            $('#t3').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-disasterarea'}, function (data) {
            $('#t4').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-disasterplant'}, function (data) {
            $('#t5').html(data + '种');
        });

    });
</script>