<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PhotogallerySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="photogallery-search">

    <?php $form = ActiveForm::begin([
        'action' => ['photogalleryindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'management_area') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'tablename') ?>

    <?= $form->field($model, 'picaddress') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
