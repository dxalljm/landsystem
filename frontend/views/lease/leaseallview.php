<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ManagementArea;
use app\models\Cooperative;
/* @var $this yii\web\View */
/* @var $model app\models\Lease */

?>
<div class="lease-view">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        租赁情况明细
                    </h3>
                </div>
                <div class="box-body">

    <table class="table table-bordered table-condensed">
  <tr>
    <td colspan="5" align="center"><h4>承租人基础信息</h4></td>
    </tr>
  <tr>
    <td align="center">承租人姓名</td>
    <td align="center">身份证号</td>
    <td align="center">电话</td>
    <td align="center">租赁面积</td>
    <td align="center">租赁期限</td>
      <td align="center"></td>
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
            </div>
        </div>
    </div>
</section>
</div>
