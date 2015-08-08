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
    
<table width="441" class="table table-striped table-bordered table-hover table-condensed">
  <tr>
    <td width="42" align="right">合作社名称</td>
    <td width="344"><?= $form->field($model, 'cooperativename')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    <td width="39">&nbsp;</td>
  </tr>
  <tr>
    <td align="right">合作社类型</td>
    <td><?= $form->field($model, 'cooperativetype')->dropDownList(ArrayHelper::map(Cooperativetype::find()->all(), 'id', 'typename'),['prompt'=>'请选择'])->label(false)->error(false) ?></td>
    <td><?php echo Html::a('+', 'index.php?r=cooperativetype/cooperativetypecreate',['class' => 'btn btn-warning delete-member-family']) ?> <?php echo Html::a('-', '#',['id'=>'cooperativetypedelete','class' => 'btn btn-warning delete-member-family']) ?></td>
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
<?php
$script = <<<JS
jQuery('#cooperativetypedelete').click(function(){
    var typeid = $('#cooperative-cooperativetype').val();
    $.get('/landsystem/frontend/web/index.php?r=cooperativetype/cooperativetypedelete',{id:typeid},function (data) {
		  $('body').html(data);
	});

});
JS;
$this->registerJs($script);
?>
</div>
