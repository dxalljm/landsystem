<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Collection;

/* @var $this yii\web\View */
/* @var $model app\models\Tempprintbill */
?>
<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
       <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<div class="collection-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $farm->farmname.'('.$farm->farmername.')' ?>历年承包费收缴明细
                    </h3>
                </div>
                <div class="box-body">
     <table class="table table-striped table-bordered table-hover table-condensed">
  <tr>
    <td align="center">应追缴年度</td>
    <td align="center">实收金额</td>
    <td align="center">应追缴费面积</td>
    <td align="center">应追缴费金额</td>
    <td align="center">剩余欠缴金额</td>
    <td align="center">状态</td>
  </tr>

  <?php //var_dump($collectiondataProvider);?>
  <?php foreach($collectiondataProvider as $val) {?>
  <tr>
    <td align="center"><?= $val['ypayyear']?></td>
    <td align="center"><?= $val['real_income_amount']?></td>
    <td align="center"><?= $val['ypayarea']?></td>
    <td align="center"><?= $val['ypaymoney']?></td>
    <td align="center"><?= Collection::getOwe($farm->id,$val['ypayyear'])?></td>
    <td align="center"><?php if($val['state'] == 1) echo '已缴纳'; else echo '未缴纳';?></td>
  </tr>
  <?php }?>
</table>
                
                </div>
            </div>
        </div>
    </div>
</section>
</div>
