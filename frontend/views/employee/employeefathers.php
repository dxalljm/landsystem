<?php
namespace frontend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Employee;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\employeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'employee';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<table class="table table-striped table-bordered table-hover table-condensed">
  <tr>
    <td width="20%" colspan="2" align="center">承租人</td>
    <td colspan="2" align="center">租赁面积</td>
    <td align="center">操作</td>
    </tr>
  <?php foreach($lease as $val) {?>
  <tr>
    <td colspan="2" align="center"><?= $val['lessee'] ?></td>
    <td colspan="2" align="center"><?= $val['lease_area'] ?></td>
    <td align="center"><?= Html::a('雇佣','index.php?r=employee/employeecreate&father_id='.$val['id'], [
            			'id' => 'employeecreate',
            			'title' => '给'.$val['lessee'].'添加雇工人员',
            			//'class' => 'btn btn-primary',
            			]).'&nbsp;&nbsp;&nbsp;&nbsp;'.Html::a('批量雇佣','index.php?r=employee/employeecreate&father_id='.$val['id'], [
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
    <td width="15%" align="center"><?= GridView::widget([
        
        'columns' => [
           


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?></td>
    </tr>
  <?php }}?>
</table>

</div>
