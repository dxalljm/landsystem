<?php
namespace frontend\controllers;

use Yii;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use frontend\helpers\MoneyFormat;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\tempprintbillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'tempprintbill';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tempprintbill-index">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <h3>截止<?= date('Y年m月d日',$create_at)?>止已经收缴<?= MoneyFormat::num_format($billSum)?>元整</h3>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'farmername',
            //'standard',
            'number',
            'amountofmoney',
            // 'bigamountofmoney',
            'nonumber',
            //'state',
// 			[
// 				'attribute' => 'create_at',
// 				'label' => '开票时间',
// 				'value' => function($model)
// 				{
// 					return date('Y-m-d',$model->create_at);
// 				}
// 			],
            [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {print} {update}',
            'buttons' => [
            		'view' => function ($url, $model, $key) {
            		$url = Url::to('index.php?r=tempprintbill/tempprintbillsview&id='.$model->id);
            			$options = [
            					'title' => Yii::t('yii', '查看'),
            					'aria-label' => Yii::t('yii', 'View'),
            					'data-pjax' => '0',
            			];
            		
            			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
            		},
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'print' => function ($url, $model, $key) {
                	$url = Url::to('index.php?r=tempprintbill/tempprintbillview&id='.$model->id);
                    $options = [
                        'title' => Yii::t('yii', '查看并打印'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    ];
                    
                    return Html::a('<span class="fa fa-print"></span>', $url, $options);
                },
                'update' => function ($url, $model, $key) {
                	$options = [
                			'title' => Yii::t('yii', '报废'),
                			'aria-label' => Yii::t('yii', 'delete'),
                			'data-pjax' => '0',
                	];
                
                	return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                },
               	]
       	 	],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
