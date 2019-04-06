<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use dosamigos\datetimepicker\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Lockstate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lockstate-form">
    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
    <tr>
        <td width=15% align='right'>系统状态</td>
        <td align='left' colspan="2"><?= $form->field($model, 'systemstate')->radioList([0=>'解锁',1=>'锁定'])->label(false)->error(false) ?></td>
        <td width=15% align='right'>系统锁定期限</td>
        <?php
            if(!empty($model->systemstatedate)) {
                $model->systemstatedate = date('Y-m-d H:i:s',$model->systemstatedate);
            }
        ?>
        <td align='left' colspan="3"><?= $form->field($model, 'systemstatedate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
                DateTimePicker::className(), [
                // inline too, not bad
                'inline' => false,
                'language'=>'zh-CN',

                'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss'
                ]]) ?></td>
    </tr>
    <tr>
        <td width=15% align='right'>业务锁定状态(勾选为锁定)</td>
        <?php
        if($model->platestate) {
            $model->platestate = explode(',',$model->platestate);
        }
        ?>
        <td align='left' colspan="6"><?= $form->field($model, 'platestate')->checkboxList(\app\models\Mainmenu::getBusinessMenu())->label(false)->error(false) ?></td>
    </tr>
    <tr>
        <td width=15% align='right'>贷款配置项</td>
        <td align='left'><?= $form->field($model, 'loanconfig')->radioList([0=>'解锁',1=>'锁定'])->label(false)->error(false) ?></td>
        <td width=15% align='right'>贷款配置冻结日期</td>
        <td width=3% align='right'>起</td>
        <td align='left'><?= $form->field($model, 'loanconfigdate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
                DateTimePicker::className(), [
                // inline too, not bad
                'inline' => false,
                'language'=>'zh-CN',

                'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'startView' => 2,
                    'minView' => 2,
                    'format' => 'mm-dd'
                ]]);    ?></td>
        <td width=3% align='right'>止</td>
        <td align='left'><?= $form->field($model, 'loanconfigdateend')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
                DateTimePicker::className(), [
                // inline too, not bad
                'inline' => false,
                'language'=>'zh-CN',

                'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'startView' => 2,
                    'minView' => 2,
                    'format' => 'mm-dd'
                ]]);    ?></td>
    </tr>
    <tr>
        <td width=15% align='right'>过户配置项</td>
        <td align='left'><?= $form->field($model, 'transferconfig')->radioList([0=>'解锁',1=>'锁定'])->label(false)->error(false) ?></td>
        <td width=15% align='right'>过户冻结日期</td>
        <td width=15% align='right'>起</td>
        <td align='left'><?= $form->field($model, 'transferconfigdate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
                DateTimePicker::className(), [
                // inline too, not bad
                'inline' => false,
                'language'=>'zh-CN',

                'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'startView' => 2,
                    'minView' => 2,
                    'format' => 'mm-dd'
                ]]);    ?></td>
        <td width=15% align='right'>止</td>
        <td align='left'><?= $form->field($model, 'transferconfigdateend')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
                DateTimePicker::className(), [
                // inline too, not bad
                'inline' => false,
                'language'=>'zh-CN',

                'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'startView' => 2,
                    'minView' => 2,
                    'format' => 'mm-dd'
                ]]);    ?></td>
    </tr>
    <tr>
        <td width=15% align='right'>种植结构配置项</td>
        <td align='left'><?= $form->field($model, 'plantstate')->radioList([0=>'解锁',1=>'锁定'])->label(false)->error(false) ?></td>
        <td width=15% align='right'>种植结构冻结日期</td>
        <td width=15% align='right'>起</td>
        <td align='left'><?= $form->field($model, 'plantstatedate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
                DateTimePicker::className(), [
                // inline too, not bad
                'inline' => false,
                'language'=>'zh-CN',

                'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'startView' => 2,
                    'minView' => 2,
                    'format' => 'mm-dd'
                ]]);    ?></td>
        <td width=15% align='right'>止</td>
        <td align='left'><?= $form->field($model, 'plantstatedateend')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
                DateTimePicker::className(), [
                // inline too, not bad
                'inline' => false,
                'language'=>'zh-CN',

                'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'startView' => 2,
                    'minView' => 2,
                    'format' => 'mm-dd'
                ]]);    ?></td>
    </tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
