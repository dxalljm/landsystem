<?php
use app\models\Tables;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Plant;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use frontend\helpers\arraySearch;
use app\models\User;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<div class="lease-index">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php User::tableBegin('承包费收缴');?>
<?php
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
// 	var_dump($data->getEchartsData(['real_income_amount','amounts_receivable'],1,'showShadowThermometer'));
// 	var_dump($_GET);
//'<tr>
//			        <td></td>
//			        <td align="center"><strong>合计</strong></td>
//			        <td align="center"><strong>'.$data->count('farms_id').'户</strong></td>
//			        <td align="center"><strong>'.$data->count('farmer_id').'个</strong></td>
//			        <td align="center"><strong>'.$data->sum('amounts_receivable').'元</strong></td>
//					<td align="center"><strong>'.$data->andWhere(['state','>=',1])->sum('real_income_amount').'元</strong></td>
//					<td align="center"><strong>'.$data->andWhere(['state','>=',1])->sum('measure').'亩</strong></td>
//					<td align="center"><strong>'.$data->andWhere(['state','>=',1])->sum('owe').'元</strong></td>
//					<td align="center"><strong>'.$data->andWhere(['state','>=',1])->sum('ypayarea').'亩</strong></td>
//    				<td align="center"><strong>'.$data->andWhere(['state','>=',1])->sum('ypaymoney').'元</strong></td>
//    				<td align="center"><strong>'.$data->andWhere(['state','>=',1])->count().'户</strong></td>
//			        </tr>',

?>
<?php $form = ActiveFormrdiv::begin(['method'=>'get']); 
// var_dump(date('Y-m-d',$enddate));exit;
//if(!empty($begindate)) $bd = date('Y-m-d',$begindate); else $bd = '';
//if(!empty($enddate)) $ed = date('Y-m-d',$enddate); else $ed = '';
// var_dump($ed);
// if(date('Y-m-d',$begindate) == date('Y-m-d',$enddate)) {
// 	$bd = date('Y').'-01-01';
// }
?>
                    <table class="table table-hover">
                        <tr>
                            <td align="right">自</td>
                            <td><?php echo DateTimePicker::widget([
                                    'name' => 'begindate',
                                    'language' => 'zh-CN',
                                    'value' => $begindate,
                                    'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
//                                     'options' => [
//                                         'readonly' => true
//                                     ],
//                                    'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                                    'clientOptions' => [
                                        'autoclose' => true,
                                        'minView' => 2,
                                        'maxView' => 4,
                                        'format' => 'yyyy-mm-dd'
        
                                    ]
                                ]);?></td>
                            <td>至</td>
                            <td><?php echo DateTimePicker::widget([
                                    'name' => 'enddate',
                                    'language' => 'zh-CN',
                                    'value' => $enddate,
                                    'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                                    //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                    'options' => [
                                        'readonly' => true
                                    ],
                                    'clientOptions' => [
                                    'autoclose' => true,
                                    'minView' => 2,
                                    'maxView' => 4,
                                    'format' => 'yyyy-mm-dd'
                                    ]
                                ]);?></td>
                            <td>止</td>
                            <td><?= html::submitButton('查询',['class'=>'btn btn-success','id'=>'searchButton'])?>&nbsp;<?= html::a('今天',Url::to(['collection/collectioninfo','begindate'=>date('Y-m-d'),'enddate'=>date('Y-m-d')]),['class'=>'btn btn-success','id'=>'searchDay'])?>&nbsp;<div class="btn-group">
                                    <button type="button" class="btn btn-success" id="years"><?php if(empty($year)) echo User::getYear();else echo $year;?>年度</button>
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <?php
                                    $plantprice = \app\models\PlantPrice::find()->all();
                                    ?>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php
                                        foreach ($plantprice as $price) {
                                        ?>
                                        <li><a href="<?= Url::to(['collection/collectioninfo','year'=>$price['years']])?>" id="selectYear"><?= $price['years']?>年度</a></li>
                                        <?php }?>
                                    </ul>

                                </div>
                                <?= Html::a('导出'.$year.'年度'.'XLS',Url::to(['collection/collectiontoxls','year'=>$year]),['class'=>'btn btn-success'])?>
                            </td>
                        </tr>
                    </table>
                    <?php ActiveFormrdiv::end(); ?>

            <ul class="nav nav-pills nav-pills-warning">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">承包佛收缴统计表</a></li>
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
                                        <td align="left" id="t0"><strong></strong></td>
                                        <td align="left" id="t7"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>

                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        
                                        <td align="center" id="t8"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>',
