<?php

use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Yieldbase;
use app\models\Plant;
use app\models\User;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Url;
use frontend\helpers\MoneyFormat;
use app\models\Search;
use frontend\helpers\arraySearch;
use frontend\helpers\Echartsdata;
use frontend\helpers\ES;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

   <script type="text/javascript" src="vendor/bower/echarts/build/dist/echarts.js"></script>
<script type="text/javascript" src="vendor/bower/echarts/build/dist/echarts.min.js"></script>           
   <?= $this->render('..//search/searchindex',['tab'=>$tab,'class'=>$class,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
// 	$data->getName('Plant', 'typename', 'plant_id')->getEchartsData('area',10000);
$arrclass = explode('\\',$dataProvider->query->modelClass);
//var_dump($data->getName('Goodseed', 'typename', 'goodseed_id')->getList());
?>

<div class="nav-tabs-custom">
            <ul class="nav nav-pills nav-pills-warning">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <li class=""><a href="#plantingstructure" data-toggle="tab" aria-expanded="false">产量图表</a></li>
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
                                        <td align="center" id="t0"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                        <td align="center" id="t7"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                    </tr>',
                   'columns' => [
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
                           'options' =>['width'=>150],
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
                               return \app\models\Plant::find()->where(['id'=>$model->plant_id])->one()['typename'];
                           },
                           'filter' => \app\models\Plantingstructurecheck::getPlantname($totalData)
                       ],
                       [
                           'label' => '良咱',
                           'attribute' => 'goodseed_id',
                           'value' => function($model) {
                               return \app\models\Goodseed::find()->where(['id'=>$model->goodseed_id])->one()['typename'];
                           },
                           'filter' => \app\models\Plantingstructurecheck::getAllname($totalData)
                       ],
                       'area',
                       [
                           'label' => '单产（每亩）',
                           'options' =>['width'=>150],
                           'value' => function ($model) {
                               return Yieldbase::find()->where(['plant_id'=>$model->plant_id,'year'=>$model->year])->one()['yield'] . '斤';
                           }
                       ],
                       [
                           'label' => '总产',
                           'options' =>['width'=>200],
                           'value' => function ($model) {
                               return MoneyFormat::num_format($model->area * Yieldbase::find()->where(['plant_id'=>$model->plant_id,'year'=>$model->year])->one()['yield']) . '斤';
                           }
                       ],

                   ],
               ]);  ?>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id="plantingstructure">
                  <div class="col-xs-6" id="plantingstructuredata" style="width: 1500px; height: 600px; margin: 0 auto;"></div>
                  <?php
                  $ns = Echartsdata::getYieldsCache($totalData);
                  $nx = Echartsdata::getYieldsTypename($totalData)['typename'];
                  ?>
                  <?php
                  //                  $series = Yields::get
                  //$html .= ES::barLabel2()->DOM($position,false)->options(['color'=>['#003366', '#e5323e'],'legend'=>['计划','复核'],'xAxis'=>$x,'series'=>[$newdata->plan,$newdata->fact],'unit'=>'亩'])->JS();
                  echo ES::bar()->DOM('plantingstructuredata',false)->options(['title'=>'产量统计表','legend'=>['产量'],'xAxis'=>$nx,'unit'=>'斤','series'=>$ns])->JS();
                  ?>
              </div>
              <!-- /.tab-pane -->

               <?php if($data->getName('Goodseed', 'typename', 'goodseed_id')->getList()) {?>
            <div id="goodseedEcharts" class='tab-pane'>
                <div id="goodseedEchartslist" class='tab-pane'>
                    <div id="goodseedinfo" style="width: 1500px; height: 600px; margin: 0 auto;"></div>
                    <?php
                    $ngs = Echartsdata::getGoodseedinfo($totalData);
                    $ngx = Echartsdata::getGoodseedTypename($totalData,'','typename');
                    echo ES::bar()->DOM('goodseedinfo',false)->options(['title'=>'良种产量统计表','legend'=>['产量'],'xAxis'=>$ngx,'unit'=>'斤','series'=>$ngs])->JS();
                    ?>
                </div>
            </div>
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
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t0').html('合计('+ data + ')户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t1').html('种植者'+ data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id',andwhere:'<?= json_encode(['lease_id'=>0])?>'}, function (data) {
            $('#t2').html('法人种植'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-lease_id'}, function (data) {
            $('#t3').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-plant_id'}, function (data) {
            $('#t4').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-goodseed_id'}, function (data) {
            $('#t5').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-area'}, function (data) {
            $('#t6').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'mulyieldSum-area-plant_id'}, function (data) {
            $('#t7').html(data + '斤');
        });
    });
</script>
		