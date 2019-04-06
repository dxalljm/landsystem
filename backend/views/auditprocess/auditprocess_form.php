<?php

use yii\helpers\Html;
use backend\helpers\ActiveFormrdiv;
use app\models\Processname;

/* @var $this yii\web\View */
/* @var $model app\models\Auditprocess */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auditprocess-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>项目名称</td>
<td align='left'><?= $form->field($model, 'projectname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>执行方法</td>
<td align='left'><?= $form->field($model, 'actionname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>流程</td>
<td align='left'><?php foreach (Processname::find()->all() as $value) {
	echo html::button($value['processdepartment'],['onclick'=>'setProcess("'.$value['processdepartment'].'","'.$value['Identification'].'")','id'=>$value['id']]).'&nbsp;&nbsp;&nbsp;';
}?></td>
</tr>
<tr>
<td width=15% align='right'>审核过程</td>
<td align='left'>
<?php echo html::textInput('temp-process',$processnamestr,['class'=>'form-control','id'=>'tempprocess'])?>
<?= $form->field($model, 'process')->hiddenInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
function setProcess(processname,Identification)
{
	var process = '';
	var tempprocess = '';
	//$('#'+id).attr('disabled',true);
	if($('#tempprocess').val() == '') {
		tempprocess = processname;
		process = Identification;
	}
	else {
		process = $('#auditprocess-process').val() + '>'+Identification;
		tempprocess = $('#tempprocess').val() + '>' + processname;
	}
	$('#tempprocess').val(tempprocess);
	$('#auditprocess-process').val(process);
}
</script>