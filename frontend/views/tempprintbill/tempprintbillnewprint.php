<?php
namespace frontend\controllers;

use app\models\User;
use frontend\helpers\MoneyFormat;
use yii;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tempprintbill */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'tempprintbill'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['tempprintbillindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tempprintbill-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
                    <?php $form = ActiveFormrdiv::begin(); ?>
    开票时间：<?= date('Y年m月d日 H时s分i秒',$model->create_at) ?>
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3" align="center"><h3>大兴安岭岭南宜农林地承包费专用票据</h3></td>
    </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('Y年m月d日',$model->create_at)?></td>
    <td align="right">NO:</td>
    <td width="30%"><?= $form->field($model, 'nonumber')->textInput()->label(false)->error(false) ?></td>
  </tr>
</table>
<table width="100%" border="1">
  <tr>
    <td width="14%" height="31" align="center">&nbsp;收款单位（缴款人）      </td>
    <td height="31" colspan="5">&nbsp;&nbsp;<?= $farm['farmname'].'('.$model->farmername.')&nbsp;&nbsp;&nbsp;&nbsp;合同号：'.$farm['contractnumber']?></td>
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
    <td>&nbsp;&nbsp;<?= MoneyFormat::num_format($model->amountofmoney)?></td>
  </tr>
  <tr>
  	<td align="right">备注：</td>
    <td colspan="5"><?= $form->field($model, 'remarks')->textInput()->label(false)->error(false) ?></td>
    </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="60%">收款单位（盖章）大兴安岭林业管理局岭南管委会</td>
    <td width="13%">收款人：<?= Yii::$app->getUser()->getIdentity()->realname?></td>
    <td width="27%" align="right">（微机专用 手填无效）</td>
  </tr>
</table>
                    <div class="form-group">
                        <?= Html::submitButton('提交打印', ['class' => $model->isNewRecord]) ?>
                    </div>

                    <?php ActiveFormrdiv::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
