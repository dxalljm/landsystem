<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\GoodseedinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Goodseedinfos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goodseedinfo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Goodseedinfo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'farms_id',
            'management_area',
            'lease_id',
            'planting_id',
            // 'plant_id',
            // 'goodseed_id',
            // 'zongdi',
            // 'area',
            // 'create_at',
            // 'update_at',
            // 'year',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
