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
use app\models\PlantPrice;
use app\models\Theyear;
use frontend\helpers\MoneyFormat;
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
	
	$totalNoData = clone $nodata;
	$totalNoData->pagination = ['pagesize'=>0];
	$nocontractdata = arraySearch::find($totalNoData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
// 	var_dump($data->count('state',false));exit;
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
                                        <td align="left"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t7"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t8"><strong></strong></td>
                                        <td></td>
                                        <td></td>
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
                    'options' =>['width'=>120],
                    'value' => function ($model) {

                        return Farms::find ()->where ( [
                            'id' => $model->farms_id
                        ] )->one ()['farmername'];

                    }
                ],
                'amounts_receivable',
                'real_income_amount',
                'measure',
                'owe',
                'ypaymoney',
                [
                    'label' => '缴费日期',
                    'attribute' => 'update_at',
                    'options' =>['width'=>120],
                    'value' => function ($model) {
                        if($model->state >= 1)
                            return date('Y-m-d',$model->update_at);
                        else
                            return '';
                    }
                ],
                [
                    'format'=>'raw',
                    'attribute' => 'state',
                    'options' =>['width' => '100'],
                    'value' => function($model) {
                        if($model->state) {
                            if($model->state == 1)
                                return '<span class="text-green">已缴纳</span>';
                            if($model->state == 2)
                                return '<span class="text-green">部分缴纳</span>';
                        }
                        else {
                            if($model->dckpay)
                                return '<span class="text-blue">已提交</span>';
                            else
                                return '<span class="text-red">未缴纳</span>';
                        }
                    },
                    'filter' => [1=>'已缴纳',0=>'未缴纳',2=>'部分缴纳',3=>'已提交'],
                ],
                [
                    'attribute' => 'payyear',
                    'filter' => ArrayHelper::map(PlantPrice::find()->all(),'years','years'),
                ],
//                'payyear',
            ],
        ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php
              if(isset($_GET['begindate'])) {
                  $begindate = strtotime($_GET['begindate']);
              } else {
                  $begindate = NULL;
              }
              if(isset($_GET['enddate'])) {
                  $enddate = strtotime($_GET['enddate']);
              } else {
                  $enddate = NULL;
              }
              if(isset($_GET['collectionSearch']['payyear'])) {
                  $year = $_GET['collectionSearch']['payyear'];
              } else {
                  $year = null;
              }
                    $echartsData = $data->setEchartsName(['实收金额','应收金额'])->collectionShowShadow($year,$begindate,$enddate);
//              var_dump($echartsData);
                    if(isset($data->where[0]['management_area'])) {
                        if (is_array($data->where[0]['management_area'])) {
                            $areaname = Farms::getManagementArea('small')['areaname'];
                        } else {
                            $areaname = [Farms::getManagementArea()['areaname'][$data->where[0]['management_area'] - 1]];
                        }
                    } else {
                        $areaname = Farms::getManagementArea('small')['areaname'];
                    }
              ?>
                <div id="collection" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				wdjShowEchart('collection',<?= json_encode(['实收金额','应收金额'])?>,<?= json_encode($areaname)?>,<?= json_encode($echartsData['all'])?>,<?= json_encode($echartsData['real'])?>,'元');
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
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t1').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-amounts_receivable'}, function (data) {
            $('#t3').html(data + '元');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-real_income_amount',andWhere:'<?= json_encode(['state','>=',1])?>'}, function (data) {
            $('#t4').html(data + '元');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-measure',andWhere:'<?= json_encode(['state','>=',1])?>'}, function (data) {
            $('#t5').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-owe',andWhere:'<?= json_encode(['state','>=',1])?>'}, function (data) {
            $('#t6').html(data + '元');
        });
//        $.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>//',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'sum-ypayarea',andWhere:'<?//= json_encode(['state','>=',1])?>//'}, function (data) {
//            $('#t7').html(data + '亩');
//        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-ypaymoney',andWhere:'<?= json_encode(['state','>=',1])?>'}, function (data) {
            $('#t7').html(data + '元');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count',andWhere:'<?= json_encode(['state','>=',1])?>'}, function (data) {
            $('#t10').html('完成'+data+'个');
        });
    });
</script>