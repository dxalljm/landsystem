<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\insuranceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insurance-search">

    <?php $form = ActiveForm::begin([
        'action' => ['insuranceindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'management_area') ?>

    <?= $form->field($model, 'year') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'policyholder') ?>

    <?php // echo $form->field($model, 'cardid') ?>

    <?php // echo $form->field($model, 'telephone') ?>

    <?php // echo $form->field($model, 'wheat') ?>

    <?php // echo $form->field($model, 'soybean') ?>

    <?php // echo $form->field($model, 'insuredarea') ?>

    <?php // echo $form->field($model, 'insuredwheat') ?>

    <?php // echo $form->field($model, 'insuredsoybean') ?>

    <?php // echo $form->field($model, 'company_id') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'policyholdertime') ?>

    <?php // echo $form->field($model, 'managemanttime') ?>

    <?php // echo $form->field($model, 'halltime') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
