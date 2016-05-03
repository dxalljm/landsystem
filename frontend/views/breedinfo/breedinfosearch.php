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
	        <td></td>
			<td></td>
			<td><strong>'.$data->sum('basicinvestment',10000).'万元</strong></td>
			<td><strong>'.$data->sum('housingarea').'平方米</strong></td>
	        <td><strong>'.$data->count('breedtype_id').'种</strong></td>
			<td><strong>'.$data->sum('number').'</strong></td>
	        </tr>',
        'columns' => Search::getColumns(['management_area','breed_id','basicinvestment','housingarea','breedtype_id','number'],$totalData),
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php //var_dump($data->getName('Breedtype', 'unit', 'breedtype_id')->typenameList());?>
                <div id="breedinfo" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				showShadow('breedinfo',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($data->getName('Breedtype', 'typename', 'breedtype_id')->typenameList())?>,<?= $data->getName('Breedtype', 'typename', 'breedtype_id')->showAllShadow('sum','number')?>,<?= json_encode($data->getName('Breedtype', 'unit', 'breedtype_id')->typenameList())?>);
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
