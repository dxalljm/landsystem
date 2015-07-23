<?php
namespace frontend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ManagementArea;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="farms-menu">
	<h1>业务办理</h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
            	'attribute' => 'farmname',

            ],
            [
            	'label' => '管理区',
              	'attribute' => 'areaname',      						
            	'value' => 'managementarea.areaname',
            ],
            [
            
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	$url = ['/farms/farmsmenu','id'=>$model->id,'areaid'=>$model->management_area];
            	return Html::a('进入业务办理',$url, [
            			'id' => 'farmermenu',
            			'title' => '农场相关业务办理',
            	]);
            }
            ],
            [
            
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	// $url = ['/user/userassign','id'=>$model->id];
            	return Html::a('详细信息','#', [
            			'id' => 'farmercreate',
            			'title' => '填写承包信息',
            			//'class' => 'btn btn-primary btn-lg',
            			'data-toggle' => 'modal',
            			'data-target' => '#farmercontract-modal',
            			//'data-id' => $key,
            			'onclick'=> 'farmercontract('.$key.')',
            			//'data-pjax' => '0',
            
            	]);
            }
            ],
        ],
    ]); ?>

</div>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'farmercontract-modal',
	'size'=>'modal-lg',
	//'header' => '<h4 class="modal-title">登记表</h4>',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',

]); 

?>
<?php \yii\bootstrap\Modal::end(); ?>