<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AfterchenqianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'afterchenqian';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="afterchenqian-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?><font color="red">（<?= User::getYear()?>年度）</font>
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
            	$url = ['/afterchenqian/afterchenqiancreate','farms_id'=>$model->id];

            	
            	$option = '添加陈欠';
            	$title = '';

            	return Html::a($option,$url, [
            			'title' => $title,
            			'class' => 'btn btn-primary btn-xs',
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
