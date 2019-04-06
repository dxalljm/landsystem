<?php
namespace frontend\controllers;

use dosamigos\datetimepicker\DateTimePicker;
use app\models\Lease;
use app\models\Mainmenu;
use app\models\Plant;
use app\models\Tables;
use yii\helpers\Html;
use yii;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\User;
use app\models\Farmer;
use frontend\helpers\grid\GridView;
use app\models\Plantingstructurecheck;
use app\models\Theyear;
use app\models\Help;
use frontend\helpers\htmlColumn;
use frontend\helpers\ActiveFormrdiv;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<script type="text/javascript" src="js/jquery.json-2.2.min.js"></script>
<script src="vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
<script src="vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<div class="farms-index">

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3>
                            <?php $farms = Farms::find()->where(['id'=>$_GET['farms_id']])->one();
                            echo $_GET['farms_id'];
                            echo Html::hiddenInput('farms_id',$farms['id'],['id'=>'farmsID']);
                            $plantings = Plantingstructurecheck::find()->where(['farms_id'=>$_GET['farms_id']])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();?>
                            <?= Help::showHelp3('种植结构核实数据','plantingstructurecheck-index')?><font color="red">(<?= User::getYear()?>年度)</font>&nbsp;
                        </h3>
                    </div>
                    <div class="box-body">
                        <?php $form = ActiveFormrdiv::begin(); ?>
                        <script type="text/javascript">
                            function openwindows(url)
                            {
                                window.open(url,'','width=1200,height=600,top=50,left=80, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');
                                self.close();
                            }
                        </script>
                        <?= Farms::showFarminfo_sm($_GET['farms_id'])?>
                        <?php
                        $area = $farms['contractarea'];
                        if($noarea > 0) {
                            ?>
                            <table class="table table-bordered table-hover" id="farmerinfo">
                                <tr>
                                    <td align="right"><h4>增加租赁信息</h4></td>
                                    <td><?= Html::a('添加租赁者', '#', ['id' => 'addLease', 'class' => 'btn btn-default', 'onclick' => 'showDialoglease(' . $farms['id'] . ')']); ?></td>
                                </tr>
                            </table>
                            <table border="1" width="100%" cellpadding="0" cellspacing="0">
                                <tr bgcolor="#faebd7">
                                    <td width="8%" rowspan="2" align="center"><strong>法人</strong></td>
                                    <td width="12%" rowspan="2" align="center"><strong>种植面积</strong></td>
                                    <td width="5%" rowspan="2" align="center"><strong>大豆</strong></td>
                                    <td width="10%" colspan="2" align="center"><strong>补贴归属</strong></td>
                                    <td width="5%" rowspan="2" align="center"><strong>玉米</strong></td>
                                    <td width="" colspan="2" align="center"><strong>补贴归属</strong></td>
                                    <td width="5%" rowspan="2" align="center"><strong>小麦</strong></td>
                                    <td width="5%" rowspan="2" align="center"><strong>马铃薯</strong></td>
                                    <td width="5%" rowspan="2" align="center"><strong>杂豆</strong></td>
                                    <td width="5%" rowspan="2" align="center"><strong>北药</strong></td>
                                    <td width="5%" rowspan="2" align="center"><strong>蓝莓</strong></td>
                                    <td width="5%" rowspan="2" align="center"><strong>其它</strong></td>
                                    <td width="9%" rowspan="2" align="center"><strong>核实日期</strong></td>
                                    <td rowspan="2" align="center"><strong>操作</strong></td>
                                </tr>
                                <tr bgcolor="#faebd7">
                                    <td align="center">法人</td>
                                    <td align="center">种植者</td>
                                    <td align="center">法人</td>
                                    <td align="center">种植者</td>
                                </tr>
                                <tr>
                                    <td align="center"><?= $farms['farmername'] ?></td>
                                    <?php $plantingsum = Plantingstructurecheck::find()->where(['farms_id' => $_GET['farms_id'], 'lease_id' => 0, 'year' => User::getYear()])->sum('area');
                                    if (empty($plantingsum)) {
                                        $plantingsum = 0;
                                    }

                                    if ($leases) $area = $noarea;
                                    ?>
                                    <td align="center"
                                        id="areasum"><?= $area?>
                                        (<span id="inputsum"><?= sprintf("%.2f", $plantingsum) ?></span>)
                                        亩
                                    </td>
                                    <?php
                                    $dd = 0;
                                    $ym = 0;
                                    $xm = 0;
                                    $mls = 0;
                                    $zd = 0;
                                    $by = 0;
                                    $lm = 0;
                                    $other = 0;
                                    $farmerplantings = Plantingstructurecheck::find()->where(['farms_id' => $_GET['farms_id'], 'lease_id' => 0, 'year' => User::getYear()])->all();
                                    if ($farmerplantings) {

                                        foreach ($farmerplantings as $v) {
                                            $plant = Plant::findOne($v['plant_id']);

                                            switch ($plant->typename) {
                                                case '大豆':
                                                    $dd = $v['area'];
                                                    break;
                                                case '玉米':
                                                    $ym = $v['area'];
                                                    break;
                                                case '小麦':
                                                    $xm = $v['area'];
                                                    break;
                                                case '马铃薯':
                                                    $mls = $v['area'];
                                                    break;
                                                case '杂豆':
                                                    $zd = $v['area'];
                                                    break;
                                                case '北药':
                                                    $by = $v['area'];
                                                    break;
                                                case '蓝莓':
                                                    $lm = $v['area'];
                                                    break;
                                                case '其它':
                                                    $other = $v['area'];
                                                    break;
                                            }
                                        }
                                    }
//                                    echo Html::hiddenInput('area',$sum);
                                    ?>
                                    <td align="center">
                                        <?= html::textInput('dd', $dd, ['id' => 'ddarea', 'class' => 'form-control']) ?>
                                    </td>
                                    <td width="8%"
                                        align="center"><?= html::textInput('ddcj_farmer', '100%', ['id' => 'ddcj-farmer', 'class' => 'form-control','disabled'=>true]) ?></td>
                                    <td width="8%"
                                        align="center"><?= html::textInput('ddcj_lessee', '0%', ['id' => 'ddcj-lessee', 'class' => 'form-control','disabled'=>true]) ?></td>
                                    <td align="center"><?= html::textInput('ym', $ym, ['id' => 'ymarea', 'class' => 'form-control']) ?></td>
                                    <td width="8%"
                                        align="center"><?= html::textInput('ymcj_farmer', '100%', ['id' => 'ymcj-farmer', 'class' => 'form-control','disabled'=>true]) ?></td>
                                    <td width="8%"
                                        align="center"><?= html::textInput('ymcj_lessee', '0%', ['id' => 'ymcj-lessee', 'class' => 'form-control','disabled'=>true]) ?></td>
                                    <td align="center">
                                        <?= html::textInput('xm', $xm, ['id' => 'xmarea', 'class' => 'form-control']) ?>
                                    </td>
                                    <td align="center">
                                        <?= html::textInput('mls', $mls, ['id' => 'mlsarea', 'class' => 'form-control']) ?>
                                    </td>
                                    <td align="center">
                                        <?= html::textInput('zd', $zd, ['id' => 'zdarea', 'class' => 'form-control']) ?>
                                    </td>
                                    <td align="center">
                                        <?= html::textInput('by', $by, ['id' => 'byarea', 'class' => 'form-control']) ?>
                                    </td>
                                    <td align="center">
                                        <?= html::textInput('lm', $lm, ['id' => 'lmarea', 'class' => 'form-control']) ?>
                                    </td>
                                    <td align="center">
                                        <?= html::textInput('other', $other, ['id' => 'otherarea', 'class' => 'form-control']) ?>
                                    </td>
                                    <td align="center">
                                        <?= DateTimePicker::widget([
                                            'name' => 'verify',
                                            'value' => date('Y-m-d'),
                                            'inline' => false,
                                            'language'=>'zh-CN',

                                            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'minView' => 2,
                                                'maxView' => 4,
                                                'format' => 'yyyy-mm-dd'
                                            ]
                                        ]); ?>
                                    </td>
                                    <td align="center">
                                        <?php
                                        if(Plantingstructurecheck::find()->where(['farms_id'=>$farms['id'],'lease_id'=>0,'year'=>User::getYear()])->sum('area')) {
                                            echo html::a('重置','#',['class'=>'btn btn-danger','onclick'=>'deletefarmer('.$farms['id'].')']);
                                        } else {
                                            echo html::a('重置','#',['class'=>'btn btn-danger','onclick'=>'deletefarmercontent()']);
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <?php
                        }
                            if($leases) {
                                ?>
                                <table border="1" width="100%" cellpadding="0" cellspacing="0">
                                    <tr bgcolor="#faebd7">
                                        <td width="8%" rowspan="2" align="center" bgcolor="#D1F5B2"><strong>租种者</strong></td>
                                      <td width="12%" rowspan="2" align="center" bgcolor="#D1F5B2"><strong>种植面积</strong></td>
                                        <td  width="5%"rowspan="2" align="center" bgcolor="#D1F5B2"><strong>大豆</strong></td>
                                        <td colspan="2" align="center" bgcolor="#D1F5B2"><strong>补贴归属</strong></td>
                                        <td  width="5%"rowspan="2" align="center" bgcolor="#D1F5B2"><strong>玉米</strong></td>
                                        <td colspan="2" align="center" bgcolor="#D1F5B2"><strong>补贴归属</strong></td>
                                        <td  width="5%"rowspan="2" align="center" bgcolor="#D1F5B2"><strong>小麦</strong></td>
                                        <td  width="5%"rowspan="2" align="center" bgcolor="#D1F5B2"><strong>马铃薯</strong></td>
                                        <td  width="5%"rowspan="2" align="center" bgcolor="#D1F5B2"><strong>杂豆</strong></td>
                                        <td  width="5%"rowspan="2" align="center" bgcolor="#D1F5B2"><strong>北药</strong></td>
                                        <td  width="5%"rowspan="2" align="center" bgcolor="#D1F5B2"><strong>蓝莓</strong></td>
                                        <td  width="5%"rowspan="2" align="center" bgcolor="#D1F5B2"><strong>其它</strong></td>
                                        <td width="9%" rowspan="2" align="center" bgcolor="#D1F5B2"><strong>核实日期</strong></td>
                                        <td rowspan="2" colspan="2" align="center" bgcolor="#D1F5B2"><strong>操作</strong></td>
                                    </tr>
                                    <tr bgcolor="#faebd7">
                                        <td align="center" bgcolor="#D1F5B2">法人</td>
                                        <td align="center" bgcolor="#D1F5B2">种植者</td>
                                        <td align="center" bgcolor="#D1F5B2">法人</td>
                                        <td align="center" bgcolor="#D1F5B2">种植者</td>
                                    </tr>
                                    <?php

                                    foreach ($leases as $val) {
                                        $leaseAll = Plantingstructurecheck::find()->where(['lease_id'=>$val['id']])->all();
                                        $leaseSum = Plantingstructurecheck::find()->where(['lease_id'=>$val['id']])->sum('area');
                                        if(empty($leaseSum)) {
                                            $leaseSum = 0;
                                        }
                                        $leasedd = 0;
                                        $leaseym = 0;
                                        $leasexm = 0;
                                        $leasemls = 0;
                                        $leasezd = 0;
                                        $leaseby = 0;
                                        $leaselm = 0;
                                        $leaseother = 0;
                                        foreach ($leaseAll as $v) {
                                            $plant = Plant::findOne($v['plant_id']);

                                            switch ($plant->typename) {
                                                case '大豆':
                                                    $leasedd = $v['area'];
                                                    break;
                                                case '玉米':
                                                    $leaseym = $v['area'];
                                                    break;
                                                case '小麦':
                                                    $leasexm = $v['area'];
                                                    break;
                                                case '马铃薯':
                                                    $leasemls = $v['area'];
                                                    break;
                                                case '杂豆':
                                                    $leasezd = $v['area'];
                                                    break;
                                                case '北药':
                                                    $leaseby = $v['area'];
                                                    break;
                                                case '蓝莓':
                                                    $leaselm = $v['area'];
                                                    break;
                                                case '其它':
                                                    $leaseother = $v['area'];
                                                    break;
                                            }
                                        }
                                        if(empty($val['ddcj_farmer'])) {
                                            $ddcjfarmer = '0%';
                                        } else {
                                            $ddcjfarmer = $val['ddcj_farmer'];
                                        }
                                        if(empty($val['ddcj_lessee'])) {
                                            $ddcjlessee = '100%';
                                        } else {
                                            $ddcjlessee = $val['ddcj_lessee'];
                                        }
                                        if(empty($val['ymcj_farmer'])) {
                                            $ymcjfarmer = '0%';
                                        } else {
                                            $ymcjfarmer = $val['ymcj_farmer'];
                                        }
                                        if(empty($val['ymcj_lessee'])) {
                                            $ymcjlessee = '100%';
                                        } else {
                                            $ymcjlessee = $val['ymcj_lessee'];
                                        }
                                        echo Html::hiddenInput('leaseid-'.$val['id'],$val['id']);
                                        ?>

                                        <tr>
                                            <td align="center"><?= $val['lessee'] ?></td>
                                            <?= html::hiddenInput('leasearea-'.$val['id'],$val['lease_area'],['id'=>'leasearea-'.$val['id']])?>
                                            <td align="center" id="leasearea-<?= $val['id']?>"><?= $val['lease_area']?>(<sapn id="leaseinput-<?= $val['id']?>"><?= sprintf("%.2f", $leaseSum)?></sapn>)亩</td>
                                                <td align="center">
                                                    <?= html::textInput('leasedd'."-".$val['id'],$leasedd,['id'=>'leaseddarea'."_".$val['id'],'class'=>'form-control','onfocus'=>'leaseclick("ddarea",'.$val['id'].')','onchange'=>'leasechange("ddarea",'.$val['id'].')','onblur'=>'leasearea("ddarea",'.$val['id'].')']) ?>
                                                </td>
                                                <td width="8%" align="center">
                                                    <?php
                                                    if($leasedd) {
//                                                        echo html::dropDownList('leaseddcj_farmer' . "-" . $val['id'], $ddcjfarmer, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseddcj-farmer' . "_" . $val['id'], 'class' => 'form-control ddcj', 'onclick' => 'bfbset("leaseddcj","farmer",' . $val['id'] . ')', 'onchange' => 'bfb("leaseddcj","farmer",' . $val['id'] . ')']);
                                                        echo html::textInput('leaseddcj_farmer' . "-" . $val['id'], $ddcjfarmer, ['id' => 'leaseddcj-farmer' . "_" . $val['id'], 'class' => 'form-control','onfocus'=>'cjfocus("leaseddcj","farmer",'.$val['id'].')',  'onchange' => 'bfb("leaseddcj","farmer",' . $val['id'] . ')','onblur' => 'cjblur("leaseddcj","farmer",' . $val['id'] . ')']);
                                                    } else {
//                                                        echo html::dropDownList('leaseddcj_farmer' . "-" . $val['id'], $ddcjfarmer, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseddcj-farmer' . "_" . $val['id'], 'class' => 'form-control ddcj', 'onclick' => 'bfbset("leaseddcj","farmer",' . $val['id'] . ')', 'onchange' => 'bfb("leaseddcj","farmer",' . $val['id'] . ')', 'style' => 'display:none']);
                                                        echo html::textInput('leaseddcj_farmer' . "-" . $val['id'], $ddcjfarmer, ['id' => 'leaseddcj-farmer' . "_" . $val['id'], 'class' => 'form-control','onfocus'=>'cjfocus("leaseddcj","farmer",'.$val['id'].')', 'onchange' => 'bfb("leaseddcj","farmer",' . $val['id'] . ')','onblur' => 'cjblur("leaseddcj","farmer",' . $val['id'] . ')','style' => 'display:none']);
                                                    }?></td>
                                                <td width="8%" align="center">
                                                    <?php
                                                    if($leasedd) {
//                                                        echo html::dropDownList('leaseddcj_lessee' . "-" . $val['id'], $ddcjlessee, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseddcj-lessee' . "_" . $val['id'], 'class' => 'form-control ddcj', 'onclick' => 'bfbset("leaseddcj","lessee",' . $val['id'] . ')', 'onchange' => 'bfb("leaseddcj","lessee",' . $val['id'] . ')']);
                                                        echo html::textInput('leaseddcj_lessee' . "-" . $val['id'], $ddcjlessee, ['id' => 'leaseddcj-lessee' . "_" . $val['id'], 'class' => 'form-control', 'onfocus'=>'cjfocus("leaseddcj","lessee",'.$val['id'].')', 'onchange' => 'bfb("leaseddcj","lessee",' . $val['id'] . ')','onblur' => 'cjblur("leaseddcj","lessee",' . $val['id'] . ')']);
                                                    } else {
//                                                        echo html::dropDownList('leaseddcj_lessee' . "-" . $val['id'], $ddcjlessee, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseddcj-lessee' . "_" . $val['id'], 'class' => 'form-control ddcj', 'onclick' => 'bfbset("leaseddcj","lessee",' . $val['id'] . ')', 'onchange' => 'bfb("leaseddcj","lessee",' . $val['id'] . ')', 'style' => 'display:none']);
                                                        echo html::textInput('leaseddcj_lessee' . "-" . $val['id'], $ddcjlessee, ['id' => 'leaseddcj-lessee' . "_" . $val['id'], 'class' => 'form-control', 'onfocus'=>'cjfocus("leaseddcj","lessee",'.$val['id'].')', 'onchange' => 'bfb("leaseddcj","lessee",' . $val['id'] . ')','onblur' => 'cjblur("leaseddcj","lessee",' . $val['id'] . ')','style' => 'display:none']);
                                                    }?></td>
                                                <td align="center"><?= html::textInput('leaseym'."-".$val['id'],$leaseym,['id'=>'leaseymarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("ymarea",'.$val['id'].')','onchange'=>'leasechange("ymarea",'.$val['id'].')','onblur'=>'leasearea("ymarea",'.$val['id'].')']) ?></td>
                                                <td width="8%" align="center">
                                                    <?php
                                                    if($leaseym) {
//                                                        echo html::dropDownList('leaseymcj_farmer' . "-" . $val['id'], $ymcjfarmer, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseymcj-farmer' . "_" . $val['id'], 'class' => 'form-control ymcj', 'onclick' => 'bfbset("leaseymcj","farmer",' . $val['id'] . ')', 'onchange' => 'bfb("leaseymcj","farmer",' . $val['id'] . ')']);
                                                        echo html::textInput('leaseymcj_farmer' . "-" . $val['id'], $ymcjfarmer, ['id' => 'leaseymcj-farmer' . "_" . $val['id'], 'class' => 'form-control',  'onfocus'=>'cjfocus("leaseymcj","farmer",'.$val['id'].')',  'onchange' => 'bfb("leaseymcj","farmer",' . $val['id'] . ')','onblur' => 'cjblur("leaseymcj","farmer",' . $val['id'] . ')']);
                                                    } else {
//                                                        echo html::dropDownList('leaseymcj_farmer' . "-" . $val['id'], $ymcjfarmer, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseymcj-farmer' . "_" . $val['id'], 'class' => 'form-control ymcj', 'onclick' => 'bfbset("leaseymcj","farmer",' . $val['id'] . ')', 'onchange' => 'bfb("leaseymcj","farmer",' . $val['id'] . ')', 'style' => 'display:none']);
                                                        echo html::textInput('leaseymcj_farmer' . "-" . $val['id'], $ymcjfarmer, ['id' => 'leaseymcj-farmer' . "_" . $val['id'], 'class' => 'form-control', 'onfocus'=>'cjfocus("leaseymcj","farmer",'.$val['id'].')', 'onchange' => 'bfb("leaseymcj","farmer",' . $val['id'] . ')','onblur' => 'cjblur("leaseymcj","farmer",' . $val['id'] . ')','style' => 'display:none']);
                                                    }?></td>
                                                <td width="8%" align="center">
                                                    <?php
                                                    if($leaseym) {
//                                                        echo html::dropDownList('leaseymcj_lessee' . "-" . $val['id'], $ymcjlessee, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseymcj-lessee' . "_" . $val['id'], 'class' => 'form-control ymcj', 'onclick' => 'bfbset("leaseymcj","lessee",' . $val['id'] . ')', 'onchange' => 'bfb("leaseymcj","lessee",' . $val['id'] . ')']);
                                                        echo html::textInput('leaseymcj_lessee' . "-" . $val['id'], $ymcjlessee, ['id' => 'leaseymcj-lessee' . "_" . $val['id'], 'class' => 'form-control', 'onfocus'=>'cjfocus("leaseymcj","lessee",'.$val['id'].')', 'onchange' => 'bfb("leaseymcj","lessee",' . $val['id'] . ')','onblur' => 'cjblur("leaseymcj","lessee",' . $val['id'] . ')']);
                                                    } else {
//                                                        echo html::dropDownList('leaseymcj_lessee' . "-" . $val['id'], $ymcjlessee, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseymcj-lessee' . "_" . $val['id'], 'class' => 'form-control ymcj', 'onclick' => 'bfbset("leaseymcj","lessee",' . $val['id'] . ')', 'onchange' => 'bfb("leaseymcj","lessee",' . $val['id'] . ')', 'style' => 'display:none']);
                                                        echo html::textInput('leaseymcj_lessee' . "-" . $val['id'], $ymcjlessee, ['id' => 'leaseymcj-lessee' . "_" . $val['id'], 'class' => 'form-control', 'onfocus'=>'cjfocus("leaseymcj","lessee",'.$val['id'].')', 'onchange' => 'bfb("leaseymcj","lessee",' . $val['id'] . ')','onblur' => 'cjblur("leaseymcj","lessee",' . $val['id'] . ')','style' => 'display:none']);
                                                    }?></td>

                                                <td align="center">
                                                    <?= html::textInput('leasexm'."-".$val['id'],$leasexm,['id'=>'leasexmarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("xmarea",'.$val['id'].')','onchange'=>'leasechange("xmarea",'.$val['id'].')','onblur'=>'leasearea("xmarea",'.$val['id'].')']) ?>
                                                </td>
                                                <td align="center">
                                                    <?= html::textInput('leasemls'."-".$val['id'],$leasemls,['id'=>'leasemlsarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("mlsarea",'.$val['id'].')','onchange'=>'leasechange("mlsarea",'.$val['id'].')','onblur'=>'leasearea("mlsarea",'.$val['id'].')']) ?>
                                                </td>
                                                <td align="center">
                                                    <?= html::textInput('leasezd'."-".$val['id'],$leasezd,['id'=>'leasezdarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("zdarea",'.$val['id'].')','onchange'=>'leasechange("zdarea",'.$val['id'].')','onblur'=>'leasearea("zdarea",'.$val['id'].')']) ?>
                                                </td>
                                                <td align="center">
                                                    <?= html::textInput('leaseby'."-".$val['id'],$leaseby,['id'=>'leasebyarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("byarea",'.$val['id'].')','onchange'=>'leasechange("byarea",'.$val['id'].')','onblur'=>'leasearea("byarea",'.$val['id'].')']) ?>
                                                </td>
                                                <td align="center">
                                                    <?= html::textInput('leaselm'."-".$val['id'],$leaselm,['id'=>'leaselmarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("lmarea",'.$val['id'].')','onchange'=>'leasechange("lmarea",'.$val['id'].')','onblur'=>'leasearea("lmarea",'.$val['id'].')']) ?>
                                                </td>
                                                <td align="center">
                                                    <?= html::textInput('leaseother'."-".$val['id'],$leaseother,['id'=>'leaseotherarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("otherarea",'.$val['id'].')','onchange'=>'leasechange("otherarea",'.$val['id'].')','onblur'=>'leasearea("otherarea",'.$val['id'].')']) ?>
                                                </td>
                                                <td align="center">
                                                    <?= DateTimePicker::widget([
                                                        'name' => 'verify-'.$val['id'],
                                                        'value' => date('Y-m-d'),
                                                        'inline' => false,
                                                        'language'=>'zh-CN',
                                                        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                                                        'clientOptions' => [
                                                            'autoclose' => true,
                                                            'minView' => 2,
                                                            'maxView' => 4,
                                                            'format' => 'yyyy-mm-dd'
                                                        ]
                                                    ]); ?>
                                                </td>
                                                <td align="center">
                                                    <?= html::a('<span class="text text-white">删除</span>','#',['class'=>'btn btn-danger','onclick'=>'deletelease('.$val['id'].')'])?>

                                                </td>
                                                <td>
                                                    <?= html::a('<span class="text text-white">重置</span>','#',['class'=>'btn btn-success','onclick'=>'deleteleasecontent('.$val['id'].')'])?>
                                                </td>
                                            </tr>
                                    <?php }?>
                                </table>
                        <?php }?>
                        <?php ActiveFormrdiv::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="dialogMsglease" title="租赁者信息录入">

