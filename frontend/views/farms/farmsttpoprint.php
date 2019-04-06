<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Parcel;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Reviewprocess;
use app\models\Processname;
/* @var $this yii\web\View */
/* @var $model app\models\Farms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farms-print">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table width="100%" border="1" cellpadding="0" class="ttpoprint">
<thead align="center"><h1>宜农林地承包经营权转让审批表</h1><td width="20%"></thead>
  <tr>
    <td align="center">原农场名称</td>
    <td width="13%" align="center">原法人</td>
    <td width="17%" align="center">原面积</td>
    <td width="22%" align="center">现农场名称</td>
    <td width="14%" align="center">现法人</td>
    <td width="14%" align="center">现面积</td>
  </tr>
  <tr>
    <td align="center"><?= $oldfarm->farmname?></td>
    <td align="center"><?= $oldfarm->farmername?></td>
    <td align="center"><?= $oldfarm->measure?></td>
    <td align="center"><?= $newfarm->farmname?></td>
    <td align="center"><?= $newfarm->farmername?></td>
    <td align="center"><?= $newfarm->measure?></td>
  </tr>
  <?php foreach ($process as $value) { ?>
  <tr>
    <td align="center"><p><?= Processname::find()->where(['Identification'=>$value])->one()['processdepartment']?><br />
    意&nbsp;&nbsp;&nbsp;&nbsp;见</p></td>
    <td colspan="5" align="center">&nbsp;</td>
    </tr>
  <?php }?>
</table>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>