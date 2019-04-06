<?php
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Loan;
use app\models\ManagementArea;
use frontend\helpers\arraySearch;
use app\models\User;
use frontend\helpers\ES;
use yii\helpers\Html;
use frontend\helpers\Echartsdata;
use app\models\Theyear;
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
            <?php User::tableBegin('贷款');?>

<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
//'total' => '<tr>
//			        <td></td>
//			        <td align="center"><strong>合计</strong></td>
//			        <td><strong>'.$data->count('farms_id').'户</strong></td>
//			        <td><strong>'.$data->count('farmer_id').'个</strong></td>
//			        <td><strong>'.$data->sum('mortgagearea').'亩</strong></td>
//					<td><strong>'.$data->count('mortgagebank').'个</strong></td>
//					<td><strong>'.$data->sum('mortgagemoney').'元</strong></td>
//			        </tr>',
// 	var_dump($tab);exit;

?>
            <?php //echo Html::a('XLS导入', ['xlsimport'], ['class' => 'btn btn-success']) ?>
            <?php //echo Html::a('XLS导入', ['xlsimport'], ['class' => 'btn btn-success']) ?>
            <ul class="nav nav-pills nav-pills-warning">
                <li class="active" id="loaninfo"><a href="#loaninfolist" data-toggle="tab" aria-expanded="true">贷款统计表</a></li>
                <li class="" id="monthinfo"><a href="#monthinfolist" data-toggle="tab" aria-expanded="false">月贷款图表</a></li>
                <li class="" id="yearinfo"><a href="#yearinfolist" data-toggle="tab" aria-expanded="false">贷款图表</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="loaninfolist">
                <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
                    'total' => '<tr height="40">
                                        <td></td>	
                                        <td align="left" id="t0"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t0"><strong></strong></td>
                                        <td align="left" id="t0"><strong></strong></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t4"><strong><div class="" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
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
                'options' =>['width'=>80],
                'value' => function ($model) {

                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['farmername'];

                }
            ],
            [
                'label' => '合同号',
                'attribute' => 'farmstate',
                'options' => ['width'=>170],
                'value' => function($model) {
                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['contractnumber'];
                },
                'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同',5=>'其它'],
            ],
            [
                'label' => '合同面积',
                'value' => function($model) {
                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['contractarea'];
                }
            ],
            'mortgagearea',
            [
                'attribute' => 'mortgagebank',
                'options' => ['width'=>200],
                'filter' => Loan::getBankName(),
            ],
            'mortgagemoney',
            [
                'attribute'=>'begindate',
                'options' => ['width'=>100]
            ],
            [
                'attribute'=>'enddate',
                'options' => ['width'=>100]
            ],
//            'begindate',
//            'enddate',
            [
            	'label' => '解冻时间',
            	'value' => function ($model) {
            		if($model->lock == 0)
            			return date('Y-m-d',$model->update_at);
            }
            ],
            [
                'label' => '年度',
                'attribute' => 'year',
                'filter' => ['2017'=>'2017','2018'=>'2018'],
            ],
            [
            	'label' => '状态',	
            	'attribute' => 'lock',
            	'value' => function ($model) {
            	$farm = Farms::find()->where(['id'=>$model->farms_id])->one();
//             	return $model->lock.'-'.$farm['locked'];
            	if($model->lock and $farm['locked']) {
            		return '已冻结';
            	} else {
            		return '已解冻';
            	}
            },
            'filter' => [0=>'已解冻',1=>'已冻结'],
            ],                    
        ],
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="monthinfolist">
<!--              --><?php //var_dump(json_decode($data->getName('self', 'mortgagebank', 'mortgagebank')->showAllShadow('sum','mortgagemoney')));?>

                  <?php
                  $months = Echartsdata::getMonths($totalData,'Y年m月');
//                  var_dump($months);exit;
                  $banks = Echartsdata::getDataBankList($totalData);
                  $newdata = Echartsdata::getLoan($totalData);
                  $pjz = Echartsdata::getLoanPjz($totalData);
                  //柱形图表(组)--柱形条显示名称options=['color'=>['#003366', '#006699', '#4cabce', '#e5323e'],'legend'=>['投入品1','投入品2','投入品3','农药1'],'xAxis'=>['小麦','玉米','大豆','杂豆','马铃薯'],'series'=>[[12,53,24,54,43],[65,34,26,34,23],[64,34,24,34,43],[36,54,26,76,14],[54,24,54,23,54]]]
                  echo ES::barLabel3()->DOM('loanecharts',true,'1500px','500px')->options(['legend'=>$banks,'xAxis'=>$months,'series'=>$newdata,'pjz'=>$pjz,'unit'=>['万元']])->JS();
                  ?>

            </div>
                <div class="tab-pane" id="yearinfolist">
                    <?php
//                    $months =Theyear::getMonths();
                    $banks = Echartsdata::getDataBankList($totalData);
                    $newdata = Echartsdata::getYearLoan($totalData);

                    //柱形图,options=['title'=>'测试','tooltip'=>[],'legend'=>['销量'],'xAxis'=>["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"],'yAxis'=>[],'series'=>[5, 20, 36, 10, 10, 20]]
                    echo ES::bar()->DOM('loanyearecharts',true,'1500px','500px')->options(['title'=>'贷款统计','tooltip'=>[],'legend'=>['贷款'],'yAxis'=>[],'xAxis'=>$banks,'series'=>$newdata,'unit'=>'万元'])->JS();
                    ?>

                </div>
                <?php User::dataListEnd();?>
            </div>
    </div>
</section>
</div>
<?php
$tab = new \frontend\helpers\Tab();
echo $tab->createTab(Yii::$app->controller->action->id,['loaninfo','monthinfo','yearinfo']);
//echo $tab->test();
?>
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t1').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-mortgagearea'}, function (data) {
            $('#t3').html(data + '亩');
        });
        
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-mortgagemoney'}, function (data) {
            $('#t5').html(data + '万元');
        });
    });
</script>