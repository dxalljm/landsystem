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
// 	var_dump(arraySearch::find($totalData)->search()->count());
// 	exit;
?>
<?php
// 	arraySearch::find($totalData)->search()->count('goodseed_id',true);exit;
	$planter = arraySearch::find($totalData)->search()->count('farmer_id',true);
	$leaseer = arraySearch::find($totalData)->search()->count('lease_id',true);
	$plantFarmer = $planter - $leaseer;
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
						        <td align="center"><strong>合计（'.arraySearch::find($totalData)->search()->count('farms_id',true).'户）</strong></td>
						        <td><strong>种植者'.$planter.'个</strong></td>
						        <td><strong>法人种植'.$plantFarmer.'个</strong></td>
						        <td><strong>'.$leaseer.'个</strong></td>
						        <td><strong>'.arraySearch::find($totalData)->search()->count('plant_id',true).'种</strong></td>
						        <td><strong>'.arraySearch::find($totalData)->search()->count('goodseed_id',true).'种</strong></td>
						        <td><strong>'.arraySearch::find($totalData)->search()->sum('area',10000).'万亩</strong></td>
						        </tr>',
			        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','lease_id','plant_id','goodseed_id','area'],$totalData),
			    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id="plantingstructure">
              <div id="plantingstructuredata" style="width:1000px; height: 600px; margin: 0 auto"></div>
             <!--  -->
              <!-- /.tab-pane -->

               <?php if(Plantingstructure::getGoodseedname($totalData)) {?>
            <div id="goodseedEcharts" class='tab-pane'>
            <div id="goodseedinfo" style="width: 1000px; height: 600px; margin: 0 auto;"></div>
              <?php //var_dump(Plantingstructure::getGoodseedname($params));?>
            </div>
            <!--  -->
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

		