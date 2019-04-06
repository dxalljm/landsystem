<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Fixedtype;
/* @var $this yii\web\View */
/* @var $model app\models\Fixed */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fixed-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
    <tr>
        <td width=15% align='right'>类别</td>
        <td align='left'><?= $form->field($model, 'typeid')->textInput(['list'=>'selectList','error'=>true])->label(false) ?></td>
        <datalist id="selectList">
            <?php
            foreach (Fixedtype::find()->all() as $value) {
                echo '<option>'.$value['typename'].'</option>';
            }
            ?>
        </datalist>
    </tr>
    <tr>
        <td width=15% align='right'>名称</td>
        <td align='left'><?= $form->field($model, 'name')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    </tr>
    <tr>
        <td width=15% align='right'>单位</td>
        <td align='left'><?= $form->field($model, 'unit')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
    </tr>
    <tr>
        <td width=15% align='right'>数量</td>
        <td align='left'><?= $form->field($model, 'number')->textInput()->label(false)->error(false) ?></td>
    </tr>
    <tr>
        <td width=15% align='right'>状态</td>
        <td align='left'><?= $form->field($model, 'state')->dropDownList(['正常使用'=>'正常使用','无法使用'=>'无法使用'])->label(false)->error(false) ?></td>
    </tr>
    <tr>
        <td width=15% align='right'>备注</td>
        <td align='left'><?= $form->field($model, 'remarks')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
    </tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
