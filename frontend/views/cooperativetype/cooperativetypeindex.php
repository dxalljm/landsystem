<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\cooperativetypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'cooperativetype';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cooperativetype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['cooperativetypecreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'typename',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
