<?php
namespace frontend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Employee;
use yii\grid\ActionColumn;
use Yii;
use app\models\Lease;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\employeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'employee';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

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
    <?php Farms::showRow($_GET['farms_id']);?>
<?php 
		$leaseSumArea = 0;
		$strArea = '';
		$arrayArea = [];
		
		foreach ($lease as $value) {	
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
    <td width="20%" colspan="2" align="center"><?= $farms->farmername?></td>
    <td align="center"><?= implode('、',$arrayZongdi)?></td>
    <td align="center"><?= round($farms['measure'] - $leaseSumArea)?>亩</td>
    
    <td align="center"><?= Html::a('雇佣','index.php?r=employee/employeecreate&father_id=0&farms_id='.$_GET['farms_id'], [
            			'id' => 'employeecreate',
            			'title' => '给'.$farms->farmername.'添加雇工人员',
            			//'class' => 'btn btn-primary',
            			]).'&nbsp;&nbsp;&nbsp;&nbsp;'.Html::a('批量雇佣','index.php?r=employee/employeebatch&father_id=0&farms_id='.$_GET['farms_id'], [
            			'id' => 'employeecreates',
            			'title' => '给'.$farms->farmername.'批量添加雇工人员',
            			//'class' => 'btn btn-primary',
            			]);?></td>
  </tr>
  <?php 
  		$employee = Employee::find()->where(['father_id'=>0])->all();
	  	foreach($employee as $emp) {
  ?>
  
  <tr>
    <td align="right">|_</td>
    <td width="9%" align="center"><?= $emp['employeename']?></td>
    <td width="15%" align="center"><?= $emp['employeetype']?></td>
    <td width="15%" align="center"><?= $emp['cardid']?></td>
    <td width="15%" align="center"><?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'index.php?r=employee/employeeview&id='.$emp['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                ]);?>&nbsp;&nbsp;<?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'index.php?r=employee/employeeupdate&id='.$emp['id'].'&father_id=0&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', 'update'),
                    'data-pjax' => '0',
                ]);?>&nbsp;&nbsp;<?= Html::a('<span class="glyphicon glyphicon-trash"></span>', 'index.php?r=employee/employeedelete&id='.$emp['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', 'delete'),
                    'data-pjax' => '0',
                ]);?></td>
    </tr>
    <?php }?>
    <?php }?>
    <table class="table table-bordered table-hover">
  <tr>
    <td width="20%" colspan="2" align="center">承租人</td>
    <td align="center">宗地</td>
    <td width="12%" align="center">总面积</td>
    <td width="12%" align="center">操作</td>
  </tr>
  <?php foreach($lease as $val) {?>
  <tr>
    <td colspan="2" align="center"><?= $val['lessee'] ?></td>
    <td align="center"><?= $val['lease_area'] ?></td>
    <td align="center"><?= Lease::getListArea($val['lease_area'])?>亩</td>
    <td align="center"><?= Html::a('雇佣','index.php?r=employee/employeecreate&father_id='.$val['id'].'&farms_id='.$_GET['farms_id'], [
            			'id' => 'employeecreate',
            			'title' => '给'.$val['lessee'].'添加雇工人员',
            			//'class' => 'btn btn-primary',
            			]).'&nbsp;&nbsp;&nbsp;&nbsp;'.Html::a('批量雇佣','index.php?r=employee/employeebatch&father_id='.$val['id'].'&farms_id='.$_GET['farms_id'], [
            			'id' => 'employeecreates',
            			'title' => '给'.$val['lessee'].'批量添加雇工人员',
            			//'class' => 'btn btn-primary',
            			]);?></td>
    </tr>
  <?php 
  		$employee = Employee::find()->where(['father_id'=>$val['id']])->all();
	  	foreach($employee as $emp) {
  ?>
  <tr>
    <td align="right">|_</td>
    <td width="9%" align="center"><?= $emp['employeename']?></td>
    <td width="15%" align="center"><?= $emp['employeetype']?></td>
    <td width="15%" align="center"><?= $emp['cardid']?></td>
    <td width="15%" align="center"><?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'index.php?r=employee/employeeview&id='.$emp['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                ]);?>&nbsp;&nbsp;<?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'index.php?r=employee/employeeupdate&id='.$emp['id'].'&father_id='.$val['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', 'update'),
                    'data-pjax' => '0',
                ]);?>&nbsp;&nbsp;<?= Html::a('<span class="glyphicon glyphicon-trash"></span>', 'index.php?r=employee/employeedelete&id='.$emp['id'].'&farms_id='.$_GET['farms_id'], [
                    'title' => Yii::t('yii', 'delete'),
                    'data-pjax' => '0',
                ]);?></td>
    </tr>
  <?php }}?>
</table>
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