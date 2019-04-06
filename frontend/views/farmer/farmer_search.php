<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\farmerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farmer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['farmerindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'farmername') ?>

    <?= $form->field($model, 'cardid') ?>

    <?= $form->field($model, 'farms_id') ?>

    <?= $form->field($model, 'isupdate') ?>

    <?php // echo $form->field($model, 'farmerbeforename') ?>

    <?php // echo $form->field($model, 'nickname') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'nation') ?>

    <?php // echo $form->field($model, 'political_outlook') ?>

    <?php // echo $form->field($model, 'cultural_degree') ?>

    <?php // echo $form->field($model, 'domicile') ?>

    <?php // echo $form->field($model, 'nowlive') ?>

    <?php // echo $form->field($model, 'telephone') ?>

    <?php // echo $form->field($model, 'living_room') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'cardpic') ?>

    <?php // echo $form->field($model, 'years') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
