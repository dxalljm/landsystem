<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\ManagementArea;
use app\models\Nation;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use app\models\Plant;

/* @var $this yii\web\View */
/* @var $model app\models\farmer */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
.farmer-form h4 {
	color: #F00;
}
</style>


<div class="farmer-form">

  <p>农场信息 </p>
    <table class="table table-striped table-bordered table-hover table-condensed">
      <tr>
        <td width="174" align="right">农场名称：</td>
        <td width="250" align="left">&nbsp;          <?= $farm->farmname;?></td>
        <td width="144" align="right">管理区：</td>
        <td width="282" align="left">&nbsp;<?= ManagementArea::find()->where(['id'=>$farm->management_area])->one()['areaname'];?></td>
      </tr>
      <tr>
        <td align="right">承包年限：</td>
        <td align="left">&nbsp;          <?= $farm->contractlife;?></td>
        <td align="right">审批年度：</td>
        <td align="left">&nbsp;          <?= $farm->spyear;?></td>
      </tr>
      <tr>
        <td align="right">面积：</td>
        <td align="left">&nbsp;          <?= $farm->measure;?></td>
        <td align="right">宗地：</td>
        <td align="left">&nbsp;          <?= $farm->zongdi;?></td>
      </tr>
      <tr>
        <td align="right">农场位置：</td>
        <td colspan="3" align="left">&nbsp;<?= $farm->address;?>
        </td>
      </tr>
    </table>
    <br>
   <p>承包人信息</p>
   <table class="table table-striped table-bordered table-hover table-condensed">
      <tr>
        <td width="170" height="25" align="right">承包人姓名：</td>
        <td width="85"><?= '&nbsp;'.$model->farmername; ?></td>
        <td width="73" align="right">曾用名：</td>
        <td width="78"><?= '&nbsp;'.$model->farmerbeforename; ?></td>
        <td width="82" height="25" align="right">绰号：</td>
        <td width="173"><?= '&nbsp;'.$model->nickname; ?></td>
        <td width="183" rowspan="6" align="center"><?= '&nbsp;'.Html::img($model->photo,['width'=>'180px','height'=>'200px']); ?></td>
     </tr>
      <tr>
        <td align="right">身份证号：</td>
        <td colspan="3"><?= '&nbsp;'.$model->cardid; ?></td>
        <td align="right">性别：</td>
        <td><?= '&nbsp;'.$model->gender; ?></td>
      </tr>
      <tr>
        <td align="right">民族：</td>
        <td colspan="3"><?= '&nbsp;'.$model->nation; ?></td>
        <td align="right">政治面貌：</td>
        <td><?= '&nbsp;'.$model->political_outlook; ?></td>
      </tr>
      <tr>
        <td align="right">文化程度：</td>
        <td colspan="3"><?= '&nbsp;'.$model->cultural_degree; ?></td>
        <td align="right">电话：</td>
        <td><?= '&nbsp;'.$model->telephone; ?></td>
      </tr>
      <tr>
        <td align="right">现住地：</td>
        <td colspan="5"><?= '&nbsp;'.$model->nowlive; ?></td>
      </tr>
	  <tr>
        <td align="right">户籍所在地：</td>
        <td colspan="5"><?= '&nbsp;'.$model->domicile; ?></td>
      </tr>
      <tr>
        <td align="right">身份证扫描件：</td>
        <td colspan="6"><?= '&nbsp;'.Html::img($model->cardpic,['width'=>'400px','height'=>'220px']); ?></td>
      </tr>
  </table>
  <br>
  <p>宜林农地租赁</p>

  <table class="table table-striped table-bordered table-hover table-condensed">
  <tr>
    <td align="center">承租人</td>
    <td align="center">租凭面积</td>
    <td align="center">种植结构</td>
    </tr>
<?php foreach($lease as $val) {?>
  <tr>
    <td align="center">&nbsp;<?= $val['lessee']?></td>
    <td align="center">&nbsp;<?= $val['lease_area']?></td>
    <td align="center">&nbsp;<?= Plant::find()->where(['id'=>$val['plant_id']])->one()['cropname']?></td>
    </tr>
  <tr>
<?php }?>
</table>
</div>
<script type="text/javascript">

function submittype(v) {
	$('#farmer-isupdate').val(v);
}

</script>

