<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MachineapplySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="machineapply-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'farmername') ?>

    <?= $form->field($model, 'age') ?>

    <?= $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'domicile') ?>

    <?php // echo $form->field($model, 'management_area') ?>

    <?php // echo $form->field($model, 'cardid') ?>

    <?php // echo $form->field($model, 'telephone') ?>

    <?php // echo $form->field($model, 'machineoffarm_id') ?>

    <?php // echo $form->field($model, 'farmerpinyin') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
