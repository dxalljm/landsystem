<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\helpers\ActiveFormrdiv;
use app\models\Farms;
use app\models\User;
use app\models\insurancecompany;
use app\models\Machineoffarm;
use app\models\Machine;
use app\models\Machinetype;
use app\models\Plant;
use app\models\Lease;
use app\models\Insurance;
use app\models\Fireprevention;
use app\models\Plantingstructure;
use app\models\Theyear;
use frontend\helpers\htmlColumn;
use yii\helpers\Url;
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
<script src="js/cardVerification.js"></script>
<div class="sixcheck-index">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3>农业基础数据调查表<font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                    <!--                    <div class="box-body">-->

                    <?= Html::hiddenInput('row',1,['id'=>'rows'])?>
                    <table>
                        <tr>
                            <td><?= html::a('打印',Url::to(['print/printsixcheck','farms_id'=>$_GET['farms_id']]),['class'=>'btn btn-success'])?></td>
                            <td>&nbsp;</td>
                            <td>
                                <?= Html::a('增加农业机械情况','#', [
//                                'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/machineoffarm/machineoffarmsix','farms_id'=>$_GET['farms_id']])."','','width=1200,height=800,top=50,left=80, location=no, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
                                    'onclick' => "showMachineModel(".$_GET['farms_id'].")",
                                    "class"=>'btn btn-success'
                                ]);?>
                            </td>
                        </tr>
                    </table> <br>
                    <?= Farms::showFarminfo2($_GET['farms_id'])?>

<!--                    --><?php //var_dump($farm);exit; ?>
                    <?php

                        $leasepArea = 0.0;
                        $farmerpArea = 0.0;
                        $farmerArea = 0.0;
                        $leaseArea = 0.0;
                        $allarea = $farm->contractarea;
                        foreach ($leaseData as $value) {
                            $leasepArea = Plantingstructure::find()->where(['lease_id'=>$value['id']])->sum('area');
                            $leaseArea += $value['lease_area'];
                        }
                        $farmerpArea = (float)Plantingstructure::find()->where(['lease_id'=>0,'farms_id'=>$_GET['farms_id']])->sum('area');
                        $farmerArea = (float)bcsub($allarea , $leaseArea,2);
