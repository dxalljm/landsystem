<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Machinetype;
use frontend\widgets\CategorySelect;
/* @var $this yii\web\View */
/* @var $model app\models\Machine */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="machine-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
    <?php $dropdownValue = Machinetype::find()->where(['father_id'=>1])->all();?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>类别</td>
<td align='left'><?php

			$listData = Machinetype::getAllList();

			if (!$model->isNewRecord && isset($listData[$model->id])) {
				unset($listData[$model->id]);
			}

			if (count($listData) > 0) {
				echo CategorySelect::widget([
					"model" => $model,
					'isDisableParent' => null,
					"attribute" => 'father_id',
					'isShowFinal' => null,
					"categories" => $listData,
					'selectedValue' => $model->father_id,
					'htmlOptions' => [
						'class' => 'form-control col-sm-5 col-lg-5',
						'name' => 'Machinetype[father_id]'
					]
				]);

			} else {
				echo "无可用父分类";
			}
			?></td>
</tr>
<tr>
<td width=15% align='right'>类型名称</td>
<td align='left'><?= $form->field($model, 'typename')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
