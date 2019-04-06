<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\Help;
/* @var $this yii\web\View */
/* @var $model app\models\Help */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="help-form">
<table class="table table-bordered table-hover">
<tr>
    <td width=15% align='right'><?php echo $help->title;?></td>
</tr>
<tr>
    <td width=15% align='right'><?php echo $help->content;?></td>
</tr>
</table>
</div>
