<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Cooperativetype;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\cooperativeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'cooperative';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cooperative-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['cooperativecreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'cooperativename',
            //'cooperativetype',
            [
            	'attribute'=>'cooperativetype',
            	'value'=>function($model){
            		return Cooperativetype::find()->where(['id'=>$model->cooperativetype])->one()['typename'];
            	}
            ],
            'directorname',
            // 'peoples',
            // 'finance',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
