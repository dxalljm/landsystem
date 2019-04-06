<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\estateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="estate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['estateindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tjsqjbzs') ?>

    <?= $form->field($model, 'tjsqjbzscontent') ?>

    <?= $form->field($model, 'tjsffyj') ?>

    <?= $form->field($model, 'tjsffyjcontent') ?>

    <?php // echo $form->field($model, 'sfyzy') ?>

    <?php // echo $form->field($model, 'sfyzycontent') ?>

    <?php // echo $form->field($model, 'sfmqzongdi') ?>

    <?php // echo $form->field($model, 'sfmqzongdicontent') ?>

    <?php // echo $form->field($model, 'sfydcbg') ?>

    <?php // echo $form->field($model, 'sfydcbgcontent') ?>

    <?php // echo $form->field($model, 'reviewprocess_id') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
