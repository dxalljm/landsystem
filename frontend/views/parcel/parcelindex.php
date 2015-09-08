<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\parcelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'parcel';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parcel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['parcelcreate'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('XLS导入', ['parcelxls'], ['class' => 'btn btn-success']) ?>
    </p>
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Data Tables
            <small>advanced tables</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tables</a></li>
            <li class="active">Data tables</li>
          </ol>
        </section>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //'tableOptions' => 'table table-bordered table-hover',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'serialnumber',
            //'temporarynumber',
            'unifiedserialnumber',
            //'powei',
            // 'poxiang',
            // 'podu',
             'agrotype',
             'stonecontent',
             'grossarea',
            // 'piecemealarea',
            // 'netarea',
            // 'figurenumber',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
