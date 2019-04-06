<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\firepreventionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fireprevention-search">

    <?php $form = ActiveForm::begin([
        'action' => ['firepreventionindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'firecontract') ?>

    <?= $form->field($model, 'safecontract') ?>

    <?= $form->field($model, 'environmental_agreement') ?>

    <?php // echo $form->field($model, 'firetools') ?>

    <?php // echo $form->field($model, 'mechanical_fire_cover') ?>

    <?php // echo $form->field($model, 'chimney_fire_cover') ?>

    <?php // echo $form->field($model, 'isolation_belt') ?>

    <?php // echo $form->field($model, 'propagandist') ?>

    <?php // echo $form->field($model, 'fire_administrator') ?>

    <?php // echo $form->field($model, 'cooker') ?>

    <?php // echo $form->field($model, 'fieldpermit') ?>

    <?php // echo $form->field($model, 'propaganda_firecontract') ?>

    <?php // echo $form->field($model, 'leaflets') ?>

    <?php // echo $form->field($model, 'employee_firecontract') ?>

    <?php // echo $form->field($model, 'rectification_record') ?>

    <?php // echo $form->field($model, 'equipmentpic') ?>

    <?php // echo $form->field($model, 'peoplepic') ?>

    <?php // echo $form->field($model, 'facilitiespic') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
