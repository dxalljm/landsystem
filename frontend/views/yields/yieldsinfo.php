<?php

use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Lease;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Url;
use yii\widgets\ActiveFormrdiv;
use app\models\Search;
use app\models\Yields;
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

 <?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
// 	var_dump($totalData->getModels());exit;
	$data = arraySearch::find($totalData)->search();
?>
 
<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">图表</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['method'=>'get'],
        'total' => '<tr>
			        <td></td>
			        <td align="center"><strong>合计</strong></td>
			        <td><strong>'.$data->count('farms_id',true).'户</strong></td>
			        <td><strong>'.$data->count('farmer_id',true).'个</strong></td>
			        <td><strong>'.$data->count('plant_id',true).'个</strong></td>
			        <td><strong>'.$data->otherSum('planting_id',['Plantingstructure','area'],10000).'万亩</strong></td>
			        <td><strong>'.$data->sum('single',10000).'万斤</strong></td>
			        <td><strong>'.$data->mulOtherSum('single','planting_id',['Plantingstructure','area'],10000).'万斤</strong></td>
			        </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','planting_id','single','allsingle'],$totalData),
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php //var_dump($data->getName('Plant', 'cropname', 'plant_id')->getEchartsData(['*','single',['Plantingstructure',['planting_id','area']]],10000));?>
                <div id="yields" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				showAllShadow('yields',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($data->getName('Plant', 'cropname', 'plant_id')->typenameList())?>,<?= $data->getName('Plant', 'cropname', 'plant_id')->showAllShadow('mulOtherSum',['single','planting_id',['Plantingstructure','area']],10000)?>,'万斤');
				//showStacked('collection','应收：<?php //echo Collection::totalAmounts()?> 实收：<?php //echo Collection::totalReal()?>',<?php //echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php //echo Collection::getCollection()?>,'万元');
		</script>

            </div>
          </div>
 
                </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
