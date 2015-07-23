<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = 'My Yii Application';
?>
<div class="site-index">


        <h1>欢迎您 <?= yii::$app->user->identity->username?></h1>
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
