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
use frontend\helpers\arraySearch;
use app\models\PlantPrice;
use app\models\Theyear;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
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
// 	var_dump($data->getEchartsData(['real_income_amount','amounts_receivable'],1,'showShadowThermometer'));
	var_dump($_GET);
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
			        <td><strong>'.$data->sum('amounts_receivable',10000).'万元</strong></td>			        
					<td><strong>'.$data->sum('real_income_amount',10000).'万元</strong></td>
					<td><strong>'.$data->sum('owe',10000).'万元</strong></td>
					<td><strong>'.$data->sum('ypayarea',10000).'万亩</strong></td>
    				<td><strong>'.$data->sum('ypaymoney',10000).'万元</strong></td>
			        </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','amounts_receivable','real_income_amount','owe','ypayarea','ypaymoney'],$totalData),
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php var_dump($data->setEchartsName(['应收金额','实收金额'])->getEchartsData(['real_income_amount',['*','measure',['PlantPrice',['years'=>Theyear::getYear()],'price']]],10000,'showShadowThermometer',1))?>
                <div id="collection" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				showShadowThermometer('collection',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= $data->setEchartsName(['应收金额','实收金额'])->getEchartsData(['real_income_amount',['*','measure',['PlantPrice',['price'=>Theyear::getYear()],'price']]],10000,'showShadowThermometer',1)?>,'万元');
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
