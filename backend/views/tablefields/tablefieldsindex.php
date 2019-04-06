<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Tablefields;
use app\models\Tables;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\tablefieldsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '表项管理('.Tables::findOne(['id'=>$_GET['id']])['Ctablename'].')';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tablefields-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加表项目', ['tablefieldscreate','tables_id'=>$_GET['id']], ['class' => 'btn btn-success']) ?>
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

             [
            'class' => 'backend\helpers\eActionColumn',
            'template' => '{view} {update} {delete}',
            'buttons' => [
                // 下面代码来自于 backend\helpers\eActionColumn 简单修改了下
                'view' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'View'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    ];
                    $url.='&tables_id='.$_GET['id'];
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                },
                'update' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'Update'),
                        'aria-label' => Yii::t('yii', 'Update'),
                        'data-pjax' => '0',
                    ];
                    $url.='&tables_id='.$_GET['id'];
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                },
                'delete' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'Delete'),
                        'aria-label' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ];
                    $url.='&tables_id='.$_GET['id'];
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                },
            	],
            ]
        ],
    ]); ?>

</div>
