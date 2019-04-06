<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\helpers\ActiveFormrdiv;
use app\models\Farms;
use app\models\User;
use app\models\Insurancecompany;
use app\models\Goodseed;
use app\models\Inputproduct;
use app\models\Pesticides;
use app\models\Plant;
use app\models\Machineoffarm;
use app\models\Machine;
use app\models\Machinetype;
use app\models\Plantingstructure;
use app\models\Theyear;
use frontend\helpers\htmlColumn;
use yii\helpers\Url;
use app\models\Fireprevention;
use app\models\ManagementArea;
use app\models\Goodseedinfo;
/* @var $this yii\web\View */
/* @var $model app\models\Tables */
//var_dump($inputData);
?>
<style>
    td{
        vertical-align: middle !important;
    }
</style>
<!--<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>-->
<!--<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>-->
<!--<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>-->
<!--<script src="js/cardVerification.js"></script>-->
<div class="sixcheck-index">
    <section class="content">
        <?= Farms::showFarminfo2($_GET['farms_id'])?>
        <?= Html::a('打印基础调查表','#', [
            'class'=>'btn btn-success',
            'onclick' => 'myPREVIEWone('.$farm->id.',"'.$farm->farmerpinyin.'")'
        ]);?>
        <?= Html::a('设计表格','#', [
            'class'=>'btn btn-success',
            'onclick' => 'myDesign('.$farm->id.',"'.$farm->farmerpinyin.'")'
        ]);?>
        <div class="row">

            <div class="col-md-4">
                <div class="card card-chart">
                    <div class="card-header" data-background-color="white">
                        <div class="card card-plain" id="lease">
                            <h4>&nbsp;&nbsp;<strong>租赁信息列表</strong></h4>
                            <p class="category text-red"><?php echo '&nbsp;&nbsp;已经租赁 '.$overarea.' 亩'.'&nbsp;&nbsp;租赁人数:'.count($leases).'人';?></p>
                            <div class="card-content table-responsive" style="overflow-y:scroll;width:500px; height:180px;">
                        <?php
                        if($leases) {
                            echo '<table class="table table-hover" height="180px" width="200px">';
                            echo '<tr>';
                            echo '<td >序号</td>';
                            echo '<td w>承租人</td>';
                            echo '<td >面积</td>';
                            echo '<td>操作</td>';
                            echo '</tr>';
                            foreach ($leases as $key => $value) {
                                $i = $key + 1;
                                echo '<tr>';
                                echo '<td>' . $i . '</td>';
                                echo '<td>' . $value['lessee'] . '</td>';
                                echo '<td>' . $value['lease_area'] . '&nbsp;亩</td>';
                                echo '<td align="left">' . Html::a('更新','#',['class'=>'btn btn-xs','onclick'=>'leaseupdateDialog('.$value['id'].','.$_GET['farms_id'].')','disabled'=>User::disabled()]).'&nbsp;'.Html::a('删除',Url::to(['lease/leasedeleteajax','id'=>$value['id']]),['disabled'=>User::disabled(),'class'=>'btn btn-xs btn-danger','data' => [
                                        'confirm' => '您确定要删除这项吗？',
                                        'method' => 'post',
                                    ],]) .'&nbsp;'.Html::a('租赁合同',Url::to(['print/printleasecontract','lease_id'=>$value['id']]),['class'=>'btn btn-xs btn-success']). '</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                        } else {
                            echo '<table class="table" height="180px">';
                            echo '<tr>';
                            echo '<td class="text text-center"><h3>此农场暂无租赁信息</h3></td>';
                            echo '</tr>';
                            echo '</tbody>';
                            echo '</table>';
                        }
                        ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title">租赁信息</h4>
                        <p class="category"><?php
//                            var_dump(bccomp('123.1','144.4'));
                            if(bccomp(Plantingstructure::getWriteArea($_GET['farms_id'],User::getYear()) , $farm['contractarea']) < 0) {if(User::disabled()) $distate=true;else $distate=false;} else {$distate=true;} echo Html::a('点此添加租赁信息','#',['onclick'=>'leasecreateDialog('.$_GET['farms_id'].')','class'=>'btn btn-xs','disabled'=>$distate])?></p>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <div class="card card-chart">
                    <div class="card-header" data-background-color="white">
                        <div class="card card-plain" id="plantingstructure">
                            <h4>&nbsp;&nbsp;<strong>种植结构计划列表</strong></h4>
                            <p class="category text-red"><?php echo '&nbsp;&nbsp;计划种植 '.$areaSum.' 亩';?></p>
                            <div class="card-content table-responsive" style="overflow-y:scroll;width:500px; height:180px;">
                                <?php
                                if($plantingstructure) {
                                    echo '<table class="table table-hover" height="180px">';
                                    echo '<tr>';
                                    echo '<td >序号</td>';
                                    echo '<td >种植者</td>';
                                    echo '<td >作物</td>';
                                    echo '<td>面积</td>';
                                    echo '<td>操作</td>';
                                    echo '</tr>';
                                    foreach ($plantingstructure as $key => $value) {
                                        $goodseedHtml = '';
                                        if($value['goodseed_id']) {
                                            $goodseedHtml = '<i class="fa fa-pagelines text-success"></i>';
                                        }
                                        $i = $key + 1;
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        if($value['lease_id']) {
                                            echo '<td>' . \app\models\Lease::find()->where(['id'=>$value['lease_id']])->one()['lessee'] . '</td>';
                                        } else {
                                            echo '<td>' . $farm['farmername'] . '</td>';
                                        }
                                        echo '<td>' . Plant::find()->where(['id'=>$value['plant_id']])->one()['typename'] .$goodseedHtml. '<span id="is-goodseed_'.$value["id"].'">';
                                        if(Goodseedinfo::isGoodseed($value["id"])) {
                                            echo '<i class="fa fa-pagelines text-success"></i>';
                                        }
                                        echo '</span></td>';
                                        echo '<td>' . $value['area'] . '&nbsp;亩</td>';
                                        echo '<td>' .Html::a('良种','#',['class'=>'btn btn-xs btn-success','onClick'=>'showGoodseed('.$value['id'].','.$value['farms_id'].','.$value['plant_id'].')']).Html::a('投入品','#',['class'=>'btn btn-xs btn-success','onclick'=>'createT('.$value['id'].')','disabled'=>User::disabled()]).Html::a('<span class="glyphicon glyphicon-trash"></span>','#',['class'=>'btn btn-xs btn-danger','onclick'=>'delPlan('.$value['id'].')','disabled'=>User::disabled()]).'</td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                } else {
                                    echo '<table class="table" height="180px">';
                                    echo '<tr>';
                                    echo '<td class="text text-center"><h3>此农场暂无租赁信息</h3></td>';
                                    echo '</tr>';
                                    echo '</tbody>';
                                    echo '</table>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title">种植结构计划</h4>
                        <p class="category"><?php echo Html::a('点此编辑种植结构','#',['onclick'=>'plantcreateDialog('.$_GET['farms_id'].')','class'=>'btn btn-xs','disabled'=>User::disabled()])?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-chart">
                    <div class="card-header" data-background-color="white">
                        <div class="card card-plain" id="fire">
                            <h4>&nbsp;&nbsp;<strong>防火宣传情况</strong></h4>
                            <p class="category text-red"><?php if(Fireprevention::getSixbfb($_GET['farms_id']) == 3) echo '<span class="text-success">&nbsp;&nbsp;完成</span>'; else echo '<span class="text-danger">&nbsp;&nbsp;未完成</span>';?></p>
                            <div class="card-content table-responsive" style="overflow-y:scroll;width:500px; height:180px;">
                                <?php
                                if($fire) {
//                                    $fireList = Fireprevention::
                                    echo '<table class="table table-hover" height="180px">';
                                    echo '<tr>';
                                    echo '<td><button class="btn btn-raised btn-round btn-rose">
                                    <strong>防火合同</strong>
                                <div class="ripple-container"></div></button></td>';
                                    echo '<td><button class="btn btn-raised btn-round btn-rose">
                                    <strong>安全生产合同</strong>
                                <div class="ripple-container"></div></button></td>';
                                    echo '<td><button class="btn btn-raised btn-round btn-rose">
                                    <strong>环境保护合同</strong>
                                <div class="ripple-container"></div></button></td>';
                                    echo '</tr>';
                                    echo '<tr>';
                                    echo '<td class="text-center" style="font-size:30px">';
                                    if($fire['firecontract']) {
                                        echo '<i class="fa fa-fw fa-check-circle text-success"></i>';
                                    } else {
                                        echo '<i class="fa fa-fw fa-times-circle text-danger"></i>';
                                    }
                                    echo '</td>';
                                    echo '<td class="text-center" style="font-size:30px">';
                                    if($fire['safecontract']) {
                                        echo '<i class="fa fa-fw fa-check-circle text-success"></i>';
                                    } else {
                                        echo '<i class="fa fa-fw fa-times-circle text-danger"></i>';
                                    }
                                    echo '</td>';
                                    echo '<td class="text-center" style="font-size:30px">';
                                    if($fire['environmental_agreement']) {
                                        echo '<i class="fa fa-fw fa-check-circle text-success"></i>';
                                    } else {
                                        echo '<i class="fa fa-fw fa-times-circle text-danger"></i>';
                                    }
                                    echo '</td>';
                                    echo '</tr>';
                                    echo '</table>';
                                } else {
                                    echo '<table class="table" height="180px">';
                                    echo '<tr>';
                                    echo '<td class="text text-center"><h3>此农场还没有完成防火宣传任务</h3></td>';
                                    echo '</tr>';
                                    echo '</table>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title">防火宣传任务</h4>
                        <p class="category"><?php echo Html::a('点此添加防火宣传任务','#',['onclick'=>'firecreateDialog('.$_GET['farms_id'].')','class'=>'btn btn-xs','disabled'=>User::disabled()])?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card card-chart">
                    <div class="card-header" data-background-color="white">
                        <div class="card card-plain" id="fire">
                            <h4>&nbsp;&nbsp;<strong>畜牧养殖情况</strong></h4>
                            <p class="category text-red"></p>
                            <div class="card-content table-responsive" style="overflow-y:scroll;width:500px; height:180px;">
                                <?php
                                if($breedinfos) {
                                //                                    $fireList = Fireprevention::
                                    echo '<table class="table table-hover" height="180px">';
                                    echo '<tr>';
                                    echo '<td>序号</td>';
                                    echo '<td>类型</td>';
                                    echo '<td>数量</td>';
                                    echo '</tr>';
                                    foreach ($breedinfos as $key => $value) {
                                        $type = \app\models\Breedtype::find()->where(['id'=>$value['breedtype_id']])->one();
                                        $i = $key + 1;
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $type['typename'] . '</td>';
                                        echo '<td>' . $value['number'] . $type['unit'].'</td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                } else {
                                    echo '<table class="table" height="180px">';
                                    echo '<tr>';
                                    echo '<td class="text text-center"><h3>此农场还没有填写养殖信息</h3></td>';
                                    echo '</tr>';
                                    echo '</table>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title">畜牧养殖</h4>
                        <p class="category"><?php echo Html::a('点此填写畜牧养殖','#',['onclick'=>'breedcreateDialog('.$_GET['farms_id'].')','class'=>'btn btn-xs','disabled'=>User::disabled()])?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-chart">
                    <div class="card-header" data-background-color="white">
                        <div class="card card-plain" id="fire">
                            <h4>&nbsp;&nbsp;<strong>农产品销售情况(<?= User::getLastYear()?>)</strong></h4>
                            <p class="category text-red"></p>
                            <div class="card-content table-responsive" style="overflow-y:scroll;width:500px; height:180px;">
                                <?php
                                if($sales) {
                                    //                                    $fireList = Fireprevention::
                                    echo '<table class="table table-hover" height="180px">';
                                    echo '<tr>';
                                    echo '<td>序号</td>';
                                    echo '<td>作物</td>';
                                    echo '<td>去向</td>';
                                    echo '<td>产量</td>';
                                    echo '</tr>';
//                                    var_dump($sales);exit;
                                    foreach ($sales as $key => $value) {
                                        $wheretype = \app\models\Saleswhere::find()->where(['id'=>$value['whereabouts']])->one();
                                        $typename = Plant::findOne($value['plant_id']);
                                        $i = $key + 1;
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>'.$typename->typename.'</td>';
                                        echo '<td>' . $wheretype['wherename'] . '</td>';
                                        echo '<td>' . $value['volume'] . '斤</td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                } else {
                                    echo '<table class="table" height="180px">';
                                    echo '<tr>';
                                    echo '<td class="text text-center"><h3>此农场还没有填写销售信息</h3></td>';
                                    echo '</tr>';
                                    echo '</tbody>';
                                    echo '</table>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title">销售信息</h4>
                        <p class="category"><?php echo Html::a('点此填写销售信息','#',['onclick'=>'salescreateDialog('.$_GET['farms_id'].')','class'=>'btn btn-xs','disabled'=>User::disabled()])?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-chart">
                    <div class="card-header" data-background-color="white">
                        <div class="card card-plain" id="fire">
                            <h4>&nbsp;&nbsp;<strong>保险情况(<?= User::getYear()?>)</strong></h4>
                            <p class="category text-red"></p>
                            <div class="card-content table-responsive" style="overflow-y:scroll;width:500px; height:180px;">
                                <?php
                                if($insurance) {
                                    //                                    $fireList = Fireprevention::
                                    $insurancetype = \app\models\Insurancetype::find()->all();
                                    echo '<table class="table table-hover" height="180px" >';
                                    echo '<tr>';
                                    echo '<td>被保险人</td>';
                                    echo '<td>保险面积</td>';
                                    echo '<td>操作</td>';
                                    echo '</tr>';
                                    foreach ($insurance as $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['policyholder'] . '</td>';
                                        echo '<td>' . $value["insuredarea"] . '亩</td>';
                                        echo '<td>' . Html::a('撤消保险', '#', ['class' => 'btn btn-xs btn-danger','id'=>'insurance-del-'.$value['id'], 'onclick' => 'insuranceDel(' . $value['id'] . ')','disabled'=>User::disabled()]) . '</td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                } else {
                                    echo '<table class="table" height="160px">';
                                    echo '<tr>';
                                    echo '<td class="text text-center"><h3>此农场还未参加保险</h3></td>';
                                    echo '</tr>';
                                    echo '</table>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <h4 class="card-title"><?php if($insurance) echo '<span class="text-success">已参加保险</span>'; else { echo '<span class="text-danger">未参加保险</span>';}?></h4>
                        <p class="category">
                            <?php
                            if($showInsuranceButton) {
                                echo Html::a('参加保险', '#', ['class' => 'btn btn-xs btn-success', 'onclick' => 'insuranceCreate(' . $_GET['farms_id'] . ')','disabled'=>User::disabled()]);
                            } else {
                                echo Html::a('参加保险', '#', ['class' => 'btn btn-xs btn-success', 'disabled'=>true]);
                            }
                            ?>
                            </p>
                    </div>
                </div>
            </div>
            <?= Html::hiddenInput('tempid','',['id'=>'temp-id'])?>
            <?= Html::hiddenInput('refresh',false,['id'=>'Refresh'])?>
        </div>

    </section>
</div>
<div id="dialogMsg" title="">

</div>
<div id="goodseed" title="良种">
</div>
<div id="farmMsg" title="信息" class="text-red">
    <table class="table table-bordered table-hover">
        <tr>
            <td>
                对不起!以下三项法人信息必须填写完整,不能为空,请到<strong><font color="red"><?= Html::a('法人信息',Url::to(['farmer/farmercreate','farms_id'=>$farm->id]))?></font></strong>中填写。
            </td>
        </tr>
        <tr>
            <td>
                <?php if($farm->cardid) {
                    if(strlen($farm->cardid) == 18)
                        echo '身份证信息:'.$farm->cardid.'   请检查是否正确。';
                    else {
                        echo '身份证信息不正确,请仔细检查';
                    }
                } else {
                    echo '身份证信息为空,请补充此信息。';
                }?>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                if(empty($farm->address)) {
                    echo '农场位置为空,请补充此信息。';
                } else {
                    echo '农场位置信息:'.$farm->address.'   请检查是否正确。';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                if(empty($farm->longitude) or empty($farm->latitude)) {
                    echo '农场坐标为空,请补充此信息。';
                } else {
                    echo '农场坐标信息:'.$farm->longitude.'  '.$farm->latitude.'    请检查是否正确。';
                }
                ?>
            </td>
        </tr>
    </table>
</div>
<script>
    $(document).ready(function(){
        if(<?= Farms::isFarmsInfo($farm->id)?> == 1) {
            $("#farmMsg").dialog("open");
        }
    });
    $( "#farmMsg" ).dialog({
        autoOpen: false,
        width: 600,
        modal:true,
        closeOnEscape:false,
        open:function(event,ui){$(".ui-dialog-titlebar-close").hide();},
        buttons: [
            {
                text: "确定",
                click: function() {
                    $( this ).dialog( "close" );
                    location.href = "<?= Url::to(['farms/farmsmenu','farms_id'=>$farm->id])?>";
                }

            },

        ]
    });
</script>
<script>
function leasecreateDialog(id)
{

    $('#ui-id-1').html('租赁信息');
    var sum = <?= $overarea?>;
    if(sum == <?= $farm['contractarea']?>) {
        alert('没有剩余面积可以租赁了,请检查其它项是否正确后再试。')
    } else {
        $.get('index.php?r=lease/leasecreateajax', {farms_id: id}, function (body) {
            $('#dialogMsg').html(body);
            $("#dialogMsg").dialog("open");
        });
    }
}
function createT(id)
{

    $('#ui-id-1').html('投入器情况');
    $.get('index.php?r=plantingstructure/plantingstructuret', {id: id}, function (body) {
        $('#dialogMsg').html(body);
        $("#dialogMsg").dialog("open");
    });
}
function delPlan(id)
{
    if(confirm('确定要删除种植结构信息吗？')) {
        $.getJSON('index.php?r=plantingstructure/delplan', {id: id}, function (data) {
            if (data.state)
                window.location.reload();
        });
    }
}
function plantcreateDialog(id)
{

    $('#ui-id-1').html('种植结构计划');
    var sum = <?= $overarea?>;
    $.get('index.php?r=plantingstructure/plantingstructuresixcheck', {farms_id: id}, function (body) {
        $('#dialogMsg').html(body);
        $("#dialogMsg").dialog("open");
    });
}
function insuranceCreate(id)
{

    $.get('index.php?r=insurance/insurancesix', {farms_id: id}, function (body) {
        $('#dialogMsg').html(body);
        $("#dialogMsg").dialog("open");
    });
}
function insuranceDel(id)
{

    var msg = "您真的确定要撤消保险吗？";
    if (confirm(msg)==true){
        $.getJSON('index.php?r=insurance/insuranceplansixdelete', {id: id}, function (data) {
            if(data.state) {
                window.location.reload();
            }
        });
    }else{
        return false;
    }

}
function salescreateDialog(id)
{

    $('#ui-id-1').html('种植结构计划');
    var sum = <?= $overarea?>;
    $.get('index.php?r=sales/salesindexajax', {farms_id: id}, function (body) {
        $('#dialogMsg').html(body);
        $("#dialogMsg").dialog("open");
    });
}
function leaseupdateDialog(lease_id,id)
{

    $('#temp-id').val(lease_id);
    $('#ui-id-1').html('租赁信息');
    $.get('index.php?r=lease/leaseupdateajax', {id:lease_id,farms_id: id}, function (body) {
        $('#dialogMsg').html(body);
        $("#dialogMsg").dialog("open");
    });
}

function breedcreateDialog(id)
{

    $('#ui-id-1').html('畜牧养殖情况');
    $.get('index.php?r=breed/breedcreateajax', {farms_id: id}, function (body) {
        $('#dialogMsg').html(body);
        $("#dialogMsg").dialog("open");
    });
}
function firecreateDialog(id)
{

    $('#ui-id-1').html('防火宣传');
    $.get('index.php?r=fireprevention/firepreventionajax', {farms_id: id}, function (body) {
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
            var form = $('form').serializeArray();
            console.log($.toJSON(form));
//            alert($('#temp-id').val());
//            if($('#Refresh').val()) {
//                window.location.reload();
//            }
            $.getJSON('index.php?r=sixcheck/sixchecksave',{'value':$.toJSON(form),'farms_id':"<?= $_GET['farms_id']?>",'id':$('#temp-id').val()},function (data) {
                console.log(data);
                if(data.state && data.isBack == '') {
                    window.location.reload();
                } else {
                    if(data.isBack == 'sales') {
                        salescreateDialog("<?= $_GET['farms_id']?>");
                    }
                }
            });
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
function showGoodseed(planting_id,farms_id,plant_id) {
    $.get('index.php?r=goodseedinfo/goodseedinfolistajax', {'farms_id':farms_id,'planting_id':planting_id,'plant_id':plant_id}, function (body) {
//            console.log(body);
        $('#goodseed').html(body);
        $("#goodseed").dialog("open");
    });
}

$( "#goodseed" ).dialog({
    autoOpen: false,
    width: 800,
    height:600,
    modal:true,
//        closeOnEscape:false,
//        open:function(event,ui){$(".ui-dialog-titlebar-close").hide();},
    buttons: [
        {
            text: "确定",
            click: function() {
                $( this ).dialog( "close" );
                var tempid = $('#temp-id').val();
                var planting_id = $('#planting-id').val();
                var type_id = new Array();
                var area_num = new Array();
                for(i=1;i<=tempid;i++) {
                    var t = $('#type_'+i).val();
                    if(t !== undefined || t !== '') {
                        type_id[i] = t;
                    }
                    var area = $('#area_'+i).val();
                    if(area !== undefined || area !== '') {
                        area_num[i] = area;
                    }
                }
                $.getJSON('index.php?r=goodseedinfo/goodseedinfosave2',{'typename':type_id.join(','),'area':area_num.join(','),'planting_id':planting_id},function (data) {
                    if(data.save > 0) {
                        $('#is-goodseed_' + planting_id).html('<i class="fa fa-pagelines text-success"></i>');
                    } else {
                        $('#is-goodseed_' + planting_id).html('');
                    }
                });
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

<script language="javascript" type="text/javascript">
    var LODOP; //声明为全局变量

    function myPREVIEW() {
        CreatePage();
        LODOP.PREVIEW();
    };
    function myPREVIEWpage() {
        CreatePagepage();
        LODOP.PREVIEW();
    };
    function myPREVIEWone(farms_id,farmerpinyin) {
        CreateOnePage(farms_id,farmerpinyin);
        LODOP.PREVIEW();
    };
    function myDesign(farms_id,farmerpinyin) {
        CreateOnePage(farms_id,farmerpinyin);
        LODOP.PRINT_DESIGN();

    };

    function CreateOnePage(farms_id,farmerpinyin){
        LODOP=getLodop();
        LODOP.PRINT_INITA(0,0,"297mm","210mm","打印控件功能");
//			LODOP.SET_PRINT_PAGESIZE('2','297mm','210mm');
        if(<?= $isFarmerPlanting?>) {
            LODOP.NewPage();
            LODOP.ADD_PRINT_TBURL(100,13,1200,120,"index.php?r=print/sixtable&farms_id="+farms_id);
//            LODOP.ADD_PRINT_TBURL(147,16,1200,120,"index.php?r=print/fire&farms_id="+farms_id);
            LODOP.ADD_PRINT_TBURL(173,10,1206,540,"index.php?r=print/farmertable&farms_id="+farms_id);
//            LODOP.ADD_PRINT_TBURL(526,16,1200,500,"index.php?r=print/beizhu");
            LODOP.ADD_PRINT_TBURL(706,10,1078,863,"<?= Url::to(['print/downtable'])?>");
            LODOP.ADD_PRINT_TEXT(10,250,657,33,"<?= User::getYear()?>年度岭南生态农业示范区农业基础数据调查表");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",20);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(80,20,341,24,"管理区：<?= ManagementArea::getNowManagementareaName()?>");
            LODOP.ADD_PRINT_TEXT(80,920,160,25,"单位：亩、升、斤、头");
            LODOP.ADD_PRINT_TEXT(720,1020,160,25,farmerpinyin);
            LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",20);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
//            LODOP.ADD_PRINT_SHAPE(4,286,18,1050,8,0,1,"#C0C0C0")
        }
        <?php
            foreach($leases as $key => $lease):
        $k = $key + 1;
        ?>
        LODOP.NewPage();
        LODOP.ADD_PRINT_TBURL(100,13,1200,120,"index.php?r=print/sixtable&farms_id="+farms_id);
//        LODOP.ADD_PRINT_TBURL(147,16,1200,120,"index.php?r=print/fire&farms_id="+farms_id);
        LODOP.ADD_PRINT_TBURL(173,10,1206,520,"index.php?r=print/leasetable&farms_id="+farms_id+'&lease_id=<?= $lease['id']?>');
//        LODOP.ADD_PRINT_TBURL(526,16,1200,500,"index.php?r=print/beizhu");
        LODOP.ADD_PRINT_TBURL(706,10,1078,863,"<?= Url::to(['print/downtable'])?>");
        LODOP.ADD_PRINT_TEXT(10,250,657,33,"<?= User::getYear()?>年度岭南生态农业示范区农业基础数据调查表");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",20);
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        LODOP.ADD_PRINT_TEXT(80,32,341,24,"管理区：<?= ManagementArea::getNowManagementareaName()?>");
        LODOP.ADD_PRINT_TEXT(80,920,160,25,"单位：亩、升、斤、头");
        LODOP.ADD_PRINT_TEXT(720,1000,160,25,farmerpinyin+'-'+<?= $k?>);
        LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);
        LODOP.SET_PRINT_STYLEA(0,"FontSize",20);
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
//        LODOP.ADD_PRINT_SHAPE(4,286,18,1050,8,0,1,"#C0C0C0");
        <?php endforeach;?>

//        if(<?//= !$isFarmerPlanting?>// || <?//= !$islease?>//) {
//            LODOP.NewPage();
//            LODOP.ADD_PRINT_TBURL(100,13,1200,120,"index.php?r=print/sixtable&farms_id="+farms_id);
////            LODOP.ADD_PRINT_TBURL(147,16,1200,120,"index.php?r=print/fire&farms_id="+farms_id);
//            LODOP.ADD_PRINT_TBURL(173,10,1206,540,"index.php?r=print/farmertable&farms_id="+farms_id);
////            LODOP.ADD_PRINT_TBURL(526,16,1200,500,"index.php?r=print/beizhu");
//            LODOP.ADD_PRINT_TBURL(706,10,1078,863,"<?//= Url::to(['print/downtable'])?>//");
//            LODOP.ADD_PRINT_TEXT(10,250,657,33,"<?//= User::getYear()?>//年度岭南生态农业示范区农业基础数据调查表");
//            LODOP.SET_PRINT_STYLEA(0,"FontSize",20);
//            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
//            LODOP.ADD_PRINT_TEXT(80,20,341,24,"管理区：<?//= ManagementArea::getNowManagementareaName()?>//");
//            LODOP.ADD_PRINT_TEXT(80,920,160,25,"单位：亩、升、斤、头");
//            LODOP.ADD_PRINT_TEXT(720,1020,160,25,farmerpinyin);
//            LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);
//            LODOP.SET_PRINT_STYLEA(0,"FontSize",20);
//            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
////            LODOP.ADD_PRINT_SHAPE(4,286,18,1050,8,0,1,"#C0C0C0")
//        }
    };
</script>