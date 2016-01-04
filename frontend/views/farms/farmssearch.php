<?php

use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Lease;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Url;
use yii\widgets\ActiveFormrdiv;
use app\models\Search;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="lease-index">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

              
  <?= $this->render('..//search/searchindex',['tab'=>$tab,'management_area'=>$management_area,'begindate'=>$begindate,'enddate'=>$enddate]);?>

 <?= GridView::widget([
        'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
<<<<<<< HEAD
        'columns' => Search::getColumns(['management_area','farmname','farmername','address','telephone','measure','operation'],$params),
=======

        'columns' => Search::getColumns(['management_area','farmname','farmername','address','telephone','measure','operation'],$params),

>>>>>>> eaec1d78e94b3bce8fc1937e082afd1c832da24f
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
