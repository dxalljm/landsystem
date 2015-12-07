<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\HuinongSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="huinong-search">

    <?php $form = ActiveForm::begin([
        'action' => ['huinongindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'subsidiestype_id') ?>

    <?= $form->field($model, 'subsidiesarea') ?>

    <?= $form->field($model, 'subsidiesmoney') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
