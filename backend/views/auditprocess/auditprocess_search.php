<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AuditprocessSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auditprocess-search">

    <?php $form = ActiveForm::begin([
        'action' => ['auditprocessindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'projectname') ?>

    <?= $form->field($model, 'process') ?>

    <?= $form->field($model, 'actionname') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
