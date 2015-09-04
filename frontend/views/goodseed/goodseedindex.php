<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Plant;
use yii\data\DataProviderInterface;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\goodseedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'goodseed';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goodseed-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['goodseedcreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
            	'attribute'=>'plant_id',
            	'value' => function ($model) {
            		return Plant::find()->where(['id'=>$model->plant_id])->one()['cropname'];
            	}
            ],
            'plant_model',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
