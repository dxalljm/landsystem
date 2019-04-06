<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\GoodseedinfocheckSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goodseedinfocheck-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'management_area') ?>

    <?= $form->field($model, 'lease_id') ?>

    <?= $form->field($model, 'planting_id') ?>

    <?php // echo $form->field($model, 'plant_id') ?>

    <?php // echo $form->field($model, 'goodseed_id') ?>

    <?php // echo $form->field($model, 'zongdi') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'total_area') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
