<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\mainmenuSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mainmenu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['mainmenuindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'menuname') ?>

    <?= $form->field($model, 'menuurl') ?>
    
    <?= $form->field($model, 'sort') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
