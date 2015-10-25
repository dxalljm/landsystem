<?php
namespace frontend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Lease;
use app\models\Farms;
use app\models\Plant;
use app\models\Goodseed;
use app\models\Yields;
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
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <table class="table table-bordered table-hover">
	    <tr>
	    	<td align="center">承租人/法人</td>
	    	<td align="center">宗地</td>
	    	<td align="center">承包面积</td>
	    	<td align="center">作物</td>
	    	<td align="center">良种型号</td>
	    	<td align="center">操作</td>
	    </tr>
	    <?php foreach ($plantings as $planting) {?>
	    <?php $name = Lease::find()->where(['id'=>$planting->lease_id])->one()['lessee'];?>
	    <tr>
	    	<td align="center"><?php if($name == '') echo Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmername']; else echo $name;?></td>
	    	<td align="center"><?= $planting->zongdi;?></td>
	    	<td align="center"><?= $planting->area;?>亩</td>
	    	<td align="center"><?= Plant::find()->where(['id'=>$planting->plant_id])->one()['cropname']?></td>
	    	<td align="center"><?= Goodseed::find()->where(['id'=>$planting->goodseed_id])->one()['plant_model']?></td>
	    	<td align="center"><?= Html::a('产量信息', ['yieldscreate','planting_id'=>$planting->id,'farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success']) ?></td>
	    </tr>
		<?php $yields = Yields::find()->where(['planting_id'=>$planting->id])->one();?>
		<?php if($yields) {?>
		<tr>
	    <td width="20%" align="center">|_</td>
	    
	    <td align="center">单产：<?= $yields->single ?>斤</td>
	    <td colspan="3" align="center">总产：<?= $yields->single*$planting->area?>斤</td>
	    
	    </tr>
	    <?php }}?>
    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
