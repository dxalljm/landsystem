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

<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <li class=""><a href="#plantingstructure" data-toggle="tab" aria-expanded="false">种植结构图表</a></li>
              <?php if(Plantingstructure::getGoodseedname($params)) {?>
              <li class=""><a href="#goodseedEcharts" data-toggle="tab" aria-expanded="false">良种图表</a></li>
              <?php }?>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
               <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'total' => '<tr>
			        <td></td>
			        <td align="center"><strong>合计（'.Plantingstructure::getFarmRows($params).'户）</strong></td>
			        <td><strong>种植者'.Plantingstructure::getPlanter($params).'个</strong></td>
			        <td><strong>法人种植'.Plantingstructure::getFarmerrows($params).'个</strong></td>
			        <td><strong>'.Plantingstructure::getLeaseRows($params).'个</strong></td>
			        <td><strong>'.Plantingstructure::getPlantRows($params).'种</strong></td>
			        <td><strong>'.Plantingstructure::getGoodseedRows($params).'种</strong></td>
			        <td><strong>'.Plantingstructure::getArea($params).'万亩</strong></td>
			        </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','lease_id','plant_id','goodseed_id','area'],$params),
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id="plantingstructure">
              <div id="plantingstructuredata" style="width:1000px; height: 600px; margin: 0 auto"></div>
              </div>
              <script type="text/javascript">
				showAllShadow('plantingstructuredata',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode(Plantingstructure::getPlantname($params)['typename'])?>,<?= Plantingstructure::getPlantingstructure($params)?>,'万亩');
				//showStacked('collection','应收：<?php //echo Collection::totalAmounts()?> 实收：<?php //echo Collection::totalReal()?>',<?php //echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php //echo Collection::getCollection()?>,'万元');
		</script>
              <!-- /.tab-pane -->

               <?php if(Plantingstructure::getGoodseedname($params)) {?>
            <div id="goodseedEcharts" style="width: 1000px%; height: 600px; margin: 0 auto;" >
              <?php //var_dump(Plantingstructure::getGoodseedname($params));exit;?>
            </div>
            <script type="text/javascript">
				showAllShadow('goodseedEcharts',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode(Plantingstructure::getGoodseedname($params)['typename'])?>,<?= Plantingstructure::getGoodseedEcharts($params)?>,'万亩');
				//showStacked('collection','应收：<?php //echo Collection::totalAmounts()?> 实收：<?php //echo Collection::totalReal()?>',<?php //echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php //echo Collection::getCollection()?>,'万元');
		</script>
            <?php }?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
                