//        var_dump($farmerpArea);var_dump($farmerArea);
                        if(bccomp($farmerArea,$farmerpArea)) {
                            $isShowFarmerAdd = true;
                        } else {
                            $isShowFarmerAdd = false;
                        }
                        if(bccomp($leaseArea,$leasepArea)) {
                            $isShowLeaseAdd = true;
                        } else {
                            $isShowLeaseAdd = false;
                        }
                        ?>
                    <div style="border-style:solid;border:3px solid #03ba0c;">
                    <table class="table table2-bordered table-hover">
                        <tr bgcolor="#faebd7">
                            <td width="12%" colspan="2" align="center"><strong>法人</strong></td>
                            <td colspan="2" align="center"><strong>种植面积</strong></td>
                            <td width="22%" align="center"><strong>操作</strong></td>
                        </tr>
                        <tr>
                            <td width="12%" colspan="2" align="center"><?= $farm->farmername ?></td>
                            <td colspan="2" align="center"><?= $farmerArea?>亩</td>
                            <td align="center"></td>
                        </tr>
                        <?php
                        $farmerplantings = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>0])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
                        if($farmerplantings) {
                        foreach($farmerplantings as $v) {
                            ?>
                            <tr>
                                <td colspan="2" align="center">|_</td>

                                <td align="center">种植作物面积：<?= $v['area']?>亩</td>
                                <td align="center">作物：<?= Plant::find()->where(['id'=>$v['plant_id']])->one()['typename']?></td>
                                <td align="center"><?php htmlColumn::showButton(['onclick'=>'showModel('.$v['id'].','.$v['lease_id'].','.$v['farms_id'].')']);?></td>
                            </tr>
                        <?php }?>
                    </table>
                    <?php
                        $insurancelist = [];
                        $msg = '';
                        foreach ($insuranceData as $insurance) {
                            if ($insurance->cardid == $farm->cardid) {
                                $insurancelist[] = $insurance;
                            } else {
                                $msg = '没有参加种植业保险';
                            }
                        }
//                    var_dump($insurancelist);exit;

                    ?>
                    <table class="table table2-bordered table-hover">
                        <tr>
                            <td colspan="9" align="center" class=""><h4>种植业保险</h4></td>
                        </tr>
                        <tr align="center">
                            <td>投保人</td>
                            <td>被保险人</td>
                            <td>投保面积</td>
                            <td>承保公司</td>
                            <td>投保大豆面积</td>
                            <td>投保小麦面积</td>
                            <td>投保其他面积</td>
                            <td>状态</td>
                            <td>操作</td>
                        </tr>
                        <?php if($insurancelist) {
                            foreach ($insurancelist as $insurance) {

                        ?>
                        <tr align="center">
                            <td><?= $insurance['policyholder']?></td>
                            <td><?= $insurance['policyholder']?></td>
                            <td><?= $insurance['insuredarea']?></td>
                            <td><?= Insurancecompany::find()->where(['id'=>$insurance['company_id']])->one()['companynname'];?></td>
                            <td><?= $insurance['insuredsoybean']?></td>
                            <td><?= $insurance['insuredwheat']?></td>
                            <td><?= $insurance['insuredother']?></td>
                            <td><?php switch ($insurance['state']) {
                                    case 1:
                                        echo '完成';
                                        break;
                                    case 0:
                                        if($insurance['fwdtstate'])
                                            echo '服务大厅审核通过';
                                        else
                                            echo '审核中';
                                        break;
                                    case -1;
                                        echo '取消';
                                }?></td>
                            <td><?php
                                if(!$insurance['state'])
                                    echo Html::a('撤消申请',Url::to(['insurance/insurancedelete','id'=>$insurance['id']]),[
                                        'class'=>'btn btn-danger btn-xs',
                                        'data' => [
                                            'confirm' => '您确定要撤消申请吗？',
                                            'method' => 'post',
                                        ],
                                    ]);
                                else
                                    echo Html::a('撤消申请','#',['class'=>'btn btn-danger btn-xs','disabled'=>true]);
                                ?></td>
                        </tr>
                        <?php }} else {
                                echo '<tr><td colspan="9">&nbsp;&nbsp;没有参加种植业保险</td></tr>';
                            }?>
                    </table>
                    <?php }?>
                        </div>
                    <br>
                <?php
                if($leaseData) {
                    $isLeaseViewAdd = 0;
                    $msg = '';
                    foreach($leaseData as $val) {
                        $insurancelist2 = Insurance::find()->where(['cardid'=>$val['lessee_cardid'],'farms_id'=>$farm->id])->one();
                        $isLeaseViewAdd += Plantingstructure::find()->where(['lease_id'=>$val['id']])->one()['area'];
                    ?>
                    <div style="border-style:solid;border:3px solid #03ba0c;">
                    <table class="table table2-bordered table-hover">
                        <tr bgcolor="#faebd7">
                            <td width="12%" colspan="2" align="center"><strong>承租人</strong></td>
                            <td colspan="2" align="center"><strong>承租面积</strong></td>
                            <td width="22%" align="center"><strong>操作</strong></td>
                        </tr>
                            <tr>
                                <td colspan="2" align="center"><?= $val['lessee'] ?></td>
                                <td colspan="2"  align="center"><?= $val['lease_area']?>亩</td>
                                <td align="center"><?php if($isShowLeaseAdd) {?><?= Html::a('添加','index.php?r=plantingstructure/plantingstructurecreate&lease_id='.$val['id'].'&farms_id='.$_GET['farms_id'], [
                                        'id' => 'employeecreate',
                                        'title' => '给'.$val['lessee'].'添加',
                                        'class' => 'btn btn-primary',
                                    ]);?><?php }?></td>
                                <?php
                                $plantData = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>$val['id']])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
                                ?>
                            </tr>
                            <?php

                            foreach($plantData as $v) {
                                ?>
                                <tr>
                                    <td colspan="2" align="center">|_</td>
                                    <td align="center">种植作物面积：<?= $v['area']?>亩</td>
                                    <td align="center">作物：<?= Plant::find()->where(['id'=>$v['plant_id']])->one()['typename']?></td>
                                    <td align="center"><?php htmlColumn::showButton(['onclick'=>'showModel('.$v['id'].','.$v['lease_id'].','.$v['farms_id'].')']);?></td>
                                </tr>
                                <?php }?>
                    </table>
                        <table class="table table2-bordered table-hover leasetable" style="border-top:solid #94019d;">
                            <tr>
                                <td colspan="30" align="center" class=""><h4>补贴归属</h4></td>
                                <td colspan="2" align="center"><?php htmlColumn::showButton(['onclick'=>'showBtModel('.$val['id'].','.$val['farms_id'].')']);?></td>
                            </tr>
                            <tr>
                                <td colspan="8" align="center" class="">综合直补</td>
                                <td colspan="8" align="center" class="">大豆差价补贴</td>
                                <td colspan="8" align="center" class="">良种补贴</td>
                                <td colspan="8" align="center" class="">其他新增补贴</td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right" width="6%">法人占比</td>
                                <td colspan="2" align="left" ><?= $val['zhzb_farmer'];?></td>
                                <td colspan="2" align="right" width="7%">承租人占比</td>
                                <td colspan="2" align="left"><?= $val['zhzb_lessee'];?></td>

                                <td colspan="2" align="right" width="6%">法人占比</td>
                                <td colspan="2" align="left" ><?= $val['ddcj_farmer'];?></td>

                                <td colspan="2" align="right" width="7%">承租人占比</td>
                                <td colspan="2" align="left"><?= $val['ddcj_lessee'];?></td>
                                <td colspan="2" align="right" width="6%">法人占比</td>
                                <td colspan="2" align="left"><?= $val['goodseed_farmer'];?></td>
                                <td colspan="2" align="right" width="7%">承租人占比</td>
                                <td colspan="2" align="left"><?= $val['goodseed_lessee'];?></td>
                                <td colspan="2" align="right" width="6%">法人占比</td>
                                <td colspan="2" align="left" ><?= $val['new_farmer'];?></td>
                                <td colspan="2" align="right" width="7%">承租人占比</td>
                                <td colspan="2" align="left"><?= $val['new_lessee'];?></td>
                            </tr>
                        </table>
                        <table class="table table2-bordered table-hover" style="border-top:solid #94019d;">
                            <tr>
                                <td colspan="9" align="center" class=""><h4>种植业保险</h4></td>
                            </tr>
                            <tr align="center">
                                <td>投保人</td>
                                <td>被保险人</td>
                                <td>投保面积</td>
                                <td>承保公司</td>
                                <td>投保大豆面积</td>
                                <td>投保小麦面积</td>
                                <td>投保其他面积</td>
                                <td>状态</td>
                                <td>操作</td>
                            </tr>
                    <?php if(isset($insurancelist2) and $insurancelist2) {
                        ?>
                            <tr align="center">
                                <td><?= $insurancelist2['policyholder']?></td>
                                <td><?= $insurancelist2['policyholder']?></td>
                                <td><?= $insurancelist2['insuredarea']?></td>
                                <td><?= Insurancecompany::find()->where(['id'=>$insurancelist2['company_id']])->one()['companynname'];?></td>
                                <td><?= $insurancelist2['insuredsoybean']?></td>
                                <td><?= $insurancelist2['insuredwheat']?></td>
                                <td><?= $insurancelist2['insuredother']?></td>
                                <td><?php switch ($insurancelist2['state']) {
                                                case 1:
                                                    echo '完成';
                                                    break;
                                                case 0:
                                                    if($insurancelist2['fwdtstate'])
                                                        echo '服务大厅审核通过';
                                                    else
                                                        echo '审核中';
                                                    break;
                                                case -1;
                                                    echo '取消';
                                            }?></td>
                                <td><?php
                                            if(!$insurancelist2['state'])
                                                echo Html::a('撤消申请',Url::to(['insurance/insurancedelete','id'=>$insurancelist2['id']]),[
                                                    'class'=>'btn btn-danger btn-xs',
                                                    'data' => [
                                                        'confirm' => '您确定要撤消申请吗？',
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                            else
                                                echo Html::a('撤消申请','#',['class'=>'btn btn-danger btn-xs','disabled'=>true]);
                                ?></td>
                            </tr>
                        <?php } else {
                            echo '<tr><td colspan="9">&nbsp;&nbsp;没有参加种植业保险</td></tr>';
                        }?>
                        </table>
                        </div>
                        <br>
                     <?php }?>

                    </div>
                    <?php }?>
                    <div id="add-lease"></div>
                    <table><tr><td>&nbsp;</td></tr></table>
                        <div style="border-style:solid;border:3px solid #0483E9;">
                            <table class="table table2-bordered table-hover" id="fire">
                                <tr>
                                    <td colspan="5" class="text text-center"><h4>防火工作</h4></td>
                                    <td class="text text-center"><?php htmlColumn::showButton(['onclick'=>'showFireModel('.$_GET['farms_id'].')']);?></td>
                                </tr>
                                <tr>
                                    <td align="center">签订防火合同</td>
                                    <td align="center">签订安全生产合同</td>
                                    <td align="center">签订环境保护合同</td>
                                    <td align="center">签订野外作业证</td>
                                    <td align="center">签订防火宣传</td>
                                    <td align="center">检查记录</td>
                                </tr>
                                <tr>
                                    <td align="center"><?php if($fireData['firecontract']) echo '是';else echo '否';?></td>
                                    <td align="center"><?php if($fireData['safecontract']) echo '是';else echo '否';?></td>
                                    <td align="center"><?php if($fireData['environmental_agreement']) echo '是';else echo '否';?></td>
                                    <td align="center"><?php if($fireData['fieldpermit']) echo '是';else echo '否';?></td>
                                    <td align="center"><?php if($fireData['leaflets']) echo '是';else echo '否';?></td>
                                    <td align="center"><?php if($fireData['rectification_record']) echo '是';else echo '否';?></td>
                                </tr>
                            </table>
                        </div>
                    <table><tr><td>&nbsp;</td></tr></table>
                    <?php if($breedData) {?>
                        <div style="border-style:solid;border:3px solid #1d03ba;">
                            <table class="table table2-bordered table-hover">
                                <tr>
                                    <td colspan="5" class="text text-center"><h4>养殖信息</h4></td>
                                    <td align="center"><?php htmlColumn::showButton(['onclick'=>'showbreedModel('.$breedData['farms_id'].')']);?></td>
                                </tr>
                                <tr>
                                    <td width=15% align='right'>养殖场名称</td>
                                    <td align='left'><?= $breedData['breedname'] ?></td>
                                    <td align='right'>养殖位置</td>
                                    <td align='left'><?= $breedData['breedaddress'] ?></td>
                                    <td align='right'>是否示范户</td>
                                    <td align='left'><?php if($breedData['is_demonstration']) echo '是'; else echo '否';?></td>

                                </tr>
                            </table>
                            <?php $breedinfos = \app\models\Breedinfo::find()->where(['breed_id'=>$breedData['id']])->all();
                                if($breedinfos) {
                            ?>
                            <table class="table table2-bordered table-hover" id="breedtype">
                                <tbody>
                                <tr>
                                    <td align='center'>牲畜类型</td>
                                    <td align='center'>养殖数量</td>
                                    <td align='center'>基础投资</td>
                                    <td align='center'>圈舍面积</td>
                                </tr>
                                <?php
                                    foreach ($breedinfos as $breedinfo) {
                                ?>
                                <tr>
                                    <td width="15%" align='center'><?= \app\models\Breedtype::find()->where(['id'=>$breedinfo['breedtype_id']])->one()['typename']?></td>
                                    <td align='center'><?= $breedinfo['number']?></td>
                                    <td align='center'><?= $breedinfo['basicinvestment']?></td>
                                    <td align='center'><?= $breedinfo['housingarea']?></td>
                                </tr>
                                <?php }?>
                                </tbody>
                            </table>
                            <?php }}?>
                            </div>
                            <br>
                            <?php
                            $machines = Machineoffarm::find()->where(['cardid'=>$farm->cardid])->all();
                            ?>
                            <div style="border-style:solid;border:3px solid #1d03ba;">
                                <table class="table table2-bordered table-hover">
                                    <tr>
                                        <td colspan="3" align='center'>农机品目</td>
                                        <td align='center'>机具名称</td>
                                        <td align='center'>型号</td>
                                        <td align='center'>数量</td>
                                        <td align='center'>购置年份</td>
                                    </tr>
                                    <?php
                                    foreach ($machines as $machine) {
                                        $m = Machine::find()->where(['id'=>$machine['machine_id']])->one();
                                        $type = Machinetype::find()->where(['id'=>$machine['machinetype_id']])->one();
                                        ?>
                                        <tr>
                                            <td colspan="3" align='center'><?php if($type) echo $type['typename'];?></td>
                                            <td align='center'><?= $machine['machinename']?></td>
                                            <td align='center'><?php if($m) echo $m['implementmodel'];?></td>
                                            <td align='center'>1</td>
                                            <td align='center'><?= $machine['acquisitiontime']?></td>
                                        </tr>
                                    <?php }?>
                                </table>
                            </div>
                        </div>
                    <br>
                </div>
            </div>
        </div>
</div>
</section>

<?php \yii\bootstrap\Modal::begin([
    'id' => 'modal-form',
    'size'=>'modal-big',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
    'options' => ['data-keyboard' => 'false', 'data-backdrop' => 'static']
    //'header' => '<h4 class="modal-title"></h4>',
]);
\yii\bootstrap\Modal::end(); ?>
<div id="js"></div>
</div>
<script>

    var father_id = '';
    //添加养殖种类
    $('#add-breedtype').click(function () {
        var template = $('#breedtype-template').html();
        $('#breedtype > tbody').append(template);
    });

    function showModel(id,lease_id,farms_id) {
        $.get('index.php?r=plantingstructure/plantingstructureupdateajax', {id: id,lease_id:lease_id,farms_id:farms_id}, function (body) {
            // 显示modal
            $('#modal-form').modal('show');
            // 填充内容
//               $('.modal-body').html('');
            $('.modal-body').html(body);
        });
    }

    function showBtModel(id,farms_id) {
        $.get('index.php?r=lease/leaseupdateajax', {id: id,farms_id:farms_id}, function (body) {
            // 显示modal
            $('#modal-form').modal('show');
            // 填充内容
//               $('.modal-body').html('');
            $('.modal-body').html(body);
        });
    }

    function showbreedModel(farms_id) {
        $.get('index.php?r=breed/breedcreateajax', {farms_id:farms_id}, function (body) {
            // 显示modal
            $('#modal-form').modal('show');
            // 填充内容
//               $('.modal-body').html('');
            $('.modal-body').html(body);
        });
    }

    function showFireModel(farms_id) {
        $.get('index.php?r=fireprevention/firepreventioncreateajax', {farms_id:farms_id}, function (body) {
            // 显示modal
            $('#modal-form').modal('show');
            // 填充内容
//               $('.modal-body').html('');
            $('.modal-body').html(body);
        });
    }

</script>