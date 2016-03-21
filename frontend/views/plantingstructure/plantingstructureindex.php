<?php

use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Lease;
use app\models\Theyear;
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
<?php Farms::showRow($_GET['farms_id']);?>
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
		$allarea = $farms['measure'];
		
		$isView = bcsub($allarea , $leaseSumArea,2);
		if($isView) {
			$arrayZongdi = Lease::getNOZongdi($_GET['farms_id']);
		if(is_array($arrayZongdi))
			$zongdilist = implode('、',$arrayZongdi);
		else 
			$zongdilist =  bcsub($farms['measure'] , $arrayZongdi,2);
		//var_dump($arrayZongdi);
	?>
<table class="table table-bordered table-hover">
  <tr>
    <td width="20%" colspan="2" align="center">法人</td>
    <td width="12%" align="center">总面积</td>
    
    <td width="12%" align="center">操作</td>
    </tr>
  <tr>
    <td colspan="2" align="center"><?= $farms['farmername'] ?></td>
    <?php 
    	  $plantings = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>0])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
//     	  var_dump($plantings);
    	  $sumArea = 0;
    	  foreach($plantings as $value) {
    	  	$sumArea += (float)$value['area'];
    	  }
    	  
    	  //echo $val['lease_area'];
    ?>
    <td align="center"><?= $isView?>亩</td>
    <td align="center"><?php if(bcsub($isView,$sumArea)) {?><?= Html::a('添加','index.php?r=plantingstructure/plantingstructurecreate&lease_id=0&farms_id='.$_GET['farms_id'], [
            			'id' => 'employeecreate',
            			'title' => '给'.$farms['farmername'].'添加',
            			'class' => 'btn btn-primary',
            			]);?><?php }?></td>
    </tr>
  <?php 
  		
	  	foreach($plantings as $v) {
  ?>
  <tr>
    <td width="20%" align="center">|_</td>
    
    <td align="center">种植面积：<?= $v['area']?>亩</td>
    <td width="12%" align="center">作物：<?= Plant::find()->where(['id'=>$v['plant_id']])->one()['cropname']?></td>
    <td><?php 
    			$controller = Yii::$app->controller->id;
    			$action = $controller.'view';
    			if(\Yii::$app->user->can($action)){
	    			echo Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'index.php?r=plantingstructure/plantingstructureview&id='.$v['id'].'&lease_id='.$v['lease_id'].'&farms_id='.$_GET['farms_id'], [
	                    'title' => Yii::t('yii', '查看'),
	                    'data-pjax' => '0',
	                ]);
    			}?>&nbsp;&nbsp;
    			<?php 
    			$controller = Yii::$app->controller->id;
    			$action = $controller.'update';
    			if(\Yii::$app->user->can($action)){
	    			echo Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'index.php?r=plantingstructure/plantingstructureupdate&id='.$v['id'].'&lease_id='.$v['lease_id'].'&farms_id='.$_GET['farms_id'], [
	                    'title' => Yii::t('yii', '更新'),
	                    'data-pjax' => '0',
	                ]);
	  			}?>&nbsp;&nbsp;
	  			<?php
	  			$controller = Yii::$app->controller->id;
	  			$action = $controller.'delete';
	  			if(\Yii::$app->user->can($action)){
		  			 	echo Html::a('<span class="glyphicon glyphicon-trash"></span>', 'index.php?r=plantingstructure/plantingstructuredelete&id='.$v['id'].'&farms_id='.$_GET['farms_id'], [
	                    'title' => Yii::t('yii', '删除'),
	                    'data-pjax' => '0',
	                    'data' => [
			                'confirm' => '您确定要删除这项吗？',
			                //'method' => 'post',
	           			 ],
	                ]);
	  			}?></td>
    </tr>
  <?php }?>
</table>
<?php }
if($leases) {
?>
<table class="table table-bordered table-hover">
  <tr>
    <td width="20%" colspan="2" align="center">承租人</td>
    <td width="12%" colspan="2" align="center">总面积</td>
    <td width="12%" align="center">操作</td>
    </tr>
  <?php foreach($leases as $val) {?>
  <tr>
    <td colspan="2" align="center"><?= $val['lessee'] ?></td>
     <td colspan="2"  align="center"><?= Lease::getListArea($val['lease_area'])?>亩</td>
    <?php 
    	  $plantings = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>$val['id']])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
    	  $sumArea = 0;
    	  foreach($plantings as $value) {
    	  	$sumArea += $value['area'];
    	  }
    	  $leaseSumArea = Lease::getListArea($val['lease_area']);
//     	  var_dump(bcsub($sumArea, $leaseSumArea));var_dump($leaseSumArea);
    ?>
    <td align="center"><?php if(bcsub($sumArea, $leaseSumArea)) {?><?= Html::a('添加','index.php?r=plantingstructure/plantingstructurecreate&lease_id='.$val['id'].'&farms_id='.$_GET['farms_id'], [
            			'id' => 'employeecreate',
            			'title' => '给'.$val['lessee'].'添加',
            			'class' => 'btn btn-primary',
            			]);?><?php }?></td>
    </tr>
  <?php 
  		
	  	foreach($plantings as $v) {
  ?>
  <tr>
    <td colspan="2" align="center">|_</td>
    <td align="center">种植面积：<?= $v['area']?>亩</td>
    <td align="center">作物：<?= Plant::find()->where(['id'=>$v['plant_id']])->one()['cropname']?></td>
    <td align="center">
    			<?php 
    			$controller = Yii::$app->controller->id;
    			$action = $controller.'view';
    			if(\Yii::$app->user->can($action)){
	    			echo Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'index.php?r=plantingstructure/plantingstructureview&id='.$v['id'].'&lease_id='.$v['lease_id'].'&farms_id='.$_GET['farms_id'], [
	                    'title' => Yii::t('yii', '查看'),
	                    'data-pjax' => '0',
	                ]);
    			}?>&nbsp;&nbsp;
    			<?php 
    			$controller = Yii::$app->controller->id;
    			$action = $controller.'update';
    			if(\Yii::$app->user->can($action)){
	    			echo Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'index.php?r=plantingstructure/plantingstructureupdate&id='.$v['id'].'&lease_id='.$v['lease_id'].'&farms_id='.$_GET['farms_id'], [
	                    'title' => Yii::t('yii', '更新'),
	                    'data-pjax' => '0',
	                ]);
	  			}?>&nbsp;&nbsp;
	  			<?php
	  			$controller = Yii::$app->controller->id;
	  			$action = $controller.'delete';
	  			if(\Yii::$app->user->can($action)){
		  			 	echo Html::a('<span class="glyphicon glyphicon-trash"></span>', 'index.php?r=plantingstructure/plantingstructuredelete&id='.$v['id'].'&farms_id='.$_GET['farms_id'], [
	                    'title' => Yii::t('yii', '删除'),
	                    'data-pjax' => '0',
	                    'data' => [
			                'confirm' => '您确定要删除这项吗？',
			                //'method' => 'post',
	           			 ],
	                ]);
	  			}?></td>
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
<script>
$('#rowjump').keyup(function(event){
	input = $(this).val();
	$.getJSON('index.php?r=farms/getfarmid', {id: input}, function (data) {
		$('#setFarmsid').val(data.farmsid);
	});
});
</script>
