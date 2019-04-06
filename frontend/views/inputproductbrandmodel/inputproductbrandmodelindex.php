<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Inputproduct;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\inputproductbrandmodelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'inputproductbrandmodel';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inputproductbrandmodel-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                                            </h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['inputproductbrandmodelcreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
            	'label' => '投入品类型',
            	'attribute' => 'inputproduct_id',
            	'value' => function ($model) {
            		$inputproducts = Inputproduct::find()->where(['id'=>$model->inputproduct_id])->one();
            		$son = Inputproduct::find()->where(['id'=>$inputproducts->father_id])->one();
            		$father = Inputproduct::find()->where(['id'=>$son->father_id])->one();
            		
            		return $father->fertilizer.'>>'.$son->fertilizer.'>>'.$inputproducts->fertilizer;
            	}
            ],
            //'inputproduct_id',
            'brand',
            'model',

            ['class' => 'frontend\helpers\eActionColumn'],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
