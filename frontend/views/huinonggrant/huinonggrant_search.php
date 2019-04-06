<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\HuinonggrantSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="huinonggrant-search">

    <?php $form = ActiveForm::begin([
        'action' => ['huinonggrantindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'huinong_id') ?>

    <?= $form->field($model, 'money') ?>

    <?= $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'note') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
