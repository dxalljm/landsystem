<?php

use app\models\tables;
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
// 	var_dump($data->sum('preventionnumber'));var_dump($data->sum('breedinfonumber'));exit;
	$number = $data->sum('preventionnumber');
// 	var_dump($number);exit;
	if((integer)$number)
		$preventionrate = sprintf("%.2f", $data->sum('preventionnumber')/$data->sum('breedinfonumber'))*100;
	else
		$preventionrate = 0;
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
        'total' => '<tr>
	        <td></td>
	        <td align="center"><strong>合计</strong></td>
	        <td><strong>'.$data->count('farms_id').'户</strong></td>
	        <td><strong>'.$data->count('farmer_id').'个</strong></td>
	        <td></td>
			<td><strong>'.$data->count('breedtype_id').'种</strong></td>
			<td><strong>'.$data->count('id').'个</strong></td>
	        <td><strong>'.$data->sum('preventionnumber').'</strong></td>
	        <td><strong>'.$data->sum('breedinfonumber').'</strong></td>
			<td><strong>'.$preventionrate.'%</strong></td>
	        </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','breedinfo_id','breedtype_id','isepidemic','preventionnumber','breedinfonumber','preventionrate'],$totalData),
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php $echartsData = $data->getName('Breedtype', 'typename', 'breedtype_id')->setEchartsName(['应免数量','免疫数量'])->showShadowThermometer(['preventionnumber','breedinfonumber'],1);?>
                <div id="prevention" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				wdjShowEchart('prevention',<?= json_encode(['已免数量','应免数量'])?>,<?= json_encode($data->getName('Breedtype', 'typename', 'breedtype_id')->typenameList())?>,<?= json_encode($echartsData[1])?>,<?= json_encode($echartsData[0])?>,'');
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
