<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Farms;
use app\models\Huinong;
/* @var $this yii\web\View */
/* @var $model frontend\models\HuinongSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="huinong-search">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'farms_id')->label(false)->error(false) ?>


    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript" charset="utf-8">
  var json = <?= Huinong::getFarminfo($_GET['huinong_id']) ?>;
  $('#huinonggrantsearch-farms_id').autocomplete({
      lookup: json,
      formatResult: function (json) {
        return json.data;
      },
      onSelect: function (suggestion) {
        location.href = suggestion.url;
        $(this).val(suggestion.data);
        
      }
  });
</script>