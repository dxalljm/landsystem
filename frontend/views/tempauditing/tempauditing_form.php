<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\ArrayHelper;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Tempauditing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tempauditing-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">

<tr>
<td width=15% align='right'>临时受权人</td>
<td align='left'><?= $form->field($model, 'tempauditing')->dropDownList(ArrayHelper::map(User::find()->all(), 'id', 'realname'))->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>开始日期</td><?php $model->begindate = date('Y-m-d');?>
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
        ]]) ?></td>
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
        ]]) ?></td>
</tr>
</table>
    <?php
    if(!\app\models\Tempauditing::find()->where(['user_id'=>Yii::$app->user->id,'state'=>1])->count()) {
        ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php
    }
    ?>
    <?php ActiveFormrdiv::end(); ?>

</div>
