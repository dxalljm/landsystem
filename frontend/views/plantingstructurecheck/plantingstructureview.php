<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Farmer;
use yii\helpers\ArrayHelper;
use app\models\Farms;
use app\models\Parcel;
use yii\web\View;
use app\models\Plant;
use app\models\Inputproduct;
use app\models\Pesticides;
use app\models\Lease;
use app\models\Goodseed;
/* @var $this yii\web\View */
/* @var $model app\models\Plantingstructure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plantingstructure-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                    <?php $farms = Farms::find()->where(['id'=>$_GET['farms_id']])->one();?>
                        <?= $farms['farmname']; ?> 的种植结构
                    </h3>
                </div>
                <div class="box-body">
    <?php $form = ActiveFormrdiv::begin(); ?>
<table width="61%" class="table table-bordered table-hover">

<tr>
<td width=15% align='right'>农场名称</td>
<td align='left'><?= $farm->farmname?></td>
<td align='right'>法人</td>

<td align='left'><?= $farm->farmername?></td>
<td align='right'>承租人</td>
<td align='left'><?= Lease::find()->where(['id'=>$_GET['lease_id']])->one()['lessee'] ?></td>

<td align='right'>农场面积：<?= $farm->measure.' 亩'?></td>
<td align='left'>种植面积：<?= $model->area?>亩</td>
</tr>
<tr>
  <td align='right'>宗地</td>
  <td colspan="3" align='left'><?= $model->zongdi ?></td>
  <?php if(isset($_GET['zongdi'])) $value = Parcel::find()->where(['id'=>$_GET['zongdi']->one()['grossarea']]); else $value = 0;?>
  <td colspan="2" align='right'>种植面积</td><?php if($model->area !== 0.0) $value = $model->area;?>
  <td colspan="2" align='left'><?= $value ?></td>
  </tr>
<tr>
  <td align='right'>种植作物</td><?php $fatherid = Plant::find()->where(['id'=>$model->plant_id])->one()['father_id'];?>
  <td align='center'><?= Plant::find()->where(['id'=>$fatherid])->one()['typename']?></td>
  <td colspan="2" align='center'><?= Plant::find()->where(['id'=>$model->plant_id])->one()['typename'] ?></td>
  <td colspan="2" align="right">良种型号</td>
  <td colspan="2"><?= Goodseed::find()->where(['id'=>$model->goodseed_id])->one()['typename']?></td>
</tr>

</table>

<table class="table table-bordered table-hover" id="plantinputproduct">

	<tbody>
		<tr>
			<td width=15% align='center'>投入品父类</td>
			<td align='center'>投入品子类</td>
			<td align='center'>投入品</td>
            <td align='center'>投入品用量</td>
		</tr>
		<?php 
		if(is_array($plantinputproductModel)) {
		foreach ($plantinputproductModel as $value) {?>
		<tr>
              <td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['father_id']])->one()['fertilizer'];?></td>
              <td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['son_id']])->one()['fertilizer']; ?></td>
              <td align="center"><?php echo Inputproduct::find()->where(['id'=>$value['inputproduct_id']])->one()['fertilizer']; ?></td>
              <td align="center"><?php echo $value['pconsumption'].'公斤/亩';?></td>
          </tr>
<?php }}?>
		
	</tbody>
</table>

<table class="table table-bordered table-hover" id="plantpesticides">
	
	<tbody>
		<tr>
			<td width=40% align='center'>农药</td>
			<td align='center'>农药用量</td>
		</tr>
		<?php 
		if(is_array($plantpesticidesModel)) {
		foreach ($plantpesticidesModel as $value) {?>
		 <tr>
              <td align="center"><?php echo Pesticides::find()->where(['id'=>$value['pesticides_id']])->one()['pesticidename']; ?></td>
              <td align="center"><?php echo $value['pconsumption'].'公斤/亩'; ?></td>
          </tr>
<?php }}?>
		
	</tbody>
</table>
	<p>
	<?= Html::a('返回', Yii::$app->getRequest()->getReferrer(), ['class' => 'btn btn-success'])?>
	</p>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

