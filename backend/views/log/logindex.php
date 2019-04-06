<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\logSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'log';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['logcreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
            	'attribute' => 'user_id',
				'value' => function($model) {
					return User::find()->where(['id'=>$model->user_id])->one()['username'];
            	}
			],
            'user_ip',
            'action',
            'action_type',
            'object_name',
            //'object_id',
            //'operate_desc',
            [
            	'attribute' => 'operate_time',
            	'value' => function ($model) {
            		return date('Y-m-d H:m:s',$model->operate_time);
            	}
            ],
            // 'object_old_attr:ntext',
            // 'object_new_attr:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
