<?php
/* @var $this yii\web\View */
namespace frontend\controllers;use app\models\User;
use Yii;
use yii\helpers\Html;
use app\models\Farms;

$this->title = '岭南管委会';
?>
<div class="site-index">

        <h1>欢迎您 <?= yii::$app->user->identity->username?></h1>

<!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-envelope-o"></i></span>
  <div class="info-box-content">
    <span class="info-box-text">您所管辖的管理区（<?= $areaname?>）</span>
    <span class="info-box-number">共有农场<?= $farmsRows?>个,面积<?= $sumMeasure?>亩</span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: <?= $sumMeasure/$allArea*100 ?>%"></div>
    </div>
    <span class="progress-description">
      占总面积 <?= $allArea?>亩 的<?= number_format($sumMeasure/$allArea*100,2) ?>%
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box -->
<br><br><br>
<?php $action = Yii::$app->controller->action->id;?>
    <div class="body-content">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <?php if(\Yii::$app->user->can('farmsindex')){?>
    <td width="20%" align="center"><?=  Html::a(html::img('images/farmsgl.png',['width'=>'150']),yii::$app->urlManager->createUrl(['/farms/farmsindex']), [
            			'id' => 'farmerview',
            			'title' => '承包信息',
            			'target' => '_black',

             	]);?></td><?php } ?>
             	<?php if(\Yii::$app->user->can('farmsbusiness')){?>
    <td width="20%" align="center"><?=  Html::a(html::img('images/ywbl.png',['width'=>'150']),yii::$app->urlManager->createUrl(['/farms/farmsbusiness']), [
            			'id' => 'farmerview',
            			'title' => '转让信息',
            			'target' => '_black',

             	]);?></td><?php } ?>
             	<?php if(\Yii::$app->user->can('collectionindex')){?>
    <td width="20%" align="center"><?=  Html::a(html::img('images/cbfsj.png',['width'=>'150']),yii::$app->urlManager->createUrl(['/collection/collectionindex']), [
            			'id' => 'farmerview',
            			'title' => '转让信息',
            			'target' => '_black',

             	]);?></td><?php } ?>
             	<?php if(\Yii::$app->user->can('bankaccountindex')){?>
   <td width="20%" align="center"><?=  Html::a(html::img('images/zhgl.png',['width'=>'150']),yii::$app->urlManager->createUrl(['/bank/bankaccountindex']), [
            			'id' => 'farmerview',
            			'title' => '转让信息',
            			'target' => '_black',

             	]);?></td><?php } ?>
             	<?php if(\Yii::$app->user->can('plantpriceindex')){?>
   <td width="20%" align="center"><?=  Html::a(html::img('images/jfjs.png',['width'=>'150']),yii::$app->urlManager->createUrl(['/plantprice/plantpriceindex']), [
            			'id' => 'farmerview',
            			'title' => '转让信息',
            			'target' => '_black',

             	]);?></td><?php } ?>
  </tr>
  </table>
    </div>
</div>
