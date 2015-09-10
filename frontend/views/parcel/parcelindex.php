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
?>
<section class="content-header">
  <h1>
       <?= Html::a('添加', ['parcelcreate'], ['class' => 'btn btn-success']) ?>
       <?= Html::a('XLS导入', ['parcelxls'], ['class' => 'btn btn-success']) ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li><a href="#"><?= $this->title?></a></li>
  </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= $this->title ?></h3>

                </div>
                <div class="box-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'unifiedserialnumber',
                            'agrotype',
                            'stonecontent',
                            'grossarea',
                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>

