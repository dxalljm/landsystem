<?php

use yii\helpers\Html;
use backend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use app\models\Mainmenu;
use app\models\User;
use app\models\MenuToUser;
/* @var $this yii\web\View */
/* @var $model app\models\Department */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="department-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>
<td width=15% align='right'>科室名称</td>
<td align='left'><?= $form->field($model, 'departmentname')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>所辖管理区</td><?php $model->membership = explode(',', $model->membership);?>
<td align='left'><?= $form->field($model, 'membership')->checkboxList(ArrayHelper::map(ManagementArea::find()->all(), 'id', 'areaname'))->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>分管领导</td>
<td align='left'><?= $form->field($model, 'leader')->dropDownList(ArrayHelper::map(User::find()->all(), 'id', 'username'))->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>科长</td>
<td align='left'><?= $form->field($model, 'sectionchief')->dropDownList(ArrayHelper::map(User::find()->all(), 'id', 'username'))->label(false)->error(false) ?></td>
</tr>
<tr>
<td width=15% align='right'>包片负责人</td>
<td align='left'><?= $form->field($model, 'chippackage')->dropDownList(ArrayHelper::map(User::find()->all(), 'id', 'username'))->label(false)->error(false) ?></td>
</tr>
    <td width=15% align='right'>功能板块选择</td>
    <td align='left'>
        <?php
        $data = Mainmenu::find()->where(['typename'=>0])->orderBy('sort ASC')->all();
        $menus =ArrayHelper::map($data,'id', 'menuname');
        $model->menulist = explode(',',$model->menulist);
//        $model->plate = explode(',',$model->plate);
        $model->businessmenu = explode(',',$model->businessmenu);
        $model->searchmenu = explode(',',$model->searchmenu);
        ?>
        <?= $form->field($model, 'menulist')->checkboxList($menus); ?>
        

        <?php $business = ArrayHelper::map(Mainmenu::find()->where(['typename'=>2])->all(),'id','menuname');?>
        <?= $form->field($model, 'businessmenu')->checkboxList($business)->label('业务菜单'); ?>
        <?php $searchmenu = MenuToUser::getSearchList();?>
        <?= $form->field($model, 'searchmenu')->checkboxList($searchmenu)?>
    </td>
    </tr>
</table>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
