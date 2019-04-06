<?php
namespace frontend\controllers;

use console\models\Collection;
use Yii;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use frontend\helpers\MoneyFormat;
use yii\helpers\Url;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\User;
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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute'=>'management_area',
                'value' => function($model) {
                    return ManagementArea::getAreanameOne($model->management_area);
                },
                'filter' => ManagementArea::getAreaname(),
            ],
            [
                'attribute' => 'farms_id',
                'value' => function ($model) {
                    if($model->farms_id == 0)
                        return '';
                    return Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'];
                }
            ],
            [
                'attribute' => 'farmername',
//                 'options' => ['width' =>80],
            ],
            //'standard',
            'measure',
            'amountofmoney',
            // 'bigamountofmoney',
            'nonumber',
            [
                'label' => '报废时间',
                'attribute' => 'update_at',
                'value' => function($model) {
                    return date('Y-m-d H:i:s',$model->update_at);
                },
            ],
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
            'template' => '{view} {modfiy} {cacle}',
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
                'modfiy' => function ($url, $model, $key) {
                    $url = Url::to('index.php?r=tempprintbill/tempprintbillnewprint&id='.$model->id);
                    $options = [
                        'title' => Yii::t('yii', '重新打印'),
                        'aria-label' => Yii::t('yii', 'update'),
                        'data-pjax' => '0',
                    ];

                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                },
                'cacle' => function ($url, $model, $key) {
                    $collection = Collection::findOne($model->collection_id);

                    $url = Url::to('index.php?r=tempprintbill/tempprintbillcacle&id='.$model->id);
                    $options = [
                        'title' => Yii::t('yii', '撤销'),
                        'aria-label' => Yii::t('yii', 'delete'),
                        'data-pjax' => '0',
                        'class' => 'btn btn-xs btn-danger',
                        'data' => [
                            'confirm' => '您确定要撤销这项吗？',
//                            'method' => 'post',
                        ],
                    ];
//                    return $collection['state'];
                    if($collection['state']) {
                        return Html::a('撤销', $url, $options);
                    } else {
                        $options['disabled'] = true;
                        return Html::a('撤销', '#', $options);
                    }
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
