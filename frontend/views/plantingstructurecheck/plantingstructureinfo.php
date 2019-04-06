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
// 	var_dump($data);exit;
// 	var_dump($data->sum('area'));
// 	$data->getName('Plant', 'typename', 'plant_id')->getEchartsData('area',10000);
	$planter = $data->count('farmer_id');
	$leaseer = $data->count('lease_id');
	$plantFarmer = $planter - $leaseer;
?>

<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表234234</a></li>
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
                   'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t0"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t4"><strong>'.count(Plant::getAllname()).'种</strong></td>
                                        <td align="left" id="t5"><strong>'.$goodseedNumber.'种</strong></td>
                                        <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                    </tr>',
                   'columns' =>[
                       ['class' => 'yii\grid\SerialColumn'],
                       [
                           'label'=>'管理区',
                           'attribute'=>'management_area',
                           'headerOptions' => ['width' => '130'],
                           'value'=> function($model) {
// 				            	var_dump($model);exit;
                               return ManagementArea::getAreanameOne($model->management_area);
                           },
                           'filter' => ManagementArea::getAreaname(),
                       ],
                       [
                           'label' => '农场名称',
                           'attribute' => 'farms_id',
                           'options' =>['width'=>120],
                           'value' => function ($model) {

                               return Farms::find ()->where ( [
                                   'id' => $model->farms_id
                               ] )->one ()['farmname'];

                           }
                       ],
                       [
                           'label' => '法人名称',
                           'attribute' => 'farmer_id',
                           'options' =>['width'=>120],
                           'value' => function ($model) {

                               return Farms::find ()->where ( [
                                   'id' => $model->farms_id
                               ] )->one ()['farmername'];

                           }
                       ],
                       [
                           'label' => '承租人',
                           'attribute' => 'lease_id',
                           'value' => function($model) {
                               return \app\models\Lease::find()->where(['id'=>$model->lease_id])->one()['lessee'];
                           }
                       ],
                       [
                           'label' => '种植结构',
                           'attribute' => 'plant_id',
                           'value' => function($model) {
                               return Plant::find()->where(['id'=>$model->plant_id])->one()['typename'];
                           },
                           'filter' => Plant::getAllname(),
                       ],
                       [
                           'label' => '良种',
                           'attribute' => 'goodseed_id',
                           'value' => function($model) {
                               return Goodseed::find()->where(['id'=>$model->goodseed_id])->one()['typename'];
                           },

                           'filter' => $goodseedArray,
                       ],
                       'area',
                   ],

               ]);?>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id="plantingstructure">
              <div id="plantingstructuredata" style="width:1000px; height: 600px; margin: 0 auto"></div>
				<?php //var_dump($data->getName('Plant', 'typename', 'plant_id')->showAllShadow('sum','area',10000));?>
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
                
                
