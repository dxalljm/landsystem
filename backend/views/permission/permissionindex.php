<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\authItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '权限管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建权限', ['permissioncreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            //'type',
            'description:ntext',
            'cname',
            'classdescription',
            // 'created_at',
            [
			 	'attribute'=>'updated_at',
			 	'value'=>function($model){
                    return  date('Y-m-d H:i:s',$model->updated_at);   //主要通过此种方式实现
                },
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
