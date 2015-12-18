<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Plant;

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
                    <h3 class="box-title">
                        <?= $this->title ?>发布                   </h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['huinongcreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'subsidiestype_id',
            [
            	'attribute' => 'typeid',
            	'value' => function($model) {
            		$classFile = 'app\\models\\'. $model->subsidiestype_id;
            		$data = $classFile::find()->where(['id'=>$model->typeid])->one();
            		if($model->subsidiestype_id == 'plant')
            			return $data['cropname'];
            		if($model->subsidiestype_id == 'goodseed') {
            			$plantcropname = Plant::find()->where(['id'=>$data['plant_id']])->one()['cropname'];
            			return $plantcropname.'/'.$data['plant_model'];
            		}
            	}
            ],
            [
            	'attribute' => 'subsidiesarea',
            	'value' => function ($model){
            		$p = $model->subsidiesarea*100;
            		return $p.'%';
            	}
            ],
            [
            	'attribute' => 'subsidiesmoney',
           		'value' => function($model){
           			return $model->subsidiesmoney.'元';
            	}	 
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
