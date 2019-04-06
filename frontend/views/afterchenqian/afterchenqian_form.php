<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use dosamigos\datetimepicker\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Afterchenqian */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="afterchenqian-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
<tr>
<td width=15% align='right'>年份</td>
<td align='left'><?= $form->field($model, 'year')->textInput()->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'startView' => 4,
        	'minView' => 4,
            'format' => 'yyyy'
        ]
])?></td>
</tr>
<tr>
<td width=15% align='right'>陈欠金额</td>
<td align='left'><?= $form->field($model, 'money')->textInput()->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
