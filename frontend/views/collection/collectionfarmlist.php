<?php
namespace frontend\controllers;use app\models\User;
use yii;
use app\models\Farms;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use frontend\helpers\grid\GridView;
use app\models\Dispute;
use app\models\ManagementArea;
use app\models\Lockedinfo;
use app\models\Help;
/* @var $this yii\web\View */
/* @var $model app\models\farms */

?>
<div class="farms-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Help::showHelp3('农场列表','ttpo-transfermerge')?></h3>
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
            'farmername',
//            [
//            	'attribute' => 'management_area',
//            	'value' => function($model) {
//            		return ManagementArea::find()->where(['id'=>$model->management_area])->one()['areaname'];
//            	}
//            ],
            'contractnumber',
            'contractarea',
			'cardid',
            'telephone',
            [
            
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	$url = ['collection/collectionlocation','farms_id'=>$model->id,'id'=>$_GET['id']];
                $option = '定位';
                $title = '定位到此农场';
            	return Html::a($option,$url, [
            			'id' => 'farmermenu',
            			'title' => $title,
            			'class' => 'btn btn-sm btn-success',
            	]);
            }
            ],
        ],
    ]); ?>
    <?= Html::a('返回', [Yii::$app->controller->id.'collectionnoinfo'], ['class' => 'btn btn-success'])?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>

