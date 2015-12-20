<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\Infrastructuretype;
use frontend\widgets\CategorySelect;
/* @var $this yii\web\View */
/* @var $model app\models\Projectapplication */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projectapplication-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>项目类型</td>
<td align='left'>
<?php

			$listData = Infrastructuretype::getAllList();

			if (!$model->isNewRecord && isset($listData[$model->id])) {
				unset($listData[$model->id]);
			}

			if (count($listData) > 0) {
				echo CategorySelect::widget([
					"model" => $model,
					'isDisableParent' => null,
					"attribute" => 'projecttype',
					'isShowFinal' => null,
					"categories" => $listData,
					'selectedValue' => $model->projecttype,
					'htmlOptions' => [
						'class' => 'form-control col-sm-5 col-lg-5',
						'name' => 'Projectapplication[projecttype]'
					]
				]);

			} else {
				echo "无可用父分类";
			}
			?>
</tr>
<tr>
<td width=15% align='right'>申请内容</td>
<td align='left'><?= $form->field($model, 'content')->textInput()->label(false)->error(false) ?></td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
