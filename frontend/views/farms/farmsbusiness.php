<?php
namespace frontend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ManagementArea;
use app\models\Farmer;
use app\models\Dispute;
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
                        业务办理
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
            'measure',
            //'management_area',
            [
            
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	$url = ['/farms/farmsmenu','farms_id'=>$model->id];
            	$disputerows = Dispute::find()->where(['farms_id'=>$model->id])->count();
            	
            	
            	if($disputerows) {
            		$option = '进入业务办理<i class="fa fa-commenting"></i>';
            		$title = '此农场有'.$disputerows.'条纠纷';
            	}
            	else { 
            		$option = '进入业务办理';
            		$title = '农场相关业务办理';
            	}
            	if($model->zongdi) {
            		$option .= '<i class="fa fa-check text-red"></i>';
            	}
            	return Html::a($option,$url, [
            			'id' => 'farmermenu',
            			'title' => $title,
            			
            	]);
            }
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