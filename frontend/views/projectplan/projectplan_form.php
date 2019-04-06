<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\helpers\ActiveFormrdiv;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Farms;
use app\models\Projectapplication;
use app\models\Projecttype;
use app\models\Infrastructuretype;
/* @var $this yii\web\View */
/* @var $model app\models\Projectplan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projectplan-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
    <?= Farms::showFarminfo2($project->farms_id)?>
<table class="table table-bordered table-hover">
<tr>
<td align="right" style="vertical-align: middle;">项目类型：</td>
<td style="vertical-align: middle;"><?= Infrastructuretype::getNameById($project->projecttype)?></td>
<td align="right" style="vertical-align: middle;">数量：</td>
<td style="vertical-align: middle;"><?= $project->projectdata.$project->unit?></td>

<td width=15% align='right'>开始日期：</td><?php if($model->begindate) $model->begindate = date('Y-m-d',$model->begindate);if($model->enddate) $model->enddate = date('Y-m-d',$model->enddate);?>
<td align='left'><?= $form->field($model, 'begindate')->textInput()->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 3,
        	'maxView' => 3,
            'format' => 'yyyy-mm-dd'
        ]])  ?></td>
        <td width=15% align='right'>结束日期：</td>
<td align='left'><?= $form->field($model, 'enddate')->textInput()->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 3,
        	'maxView' => 3,
            'format' => 'yyyy-mm-dd'
        ]])  ?></td>
</tr>
<tr>
<td align="right" style="vertical-align: middle;">补贴金额：</td>
<td style="vertical-align: middle;"><?= $form->field($model, 'money')->textInput()->label(false)->error(false)?></td>
<td align="right" style="vertical-align: middle;">项目施工合同：</td>
<td colspan="6" style="vertical-align: middle;"><span class="btn btn-success fileinput-button">
		    <i class="glyphicon glyphicon-plus"></i>
		    <span>请选择...</span>
		    <input id="fileuploadcardpic" type="file" name="upload_file" multiple="">		    
		</span></td>
		
        <?php echo $form->field($model,'contract')->hiddenInput()->label(false)?>

</tr>
<tr>
<td colspan="8">
<?php echo '&nbsp;'.Html::img($model->contract,['width'=>'400px','height'=>'220px','id'=>'contractpic']); ?>
</td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
$(function () {
	var url = "<?= Url::to(['photogallery/fileupload','controller'=>yii::$app->controller->id,'field'=>'contract','farms_id'=>$project->farms_id]);?>";
    $('#fileuploadcardpic').fileupload({
        url: url,
        dataType: 'json',
		done: function (e, data) {
			var url2 = data.result.url;
			$('#contractpic').attr('src', url2);
			$('#projectplan-contract').attr('value', url2);
        }
    });
});
</script>