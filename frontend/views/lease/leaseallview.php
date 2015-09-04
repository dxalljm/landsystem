<?php
namespace frontend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ManagementArea;
use app\models\Cooperative;
/* @var $this yii\web\View */
/* @var $model app\models\Lease */

?>
<div class="lease-view">

    <h1>租赁情况明细</h1>

<table class="table table-striped table-bordered table-hover table-condensed">
      <tr>
        <td width="128" align="right">管理区：</td>
        <td width="193" align="left"><?= ManagementArea::find()->where(['id'=>$farm->management_area])->one()['areaname']?></td>
        <td width="92" align="right">农场名称：</td>
        <td width="278" align="left"><?= $farm->farmname?></td>
      </tr>
      <tr>
        <td align="right">合作社</td>
        <td align="left">&nbsp;
          <?= Cooperative::find()->where(['id'=>$farm->cooperative_id])->one()['cooperativename']?></td>
        <td align="right">审批年度：</td>
        <td align="left">&nbsp;
          <?= $farm->spyear;?></td>
      </tr>
      <tr>
        <td align="right">面积：</td>
        <td align="left">&nbsp;
          <?= $farm->measure.'亩'?></td>
        <td align="right">宗地：</td>
        <td align="left">&nbsp;
          <?= $farm->zongdi;?></td>
      </tr>
      <tr>
        <td align="right">农场位置：</td>
        <td colspan="3" align="left">&nbsp;
          <?= $farm->address;?></td>
      </tr>
  </table>
    <table class="table table-striped table-bordered table-hover table-condensed">
  <tr>
    <td colspan="5" align="center"><h4>承租人基础信息</h4></td>
    </tr>
  <tr>
    <td align="center">承租人姓名</td>
    <td align="center">身份证号</td>
    <td align="center">电话</td>
    <td align="center">租赁面积</td>
    <td align="center">租赁期限</td>
  </tr>
  <?php foreach($leases as $lease) {?>
  <tr>
    <td align="center"><?= $lease['lessee']?></td>
    <td align="center"><?= $lease['lessee_cardid']?></td>
    <td align="center"><?= $lease['lessee_telephone']?></td>
    <td align="center"><?= $lease['lease_area']?></td>
    <td align="center"><?= '从'.$lease['begindate'].'至'.$lease['enddate'].'止'?></td>
  </tr>
  <?php }?>
</table>

</div>
