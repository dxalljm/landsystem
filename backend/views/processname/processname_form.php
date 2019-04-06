<?php

use yii\helpers\Html;
use backend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\AuthItem;
/* @var $this yii\web\View */
/* @var $model app\models\Processname */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="processname-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>流程科室名称</td>
<td align='left'><?= $form->field($model, 'processdepartment')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>标识</td>
<td align='left'><?= $form->field($model, 'Identification')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>科室</td>
<td align='left'><?= $form->field($model, 'department_id')->checkboxList(ArrayHelper::map(\app\models\Department::find()->all(), 'id', 'departmentname'))->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>职级</td>
<td align='left'><?= $form->field($model, 'level_id')->checkboxList(ArrayHelper::map(\app\models\Userlevel::find()->all(), 'id', 'levelname'))->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
