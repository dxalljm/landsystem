<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Farms;
use app\models\Plant;
use dosamigos\datetimepicker\DateTimePicker;


?>

<div class="lease-form">
<?php $farms = Farms::find()->where(['id'=>$_GET['id']])->one();?>
    <?php $form = ActiveFormrdiv::begin(); ?>
    <?php $overarea = $farms['measure']-$areas;?>
    <?= html::hiddenInput('measure',$areas,['id'=>'measure']);?>

    <table class="table table-striped table-bordered table-hover table-condensed">
  <tr>
    <td width="80" align="center">农场名称</td>
    <td colspan="2" align="center"><?= $farm->farmname?></td>
    <td align="center">法人</td>
    <td colspan="2" align="center"><?= $farmer->farmername?></td>
    <td width="107" align="center">宜农林地面积</td>
    <td width="106" align="center"><?= $farm->measure.'(已经租凭'.$overarea.')'?></td>
  </tr>
  <tr>
    <td colspan="8" align="center"><h4>承租人基础信息</h4></td>
  </tr>
  <tr>
    <td align="center">承租人姓名</td>
    <td colspan="5" align="center"><?= $form->field($model, 'lessee')->textInput()->label(false)->error(false) ?></td>
    <td colspan="2" rowspan="5" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">身份证号</td>
    <td colspan="5" align="center"><?= $form->field($model, 'lessee_cardid')->textInput()->label(false)->error(false) ?></td>
  </tr>
  <tr>
    <td align="center">电话</td>
    <td colspan="5" align="center"><?= $form->field($model, 'lessee_telephone')->textInput()->label(false)->error(false) ?></td>
  </tr>
  <tr>
    <td align="center">租赁面积</td>
    <td colspan="5" align="center"><?= $form->field($model, 'lease_area')->textInput(['value'=>$areas])->label(false)->error(false) ?></td>
  </tr>
  <tr>
    <td align="center">租赁期限</td>
    <td width="19" align="center">自</td>
    <td width="61" align="center"><?= $form->field($model, 'begindate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(DateTimePicker::className(), [
        'language' => 'zh-CN',
        'size' => 'xs',
        'template' => '{input}',
        'inline' => false,
        'clientOptions' => [
            'startView' => 'decade',
            'autoclose' => true,
            'format' => 'yyyy-mm-dd', // if inline = false
            'todayBtn' => false
        ]
    ]);?></td>
    <td width="22" align="center">至</td>
    <td width="64" align="center"><?= $form->field($model, 'enddate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(DateTimePicker::className(), [
        'language' => 'zh-CN',
        'size' => 'xs',
        'template' => '{input}',
        'inline' => false,
        'clientOptions' => [
            'startView' => 'decade',
            'autoclose' => true,
            //'CustomFormat' => 'yyyy',
            'format' => 'yyyy-mm-dd', // if inline = false
            'todayBtn' => false
        ]
    ]);?></td>
    <td width="16" align="center">止</td>
  </tr>
</table>
    <?= html::dropDownList('father_id','',ArrayHelper::map(Plant::find()->where(['father_id'=>1])->andWhere('id>1')->all(),'id','cropname'),['class'=>'form-control','id'=>'father_id']) ?>
    <?= $form->field($model, 'plant_id')->dropDownList(ArrayHelper::map(Plant::find()->all(),'id','cropname'))->label(false) ?>
<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['onclick'=>'setFarmsid('.$_GET['id'].')','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
  </div>
    <?php ActiveFormrdiv::end(); ?>
<?php
    $script = <<<JS
jQuery('#years').change(function(){
    var year = $(this).val();
    $.get('/landsystem/frontend/web/index.php?r=collection/collectionindex',{year:year},function (data) {
              $('body').html(data);
            });
});
JS;
$this->registerJs($script);

$this->registerJsFile('js/vendor/bower/devbridge-autocomplete/dist/jquery.autocomplete.js', ['position' => View::POS_HEAD]);
$this->registerJsFile('js/lease.js', ['position' => View::POS_HEAD]);
?>
<script type="text/javascript">

function setFarmsid(id)
{
    $('#lease-farms_id').val(id);
}
</script>


</div>
