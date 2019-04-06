<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\leaseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lease-search">

    <?php $form = ActiveForm::begin([
        'action' => ['leaseindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'lease_area') ?>

    <?= $form->field($model, 'lessee') ?>

    <?= $form->field($model, 'plant_id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
