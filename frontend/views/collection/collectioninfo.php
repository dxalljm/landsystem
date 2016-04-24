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
use app\models\PlantPrice;
use app\models\Theyear;
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
	$data = arraySearch::find($totalData)->search();
// 	var_dump($data->getEchartsData(['real_income_amount','amounts_receivable'],1,'showShadowThermometer'));
// 	var_dump($_GET);
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
			        <td><strong>'.$data->sum('amounts_receivable').'元</strong></td>			        
					<td><strong>'.$data->sum('real_income_amount').'元</strong></td>
					<td><strong>'.$data->sum('owe').'元</strong></td>
					<td><strong>'.$data->sum('ypayarea').'亩</strong></td>
    				<td><strong>'.$data->sum('ypaymoney').'元</strong></td>
			        </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','amounts_receivable','real_income_amount','owe','ypayarea','ypaymoney','operation'],$totalData),
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php $echartsData = $data->setEchartsName(['实收金额','应收金额'])->collectionShowShadow()?>
                <div id="collection" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				wdjShowEchart('collection',<?= json_encode(['实收金额','应收金额'])?>,<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($echartsData['all'])?>,<?= json_encode($echartsData['real'])?>,'元');
				//showStacked('collection','应收：<?php //echo Collection::totalAmounts()?> 实收：<?php //echo Collection::totalReal()?>',<?php //echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php //echo Collection::getCollection()?>,'万元');
		</script>

            </div>
          </div>        
                </div>
            </div>
        </div>
    </div>
</section>
</div>
