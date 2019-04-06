<?php
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Loan;
use app\models\ManagementArea;
use frontend\helpers\arraySearch;
use app\models\User;
use frontend\helpers\ES;
use yii\helpers\Html;
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
            <?php
            if(User::getItemname('地产科')) {
                echo Html::a('XLS导入', ['xlsimport'], ['class' => 'btn btn-success']);
            }?>
            <ul class="nav nav-pills nav-pills-warning">
                <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">贷款统计表</a></li>
                <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">柱状图表</a></li>
                <li class=""><a href="#timeline1" data-toggle="tab" aria-expanded="false">饼型图表</a></li>
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
                'filter' => Loan::getBankName(),
            ],
            'mortgagemoney',
            'begindate',
            'enddate',
            [
                'attribute' => 'year',
                'label' => '年度',
                'filter' => Loan::getYears()
            ],
            [
            	'label' => '解冻时间',
            	'value' => function ($model) {
//            		if($model->lock == 0)
            			return date('Y-m-d',$model->update_at);
            }
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
                <?php
                $loandata = [];
                $loanname = [];
                $banks = Loan::getBankName();
                foreach ($banks as $key => $bank) {
                    $sum = sprintf("%.2f", Loan::find()->where($dataProvider->query->where)->andWhere(['mortgagebank'=>$bank])->sum('mortgagemoney'));
                    $loanname[] = $bank.'('.$sum.'万元)';
                    $loandata[] = ['value'=>$sum,'name'=>$bank.'('.$sum.'万元)'];
                }
                ?>
                <div class="tab-pane" id="timeline">
                    <?php //var_dump($data->getName('self', 'mortgagebank', 'mortgagebank')->typenameList());?>
                    <div id="loan" style="width: 1200px; height: 800px; margin: 0 auto"; ></div>
                    <script type="text/javascript">
                        showAllShadow('loan',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($data->getName('self', 'mortgagebank', 'mortgagebank')->typenameList())?>,<?= $data->getName('self', 'mortgagebank', 'mortgagebank')->showAllShadow('sum','mortgagemoney',1,User::getYear())?>,'万元');
                        //showStacked('collection','应收：<?php //echo Collection::totalAmounts()?> 实收：<?php //echo Collection::totalReal()?>',<?php //echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php //echo Collection::getCollection()?>,'万元');
                    </script>

                </div>
                <div class="tab-pane" id="timeline1">
                    <div id="loanbx" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
                    <script type="text/javascript">
                        showPie('loanbx','贷款金额占比信息',<?= json_encode($loanname)?>,'贷款金额',<?= json_encode($loandata)?>,'万元');
                    </script>

                </div>
                <?php User::dataListEnd();?>
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
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-mortgagearea'}, function (data) {
            $('#t3').html(data + '亩');
        });
        
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-mortgagemoney'}, function (data) {
            $('#t5').html(data + '万元');
        });
    });
</script>