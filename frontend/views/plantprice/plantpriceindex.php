<?php
namespace frontend\controllers;use app\models\User;
use app\models\Collectionsum;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\plantpriceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'plant_price';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plant-price-index">
    <?php
    Collectionsum::updateSum('2016');
    ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?></h3></div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['plantpricecreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'price',
            'years',

            ['class' => 'frontend\helpers\eActionColumn2'],
            [
//             		'label'=>'操作',
                'format'=>'raw',
                'value' => function($model,$key) {
                    $html = '';

                        $url2 = Url::to(['plantprice/plantpriceaddcollection', 'id' => $model->id]);

                        $option2 = '批量追加缴费任务';
                        $title2 = '';
                        $html .= Html::a($option2, $url2, [

                            'title' => $title2,
                            'class' => 'btn btn-primary	btn-xs',

                        ]);
                        return $html;
                }],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
