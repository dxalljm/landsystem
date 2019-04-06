<?php

use yii\helpers\Html;
use backend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\AuthItem;
use app\models\Auditprocess;

/* @var $this yii\web\View */
/* @var $model app\models\Logicalpoint */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logicalpoint-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>方法名称</td>
<td align='left'><?= $form->field($model, 'actionname')->dropDownList(ArrayHelper::map(AuthItem::find()->all(), 'name', 'description'))->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>流程名称</td>
<td align='left'><?= $form->field($model, 'processname')->dropDownList(ArrayHelper::map(Auditprocess::find()->all(), 'id', 'projectname'))->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
