<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\breedSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="breed-search">

    <?php $form = ActiveForm::begin([
        'action' => ['breedindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'breedname') ?>

    <?= $form->field($model, 'breedaddress') ?>

    <?= $form->field($model, 'is_demonstration') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
