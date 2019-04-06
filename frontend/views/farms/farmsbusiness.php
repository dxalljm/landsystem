<?php
namespace frontend\controllers;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\ManagementArea;
use app\models\Farmer;
use app\models\Dispute;
use yii\helpers\Url;
use app\models\Lease;
use app\models\Machine;
use app\models\Machineoffarm;
use app\models\Projectapplication;
use app\models\Collection;
use app\models\Theyear;
use app\models\Farms;
use app\models\Mainmenu;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(isset($_GET['farmsSearch']['businesstype'])) {
	$businesstype = $_GET['farmsSearch']['businesstype'];
} else {
	$businesstype = null;
}
if(isset($_GET['farmsSearch']['condition'])) {
	$conidion = $_GET['farmsSearch']['condition'];
} else {
	$conidion = null;
}
$arrclass = explode('\\',$dataProvider->query->modelClass);
?>
<div class="farms-menu">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
			<?php
			User::tableBegin('业务办理');
			if(isset($_GET['iszx'])) {
				$state = 'checked=""';
				$iszx = 1;
			} else {
				$state = "";
				$iszx = 0;
			}
			?>
			<span class="pull-right">
				<div _ngcontent-c3="" class="togglebutton">
					<label _ngcontent-c3="">
						<input _ngcontent-c3="" name="iszx" type="checkbox" id="Iszx" <?= $state?>>
						<span _ngcontent-c3="" class="toggle"></span> 已流转
					</label>
				</div>
			</span>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'filterSelector' => "select[name='".$dataProvider->getPagination()->pageSizeParam."'],input[name='".$dataProvider->getPagination()->pageParam."']",
		'pager' => [
			'class' => \frontend\helpers\page\LinkPager::className(),
			'template' => '{pageButtons} {customPage} {pageSize}', //分页栏布局
			'pageSizeList' => [10, 20, 30, 50], //页大小下拉框值
			'customPageWidth' => 50,            //自定义跳转文本框宽度
			'customPageBefore' => ' 跳转到第 ',
			'customPageAfter' => ' 页 ',
		],
						'total' => '<tr height="40">
                                        <td></td>	
                                        <td align="left"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t4"></td>
                                        <td align="left" id="t5"></td>
                                        <td></td>
                                    </tr>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
            	'attribute' => 'farmname',
            ],
             [
            	'attribute' => 'farmername',
//             	'value' => function ($model) {
// 	            	$farmer = Farmer::find()->where(['farms_id'=>$model->id])->count();
// 	            	if($farmer) {
//             			return html::a($model->farmername.'<i class="fa fa-check-square-o bg-red"></i>','#');
// 	            	}
//             		else 
//             			return $model->farmername;
//             	}
            ],
