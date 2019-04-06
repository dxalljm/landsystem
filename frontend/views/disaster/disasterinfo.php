<?php

use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Lease;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Url;
use frontend\helpers\ActiveFormrdiv;
use app\models\Search;
use app\models\Disaster;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<script type="text/javascript" src="vendor/bower/echarts/build/dist/echarts.js"></script>
<script type="text/javascript" src="vendor/bower/echarts/build/dist/echarts.min.js"></script>
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
			        <td><strong>'.Disaster::getFarmRows($params).'户</strong></td>
			        <td><strong>'.Disaster::getFarmerrows($params).'个</strong></td>
			        <td><strong>'.Disaster::getTypeRows($params).'个</strong></td>
			        <td><strong>'.Disaster::getArea($params).'万亩</strong></td>
			        <td><strong>'.Disaster::getPlantRows($params).'种</strong></td>
			        <td><strong>'.Disaster::getBX($params).'个参保</strong></td>
			        </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','disastertype_id','disasterarea','disasterplant','isinsurance','yieldreduction']),
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
