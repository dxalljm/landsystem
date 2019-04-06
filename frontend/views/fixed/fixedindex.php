<?php
namespace backend\controllers;
use app\models\Tables;
use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FixedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fixed';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fixed-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <p>
                        <?php
                        if(User::disabled()) {
                            echo Html::a('新增固定资产', '#', ['class' => 'btn btn-success','disabled'=>User::disabled()]);
                        } else {
                            echo Html::a('新增固定资产', ['fixedcreate', 'farms_id' => $_GET['farms_id']], ['class' => 'btn btn-success']);
                        }?>
                    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'unit',
            'number',
            // 'state',
            // 'remarks:ntext',

            ['class' => 'frontend\helpers\eActionColumn'],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
