<?php
namespace frontend\controllers;use app\models\User;
use yii;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Lease;
use app\models\Plant;
use app\models\Goodseed;
use app\models\Plantinputproduct;
use app\models\Inputproduct;
use app\models\Plantpesticides;
use app\models\Pesticides;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\plantpesticidesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'plantpesticides';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plantpesticides-index">

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
	    	<td align="center">承租人</td>
	    	<td align="center">宗地</td>
	    	<td align="center">承包面积</td>
	    	<td align="center">作物</td>
	    	<td align="center">良种型号</td>
	    	<td align="center">操作</td>
	    </tr>
	    <?php foreach ($plantings as $planting) {?>
	    <tr>
	    	<td align="center"><?= Lease::find()->where(['id'=>$planting->lease_id])->one()['lessee']?></td>
	    	<td align="center"><?= $planting->zongdi;?></td>
	    	<td align="center"><?= $planting->area;?></td>
	    	<td align="center"><?= Plant::find()->where(['id'=>$planting->plant_id])->one()['typename']?></td>
	    	<td align="center"><?= Goodseed::find()->where(['id'=>$planting->goodseed_id])->one()['typename']?></td>
	    	<td align="center"><?= Html::a('添加', ['plantpesticidescreate','planting_id'=>$planting->id,'lease_id'=>$planting->lease_id,'plant_id'=>$planting->plant_id,'farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success']) ?></td>
	    </tr>

<?php foreach (Plantpesticides::find()->where(['farms_id'=>$planting->farms_id,'lessee_id'=>$planting->lease_id,'plant_id'=>$planting->plant_id])->all() as $value) {?>
<tr>
  <td align='center'>&nbsp;&nbsp;|_&nbsp;&nbsp;</td>
  <td colspan="2" align='center'><?= Pesticides::find()->where(['id'=>$value['pesticides_id']])->one()['pesticidename']?></td>
  <td colspan="2" align="center"><?= $value['pconsumption'].'斤/亩'?></td>
  <td align="center"><?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'index.php?r=plantpesticides/plantpesticidesview&id='.$value['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', '查看'),
                    'data-pjax' => '0',
                    //'data-target' => '#plantinputproductview-modal',
                    //'data-toggle' => 'modal',
                   // 'data-keyboard' => 'false',
                    
                    //'onclick'=> 'plantinputproductview('.$value['id'].','.$_GET['lease_id'].','.$_GET['farms_id'].')',
                ]);?>
    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'index.php?r=plantpesticides/plantpesticidesupdate&id='.$value['id'].'&lease_id='.$value['lessee_id'].'&plant_id='.$value['plant_id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', '更新'),
                    'data-pjax' => '0',
                    //'data-target' => '#plantinputproductupdate-modal',
                    //'data-toggle' => 'modal',
                    //'data-keyboard' => 'false',
                    //'data-backdrop' => 'static',
                    //'onclick'=> 'plantinputproductupdate('.$value['id'].','.$_GET['lease_id'].','.$_GET['farms_id'].')',
                ]);?>
    <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', 'index.php?r=plantpesticides/plantpesticidesdelete&id='.$value['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', '删除'),
                    'data-pjax' => '0',
                    'data' => [
		                'confirm' => '您确定要删除这项吗？',
		                //'method' => 'post',
           			 ],
                ]);?></td>
</tr>
<?php }?>
	    <?php }?>
    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
