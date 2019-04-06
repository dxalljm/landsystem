<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cooperativetype */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cooperativetype-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'typename')->textInput(['maxlength' => 500]) ?>

    <div class="form-group">

        <?php if (Yii::$app->request->isAjax): ?>
            <?= Html::button($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'ajax-create']) ?>
        <?php else: ?>
            <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
