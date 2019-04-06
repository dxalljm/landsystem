<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\groupsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="groups-search">

    <?php $form = ActiveForm::begin([
        'action' => ['groupsindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'groupname') ?>

    <?= $form->field($model, 'grouprole') ?>

    <?= $form->field($model, 'groupmark') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
