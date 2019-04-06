<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MachinesubsidySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="machinesubsidy-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'machine_id') ?>

    <?= $form->field($model, 'filename') ?>

    <?= $form->field($model, 'parameter') ?>

    <?= $form->field($model, 'subsidymoney') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
