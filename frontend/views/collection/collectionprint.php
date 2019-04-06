<?php
namespace frontend\controllers;use app\models\User;

use Yii;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use frontend\helpers\MoneyFormat;
use yii\helpers\Url;
use app\models\Farms;
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
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
            	'label' => '农场名称',
              	'attribute' => 'farmname',
            	'value' => 'farms.farmname',
            ],
            [
	            'label' => '法人姓名',
	            'value' => function ($model){
            		return Farms::find()->where(['id'=>$model->farms_id])->one()['farmername'];
            	}
            ],
            [
            	//'label' => '应收金额',
            	'attribute' => 'amounts_receivable',
            	'value' => function($model) {
            		return MoneyFormat::num_format($model->amounts_receivable).'元';
            	}
            ],
            [
	            //'label' => '实收金额',
	            'attribute' => 'real_income_amount',
	            'value' => function($model) {
	            	return MoneyFormat::num_format($model->real_income_amount).'元';
	            }
            ],
            [
	            'label' => '差额',
	            'value' => function($model) {
	            	return MoneyFormat::num_format(bcsub($model->amounts_receivable, $model->real_income_amount,2)).'元';
	            }
            ],
            [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {print} {update}',
            'buttons' => [
            		'view' => function ($url, $model, $key) {
            		$url = Url::to('index.php?r=collection/collectionview&id='.$model->id);
            			$options = [
            					'title' => Yii::t('yii', '查看'),
            					'aria-label' => Yii::t('yii', 'View'),
            					'data-pjax' => '0',
            			];
            		
            			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
            		},
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'print' => function ($url, $model, $key) {
                	$url = Url::to('index.php?r=collection/collectionview&id='.$model->id);
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
