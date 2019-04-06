<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Plant;

/* @var $this yii\web\View */
/* @var $model app\models\Yieldbase */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="yieldbase-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
<?php for($i=0;$i<count($yieldbases);$i++) {?>
<?php echo Html::hiddenInput('plantids[]', $yieldbases[$i]['plant_id'], ['class' => 'form-control']); ?>
<?php if(($i % 4) == 0) { ?>
<tr>
<?php }?>
<td align='right'>作物</td>
<td align='left'><?= Plant::find()->where(['id'=> $yieldbases[$i]['plant_id']])->one()['typename']?></td>
<td align='right'>平均亩产/斤</td>
<td align='left'><?php echo Html::textInput('yields[]',  $yieldbases[$i]['yield'], ['class' => 'form-control']); ?></td>
<?php if(($i % 4) == 3) {?>
</tr>
<?php }?>
<?php }?>
</table>
    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
