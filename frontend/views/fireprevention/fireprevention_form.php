<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Firepreventionemployee;
use app\models\Farms;
use app\models\Fireprevention;
use app\models\User;
use app\models\Picturelibrary;
/* @var $this yii\web\View */
/* @var $model app\models\Fireprevention */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .showimg img {
        vertical-align: middle;
    }
</style>
<div class="fireprevention-form">
    <?= Farms::showFarminfo2($_GET['farms_id'])?>
    <?php $form = ActiveFormrdiv::begin(); ?>
    <?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false)->error(false) ?>

    <table width="57%" class="table table-bordered table-hover">
        <?php
        foreach (Fireprevention::sixLabels() as $key => $value):
//            var_dump($key);
        ?>
		<tr>
            <td class="text-right" width="15%"><?= $value['label']?></td>
            <td class="showimg">
                <?php
                switch ($value['type']) {
                    case 'radioList':
                        echo $form->field($model, $key)->radioList([1=>'是',0=>'否'],['disabled'=>User::disabled()])->label(false)->error(false);
                        break;
                    case 'dialog':
                        echo Html::button('上传照片',['onclick'=>"showDialog('".$key."')",'class'=>'btn btn-success','disabled'=>User::disabled()]);
                        echo Html::button('查看照片',['onclick'=>"javascript:window.open('".yii::$app->urlManager->createUrl(['picturelibrary/showimg','farms_id'=>$model->farms_id,'class'=>'Fireprevention','field'=>$key,'disabled'=>User::disabled()])."','','width=1200,height=800,top=250,left=380, location=no, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",'class'=>'btn btn-success']);
                        foreach (Picturelibrary::find()->where(['farms_id'=>$_GET['farms_id'],'year'=>User::getYear(),'field'=>$key])->all() as $img) {
                            echo Html::img('http://192.168.1.10/'.$img['pic'],['width'=>80,'v']);
                            echo Html::a('<i class="fa fa-fw fa-times-circle text-danger"></i>',\yii\helpers\Url::to(['picturelibrary/picturelibrarydelete','id'=>$img['id']]),['data' => [
                                'confirm' => '您确定要删除这项吗？',
                                'method' => 'post',
                            ],]);
                        }
                }

                ?>
            </td>
        </tr>
        <?php
        endforeach;
        ?>
    </table>
    <?= Html::hiddenInput('field','',['id'=>'fieldname'])?>
<table width="57%" class="table table-striped table-bordered table-hover table-condensed">
<tr>

<td colspan="6" align='center'><h3>农场雇工登记</h3></td>

</tr>

<tr>

<td width=8% align='center'>雇工期限</td>

<td align='center'>雇工姓名</td>
<td align='center'>身份证号</td>
<td align='center'>是否吸烟</td>
<td align='center'>智障人员</td>



</tr>
<?php 
//var_dump($employees);
$i=0;
if(is_array($employees)) {
foreach($employees as $emp) {
	foreach($emp as $val) {
		$efire = Firepreventionemployee::find()->where(['employee_id'=>$val['id']])->one();
	?>
<tr>
<?php if(!empty($efire)) $value = $efire['id'];else $value = '';?>
<?php echo Html::hiddenInput('ArrEmployeesFire[id][]',$value); ?>
<?php if(!empty($efire)) $value = $efire['employee_id'];else $value = $val['id'];?>
<?php echo Html::hiddenInput('ArrEmployeesFire[employee_id][]', $value); ?>
<td width=8% align='center'><?= $val['employeetype'] ?></td>
<td align='center'><?= $val['employeename'] ?></td>
<td align='center'><?= $val['cardid'] ?></td>
<?php if(!empty($efire)) $value = $efire['is_smoking'];else $value = 0;?>
<td align='center'><?php echo Html::radioList('ArrEmployeesFire[is_smoking]['.$i.']', $value, [1=>'是',0=>'否']); ?></td>
<?php if(!empty($efire)) $value = $efire['is_retarded'];else $value = 0;?>
<td align='center'><?php echo Html::radioList('ArrEmployeesFire[is_retarded]['.$i.']', $value, [1=>'是',0=>'否']); ?></td>
</tr>
<?php $i++;}}?>
</table>
<?php }?>

    <div id="dialogMsg" title="图库"></div>

<?php Farms::showRow($_GET['farms_id']);?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','disabled'=>User::disabled()]) ?>
        
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
$('#rowjump').keyup(function(event){
	input = $(this).val();
	$.getJSON('index.php?r=farms/getfarmid', {id: input}, function (data) {
		$('#setFarmsid').val(data.farmsid);
	});
});
function showDialog(field)
{
    $('#fieldname').val(field);
    $.get('index.php?r=webupload/show', function (body) {
        $('#dialogMsg').html('');
        $('#dialogMsg').html(body);
        $("#dialogMsg").dialog("open");
    });
}
function showImage(field)
{
    $.get('index.php?r=webupload/showimg', {farms_id: <?= $_GET['farms_id']?>,table:'Fireprevention',field:field}, function (body) {
        $('#dialogMsg').html('');
        $('#dialogMsg').html(body);
        $("#dialogMsg").dialog("open");
    });
}

$( "#dialogMsg" ).dialog({
    autoOpen: false,
    width: 1500,
    //    show: "blind",
    //    hide: "explode",
    modal: true,//设置背景灰的
    position: { using:function(pos){
        var topOffset = $(this).css(pos).offset().top;
        if (topOffset = 0||topOffset>0) {
            $(this).css('top', 20);
        }
        var leftOffset = $(this).css(pos).offset().left;
        if (leftOffset = 0||leftOffset>0) {
            $(this).css('left', 360);
        }
    }},
    buttons: [
        {
            text: "确定",
            class:'btn btn-success',
            click: function() {
                $( this ).dialog( "close" );
                $.getJSON('index.php?r=webupload/ftpupload',{'farms_id':"<?= $_GET['farms_id']?>",'class':'fireprevention','field':$('#fieldname').val()},function (data) {
//                    if(data.state) {

//                    }
                });
                window.location.reload();
            }

        },
        {
            text: "取消",
            class:'btn btn-danger',
            click: function() {
                $( this ).dialog( "close" );
            }
        }
    ]
});
</script>