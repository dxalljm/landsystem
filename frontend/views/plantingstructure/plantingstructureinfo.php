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

 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'total' => '<tr>
			        <td></td>
			        <td align="center"><strong>合计</strong></td>
			        <td><strong>'.Plantingstructure::getFarmRows($params).'户</strong></td>
			        <td><strong>'.Plantingstructure::getFarmerrows($params).'个</strong></td>
			        <td><strong>'.Plantingstructure::getLeaseRows($params).'个</strong></td>
			        <td><strong>'.Plantingstructure::getPlantRows($params).'种</strong></td>
			        <td><strong>'.Plantingstructure::getGoodseedRows($params).'种</strong></td>
			        <td><strong>'.Plantingstructure::getArea($params).'万亩</strong></td>
			        </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','lease_id','plant_id','goodseed_id','area','operation'],$params),
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
