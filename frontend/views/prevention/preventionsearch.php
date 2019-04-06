<?php

use app\models\Tables;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Breedtype;
use app\models\Plant;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use frontend\helpers\arraySearch;
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
// 	var_dump($data->sum('preventionnumber'));var_dump($data->sum('breedinfonumber'));exit;
//	$number = $data->sum('preventionnumber');
//// 	var_dump($number);exit;
//	if((integer)$number)
//		$preventionrate = sprintf("%.2f", $data->sum('preventionnumber')/$data->sum('breedinfonumber'))*100;
//	else
//		$preventionrate = 0;
$arrclass = explode('\\',$dataProvider->query->modelClass);
//'total' => '<tr>
//	        <td></td>
//	        <td align="center"><strong>合计</strong></td>
//	        <td><strong>'.$data->count('farms_id').'户</strong></td>
//	        <td><strong>'.$data->count('farmer_id').'个</strong></td>
//	        <td></td>
//			<td><strong>'.$data->count('breedtype_id').'种</strong></td>
//			<td><strong>'.$data->count('id').'个</strong></td>
//	        <td><strong>'.$data->sum('preventionnumber').'</strong></td>
//	        <td><strong>'.$data->sum('breedinfonumber').'</strong></td>
//			<td><strong>'.$preventionrate.'%</strong></td>
//	        </tr>',
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
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t7"><strong></strong></td>
                                    </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','breedinfo_id','breedtype_id','isepidemic','preventionnumber','breedinfonumber','preventionrate'],$totalData),
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
                'options' =>['width'=>120],
                'value' => function ($model) {

                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['farmername'];

                }
            ],
            [
                'label' => '养殖场名称',
                'attribute' => 'breedinfo_id',
                'value' => function ($model) {
                    $breedinfo = Breedinfo::find ()->where ( [
                        'id' => $model->breedinfo_id
                    ] )->one ();
                    $breed = Breed::find ()->where ( [
                        'id' => $breedinfo->breed_id
                    ] )->one ();
                    return $breed->breedname;
                }
            ],
            [
                'attribute' => 'breedtype_id',
                'value'=> function($model) {
                    return Breedtype::find()->where(['id'=>$model->breedtype_id])->one()['typename'];
                },
                'filter' => ArrayHelper::map(Breedtype::find()->all(),'id','typename'),

            ],
            [
                'attribute' => 'isepidemic',
                'value' => function($model) {
                    // 								var_dump($params);exit;
                    return $model->isepidemic;
                },
                'filter' => ['无'=>'无','有'=>'有'],
            ],
            'preventionnumber',
            'breedinfonumber',
            'preventionrate'
        ],           
        ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php $echartsData = $data->getName('Breedtype', 'typename', 'breedtype_id')->setEchartsName(['应免数量','免疫数量'])->showShadowThermometer(['preventionnumber','breedinfonumber'],1);?>
                <div id="prevention" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				wdjShowEchart('prevention',<?= json_encode(['已免数量','应免数量'])?>,<?= json_encode($data->getName('Breedtype', 'typename', 'breedtype_id')->typenameList())?>,<?= json_encode($echartsData[1])?>,<?= json_encode($echartsData[0])?>,'');
				//showStacked('collection','应收：<?php //echo Collection::totalAmounts()?> 实收：<?php //echo Collection::totalReal()?>',<?php //echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php //echo Collection::getCollection()?>,'万元');
		</script>

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
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t1').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-breedtype_id'}, function (data) {
            $('#t3').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-id'}, function (data) {
            $('#t4').html(data + '个');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-preventionnumber'}, function (data) {
            $('#t5').html(data + '种');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-breedinfonumber'}, function (data) {
            $('#t6').html(data);
        });
    });
</script>