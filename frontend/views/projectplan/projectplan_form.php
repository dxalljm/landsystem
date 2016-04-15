<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use dosamigos\datetimepicker\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Projectplan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projectplan-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
<tr>
<td width=15% align='right'>开始日期</td>
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
</tr>
<tr>
<td width=15% align='right'>结束日期</td>
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
<td width=15% align='right'>工程计划内容</td>
<td align='left'><?= $form->field($model, 'content')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
