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
use app\models\Yields;
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
			        <td><strong>'.Yields::getFarmRows($params).'户</strong></td>
			        <td><strong>'.Yields::getFarmerrows($params).'个</strong></td>
			        <td><strong>'.Yields::getPlantRows($params).'个</strong></td>
			        <td><strong>'.Yields::getArea($params).'万亩</strong></td>
			        <td><strong>'.Yields::getPlantG($params).'万斤</strong></td>
			        <td><strong>'.Yields::getPlantA($params).'万斤</strong></td>
			        </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','planting_id','single','allsingle'],$params),
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
