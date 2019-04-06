<?php

use yii\helpers\Html;
use backend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
/* @var $this yii\web\View */
/* @var $model app\models\Groups */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="groups-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>分配管理区</td>
<td align='left'><?= html::checkboxList('managementarea','',ArrayHelper::map(ManagementArea::find()->all(), 'id', 'areaname'),['class'=>'form-control']) ?></td>
</tr>
<tr>
<td width=15% align='right'>用户组权限</td>
<td align='left'></td>
</tr>
<tr>
<td width=15% align='right'>用户组标识</td>
<td align='left'></td>
</tr>
</table>
    <div class="form-group">
       
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
