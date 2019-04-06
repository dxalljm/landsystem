<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Plant;
use app\models\Subsidiestype;
use yii\helpers\Url;
use app\models\Farms;
use app\models\Lease;
use frontend\helpers\MoneyFormat;
use app\models\Dispute;
use app\models\Collection;
use app\models\Goodseed;
use frontend\helpers\ActiveFormrdiv;
use app\models\Huinong;
use app\models\Huinonggrant;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\HuinongSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'huinong';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="huinong-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
                <?php 
//                 var_dump($huinongModel);
                $columns[] = ['class' => 'yii\grid\SerialColumn'];
                $columns[] = [
			            	'label' => '农场名称',
			            	'attribute'=>'farms_id',
			            	'value' => function ($model) {
			            		return Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'];
			            	}
			            ];
                $columns[] =  [
            	'label' => '法人姓名',
            	'value' => function ($model) {
            		return Farms::find()->where(['id'=>$model->farms_id])->one()['farmername'];
            	}
            ];
                $columns[] =  [
            	'label' => '租赁者',
            	'attribute'=>'lease_id',
            	'value' => function ($model) {
            		return Lease::find()->where(['id'=>$model->lease_id])->one()['lessee'];
            	}
            ];
                if($huinongModel->subsidiestype_id == 'plant')
                $columns[] = [
	            'label' => '作物',
	            'value' => function ($model) {
	            	$huinong = Huinong::find()->where(['id'=>$model->huinong_id])->one();
	            	return Plant::find()->where(['id'=>$huinong['typeid']])->one()['typename'];
	            }
            ];
                if($huinongModel->subsidiestype_id == 'goodseed')
                	$columns[] = [
	            'label' => '良种',
	            'value' => function ($model) {
	            	$huinong = Huinong::find()->where(['id'=>$model->huinong_id])->one();
	            	$goodseed = Goodseed::find()->where(['id'=>$huinong['typeid']])->one();
	            	return Plant::find()->where(['id'=>$goodseed['plant_id']])->one()['typename'].'/'.$goodseed['typename'];
	            }
            ];
                $columns[] = [
                		'attribute' => 'area',
	            		'value' => function ($model) {
	            	   	return $model->area.'亩';
	            }
            ];
                $columns[] = [
                		'attribute' => 'money',
                		'value' => function ($model) {
                			return $model->money.'元';
                		}
                ];
                $columns[] = [
            
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
            	if($model->locked == 1) {
            		$option .= '<i class="fa fa-lock text-red"></i>';
            		$title = '已冻结';
            	}
            	return Html::a($option,$url, [
            			'id' => 'farmermenu',
            			'title' => $title,
            	]);
            }
            ];
                ?>
                <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
                </div>
                
            </div>
        </div>
    </div>
</section>
</div>

