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
use app\models\Breedinfo;
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
			        <td><strong>'.Breedinfo::getFarmRows($params).'户</strong></td>
			        <td><strong>'.Breedinfo::getFarmerrows($params).'个</strong></td>
			        <td><strong></strong></td>
			        <td><strong></strong></td>
			        <td><strong></strong></td>
			        <td><strong>'.Breedinfo::getTypeRows($params).'种</strong></td>
					<td><strong></strong></td>

			        </tr>',
        'columns' => Search::getColumns(['management_area','breed_id','breedtype_id','number'],$params),
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
