<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\goodseedSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goodseed-search">

    <?php $form = ActiveForm::begin([
        'action' => ['goodseedindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'plant_id') ?>

    <?= $form->field($model, 'plant_model') ?>

    <?= $form->field($model, 'planting_area') ?>

    <?= $form->field($model, 'plant_measurearea') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
