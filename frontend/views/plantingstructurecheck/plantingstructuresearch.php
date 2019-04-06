<?php

use app\models\Tables;
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
   <?= $this->render('..//search/searchindex',['tab'=>$tab,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
<?php 

	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
// 	$data->getName('Plant', 'typename', 'plant_id')->getEchartsData('area',10000);
	$planter = $data->count('farmer_id');
	$leaseer = $data->count('lease_id');
	$plantFarmer = $planter - $leaseer;
?>

<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <li class=""><a href="#plantingstructure" data-toggle="tab" aria-expanded="false">种植结构图表</a></li>
              <?php if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {?>
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
						        <td align="center"><strong>合计（'.$data->count('farms_id').'户）</strong></td>
						        <td><strong>种植者'.$planter.'个</strong></td>
						        <td><strong>法人种植'.$plantFarmer.'个</strong></td>
						        <td><strong>'.$leaseer.'个</strong></td>
						        <td><strong>'.$data->count('plant_id').'种</strong></td>
						        <td><strong>'.$data->count('goodseed_id').'种</strong></td>
						        <td><strong>'.$data->sum('area').'亩</strong></td>
						        </tr>',
			        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','lease_id','plant_id','goodseed_id','area','operation'],$totalData),
			    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id="plantingstructure">
              <div id="plantingstructuredata" style="width:1000px; height: 600px; margin: 0 auto"></div>
				<?php $data->getName('Plant', 'typename', 'plant_id')->typenameList();?>
              </div>
              <script type="text/javascript">
				showAllShadow('plantingstructuredata',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($data->getName('Plant', 'typename', 'plant_id')->typenameList())?>,<?= $data->getName('Plant', 'typename', 'plant_id')->showAllShadow('sum','area');?>,'亩');
			</script>
              <!-- /.tab-pane -->

               <?php if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {?>
            <div id="goodseedEcharts" class='tab-pane'>
            <div id="goodseedinfo" style="width: 1000px; height: 600px; margin: 0 auto;"></div>
              <?php //var_dump(Plantingstructure::getGoodseedname($params));?>
            </div>
            <script type="text/javascript">
				showAllShadow('goodseedinfo',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($data->getName('Goodseed', 'typename', 'goodseed_id')->typenameList())?>,<?= $data->getName('Goodseed', 'typename', 'goodseed_id')->showAllShadow('sum','area');?>,'亩');
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

		