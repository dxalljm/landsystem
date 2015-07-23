<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\tablefieldsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '表项管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tablefields-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加表项目', ['tablefieldscreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
	            'label'=>'数据库表',
	            'attribute'=>'tablename',
	            'value'=>'tables.tablename'
			],
           // '{{%tables}}.Ctablename',
           //'tables_id',
            'fields',
            'type',
            'cfields',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
