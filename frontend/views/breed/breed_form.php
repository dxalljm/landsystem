<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Breed */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="breed-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
	<tr>
		<td width=15% align='right'>养殖场名称</td>
		<td align='right'><?= $form->field($model, 'breedname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		<td align='right'>养殖位置</td>
		<td align='right'><?= $form->field($model, 'breedaddress')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		<td align='right'>是否示范户</td>
		<td align='right'><?= $form->field($model, 'is_demonstration')->radioList([1=>'是',0=>'否'])->label(false)->error(false) ?></td>
	</tr>
</table>
<div class="form-group">
        <?= Html::button('增加养殖种类', ['class' => 'btn btn-info','title'=>'点击可增加一行养殖种类', 'id' => 'add-breedtype']) ?>
    </div>
<table class="table table-bordered table-hover" id="breedtype">
	
 <!-- 模板 -->

      <thead id="breedtype-template" class="d-none">
          <tr>
			  <?php echo Html::hiddenInput('breedtypePost[id][]', '', ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('breedtypePost[breed_id][]', '', ['class' => 'form-control']); ?>

              <td width="15%"><?php echo Html::dropDownList('breedtypePost[father_id][]', '', ArrayHelper::map($breedtypeFather, 'id', 'typename'),['prompt'=>'请选择...', 'id'=>'breedtype-father_id','class' => 'form-control']); ?></td>
              <td><?php echo Html::dropDownList('breedtypePost[breedtype_id][]', '',['prompt'=>'请选择...'], ['id'=>'breedtype-breedtype_id', 'class' => 'form-control']); ?></td>
              <td><?php echo Html::a('+', '',['class' => 'btn btn-warning', 'data-toggle' => 'modal', 'data-target' => '#breedtype-form']) ?></td>
              <td><?php echo Html::textInput('breedtypePost[number][]', '',['id'=>'breedtype-number', 'class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('breedtypePost[basicinvestment][]', '',['id'=>'breedtype-basicinvestment','class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('breedtypePost[housingarea][]', '', ['id'=>'breedtype-housingarea','class' => 'form-control']); ?></td>
              <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-breedtype']) ?></td>
              <td valign="middle" align="center">&nbsp;</td>
          </tr>
      </thead>
	<tbody>
		<tr>
			<td colspan="3" align='center'>养殖种类</td>
			<td align='center'>养殖数量</td>
            <td align='center'>基础投资</td>
			<td align='center'>圈舍面积</td>
			<td align='center'>操作</td>
		</tr>
		

		
	</tbody>
</table>
<div class="form-group">
      <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<?php \yii\bootstrap\Modal::begin([
        'id' => 'breedtype-form',
        'size'=>'modal-lg',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
        'options' => ['data-keyboard' => 'false', 'data-backdrop' => 'static']
        //'header' => '<h4 class="modal-title"></h4>',
    ]);
    \yii\bootstrap\Modal::end(); ?>
<script>
//添加养殖种类
$('#add-breedtype').click(function () {
    var template = $('#breedtype-template').html();
    $('#breedtype > tbody').append(template);
});

// 删除
$(document).on("click", ".delete-breedtype", function () {
    $(this).parent().parent().remove();
});

$(document).on("change", "select[name='breedtypePost[father_id][]']", function () {
	// 投入品子类，投入品
	var fertilizerChild = $(this).parent().next().children(),
		father_id = $(this).val();
	// 请求二级分类
	$.getJSON('index.php?r=breedtype/getbreedtypeson', {father_id: father_id}, function (data) {
		alert('55555');
		fertilizerChild.html(null);
		fertilizerChild.append('<option value="prompt">请选择</option>');
		if (data.status == 1) {
			alert('3334');
			for(i = 0; i < data.breedtypeSon.length; i++) {
				alert(data.breedtypeSon[i]['typename']);
				fertilizerChild.append('<option value="'+data.breedtypeSon[i]['id']+'">'+data.breedtypeSon[i]['typename']+'</option>');
			}
		}
	});
});
</script>