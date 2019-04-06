<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Plantingstructure;
use app\models\Inputproduct;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\Plantinputproduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plantinputproduct-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<div class="form-group">
        <?= Html::button('增加投入品', ['class' => 'btn btn-info','title'=>'点击可增加一行投入品', 'id' => 'add-plantinputproduct']) ?>
    </div>
<table class="table table-bordered table-hover" id="plantinputproduct">
	
 <!-- 模板 -->

      <thead id="plantinputdroduct-template" class="d-none">
          <tr>
			  <?php echo Html::hiddenInput('PlantInputproductPost[id][]', '', ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[farms_id][]', $planting->farms_id, ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[lessee_id][]', $planting->lease_id, ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[zongdi][]', $planting->zongdi, ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('PlantInputproductPost[plant_id][]', $planting->plant_id, ['class' => 'form-control']); ?>
              <td><?php echo Html::dropDownList('PlantInputproductPost[father_id][]', '', ArrayHelper::map(Inputproduct::find()->where(['father_id'=>1])->all(), 'id', 'fertilizer'),['prompt'=>'请选择...', 'class'=>'plantinputproduct-father_id','class' => 'form-control']); ?></td>
              <td><?php echo Html::dropDownList('PlantInputproductPost[son_id][]', '',['prompt'=>'请选择...'], ['id'=>'plantinputproduct-son_id', 'class' => 'form-control']); ?></td>
              <td><?php echo Html::dropDownList('PlantInputproductPost[inputproduct_id][]', '',['prompt'=>'请选择...'] ,['id'=>'plantinputproduct-inputproduct_id','class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('PlantInputproductPost[pconsumption][]', '', ['id'=>'plantinputproduct-pconsumption','class' => 'form-control']); ?></td>
              <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-employee']) ?></td>
          </tr>
      </thead>
	<tbody>
		<tr>
			<td width=15% align='center'>投入品父类</td>
			<td align='center'>投入品子类</td>
			<td align='center'>投入品</td>
            <td align='center'>投入品用量</td>
			<td align='center'>操作</td>
		</tr>
		

		
	</tbody>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>
<?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
</div>
<script type="text/javascript">

    // 添加雇工人员
    $('#add-plantinputproduct').click(function () {
        var template = $('#plantinputdroduct-template').html();
        $('#plantinputproduct > tbody').append(template);
    });

    // 删除
    $(document).on("click", ".delete-employee", function () {
        $(this).parent().parent().remove();
    });


	// 投入品子类联动
	$(document).on("change", "select[name='PlantInputproductPost[father_id][]']", function () {
		// 投入品子类，投入品
		var fertilizerChild = $(this).parent().next().children(),
			father_id = $(this).val();

		// 请求二级分类
		$.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
			fertilizerChild.html(null);
			fertilizerChild.append('<option value="prompt">请选择</option>');
			if (data.status == 1 && data.inputproductson !== null) {
				for(i = 0; i < data.inputproductson.length; i++) {
					fertilizerChild.append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
				}
			}
		});
	});

	// 投入品选择
	$(document).on("change", "select[name='PlantInputproductPost[son_id][]']", function () {
		// 投入品子类，投入品
		var product = $(this).parent().next().children(),
		father_id = $(this).val();

		// 请求二级分类
		$.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
			product.html(null);
			product.append('<option value="prompt">请选择</option>');
			if (data.status == 1 && data.inputproductson !== null) {
				for(i = 0; i < data.inputproductson.length; i++) {
					product.append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
				}
			}
		});
	});

</script>

