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
<?php //var_dump($params);exit;?>
 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'total' => '<tr>
			        <td></td>
			        <td align="center"><strong>合计</strong></td>
			        <td><strong>户</strong></td>
			        <td><strong>个</strong></td>
			        <td><strong>个</strong></td>
			        <td><strong>万亩</strong></td>
			        <td><strong>种</strong></td>
			        <td><strong>个参保</strong></td>
			        </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','breedname','breedaddress','is_demonstration','breedtype_id','number'],$params),
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