//			[
//				'attribute' => 'create_at',
//				'value' => function($model) {
//					return date('Y-m-d',$model->create_at);
//				}
//			],
            'contractarea',
            'contractnumber',
			[
				'attribute' => 'businesstype',
				'label' => '业务类型',
				'value' => function($model) {
					if(isset($_GET['farmsSearch']['businesstype']))
						return Mainmenu::getTenMenuOne($_GET['farmsSearch']['businesstype']);
				},
				'filter' => Mainmenu::getTenMenu()
			],
			[
				'attribute' => 'condition',
				'label' => '筛选条件',
				'value' => function($model) {
					if(isset($_GET['farmsSearch']['businesstype']) and isset($_GET['farmsSearch']['condition']))
						return Mainmenu::getConditonListOne($_GET['farmsSearch']['businesstype'],$_GET['farmsSearch']['condition']);
				},
				'filter' => Mainmenu::getConditionList()
			],
            [
            
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	$url = ['/farms/farmsmenu','farms_id'=>$model->id];
            	$disputerows = Dispute::find()->where(['farms_id'=>$model->id,'state'=>0])->count();
            	$option = '进入业务办理';
            	$title = '农场相关业务办理';
            	$html = '';
            	$html .= Html::a($option,$url, [
            			'title' => $title,
            			'class' => 'btn btn-primary btn-xs',
            	]);
            	 $html .= '&nbsp;';            	
            	
            	if($model->zongdi) {
            		$zongdioption = '<i class="fa fa-map text-red"></i>';
            		$zongdititle = Lease::getZongdiRows($model->zongdi).'块宗地';
            		$html .= Html::a($zongdioption,$url, [
            				'title' => $zongdititle,
            				
            		]);
            		$html .= '&nbsp;';
            		
            		
            	}
            	if($model->notclear) {
            		$notclearoption = '<i class="fa fa-exclamation-circle text-red"></i>';
            		$notcleartitle = '未明确地块面积'.$model->notclear.'亩';
            		$html .= Html::a($notclearoption,$url, [
            				'title' => $notcleartitle,
            	
            		]);
            		$html .= '&nbsp;';
            	}
            	if($model->notstate) {
            		$notstateoption = '<i class="fa fa-tag text-red"></i>';
            		$notstatetitle = '未明确状态面积'.$model->notstate.'亩';
            		$html .= Html::a($notstateoption,$url, [
            				'title' => $notstatetitle,
            	
            		]);
            		$html .= '&nbsp;';
            	}
            	if($disputerows) {
            		$disputeoption = '<i class="fa fa-commenting text-red"></i>';
            		$disputetitle = $disputerows.'条纠纷';
            		$url = Url::to(['dispute/disputeindex','farms_id'=>$model->id]);
            		$html .= Html::a($disputeoption,$url, [
            				'title' => $disputetitle,            		
            		]);
            		$html .= '&nbsp;';
            	}
            	$machine = Machineoffarm::find()->where(['cardid'=>$model->cardid])->count();
            	if($machine) {
            		$machineoption = '<i class="fa fa-truck text-red"></i>';
            		$machinetitle = $machine.'台农机';
            		$url = Url::to(['machineoffarm/machineoffarmindex','farms_id'=>$model->id]);
            		$html .= Html::a($machineoption,$url, [
            				'title' => $machinetitle,
            		]);
            		$html .= '&nbsp;';
            	}
            	$project = Projectapplication::find()->where(['farms_id'=>$model->id,'state'=>1])->count();
            	if($project) {
            		$projectoption = '<i class="fa fa-sticky-note-o text-red"></i>';
            		$projecttitle = $project.'个项目';
            		$url = Url::to(['projectapplication/projectapplicationindex','farms_id'=>$model->id]);
            		$html .= Html::a($projectoption,$url, [
            				'title' => $projecttitle,
            		]);
            		$html .= '&nbsp;';
            	}
            	$collection = Collection::find()->where(['farms_id'=>$model->id,'state'=>1,'payyear'=>User::getYear()])->count();
				if($collection) {
					$collecitonoption = '<i class="fa fa-cny text-red"></i>';
					$collectiontitle = '已交费';
					$url = '#';
					$html .= Html::a($collecitonoption,$url, [
						'title' => $collectiontitle,
					]);
					$html .= '&nbsp;';
				}
				$collectiondckpay = Collection::find()->where(['farms_id'=>$model->id,'dckpay'=>1,'state'=>0,'payyear'=>User::getYear()])->count();
				if($collectiondckpay) {
					$collecitonoption = '<i class="fa fa-bullhorn text-red"></i>';
					$collectiontitle = '已提交财务科';
					$url = '#';
					$html .= Html::a($collecitonoption,$url, [
						'title' => $collectiontitle,
					]);
					$html .= '&nbsp;';
				}
//             	if($model->zongdi) {
//             		$option .= '<i class="fa fa-check text-red"></i>';
//             	}
            	if($model->locked == 1) {
            		$lockoption = '<i class="fa fa-lock text-red"></i>';
            		$locktitle = '已冻结';
            		$html .= Html::a($lockoption,$url, [
            				'id' => 'farmermenu',
            				'title' => $locktitle,
            		]);
            		$html .= '&nbsp;';
            	}
//             	if($model->notstate) {
//             		$option.='<i class="fa fa-lock text-red"></i>';
//             		$title = '未明确状态面积';
//             	}
            	return $html;
            },
            'filter' => Farms::businessIcon(),
            ],
//             [
            
//             'format'=>'raw',
//             //'class' => 'btn btn-primary btn-lg',
//             'value' => function($model,$key){
//             	// $url = ['/user/userassign','id'=>$model->id];
//             	return Html::a('详细信息','#', [
//             			'id' => 'farmercreate',
//             			'title' => '填写承包信息',
//             			//'class' => 'btn btn-primary btn-lg',
//             			'data-toggle' => 'modal',
//             			'data-target' => '#farmercontract-modal',
//             			//'data-id' => $key,
//             			'onclick'=> 'farmercontract('.$key.')',
//             			//'data-pjax' => '0',
            
//             	]);
//             }
//             ],
        ],
    ]); ?>
			<?php
			User::tableEnd();
			?>
            </div>
        </div>
    </div>
</section>
</div>

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
			$('#t3').html(data + '亩');
		});
	});
	$('#Iszx').click(function () {
		var input = $("input[name='iszx']:checked").val();
		if(input == 'on') {
			window.location.href = "<?= Url::to(['farms/farmsbusiness','iszx'=>1])?>";
		} else {
			window.location.href = "<?= Url::to(['farms/farmsbusiness'])?>";
		}
	});
</script>
