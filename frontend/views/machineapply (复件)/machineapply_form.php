<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Machinetrial;
/* @var $this yii\web\View */
/* @var $model app\models\Machineapply */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="machineapply-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
    <table class="table table-bordered table-hover">
        <tr>
            <td width="10%" align="center"><strong>姓名</strong></td>
            <td  align="center"><?= $farm->farmername?></td>
            <td align="center"><strong>年龄</strong></td>
            <td align="center"><?= Farms::getAge($farm['cardid'])?></td>
            <td width="8%" colspan="2" align="center"><strong>性别</strong></td>
            <td align="center" colspan="2"><?= $farmer['gender']?></td>
        </tr>
        <tr>
            <td align="center"><strong>身份证号</strong></td>
            <td align="center" colspan="2"><?php echo $farm['cardid']?></td></td>
            <td align="center"><strong>联系电话</strong></td><?php $model->telephone = $farm['telephone'];?>
            <td align="center" colspan="6"><?php echo $form->field($model,'telephone')->textInput()->label(false)?></td></td>
        </tr>
        <tr>
            <td align="center" width="25%"><strong>户籍所在地</strong></td><?php $model->domicile = $farmer['domicile'];?>
            <td colspan="9" align="left"><?php echo $form->field($model,'domicile')->textInput()->label(false)?></td>
        </tr>

    </table>
    <table class="table table-bordered table-hover" id="isTable">
        <?php
        $data = Machinetrial::attributesList();
        foreach ($data as $key=>$value) {?>
            <tr id="tr-".$key>
                <td width="10%" align="right"><?= Html::radioList($key,'',[1=>'是',0=>'否'],['onclick'=>'radioCheck("'.$key.'")','id'=>$key.'-id']) ?></td>
                <td><?= $value?></td>
            </tr>
        <?php }
        ?>
    </table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '申请' : '申请', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','disabled'=>true,'id'=>'submitButton']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
    function radioListState()
    {
        var arr = new Array();
        var str = "<?= implode(',',Machinetrial::attributesKey())?>";
        arr = str.split(',');
        var state = false
        $.each(arr,function(){
            if(this == 'iscooperative') {
                if($('input:radio[name="'+this+'"]:checked').val() == true || $('input:radio[name="'+this+'"]:checked').val() == undefined) {
                    state = true;
                }
            } else {
                if ($('input:radio[name="' + this + '"]:checked').val() == false || $('input:radio[name="' + this + '"]:checked').val() == undefined) {
                    state = true;
                }
            }
        });
        return state;
    }
    function radioCheck(name) {
        var state = false;

        state = radioListState();

        $('#submitButton').attr('disabled',state);
    }
</script>