//        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','amounts_receivable','real_income_amount','measure','owe','ypaymoney','update_at','state','payyear','operation'],$dataProvider),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'管理区',
                'attribute'=>'management_area',
                'headerOptions' => ['width' => 180],
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
                'label' => '合同号',
                'attribute' => 'contractnumber',
                'options' => ['width'=>'250'],
                'value' => function($model) {
                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['contractnumber'];
                },
                'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理'],
            ],
            [
                'label' => '合同面积',
                'attribute' => 'contractarea',
                'options' =>['width' => '150'],
            ],
            [
                'attribute' => 'amounts_receivable',
                'options' => ['width'=>180]
            ],
            [
                'attribute' => 'real_income_amount',
                'options' => ['width'=>150]
            ],
            [
                'attribute' => 'measure',
                'options' => ['width'=>130]
            ],
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
                            if($model->iscq == 1) {
                                return '<span class="text-rose">陈欠追缴</span>';
                            } else {
                                return '<span class="text-green">已缴纳</span>';
                            }
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
                'filter' => [1=>'已缴纳',0=>'未缴纳',2=>'部分缴纳',3=>'已提交',4=>'陈欠追缴'],
            ],
            'payyear',
            [
                'label' => '操作',
                'format' => 'raw',
                'value' => function($model) {
                    $option = '查看详情';
                    $title = '';
                    $url = '#';
                    if(User::getItemname('地产科','科长') or User::getItemname('财务科','科长')) {
                        if($model->state >= 1) {
                            $url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'send','farms_id'=>$model->farms_id];
                            $option = '已缴费';
                        } else {
                            $url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'send','farms_id'=>$model->farms_id];
                            if($model->dckpay == 1)
                                $option = '详情';
                            else
                                $option = '缴费';
                        }
                    }
// 				                			var_dump(User::getItemname());
                    if(User::getItemname('财务科','科员')  or User::getItemname('管委会领导')) {
                        $url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'view','farms_id'=>$model->farms_id];
                        $option = '详情';
// 				                				var_dump($option);
                    }
                    $html = Html::a($option,$url, [
                        'id' => 'moreOperation',
                        'title' => $title,
                        'class' => 'btn btn-primary btn-xs',
// 				                    			'disabled' => $disabled,
                    ]);
                    return $html;
                }
            ],
        ],
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php
//                    if(isset($_GET['begindate']) and $_GET['begindate'] !== '')
//                        $year = date('Y',strtotime($_GET['begindate']));
//                    else
//                        $year = \app\models\User::getYear();
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
                    $echartsData = $data->setEchartsName(['实收金额','应收金额'])->collectionShowShadow($year,$begindate,$enddate);
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

            <?php User::dataListEnd();?>
            </div>
        </div>
    </div>
</section>
</div>
<?php //var_dump($dataProvider->query->where);?>
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
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-real_income_amount',andwhere:'<?= json_encode('state>0')?>'}, function (data) {
            $('#t4').html(data + '元');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-measure'}, function (data) {
            $('#t5').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-owe'}, function (data) {
            $('#t6').html(data + '元');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t7').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-ypaymoney'}, function (data) {
            $('#t8').html(data + '元');
        });
//        $.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>//',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'count',andwhere:'<?//= json_encode(['state','>=',1])?>//'}, function (data) {
//            $('#t10').html('完成'+data+'个');
//        });
    });
</script>