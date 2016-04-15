<?php
namespace frontend\controllers;

use Yii;
use app\models\tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
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
            'template' => '{view}',
            'buttons' => [
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'view' => function ($url, $model, $key) {
                	$url = Url::to('index.php?r=tempprintbill/tempprintbillsview&id='.$model->id);
                    $options = [
                        'title' => Yii::t('yii', $model->remarks),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    ];
                    
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
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
