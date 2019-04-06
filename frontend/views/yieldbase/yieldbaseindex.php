<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Plant;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\yieldbaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'yieldbase';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="yieldbase-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php User::tableBegin('产量基数');?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['yieldbasecreate'], ['class' => 'btn btn-success']) ?>
 
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
            	'attribute' =>'plant_id',
            	'value' => function($model) {
            		return Plant::find()->where(['id'=>$model->plant_id])->one()['typename'];
            	}
            ],
            'yield',
            'year',

            ['class' => 'frontend\helpers\eActionColumn'],
        ],
    ]); ?>
            <?php User::tableEnd();?>
        </div>
    </div>
</section>
</div>
