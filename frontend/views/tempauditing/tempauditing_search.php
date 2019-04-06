<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\TempauditingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tempauditing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['tempauditingindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'tempauditing') ?>

    <?= $form->field($model, 'update_at') ?>

    <?= $form->field($model, 'begindate') ?>

    <?php // echo $form->field($model, 'enddate') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
