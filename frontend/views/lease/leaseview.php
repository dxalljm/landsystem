<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Lease;

/* @var $this yii\web\View */
/* @var $model app\models\Lease */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'lease'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['leaseindex','farms_id'=>$_GET['farms_id']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lease-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
<?php if($noarea) {?>
    <p>
    	 <?= Html::a('添加', ['leasecreate', 'id' => $model->id,'farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['leaseupdate', 'id' => $model->id,'farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['leasedelete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除这项吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>
<?php }?>
    <table class="table table-bordered table-hover">
  <tr>
    <td width="80" align="center">农场名称</td>
    <td colspan="2" align="center"><?= $farm->farmname?></td>
    <td align="center">法人</td>
    <td colspan="2" align="center"><?= $farm->farmername?></td>
    <td width="107" align="center">宜农林地面积</td>
    <td width="106" align="center"><?= $farm->measure?>亩</td>
  </tr>
  <tr>
    <td colspan="8" align="center"><h4>承租人基础信息</h4></td>
  </tr>
  <tr>
    <td align="center">承租人姓名</td>
    <td colspan="6"><?= $model->lessee ?></td>
    <td rowspan="5" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">身份证号</td>
    <td colspan="6"><?= $model->lessee_cardid; ?></td>
    </tr>
  <tr>
    <td align="center">电话</td>
    <td colspan="6"><?= $model->lessee_telephone ?></td>
    </tr>
  <tr>
    <td align="center">租赁面积</td>
    <td colspan="6"><?= $model->lease_area.'&nbsp;&nbsp;&nbsp;&nbsp;共'.Lease::getListArea($model->lease_area).'亩' ?></td>
    </tr>
  <tr>
    <td align="center">租赁期限</td>
    <td width="19" align="center">自</td>
    <td width="61" align="center"><?= $model->begindate?></td>
    <td width="22" align="center">至</td>
    <td width="64" align="center"><?= $model->enddate?></td>
    <td width="16" align="center">止</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
