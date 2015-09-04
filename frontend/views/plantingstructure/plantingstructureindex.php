<?php

use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plant;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="lease-index">

    <h1><?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']; ?> 的种植结构</h1>

	<script type="text/javascript">
	function openwindows(url)
	{
		window.open(url,'','width=1200,height=600,top=50,left=80, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');
		self.close();
	}
	</script>
<table class="table table-striped table-bordered table-hover table-condensed">
  <tr>
    <td width="20%" colspan="2" align="center">承租人</td>
    <td colspan="2" align="center">租赁面积</td>
    <td align="center">操作</td>
    </tr>
  <?php foreach($leases as $val) {?>
  <tr>
    <td colspan="2" align="center"><?= $val['lessee'] ?></td>
    <td colspan="2" align="center"><?= $val['lease_area'] ?></td>
    <td align="center"><?= Html::a('添加','index.php?r=plantingstructure/plantingstructurecreate&lease_id='.$val['id'].'&farms_id='.$_GET['farms_id'], [
            			'id' => 'employeecreate',
            			'title' => '给'.$val['lessee'].'添加雇工人员',
            			'class' => 'btn btn-primary',
            			]);?></td>
    </tr>
  <?php 
  		$plantings = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>$val['id']])->all();
	  	foreach($plantings as $v) {
  ?>
  <tr>
    <td align="right">|_</td>
    <td width="9%" align="center">宗地：<?= $v['zongdi']?></td>
    <td width="15%" align="center">种植面积：<?= $v['area']?>亩</td>
    <td width="15%" align="center">作物：<?= Plant::find()->where(['id'=>$v['plant_id']])->one()['cropname']?></td>
    <td width="15%" align="center"><?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'index.php?r=plantingstructure/plantingstructureview&id='.$v['id'].'&farms_id='.$_GET['farms_id'], [
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
</div>

