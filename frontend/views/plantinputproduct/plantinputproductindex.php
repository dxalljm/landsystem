<?php
namespace backend\controllers;
use Yii;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Lease;
use app\models\Parcel;
use app\models\Plant;
use app\models\Goodseed;
use app\models\Plantingstructure;
use app\models\Plantinputproduct;
use app\models\Inputproduct;

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
	    	<td align="center"><?= Plant::find()->where(['id'=>$planting->plant_id])->one()['cropname']?></td>
	    	<td align="center"><?= Goodseed::find()->where(['id'=>$planting->goodseed_id])->one()['plant_model']?></td>
	    	<td align="center"><?= Html::a('添加', ['plantinputproductcreate','planting_id'=>$planting->id], ['class' => 'btn btn-success']) ?></td>
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
