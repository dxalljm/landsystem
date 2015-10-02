<?php
namespace frontend\controllers;

use Yii;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use frontend\helpers\MoneyFormat;
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
        <?= Html::a('缴费', ['tempprintbillcreate'], ['class' => 'btn btn-success']) ?>
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
			[
				'attribute' => 'create_at',
				'label' => '开票时间',
				'value' => function($model)
				{
					return date('Y年d月d日 H时s分i秒',$model->create_at);
				}
			],
            [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons' => [
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'view' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', '查看并打印'),
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
