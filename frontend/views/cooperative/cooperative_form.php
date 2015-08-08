<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperativetype;
/* @var $this yii\web\View */
/* @var $model app\models\Cooperative */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="cooperative-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
    
    <?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false); ?>
<table width="441" class="table table-striped table-bordered table-hover table-condensed">
  <tr>
    <td width="42" align="right">合作社名称</td>
    <td width="344"><?= $form->field($model, 'cooperativename')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    <td width="39">&nbsp;</td>
  </tr>
  <tr>
    <td align="right">合作社类型</td>
    <td><?= $form->field($model, 'cooperativetype')->dropDownList(ArrayHelper::map(Cooperativetype::find()->all(), 'id', 'typename'),['prompt'=>'请选择'])->label(false)->error(false) ?></td>
    <td><?php echo Html::a('+', 'index.php?r=cooperativetype/cooperativetypecreate&farms_id='.$_GET['farms_id'],['class' => 'btn btn-warning delete-member-family']) ?></td>
  </tr>
  <tr>
    <td align="right">理事长姓名</td>
    <td><?= $form->field($model, 'directorname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">入社人数</td>
    <td><?= $form->field($model, 'peoples')->textInput()->label(false)->error(false) ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">财务报表</td>
    <td><?= $form->field($model, 'finance')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    <td>&nbsp;</td>
  </tr>
</table>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
