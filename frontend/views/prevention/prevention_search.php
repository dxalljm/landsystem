<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\preventionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prevention-search">

    <?php $form = ActiveForm::begin([
        'action' => ['preventionindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'breedinfo_id') ?>

    <?= $form->field($model, 'preventionnumber') ?>

    <?= $form->field($model, 'breedinfonumber') ?>

    <?php // echo $form->field($model, 'preventionrate') ?>

    <?php // echo $form->field($model, 'isepidemic') ?>

    <?php // echo $form->field($model, 'vaccine') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
