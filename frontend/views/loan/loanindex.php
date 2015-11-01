<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\loanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'loan';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-index">

   <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']; ?>
                    </h3>
                </div>
                <?php Farms::showRow($_GET['farms_id']);?>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['loancreate','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'farms_id',
            'mortgagearea',
            'mortgagebank',
            'mortgagemoney',
            // 'mortgagetimelimit',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
