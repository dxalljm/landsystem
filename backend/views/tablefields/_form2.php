<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Tables;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\tablefields */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tablefields-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $tables = Tables::find()->all();$listData=ArrayHelper::map($tables,'id','tablename');?>
    <?= $form->field($model, 'tables_id')->dropDownList($listData,['prompt'=>'请选择...']) ?>

    <?= $form->field($model, 'fields')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'type')->dropDownList(['int' => '数值型','varchar'=>'字符型','text'=>'文本型']) ?>

    <?= $form->field($model, 'cfields')->textInput(['maxlength' => 100]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
