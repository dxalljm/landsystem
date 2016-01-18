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

              
   <?= $this->render('..//search/searchindex',['tab'=>$tab,'management_area'=>$management_area,'begindate'=>$begindate,'enddate'=>$enddate]);?>
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	
// 	var_dump(Farms::find()->where(['management_area'=>[1,2,3]])->all());
	var_dump(arraySearch::find($totalData)->search());
	exit;
?>
<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <li class=""><a href="#plantingstructure" data-toggle="tab" aria-expanded="false">种植结构图表</a></li>
              <?php if(Plantingstructure::getGoodseedname($totalData)) {?>
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
						        <td align="center"><strong>合计（'.Plantingstructure::getFarmRows($totalData).'户）</strong></td>
						        <td><strong>种植者'.Plantingstructure::getPlanter($totalData).'个</strong></td>
						        <td><strong>法人种植'.Plantingstructure::getFarmerrows($totalData).'个</strong></td>
						        <td><strong>'.Plantingstructure::getLeaseRows($totalData).'个</strong></td>
						        <td><strong>'.Plantingstructure::getPlantRows($totalData).'种</strong></td>
						        <td><strong>'.Plantingstructure::getGoodseedRows($totalData).'种</strong></td>
						        <td><strong>'.Plantingstructure::getArea($params).'万亩</strong></td>
						        </tr>',
			        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','lease_id','plant_id','goodseed_id','area'],$totalData),
			    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id="plantingstructure">
              <div id="plantingstructuredata" style="width:1000px; height: 600px; margin: 0 auto"></div>
              </div><?php var_dump(Plantingstructure::getPlantingstructure($totalData));exit;?>
              <script type="text/javascript">
				showAllShadow('plantingstructuredata',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode(Plantingstructure::getPlantname($totalData)['typename'])?>,<?= Plantingstructure::getPlantingstructure($totalData)?>,'万亩');
				//showStacked('collection','应收：<?php //echo Collection::totalAmounts()?> 实收：<?php //echo Collection::totalReal()?>',<?php //echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php //echo Collection::getCollection()?>,'万元');
		</script>
              <!-- /.tab-pane -->

               <?php if(Plantingstructure::getGoodseedname($totalData)) {?>
            <div id="goodseedEcharts" class='tab-pane'>
            <div id="goodseedinfo" style="width: 1000px; height: 600px; margin: 0 auto;"></div>
              <?php //var_dump(Plantingstructure::getGoodseedname($params));?>
            </div>
            <script type="text/javascript">
				showAllShadow('goodseedinfo',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode(Plantingstructure::getGoodseedname($totalData)['typename'])?>,<?= Plantingstructure::getGoodseedEcharts($totalData)?>,'万亩');
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

		