<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MachineoffarmSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="machineoffarm-search">

    <?php $form = ActiveForm::begin([
        'action' => ['machineoffarmindex'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'machine_id') ?>

    <?= $form->field($model, 'farms_id') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
