<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Parcel;
use app\models\ManagementArea;
/* @var $this yii\web\View */
/* @var $model app\models\Farms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farms-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        账页管理
                    </h3>
                </div>
                <div class="box-body">
    <?php $form = ActiveFormrdiv::begin(); ?>
<table
		class="table table-bordered table-hover">
		
		<tr>
			<td width=15% align='right'>账页号</td>
			<td colspan="5" align='left'><?= $form->field($model, 'accountnumber')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
	</table>
	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>