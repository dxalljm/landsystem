<?php
namespace frontend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Lease;
use app\models\Farms;
use app\models\Plant;
use app\models\Goodseed;
use app\models\Yields;
use app\models\Yieldbase;
use app\models\User;
use frontend\helpers\MoneyFormat;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\yieldsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'yields';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="yields-index">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <table class="table table-bordered table-hover">
	    <tr>
	    	<td align="center">承租人/法人</td>
	    	<td align="center">承包面积</td>
	    	<td align="center">作物</td>
	    	<td align="center">良种型号</td>
	    	<td align="center">平均亩产</td>
	    	<td align="center">总产</td>
	    	
	    </tr>
	    <?php foreach ($plantings as $planting) {?>
	    <?php $name = Lease::find()->where(['id'=>$planting->lease_id])->one()['lessee'];?>
	    <tr><?php $dc = Yieldbase::find()->where(['plant_id'=>$planting->plant_id,'year'=>User::getYear()])->one()['yield'];?>
	    	<td align="center"><?php if($name == '') echo Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmername']; else echo $name;?></td>
	    	<td align="center"><?= $planting->area;?>亩</td>
	    	<td align="center"><?= Plant::find()->where(['id'=>$planting->plant_id])->one()['typename']?></td>
	    	<td align="center"><?= Goodseed::find()->where(['id'=>$planting->goodseed_id])->one()['typename']?></td>
	    	<td align="center"><?= MoneyFormat::num_format($dc)?>斤</td>
	    	<td align="center"><?= MoneyFormat::num_format($dc*$planting->area)?>斤</td>
	    </tr>
	    <?php }?>
    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
