<?php
namespace frontend\controllers;use app\models\User;
use Yii;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Lease;
use app\models\Parcel;
use app\models\Plant;
use app\models\Goodseed;
use app\models\Plantingstructure;
use app\models\Plantinputproduct;
use app\models\Inputproduct;
use app\models\Farms;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\plantinputproductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'plantinputproduct';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plantinputproduct-index">

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
	    	<td align="center"><?= $planting->area;?></td>
	    	<td align="center"><?= Plant::find()->where(['id'=>$planting->plant_id])->one()['typename']?></td>
	    	<td align="center"><?= Goodseed::find()->where(['id'=>$planting->goodseed_id])->one()['typename']?></td>
	    	<td align="center"><?= Html::a('添加', ['plantinputproductcreate','planting_id'=>$planting->id,'farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success']) ?></td>
	    </tr>

<?php foreach (Plantinputproduct::find()->where(['farms_id'=>$planting->farms_id,'lessee_id'=>$planting->lease_id,'zongdi'=>$planting->zongdi,'plant_id'=>$planting->plant_id])->all() as $value) {?>
<tr>
  <td align='center'>&nbsp;&nbsp;|_&nbsp;&nbsp;</td>
  <td colspan="2" align='center'><?= Inputproduct::find()->where(['id'=>$value['father_id']])->one()['fertilizer'].'>'.Inputproduct::find()->where(['id'=>$value['son_id']])->one()['fertilizer'].'>'.Inputproduct::find()->where(['id'=>$value['inputproduct_id']])->one()['fertilizer']?></td>
  <td colspan="2" align="center"><?= $value['pconsumption'].'斤/亩'?></td>
  <td align="center"><?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '#', [
                    'title' => Yii::t('yii', '查看'),
                    'data-pjax' => '0',
                    //'data-target' => '#plantinputproductview-modal',
                    //'data-toggle' => 'modal',
                   // 'data-keyboard' => 'false',
                    
                    //'onclick'=> 'plantinputproductview('.$value['id'].','.$_GET['lease_id'].','.$_GET['farms_id'].')',
                ]);?>
    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                    'title' => Yii::t('yii', '更新'),
                    'data-pjax' => '0',
                    //'data-target' => '#plantinputproductupdate-modal',
                    //'data-toggle' => 'modal',
                    //'data-keyboard' => 'false',
                    //'data-backdrop' => 'static',
                    //'onclick'=> 'plantinputproductupdate('.$value['id'].','.$_GET['lease_id'].','.$_GET['farms_id'].')',
                ]);?>
    <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', 'index.php?r=plantingstructure/plantingstructuredelete&id='.$value['id'].'&farms_id='.$_GET['farms_id'], [
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
