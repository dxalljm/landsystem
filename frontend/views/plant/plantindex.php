<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Plant;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\plantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'plant';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plant-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['plantcreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'cropname',
            [
            	'attribute'=>'father_id',
            	'value' => function($model){
            		return Plant::find()->where(['id'=>$model->father_id])->one()['cropname'];
            	},
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
