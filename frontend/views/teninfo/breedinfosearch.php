<?php

use app\models\Tables;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Breedtype;
use app\models\Plant;
use app\models\Lease;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use frontend\helpers\arraySearch;
use app\models\Breed;
use frontend\helpers\MoneyFormat;
use frontend\helpers\Echartsdata;
use frontend\helpers\ES;
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

              
<?= $this->render('..//search/searchindex',['tab'=>$tab,'class'=>$class,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-pills nav-pills-warning">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">图表</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
                     'total' => '<tr height="40">
                                        <td></td>	
                                        <td align="left" id="t0"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong></strong></td>
                                        <td></td>
                                        <td></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
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
                             'value' => function ($model) {
                                 $breed = Breed::find ()->where ( [
                                     'id' => $model->breed_id
                                 ] )->one ();
                                 return Farms::find ()->where ( [
                                     'id' => $breed->farms_id
                                 ] )->one ()['farmname'];
                             }
                         ],
                         [
                             'label' => '养殖场名称',
                             'attribute' => 'breedname',
                             'value' => function ($model) {
                                 $breed = Breed::find ()->where ( [
                                     'id' => $model->breed_id
                                 ] )->one ();
                                 return $breed->breedname;
                             }
                         ],
                         [
                             'label' => '养殖场位置',
                             'attribute' => 'breedaddress',
                             'value' => function ($model) {
                                 $breed = Breed::find ()->where ( [
                                     'id' => $model->breed_id
                                 ] )->one ();
                                 return $breed->breedaddress;
                             }
                         ],
                         [
                             'label' => '示范户',
                             'attribute' => 'is_demonstration',
                             'value' => function ($model) {
                                 $breed = Breed::find ()->where ( [
                                     'id' => $model->breed_id
                                 ] )->one ();
                                 return $breed->is_demonstration ? '是' : '否';
                             } ,
                             'filter' => ['否','是'],
                         ],
                         [
                             'attribute' => 'basicinvestment',
                             'value' => function ($model) {
                                 return MoneyFormat::num_format ( $model->basicinvestment ) . '元';
                             }
                         ],
                         [
                             'attribute' => 'housingarea',
// 								'options' =>['width'=>80],
                             'value' => function ($model) {
                                 return $model->housingarea . '平方米';
                             }
                         ],
                         [
                             'attribute' => 'breedtype_id',
                             'value'=> function($model) {
                                 return Breedtype::find()->where(['id'=>$model->breedtype_id])->one()['typename'];
                             },
                             'filter' => ArrayHelper::map(Breedtype::find()->andWhere('father_id>1')->all(),'id','typename'),

                         ],
                         [
                             'attribute' => 'number',
                             'value' => function ($model) {
                                 $breedtype = Breedtype::find ()->where ( [
                                     'id' => $model->breedtype_id
                                 ] )->one ();
                                 return $model->number . $breedtype->unit;
                             }
                         ]
                     ],
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php //var_dump($data->getName('Breedtype', 'unit', 'breedtype_id')->typenameList());?>
              <?php
              $x = Echartsdata::getBreedinfoTypename($totalData,'typename');
              $s = Echartsdata::getBreedinfoinfo($totalData);
              $unit = Echartsdata::getBreedinfoTypename($totalData,'unit');
              echo ES::barUnit()->DOM('breedinfoEcharts',true,'1500px','500px')->options(['name'=>'畜牧业','xAxis'=>$x,'unit'=>$unit,'series'=>$s])->JS();
              ?>
            </div>
          </div>
 
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
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t1').html(data + '户');
        });
//        $.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>//',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'count-lease_id'}, function (data) {
//            $('#t2').html(data + '人');
//        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-basicinvestment'}, function (data) {
            $('#t3').html(data + '元');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-housingarea'}, function (data) {
            $('#t4').html(data + '平方米');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-breedtype_id'}, function (data) {
            $('#t5').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-number'}, function (data) {
            $('#t6').html(data);
        });

    });
</script>