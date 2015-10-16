<?php

use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Lease;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="lease-index">
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

	<script type="text/javascript">
	function openwindows(url)
	{
		window.open(url,'','width=1200,height=600,top=50,left=80, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');
		self.close();
	}
	</script>
	<?php 
		$leaseSumArea = 0;
		$strArea = '';
		$arrayArea = [];
		
		foreach ($leases as $value) {	
			$arrayArea = array_merge($arrayArea,explode('、',$value['lease_area']));
			$leaseSumArea += Lease::getListArea($value['lease_area']);
		}
		$isView = round($farms['measure'] - $leaseSumArea);
		if($isView) {
			$arrayZongdi = Lease::getNOZongdi($_GET['farms_id']);
	?>
<table class="table table-bordered table-hover">
  <tr>
    <td width="20%" colspan="2" align="center">法人</td>
    <td align="center">宗地</td>
    <td width="12%" align="center">总面积</td>
    <td width="12%" align="center">操作</td>
    </tr>
  <tr>
    <td colspan="2" align="center"><?= $farms['farmername'] ?></td>
    <td align="center"><?= implode('、',$arrayZongdi)?></td>
    <?php 
    	  $plantings = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>0])->all();
    	  $sumArea = 0;
    	  foreach($plantings as $value) {
    	  	$sumArea += $value['area'];
    	  }
    	  
    	  //echo $val['lease_area'];
    ?>
    <td align="center"><?= $isView?>亩</td>
    <td align="center"><?php if($isView - $sumArea) {?><?= Html::a('添加','index.php?r=plantingstructure/plantingstructurecreate&lease_id=0&farms_id='.$_GET['farms_id'], [
            			'id' => 'employeecreate',
            			'title' => '给'.$farms['farmername'].'添加',
            			'class' => 'btn btn-primary',
            			]);?><?php }?></td>
    </tr>
  <?php 
  		
	  	foreach($plantings as $v) {
  ?>
  <tr>
    <td colspan="2" width="20%" align="center">|_</td>
    
    <td align="center">种植面积：<?= $v['area']?>亩</td>
    <td width="12%" align="center">作物：<?= Plant::find()->where(['id'=>$v['plant_id']])->one()['cropname']?></td>
    <td width="12%" align="center"><?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'index.php?r=plantingstructure/plantingstructureview&id='.$v['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', '查看'),
                    'data-pjax' => '0',
                ]);?>&nbsp;&nbsp;<?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'index.php?r=plantingstructure/plantingstructureupdate&id='.$v['id'].'&lease_id=0&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', '更新'),
                    'data-pjax' => '0',
                ]);?>&nbsp;&nbsp;<?= Html::a('<span class="glyphicon glyphicon-trash"></span>', 'index.php?r=plantingstructure/plantingstructuredelete&id='.$v['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', '删除'),
                    'data-pjax' => '0',
                    'data' => [
		                'confirm' => '您确定要删除这项吗？',
		                //'method' => 'post',
           			 ],
                ]);?></td>
    </tr>
  <?php }?>
</table>
<?php }
if($leases) {
?>
<table class="table table-bordered table-hover">
  <tr>
    <td width="20%" colspan="2" align="center">承租人</td>
    <td align="center">宗地</td>
    <td width="12%" align="center">总面积</td>
    <td width="12%" align="center">操作</td>
    </tr>
  <?php foreach($leases as $val) {?>
  <tr>
    <td colspan="2" align="center"><?= $val['lessee'] ?></td>
    <td align="center"><?= $val['lease_area'] ?></td>
     <td align="center"><?= Lease::getListArea($val['lease_area'])?>亩</td>
    <?php 
    	  $plantings = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>$val['id']])->all();
    	  $sumArea = 0;
    	  foreach($plantings as $value) {
    	  	$sumArea += $value['area'];
    	  }
    	  $leaseSumArea = Lease::getListArea($val['lease_area']);
    	  //echo $val['lease_area'];
    ?>
    <td align="center"><?php if($sumArea !== $leaseSumArea) {?><?= Html::a('添加','index.php?r=plantingstructure/plantingstructurecreate&lease_id='.$val['id'].'&farms_id='.$_GET['farms_id'], [
            			'id' => 'employeecreate',
            			'title' => '给'.$val['lessee'].'添加',
            			'class' => 'btn btn-primary',
            			]);?><?php }?></td>
    </tr>
  <?php 
  		
	  	foreach($plantings as $v) {
  ?>
  <tr>
    <td colspan="2" width="20%" align="center">|_</td>
    <td align="center">种植面积：<?= $v['area']?>亩</td>
    <td width="12%" align="center">作物：<?= Plant::find()->where(['id'=>$v['plant_id']])->one()['cropname']?></td>
    <td width="12%" align="center"><?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'index.php?r=plantingstructure/plantingstructureview&id='.$v['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', '查看'),
                    'data-pjax' => '0',
                ]);?>&nbsp;&nbsp;<?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'index.php?r=plantingstructure/plantingstructureupdate&id='.$v['id'].'&lease_id='.$val['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', '更新'),
                    'data-pjax' => '0',
                ]);?>&nbsp;&nbsp;<?= Html::a('<span class="glyphicon glyphicon-trash"></span>', 'index.php?r=plantingstructure/plantingstructuredelete&id='.$v['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', '删除'),
                    'data-pjax' => '0',
                    'data' => [
		                'confirm' => '您确定要删除这项吗？',
		                //'method' => 'post',
           			 ],
                ]);?></td>
    </tr>
  <?php }}?>
</table>
<?php }?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

