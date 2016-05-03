<?php
namespace frontend\controllers;
use app\models\tables;
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
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="farms-menu">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        业务办理&nbsp;&nbsp;<?php //echo html::a('导出XLS',Url::to(['farms/farmstoxls']),['class'=>'btn btn-primary'])?>
                    </h3>
                </div>
                <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
            'contractarea',
            //'management_area',
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
            	$machine = Machineoffarm::find()->where(['farms_id'=>$model->id])->count();
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
            	$collection = Collection::find()->where(['farms_id'=>$model->id])->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count();
            	if($collection) {
            		$collecitonoption = '<i class="fa fa-cny text-red"></i>';
            		$collectiontitle = '已交费';
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
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'farmercontract-modal',
	'size'=>'modal-lg',
	//'header' => '<h4 class="modal-title">登记表</h4>',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',

]); 

?>
<?php \yii\bootstrap\Modal::end(); ?>