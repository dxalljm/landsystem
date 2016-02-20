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
	//'firecontract','safecontract','environmental_agreement','firetools','mechanical_fire_cover','chimney_fire_cover','isolation_belt','propagandist','fire_administrator','fieldpermit','propaganda_firecontract','employee_firecontract'
?>

    <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
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
	        </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','percent','percentvalue'],$totalData),
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
