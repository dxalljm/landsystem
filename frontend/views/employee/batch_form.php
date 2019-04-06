<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Lease;
use app\models\Employee;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">
    <?php $form = ActiveFormrdiv::begin(); ?>
    <div class="form-group">
        <h2><?php echo '为 '.Lease::find()->where(['id'=>$_GET['father_id']])->one()['lessee'].' 批量添加雇工';?></h2><?= Html::button('增加人员', ['class' => 'btn btn-info','title'=>'点击可增加雇工人员', 'id' => 'add-employee']) ?>
    </div>
<table class="table table-bordered table-hover" id="employee">
	
 <!-- 模板 -->

      <thead id="employee-template" class="d-none">
          <tr><?php echo Html::hiddenInput('EmployeesPost[id][]', '', ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('EmployeesPost[father_id][]', $_GET['father_id'], ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('EmployeesPost[farms_id][]', $_GET['farms_id'], ['class' => 'form-control']); ?>
              <td><?php echo Html::dropDownList('EmployeesPost[employeetype][]', '', ['长期工'=>'长期工','短期工'=>'短期工','临时工'=>'临时工'],['class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('EmployeesPost[employeename][]', '', ['class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('EmployeesPost[cardid][]', '', ['class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('EmployeesPost[telephone][]', '', ['class' => 'form-control']); ?></td>
              <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-employee']) ?></td>
          </tr>
      </thead>
	<tbody>
		<tr>
			<td width=15% align='center'>雇工类型</td>
			<td align='center'>雇工姓名</td>
			<td align='center'>身份证号</td>
			<td align='center'>联系电话</td>
			<td align='center'>操作</td>
		</tr>
		<?php 
		foreach($employees as $val) {
            $model->id = $val['id'];
            $model->farms_id = $val['farms_id'];
            $model->father_id = $val['father_id'];
			$model->employeetype = $val['employeetype'];
			$model->employeename = $val['employeename'];
			$model->cardid = $val['cardid'];
			$model->telephone = $val['telephone'];
		?>
		<tr>
			<td width=15% align='center'>
                <?= $form->field($model, 'id')->hiddenInput(['name' => 'EmployeesPost[id][]'])->label(false)->error(false) ?>
                <?= $form->field($model, 'father_id')->hiddenInput(['name' => 'EmployeesPost[father_id][]'])->label(false)->error(false) ?>
                <?= $form->field($model, 'farms_id')->hiddenInput(['name' => 'EmployeesPost[farms_id][]'])->label(false)->error(false) ?>
                <?= $form->field($model, 'employeetype')->dropDownList(['长期工'=>'长期工','短期工'=>'短期工','临时工'=>'临时工'], ['name' => 'EmployeesPost[employeetype][]'])->label(false)->error(false) ?>
            </td>
			<td align='center'><?= $form->field($model, 'employeename')->textInput(['maxlength' => 500, 'name' => 'EmployeesPost[employeename][]'])->label(false)->error(false) ?></td>
			<td align='center'><?= $form->field($model, 'cardid')->textInput(['maxlength' => 500, 'name' => 'EmployeesPost[cardid][]'])->label(false)->error(false) ?></td>
			<td align='center'><?= $form->field($model, 'telephone')->textInput(['maxlength' => 500, 'name' => 'EmployeesPost[telephone][]'])->label(false)->error(false) ?></td>
			<td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-employee']) ?></td>
		</tr>
		<?php }?>
	</tbody>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '更新' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('返回', [Yii::$app->controller->id.'fathers','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveFormrdiv::end(); ?>
<?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
</div>
<script type="text/javascript">

    // 添加雇工人员
    $('#add-employee').click(function () {
        var template = $('#employee-template').html();
        $('#employee > tbody').append(template);
    });

    // 删除
    $(document).on("click", ".delete-employee", function () {
        $(this).parent().parent().remove();
    });

    // 姓名
	$(document).on("blur", "input[name='EmployeesPost[employeename][]']", function () {
        if ($(this).val() == '') {
            return alert('姓名不能为空');
        }
    });

    // 身份证
	$(document).on("blur", "input[name='EmployeesPost[cardid][]']", function () {
        var val = $(this).val();
        if(/^[0-9X]/i.test(val) == false) {
            return alert('请填写正确的身份证号码');
        }
	});

</script>