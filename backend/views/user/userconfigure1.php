<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Department;
use app\models\Userlevel;
use app\models\Mainmenu;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\user */

$this->title = $model->realname;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <?php $plantarr = ArrayHelper::map(Mainmenu::find()->where(['typename'=>1])->all(), 'id', 'menuname');?>
    <?php $model->plate = explode(',',$model->plate);?>
    <?= $form->field($model, 'plate')->checkboxList($plantarr)->label('管辖板块'); ?>

    <div class="form-group">
        <?= Html::submitButton('下一步', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
