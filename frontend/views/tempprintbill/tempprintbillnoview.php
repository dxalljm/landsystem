<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tempprintbill */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'tempprintbill'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['tempprintbillindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
       <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<div class="tempprintbill-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
     <p>
        
        <?= Html::a('打印', '#', ['class' => 'btn btn-success','onclick'=>'myPREVIEW()']) ?>
        <?= Html::a('打印设计','#', ['class' => 'btn btn-success','onclick'=>'myDesign()']) ?>
    </p>
    开票时间：<?= date('Y年m月d日 H时s分i秒',$model->create_at) ?>
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3" align="center"><h3>大兴安岭岭南宜农林地承包费专用票据</h3></td>
    </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('Y年m月d日',$model->create_at)?></td>
    <td align="right">NO:</td>
    <td width="30%"><?= $model->nonumber?></td>
  </tr>
</table>
<table width="100%" border="1">
  <tr>
    <td width="14%" height="31" align="center">&nbsp;收款单位（缴款人）      </td>
    <td height="31" colspan="5">&nbsp;&nbsp;<?= $model->farmername?></td>
    </tr>
  <tr>
    <td height="31" colspan="2" align="center">收费项目</td>
    <td width="13%" align="center">单位</td>
    <td width="18%" align="center">数量</td>
    <td width="17%" align="center">标准</td>
    <td width="21%" align="center">金额</td>
  </tr>
  <tr>
    <td height="23" colspan="2" align="center" valign="middle">      宜农林地承包费</td>
    <td align="center" valign="middle">      元/亩<br /></td>
    <td align="center" valign="middle"><?= $model->measure?></td>
    <td align="center" valign="middle"><?= $model->standard?></td>
    <td align="center" valign="middle"><?= $model->amountofmoney?></td>
  </tr>
  <tr>
    <td align="center">金额合计（大写）</td>
    <td colspan="3">&nbsp;&nbsp;<?= $model->bigamountofmoney?></td>
    <td align="right">￥：</td>
    <td>&nbsp;&nbsp;<?= $model->amountofmoney?></td>
  </tr>
  <tr>
  	<td align="right">备注：</td>
    <td colspan="5">&nbsp;&nbsp;<?= $model->remarks?></td>
    </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="60%">收款单位（盖章）大兴安岭林业管理局岭南管委会</td>
    <td width="13%">收款人：<?= Yii::$app->getUser()->getIdentity()->realname?></td>
    <td width="27%" align="right">（微机专用 手填无效）</td>
  </tr>
</table>
<script language="javascript" type="text/javascript"> 
var LODOP; //声明为全局变量
window.onload = function() { 
	CreatePage();
	LODOP.PREVIEW();
};
function myPREVIEW() {	
	CreatePage();
	LODOP.PREVIEW();
};
function myDesign() {	 	
	CreatePage();
	LODOP.PRINT_DESIGN();

};
function CreatePage() {
	LODOP=getLodop(); 
	LODOP.PRINT_INITA(10,10,"190mm","100.01mm","打印控件功能演示_Lodop功能_移动公司发票全样");
	LODOP.ADD_PRINT_SETUP_BKIMG("D:\\wamp\\www\\landsystem\\frontend\\web\\images\\IMG_2015.jpg");
	LODOP.SET_SHOW_MODE("BKIMG_LEFT",8);
	LODOP.SET_SHOW_MODE("BKIMG_TOP",-29);
	LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",true);
	LODOP.SET_PRINT_STYLE("FontColor","#0000FF");
	LODOP.ADD_PRINT_TEXT(51,134,40,20,"<?= date('Y',$model->create_at)?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(51,188,23,20,"<?= date('m',$model->create_at)?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(52,231,25,20,"<?= date('d',$model->create_at)?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(-8,67,290,20,"注：电子票号与纸质票号不一致则为无效票");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(76,212,200,21,"<?= $model->farmername?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(136,146,115,20,"宜林农地承包费");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(136,293,44,20,"元/亩");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(136,363,74,20,"<?= $model->measure?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(136,482,38,20,"<?= $model->standard?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(136,582,100,20,"<?= MoneyFormat::num_format($model->amountofmoney)?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(254,544,100,20,"<?= MoneyFormat::num_format($model->amountofmoney)?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
	LODOP.ADD_PRINT_TEXT(254,205,290,20,"<?= $model->bigamountofmoney?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(309,199,202,20,"大兴安岭林业管理局岭南管委会");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(310,450,69,20,"<?= Yii::$app->getUser()->getIdentity()->realname?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(-8,506,185,20,"电子票号：<?= $model->nonumber?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
	LODOP.ADD_PRINT_TEXT(288,136,518,20,"<?= $model->remarks?>");
	LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
};
</script> 
                
                </div>
            </div>
        </div>
    </div>
</section>
</div>
