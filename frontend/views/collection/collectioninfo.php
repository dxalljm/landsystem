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
use app\models\Collection;
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
<?php //var_dump($dataProvider->getModels());exit;?>
 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'total' => '<tr>
			        <td></td>
			        <td align="center"><strong>合计</strong></td>
					<td align="center"></td>
			        <td><strong>'.Collection::getFarmRows($params).'户</strong></td>
			        <td><strong>'.Collection::getFarmerrows($params).'个</strong></td>
			        <td><strong>'.Collection::getAmounts($params).'万元</strong></td>
			        <td><strong>'.Collection::getReal($params).'万元</strong></td>
			        <td><strong>'.Collection::getAllOwe($params).'万元</strong></td>
			        <td><strong>'.Collection::getAllYpayarea($params).'万亩</strong></td>
            		<td><strong>'.Collection::getAllYpaymoney($params).'万元</strong></td>
			        </tr>',
        'columns' => Search::getColumns(['management_area','ypayyear','farms_id','amounts_receivable','real_income_amount','owe','ypayarea','ypaymoney'],$params),
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
