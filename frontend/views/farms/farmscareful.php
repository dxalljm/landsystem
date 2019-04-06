<?php
namespace frontend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use yii;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Theyear;
use app\models\Dispute;
use app\models\Machineoffarm;
use app\models\User;
use app\models\Farmer;
use yii\helpers\Url;
use frontend\helpers\arraySearch;
use frontend\helpers\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'farms';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<?php 
//	$totalData = clone $dataProvider;
//	$totalData->pagination = ['pagesize'=>0];
// 	var_dump($totalData->getModels());exit;
//	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
//'<tr>
//						<td></td>
//						<td align="center"><strong>合计</strong></td>
//						<td><strong>'.$data->count('id').'户</strong></td>
//						<td><strong>'.$data->count('farmer_id').'个</strong></td>
//						<td></td>
//						<td></td>
//						<td><strong>'.$data->sum('contractarea').'亩</strong></td>
//						<td></td>
//					</tr>',
?>
<div class="farms-index">

<?php  //echo $this->render('farms_search', ['model' => $searchModel]); ?>
    
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'total' => '<tr height="40">
                                        <td></td>	
                                        <td align="left"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"></td>
                                        <td align="left" id="t4"></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                    </tr>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
// 			'id',
            [
            	'attribute' => 'management_area',
            	'headerOptions' => ['width' => '200'],
				'value'=> function($model) {
				     return ManagementArea::getAreanameOne($model->management_area);
				 },
				 'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
            ],
            [
            	'attribute' => 'farmname',

            ],
            'farmername',
//             [
//             	'label' => '管理区',
//               	'attribute' => 'areaname',      						
//             	'value' => 'managementarea.areaname',
//             ],
			//'management_area',
// 			'address',
// 			'telephone',
			[
            	'attribute' => 'contractnumber',
            	'options' => ['width'=>'150'],
            ],
            'contractarea',
            'measure',
            'notclear',
            'notstate',
            
//			[
//				'attribute' => 'create_at',
//				'value' => function($model) {
////					var_dump($model->state);
//					return date('Y-m-d',$model->create_at);
//				}
//			],
//			[
//				'attribute' => 'update_at',
//				'value' => function($model) {
////					var_dump($model->state);
//					return date('Y-m-d',$model->update_at);
//				}
//			],
//			'state',
             [
            	'attribute'=> 'state',
             	'options' => ['width'=>'150'],
             	'value' => function ($model) {
//					var_dump($model->state);exit;
             		return Farms::getStateInfo($model->state);
             },
             'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同',5=>'其它'],
             ],
             [
             	'attribute' => 'careful',
             	'label' => '宗地状态',
             	'format' => 'raw',
             	'value' => function ($model) {
	             	if($model->measure == 0.0 or $model->measure == '') {
	             		return '0';
	             	}
	             	if($model->notclear==0 and $model->notstate==0 and $model->measure == $model->contractarea) {
	             		return '<span class="text-green"><strong>=</strong></span>';
	             	}
             		if($model->notclear > 0.0) {
             			return '<span class="text-blue"><strong>></strong></span>';
             		}
             		if($model->notstate > 0.0) {
             			return '<span class="text-red"><strong><</strong></span>'	;
             		}             	             		
             	},
             	'filter' => ['='=>'合同面积等于宗地面积','<'=>'合同面积小于宗地面积','>'=>'合同面积大于宗地面积','0'=>'没有认定宗地'],
             ],
             [
	             'attribute' => 'carefulwc',
	             'label' => '宗地状态（含0.25%误差）',
             		'format' => 'raw',
	             'value' => function ($model) {
	             	$wc = $model->contractarea*0.0025;
	             	if($model->measure == 0 or $model->measure == '') {
	             		return '0';
	             	}
	             	if(bccomp($model->notstate, $wc)<=0 and bccomp($model->notclear, $wc) <= 0) {
	             		return '<span class="text-blue"><strong>=</strong></span>'	;
	             	}
	             	if(bccomp($model->measure, $model->contractarea)<=0 and bccomp($model->notstate, $wc) <= 0) {
	             		return '<span class="text-green"><strong>></strong></span>';
	             	}
             			
	             	
	             	
	             	if(bccomp($model->notstate, $wc) == 1) {
	             		return '<span class="text-red"><strong><</strong></span>';
	             	}
	             	
	             	            
	             	
	             },
	             'filter' => ['='=>'合同面积等于宗地面积','<'=>'合同面积小于宗地面积','>'=>'合同面积大于宗地面积','0'=>'没有认定宗地'],
             ],
            [
				                'label'=>'更多操作',
				                'format'=>'raw',
				            	//'class' => 'btn btn-primary btn-lg',
				                'value' => function($model,$key){
					                $option = '查看详情';
				                	$url = Url::to(['farms/farmslandview','id'=>$model->id]);               	
				                    $html = Html::a($option,$url, [
						            			'id' => 'moreOperation',
						            			'class' => 'btn btn-primary btn-xs',
// 				                    			'disabled' => $disabled,
						            	]);
						           	return $html;
				                }
				            ],
        ],
    ]); ?>

                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php
//Farms::getFarmsStateInfo(['state'=>0,'otherstate'=>6,'management_area'=>4],'create_at','2016','count');
//var_dump(Farms::find()->where(['state'=>1,'management_area' => 1])->count());
?>
<script>
	$('.shclDefault').shCircleLoader({color: "red"});
	$(document).ready(function () {
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-id'}, function (data) {
			$('#t1').html(data + '户');
		});
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
			$('#t2').html(data + '人');
		});
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
			$('#t5').html(data + '亩');
		});
	});
</script>