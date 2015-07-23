<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Farmer;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\BankAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-account-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'farmer_id')->dropDownList(ArrayHelper::map(Farmer::find()->all(), 'id', 'farmername')) ?>
	
	<?= $form->field($model, 'bank')->dropDownList(['工商银行'=>'工商银行','建设银行'=>'建设银行','农业银行'=>'农业银行','农村信用社'=>'农村信用社','龙江银行'=>'龙江银行','邮政储蓄'=>'邮政储蓄']) ?>
	
    <?= $form->field($model, 'accountnumber')->textInput(['maxlength' => 500]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
