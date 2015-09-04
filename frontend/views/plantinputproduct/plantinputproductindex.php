<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Lease;
use app\models\Parcel;
use app\models\Plant;
use app\models\Goodseed;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\plantinputproductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'plantinputproduct';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plantinputproduct-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <table class="table table-striped table-bordered table-hover table-condensed">
	    <tr>
	    	<td>承租人</td>
	    	<td>宗地</td>
	    	<td>承包面积</td>
	    	<td>作物</td>
	    	<td>良种型号</td>
	    	<td>操作</td>
	    </tr>
	    <?php foreach ($plantings as $planting) {?>
	    <tr>
	    	<td><?= Lease::find()->where(['id'=>$planting->lease_id])->one()['lessee']?></td>
	    	<td><?= $planting->zongdi;?></td>
	    	<td><?= $planting->area;?></td>
	    	<td><?= Plant::find()->where(['id'=>$planting->plant_id])->one()['cropname']?></td>
	    	<td><?= Goodseed::find()->where(['id'=>$planting->goodseed_id])->one()['plant_model']?></td>
	    	<td><?= Html::a('添加', ['plantinputproductcreate','planting_id'=>$planting->id], ['class' => 'btn btn-success']) ?></td>
	    </tr>
	    <?php }?>
    </table>

</div>