</div>
<script>
    $(document).ready(function () {

        $("[data-mask]").inputmask();

        if(<?= $noarea?> == 0) {
            $('#farmerinfo').hide();
        }
        if($('#ddarea').val() == 0) {
            $('#ddcj-farmer').hide();
            $('#ddcj-lessee').hide();
        }
        if($('#ymarea').val() == 0) {
            $('#ymcj-farmer').hide();
            $('#ymcj-lessee').hide();
        }

    });
    //租赁
    function leaseclick(zw,id) {
        var textid = 'lease'+zw+'_'+id;
        if($('#'+textid).val() == 0) {
            $('#'+textid).val('');
        }
    }


    function deletelease(id) {
        $.getJSON('index.php?r=basedataverify/basedataverifydeletelease',{'lease_id':id},function (data) {
            if(data.state) {
                $.get('index.php?r=basedataverify/basedataverifydckinput', {farms_id:data.farms_id}, function (body) {
                    $('#dialogMsg').html('');
                    $('#dialogMsg').html(body);
                });
            }
        });
    }
    function deletefarmer(farms_id) {
        $.getJSON('index.php?r=basedataverify/basedataverifydeletefarmer',{'farms_id':farms_id},function (data) {
            if(data.state) {
                $.get('index.php?r=basedataverify/basedataverifydckinput', {farms_id:data.farms_id}, function (body) {
                    $('#dialogMsg').html('');
                    $('#dialogMsg').html(body);
                });
            }
        });
    }
    function deletefarmercontent() {
        $('#ddarea').val(0);
        $('#ymarea').val(0);
        $('#xmarea').val(0);
        $('#mlsarea').val(0);
        $('#zdarea').val(0);
        $('#byarea').val(0);
        $('#lmarea').val(0);
        $('#otherarea').val(0);
        $('#ddcj_farmer').hide();
        $('#ddcj_lessee').hide();
        $('#ymcj_farmer').hide();
        $('#ymcj_lessee').hide();
    }
    function deleteleasecontent(id) {
        $.getJSON('index.php?r=basedataverify/basedataverifydeleteleasecontent',{'lease_id':id},function (data) {
            if(data.state) {
                $.get('index.php?r=basedataverify/basedataverifydckinput', {farms_id:data.farms_id}, function (body) {
                    $('#dialogMsg').html('');
                    $('#dialogMsg').html(body);
                });
            }
        });
    }
    function leasechange(zw,id) {
        var textid = 'lease'+zw+'_'+id;
        if(!Number($('#'+textid).val()) && $('#'+textid).val() !== '') {
            alert('只能数据数字');
            $('#'+textid).val(0);
        } else {
            var sum = $('#leaseddarea' + '_' + id).val() * 1 + $('#leaseymarea' + '_' + id).val() * 1 + $('#leasexmarea' + '_' + id).val() * 1 + $('#leasemlsarea' + '_' + id).val() * 1 + $('#leasezdarea' + '_' + id).val() * 1 + $('#leasebyarea' + '_' + id).val() * 1 + $('#leaselmarea' + '_' + id).val() * 1 + $('#leaseotherarea' + '_' + id).val() * 1;
            var area = $('#leasearea-' + id).val();
            var plantsum = $('#leaseinput-' + id).html();
            var overarea = $('#leasearea-' + id).val();
            if (sum > overarea) {
                alert('对不起,已经超过种植面积,如有剩余面积,将自动显示剩余面积。');
                $('#leaseinput-' + id).html(overarea);
                var larea = area - plantsum;
                $('#' + textid).val(larea.toFixed(2));
            } else {
                $('#leaseinput' + '-' + id).html(sum.toFixed(2));
            }
        }
    }
    function leasearea(zw,id) {
        var textid = 'lease'+zw+'_'+id;
        if($('#'+textid).val() == '') {
            $('#'+textid).val(0);
        }

        if(zw == 'ddarea') {
            if($('#'+textid).val() == 0) {
                $('#leaseddcj-farmer'+'_'+id).hide();
                $('#leaseddcj-lessee'+'_'+id).hide();
            } else {
                $('#leaseddcj-farmer'+'_'+id).show();
                $('#leaseddcj-lessee'+'_'+id).show();
            }
        }
        if(zw == 'ymarea') {
            if($('#'+textid).val() == 0) {
                $('#leaseymcj-farmer'+'_'+id).hide();
                $('#leaseymcj-lessee'+'_'+id).hide();
            } else {
                $('#leaseymcj-farmer'+'_'+id).show();
                $('#leaseymcj-lessee'+'_'+id).show();
            }
        }
    }

    $('#ddarea').change(function () {
        if(!Number($(this).val()) && $(this).val() !== '') {
            alert('只能数据数字');
            $(this).val(0);
        } else {
            var sum = $(this).val() * 1 + $('#ymarea').val() * 1 + $('#xmarea').val() * 1 + $('#mlsarea').val() * 1 + $('#zdarea').val() * 1 + $('#byarea').val() * 1 + $('#lmarea').val() * 1 + $('#otherarea').val() * 1;
            var noarea = <?= $noarea?>;
            var area = <?= $area?>;
            var plantsum = $('#inputsum').html();
            if (sum > noarea) {
                alert('对不起,已经超过种植面积,如有剩余面积,将自动显示剩余面积。');
                $('#inputsum').html(noarea);
                var larea = area - plantsum;
                $(this).val(larea.toFixed(2));
            } else {
                $('#inputsum').html(sum.toFixed(2));
            }
        }
    });
    $('#ymarea').change(function () {
        if(!Number($(this).val()) && $(this).val() !== '') {
            alert('只能数据数字');
            (this).val(0);
        } else {
            var sum = $(this).val() * 1 + $('#ddarea').val() * 1 + $('#xmarea').val() * 1 + $('#mlsarea').val() * 1 + $('#zdarea').val() * 1 + $('#byarea').val() * 1 + $('#lmarea').val() * 1 + $('#otherarea').val() * 1;
            var noarea = <?= $noarea?>;
            var area = <?= $area?>;
            var plantsum = $('#inputsum').html();
            if (sum > noarea) {
                alert('对不起,已经超过种植面积,如有剩余面积,将自动显示剩余面积。');
                $('#inputsum').html(noarea);
                var larea = area - plantsum;
                $(this).val(larea.toFixed(2));
            } else {
                $('#inputsum').html(sum.toFixed(2));
            }
        }
    });
    $('#xmarea').change(function () {
        if(!Number($(this).val()) && $(this).val() !== '') {
            alert('只能数据数字');
            (this).val(0);
        } else {
            var sum = $(this).val() * 1 + $('#ymarea').val() * 1 + $('#ddarea').val() * 1 + $('#mlsarea').val() * 1 + $('#zdarea').val() * 1 + $('#byarea').val() * 1 + $('#lmarea').val() * 1 + $('#otherarea').val() * 1;
//        $('#inputsum').html(sum);
            var noarea = <?= $noarea?>;
            var area = <?= $area?>;
            var plantsum = $('#inputsum').html();
            if (sum > noarea) {
                alert('对不起,已经超过种植面积,如有剩余面积,将自动显示剩余面积。');
                $('#inputsum').html(noarea);
                var larea = area - plantsum;
                $(this).val(larea.toFixed(2));
            } else {
                $('#inputsum').html(sum.toFixed(2));
            }
        }
    });
    $('#mlsarea').change(function () {
        if(!Number($(this).val()) && $(this).val() !== '') {
            alert('只能数据数字');
            (this).val(0);
        } else {
            var sum = $(this).val() * 1 + $('#ymarea').val() * 1 + $('#xmarea').val() * 1 + $('#ddarea').val() * 1 + $('#zdarea').val() * 1 + $('#byarea').val() * 1 + $('#lmarea').val() * 1 + $('#otherarea').val() * 1;
            var noarea = <?= $noarea?>;
            var area = <?= $area?>;
            var plantsum = $('#inputsum').html();
            if (sum > noarea) {
                alert('对不起,已经超过种植面积,如有剩余面积,将自动显示剩余面积。');
                $('#inputsum').html(noarea);
                var larea = area - plantsum;
                $(this).val(larea.toFixed(2));
            } else {
                $('#inputsum').html(sum.toFixed(2));
            }
        }
    });
    $('#zdarea').change(function () {
        if(!Number($(this).val()) && $(this).val() !== '') {
            alert('只能数据数字');
            (this).val(0);
        } else {
            var sum = $(this).val() * 1 + $('#ymarea').val() * 1 + $('#xmarea').val() * 1 + $('#mlsarea').val() * 1 + $('#ddarea').val() * 1 + $('#byarea').val() * 1 + $('#lmarea').val() * 1 + $('#otherarea').val() * 1;
            var noarea = <?= $noarea?>;
            var area = <?= $area?>;
            var plantsum = $('#inputsum').html();

            if (sum > noarea) {
                alert('对不起,已经超过种植面积,如有剩余面积,将自动显示剩余面积。');
                $('#inputsum').html(noarea);
                var larea = area - plantsum;
                $(this).val(larea.toFixed(2));
            } else {
                $('#inputsum').html(sum.toFixed(2));
            }
        }
    });
    $('#byarea').change(function () {
        if(!Number($(this).val()) && $(this).val() !== '') {
            alert('只能数据数字');
            (this).val(0);
        } else {
            var sum = $(this).val() * 1 + $('#ymarea').val() * 1 + $('#xmarea').val() * 1 + $('#mlsarea').val() * 1 + $('#zdarea').val() * 1 + $('#ddarea').val() * 1 + $('#lmarea').val() * 1 + $('#otherarea').val() * 1;
            var noarea = <?= $noarea?>;
            var area = <?= $area?>;
            var plantsum = $('#inputsum').html();
            if (sum > noarea) {
                alert('对不起,已经超过种植面积,如有剩余面积,将自动显示剩余面积。');
                $('#inputsum').html(noarea);
                var larea = area - plantsum;
                $(this).val(larea.toFixed(2));
            } else {
                $('#inputsum').html(sum.toFixed(2));
            }
        }
    });
    $('#lmarea').change(function () {
        if(!Number($(this).val()) && $(this).val() !== '') {
            alert('只能数据数字');
            (this).val(0);
        } else {
            var sum = $(this).val() * 1 + $('#ymarea').val() * 1 + $('#xmarea').val() * 1 + $('#mlsarea').val() * 1 + $('#zdarea').val() * 1 + $('#byarea').val() * 1 + $('#ddarea').val() * 1 + $('#otherarea').val() * 1;
            var noarea = <?= $noarea?>;
            var area = <?= $area?>;
            var plantsum = $('#inputsum').html();

            if (sum > noarea) {
                alert('对不起,已经超过种植面积,如有剩余面积,将自动显示剩余面积。');
                $('#inputsum').html(noarea);
                var larea = area - plantsum;
                $(this).val(larea.toFixed(2));
            } else {
                $('#inputsum').html(sum.toFixed(2));
            }
        }
    });
    $('#otherarea').change(function () {
        if(!Number($(this).val()) && $(this).val() !== '') {
            alert('只能数据数字');
            (this).val(0);
        } else {
            var sum = $(this).val() * 1 + $('#ymarea').val() * 1 + $('#xmarea').val() * 1 + $('#mlsarea').val() * 1 + $('#zdarea').val() * 1 + $('#byarea').val() * 1 + $('#lmarea').val() * 1 + $('#ddarea').val() * 1;
            var noarea = <?= $noarea?>;
            var area = <?= $area?>;
            var plantsum = $('#inputsum').html();

            if (sum > noarea) {
                alert('对不起,已经超过种植面积,如有剩余面积,将自动显示剩余面积。');
                $('#inputsum').html(noarea);
                var larea = area - plantsum;
                $(this).val(larea.toFixed(2));
            } else {
                $('#inputsum').html(sum.toFixed(2));
            }
        }
    });
    function inputSum()
    {
        var sum = $('#otherarea').val() * 1 + $('#ymarea').val() * 1 + $('#xmarea').val() * 1 + $('#mlsarea').val() * 1 + $('#zdarea').val() * 1 + $('#byarea').val() * 1 + $('#lmarea').val() * 1 + $('#ddarea').val() * 1;
        return sum;
    }
    $('#ddarea').focus(function () {
        if($(this).val() == 0) {
            $(this).val('');
        }
    });
    $('#ymarea').focus(function () {
        if($(this).val() == 0) {
            $(this).val('');
        }
    });
    $('#xmarea').focus(function () {
        if($(this).val() == 0) {
            $(this).val('');
        }
    });
    $('#mlsarea').focus(function () {
        if($(this).val() == 0) {
            $(this).val('');
        }
    });
    $('#zdarea').focus(function () {
        if($(this).val() == 0) {
            $(this).val('');
        }
    });
    $('#byarea').focus(function () {
        if($(this).val() == 0) {
            $(this).val('');
        }
    });
    $('#lmarea').focus(function () {
        if($(this).val() == 0) {
            $(this).val('');
        }
    });
    $('#otherarea').focus(function () {
        if($(this).val() == 0) {
            $(this).val('');
        }
    });
    //失去焦点
    $('#ddarea').blur(function () {
        if($(this).val() == '') {
            $(this).val(0);
        }
        if($(this).val() == 0) {
            $('#ddcj-farmer').hide();
            $('#ddcj-lessee').hide();
        } else {
            $('#ddcj-farmer').show();
            $('#ddcj-lessee').show();
        }
    });
    $('#ymarea').blur(function () {
        if($(this).val() == '') {
            $(this).val(0);
        }
        if($(this).val() == 0) {
            $('#ymcj-farmer').hide();
            $('#ymcj-lessee').hide();
        } else {
            $('#ymcj-farmer').show();
            $('#ymcj-lessee').show();
        }
    });
    $('#xmarea').blur(function () {
        if($(this).val() == '') {
            $(this).val(0);
        }
    });
    $('#mlsarea').blur(function () {
        if($(this).val() == '') {
            $(this).val(0);
        }
    });
    $('#zdarea').blur(function () {
        if($(this).val() == '') {
            $(this).val(0);
        }
    });
    $('#byarea').blur(function () {
        if($(this).val() == '') {
            $(this).val(0);
        }
    });
    $('#lmarea').blur(function () {
        if($(this).val() == '') {
            $(this).val(0);
        }
    });
    $('#otherarea').blur(function () {
        if($(this).val() == '') {
            $(this).val(0);
        }
    });

    function showDialoglease(id)
    {
        var sum = inputSum();
        if(sum == <?= $farms['contractarea']?>) {
            alert('没有剩余面积可以租赁了,请检查其它项是否正确后再试。')
        } else {
            $.get('index.php?r=lease/leasecreateajax', {farms_id: id,sum:sum}, function (body) {
                $('#dialogMsglease').html(body);
                $("#dialogMsglease").dialog("open");
            });
        }
    }

    $( "#dialogMsglease" ).dialog({
        autoOpen: false,
        width: 1200,
//        height: 800,
//        show: "blind",
//        hide: "explode",
        modal: true,//设置背景灰的
        buttons: [
            {
                text: "确定",
                click: function() {
                    var form = $('form').serializeArray();
//                    console.log(form[1]);
                    var isShowMsg = false;
                    var msg = '';
//                    if(form['Lease[lessee]'].value == '') {
//                        isShowMsg = true;
//                        msg = '种植者姓名不能为空';
//                    }
//                    if(form['Lease[lessee_cardid'])
                    $.each(form,function (index,data) {
                        switch (form[index].name) {
                            case 'Lease[lessee]':
                                if(form[index].value == '') {
                                    isShowMsg = true;
                                    msg = '种植者姓名不能为空';
                                }
                                break;
                            case 'Lease[lessee_cardid]':
                                if(form[index].value == '') {
                                    isShowMsg = true;
                                    msg = '种植者身份证号不能为空';
                                }
                                break;
                        }
                    });
                    console.log($.toJSON(form));
                    if(isShowMsg) {
                        alert(msg);
                    } else {
                        $.getJSON('index.php?r=basedataverify/basedataverifysavelease',{'value':$.toJSON(form)},function (data) {
                            if(data.state) {
                                $( '#dialogMsglease' ).dialog( "close" );
                                $.get('index.php?r=basedataverify/basedataverifydckinput', {farms_id:data.farms_id}, function (body) {
                                    $('#dialogMsg').html('');
                                    $('#dialogMsg').html(body);
                                });
                            }
                        });
                    }
                }
            },
            {
                text: "取消",
                click: function() {
                    $( '#dialogMsglease' ).dialog( "close" );
                }
            }
        ]
    });
    function cjfocus(leasebt,user,id) {
        var textid = leasebt+'-'+user+'_'+id;
        var input = $('#'+textid).val();
        var num = input.substring(0,input.length-1);
        if(num == 0) {
            $('#'+textid).val('');
        } else {
            $('#'+textid).val(num);
        }
    }
    function cjblur(leasebt,user,id) {
        var textid = leasebt+'-'+user+'_'+id;
        var input = $('#'+textid).val();
        if(input == '') {
            input = 0;
        }
        $('#'+textid).val(input+'%');
    }
    function getUNBFB(id,str) {
//        var num = parseInt(str.replace(/[^0-9]/ig,""));
        var result = 100 - str*1;
        $("#"+id).val(parseFloat(result.toFixed(2))+'%');
    }
    $("#ddcj-farmer").change(function(){getUNBFB("ddcj-lessee",$(this).val());});
    $("#ddcj-lessee").change(function(){getUNBFB("ddcj-farmer",$(this).val());});

    $("#ymcj-farmer").change(function(){getUNBFB("ymcj-lessee",$(this).val());});
    $("#ymcj-lessee").change(function(){getUNBFB("ymcj-farmer",$(this).val());});

    function bfb(leasebt,user,id) {
        var input = $('#'+leasebt+'-'+user+'_'+id).val();
//        if(input.indexOf("%")<0) {
//            $('#'+leasebt+'-'+user+'_'+id).val(input+'%');
//        }
        if(user == 'farmer') {
            getUNBFB(leasebt+'-'+'lessee_'+id,input);
        }
        if(user == 'lessee') {
            getUNBFB(leasebt+'-'+'farmer_'+id,input);
        }
    }

//    function bfbclick(leasebt,user,id) {
//        var input = $('#'+leasebt+'-'+user+'_'+id).val();
//        if(input)
//    }

</script>

</div>