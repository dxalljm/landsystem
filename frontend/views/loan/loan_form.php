<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\Lease;
use app\models\Farms;
use dosamigos\datetimepicker\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Loan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="loan-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
	<?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>
<tr>
<td width=15% align='right'>抵押面积</td><?php $model->mortgagearea = Farms::find()->where(['id'=>$_GET['farms_id']])->one()['measure'];?>
<td align='left' colspan="5" ><?= $form->field($model, 'mortgagearea')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>抵押银行</td>
<td colspan="5" align='left'><?= $form->field($model, 'mortgagebank')->dropDownList(['中国建设银行'=>'中国建设银行','中国工商银行'=>'中国工商银行','中国银行'=>'中国银行','中国农业银行'=>'中国农业银行','大兴安岭农村商业银行'=>'大兴安岭农村商业银行','龙江银行'=>'龙江银行','邮政储蓄'=>'邮政储蓄'])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>贷款金额</td>
<td colspan="5" align='left'><?= $form->field($model, 'mortgagemoney')->textInput()->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>贷款年限</td>
			<td align='center'>自</td>
			<td align='center'><?= $form->field($model, 'begindate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
			<td align='center'>至</td>
			<td align='center'><?= $form->field($model, 'enddate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
        ]])?></td>
			<td align='center'>止</td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('返回', [Yii::$app->controller->id.'index','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
        <br>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
