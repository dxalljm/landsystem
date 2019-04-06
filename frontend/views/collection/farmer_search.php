<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\farmerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farmer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['farmerindex'],
        'method' => 'get',
    ]); ?>
<table width="100%" border="0">
  <tr>
    <td>法人姓名</td>
    <td><?= $form->field($model, 'farmername')->label(false)->error(false) ?></td>
  </tr>
</table>



    <?php ActiveForm::end(); ?>

</div>
