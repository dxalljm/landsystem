<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperativetype;
/* @var $this yii\web\View */
/* @var $model app\models\Cooperative */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="cooperative-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
    
<table width="441" class="table table-bordered table-hover">
  <tr>
    <td width="42" align="right">合作社名称</td>
    <td width="344"><?= $form->field($model, 'cooperativename')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    <td width="39">&nbsp;</td>
  </tr>
  <tr>
    <td align="right">合作社类型</td>
    <td><?= $form->field($model, 'cooperativetype')->dropDownList(ArrayHelper::map(Cooperativetype::find()->all(), 'id', 'typename'),['prompt'=>'请选择'])->label(false)->error(false) ?></td>
    <td>
        <?php echo Html::a('+', '',['class' => 'btn btn-warning', 'data-toggle' => 'modal', 'data-target' => '#cooperative-form']) ?>
    </td>
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
  <tr>

	<td width=15% align='right'>注册资金</td>
	
	<td align='left'><?= $form->field($model, 'registered_capital')->textInput()->label(false)->error(false) ?></td>
	<td>&nbsp;</td>
	</tr>
	
	<tr>
	
	<td width=15% align='right'>分红模式</td>
	
	<td align='left'><?= $form->field($model, 'dividendmode')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
	<td>&nbsp;</td>
	</tr>
</table>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>


    <?php \yii\bootstrap\Modal::begin([
        'id' => 'cooperative-form',
        'size'=>'modal-lg',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
        'options' => ['data-keyboard' => 'false', 'data-backdrop' => 'static']
        //'header' => '<h4 class="modal-title"></h4>',
    ]);
    \yii\bootstrap\Modal::end(); ?>




<?php
$script = <<<JS
jQuery('#cooperativetypedelete').click(function(){
    var typeid = $('#cooperative-cooperativetype').val();
    $.get('/landsystem/frontend/web/index.php?r=cooperativetype/cooperativetypedelete',{id:typeid},function (data) {
		  $('body').html(data);
	});
});

$('#cooperative-form').on('show.bs.modal', function (e) {
    $.get('index.php?r=cooperativetype/cooperativetypecreateajax', {ajax: true}, function (body) {
		$('.modal-body').html(body);
    });
});

$(document).on("click", "#ajax-create", function () {
    var typename = $('#cooperativetype-typename').val();
    $.getJSON('index.php?r=cooperativetype/cooperativetypecreateajax', {typename: typename}, function (data) {
        if (data.status == 1) {
			$('#cooperative-form').modal('hide');
			$('#cooperative-cooperativetype').append('<option selected="selected" value="'+data.data[0]+'">'+data.data[1]+'</option>');
        } else {
            alert('合作社类型添加失败');
        }
    });
});

JS;
$this->registerJs($script);





?>
</div>
