<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Breedtype */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="breedtype-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>
<td width=15% align='right'>类别</td><?php if($father_id) $model->father_id = $father_id;?>
<td align='left'><?= $form->field($model, 'father_id')->dropDownList(ArrayHelper::map($father, 'id', 'typename'))->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>类型名称</td>
<td align='left'><?= $form->field($model, 'typename')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>单位</td>
<td align='left'><?= $form->field($model, 'unit')->dropDownList(['0'=>'无','头'=>'头','匹'=>'匹','只'=>'只','条'=>'条','尾'=>'尾','个'=>'个','箱'=>'箱'])->label(false)->error(false) ?></td>
</tr>
</table>
<div class="form-group">

        <?php if (Yii::$app->request->isAjax): ?>
            <?= Html::button($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'ajax-create']) ?>
        <?php else: ?>
            <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>

    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
