<?php
namespace frontend\controllers;

use app\models\Goodseed;
use app\models\Subsidyratio;
use app\models\Subsidytypetofarm;
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
use app\models\Plantingstructure;
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
                            //echo $_GET['farms_id'];
                            echo Html::hiddenInput('farms_id',$farms['id'],['id'=>'farmsID']);
                            $plantings = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id']])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();?>
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
                            $goodseeds = yii\helpers\ArrayHelper::map(Goodseed::find()->all(),'id','plant_id');
                            $goodseedArray = array_unique($goodseeds);
                            foreach ($goodseedArray as $goodseed) {
                                echo Html::hiddenInput('Plantingstructure[goodseed_id-'.$goodseed.'-0]','',['id'=>'g-'.$goodseed.'-0']);
                            }
                            ?>
                            <table border="1" width="100%" cellpadding="0" cellspacing="0" class="table table-bordered">
                                <tr bgcolor="">
                                    <td width="8%" rowspan="2" align="center"><strong>法人</strong></td>
                                    <td width="12%" rowspan="2" align="center"><strong>种植面积</strong></td>
                                    <td width="8%" rowspan="2" align="center"><strong>大豆</strong></td>
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
                                <tr bgcolor="">
                                    <td align="center">法人</td>
                                    <td align="center">种植者</td>
                                    <td align="center">法人</td>
                                    <td align="center">种植者</td>
                                </tr>
                                <tr>
                                    <td align="center"><?= $farms['farmername'] ?></td>
                                    <?php $plantingsum = Plantingstructure::find()->where(['farms_id' => $_GET['farms_id'], 'lease_id' => 0, 'year' => User::getYear()])->sum('area');
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
                                    $ddhtml = '';
                                    $ymhtml = '';
                                    $xmhtml = '';
                                    $mlshtml = '';
                                    $farmerplantings = Plantingstructure::find()->where(['farms_id' => $_GET['farms_id'], 'lease_id' => 0, 'year' => User::getYear()])->all();
                                    if ($farmerplantings) {

                                        foreach ($farmerplantings as $v) {
                                            $plant = Plant::findOne($v['plant_id']);

                                            switch ($plant->typename) {
                                                case '大豆':
                                                    $dd = $v['area'];
                                                    $typeid = Subsidytypetofarm::find()->where(['mark'=>'ddcj'])->one()['id'];
                                                    $ddcj = Subsidyratio::find()->where(['farms_id'=>$v['farms_id'],'lease_id'=>$v['id'],'typeid'=>$typeid])->one()['farmer'];
                                                    $goodseed_id = $v['goodseed_id'];

                                                    if($goodseed_id) {
                                                        $ddhtml = '<i class="fa fa-pagelines text-success"></i>';
                                                    }
                                                    break;
                                                case '玉米':
                                                    $ym = $v['area'];
                                                    $typeid = Subsidytypetofarm::find()->where(['mark'=>'ymcj'])->one()['id'];
                                                    $ymcj = Subsidyratio::find()->where(['farms_id'=>$v['farms_id'],'lease_id'=>$v['id'],'typeid'=>$typeid])->one()['farmer'];
                                                    $goodseed_id = $v['goodseed_id'];

                                                    if($goodseed_id) {
                                                        $ymhtml = '<i class="fa fa-pagelines text-success"></i>';
                                                    }
                                                    break;
                                                case '小麦':
                                                    $xm = $v['area'];
                                                    $goodseed_id = $v['goodseed_id'];

                                                    if($goodseed_id) {
                                                        $xmhtml = '<i class="fa fa-pagelines text-success"></i>';
                                                    }
                                                    break;
                                                case '马铃薯':
                                                    $mls = $v['area'];
                                                    $goodseed_id = $v['goodseed_id'];

                                                    if($goodseed_id) {
                                                        $mlshtml = '<i class="fa fa-pagelines text-success"></i>';
                                                    }
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

                                    ?>
                                    <td align="center" id="dd"><?= $ddhtml?>
                                        <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'大豆'])->one()['id'].'-0]', $dd, ['id' => 'ddarea', 'class' => 'form-control','ondblclick'=>"setGoodseed(".Plant::find()->where(['typename'=>'大豆'])->one()['id'].",0,$(this).val(),$(this).attr('id'),'dd')"]) ?>
                                    </td>
                                    <td width="8%"
                                        align="center"><?= html::textInput('ddcj_farmer', '100%', ['id' => 'ddcj-farmer', 'class' => 'form-control','readonly'=>'readonly']) ?></td>
                                    <td width="8%"
                                        align="center"><?= html::textInput('ddcj_lessee', '0%', ['id' => 'ddcj-lessee', 'class' => 'form-control','readonly'=>'readonly']) ?></td>
                                    <td align="center" id="ym"><?= $ymhtml?><?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'玉米'])->one()['id'].'-0]', $ym, ['id' => 'ymarea', 'class' => 'form-control','ondblclick'=>"setGoodseed(".Plant::find()->where(['typename'=>'玉米'])->one()['id'].",0,$(this).val(),$(this).attr('id'),'ym')"]) ?></td>
                                    <td width="8%"
                                        align="center"><?= html::textInput('ymcj_farmer', '100%', ['id' => 'ymcj-farmer', 'class' => 'form-control','readonly'=>'readonly']) ?></td>
                                    <td width="8%"
                                        align="center"><?= html::textInput('ymcj_lessee', '0%', ['id' => 'ymcj-lessee', 'class' => 'form-control','readonly'=>'readonly']) ?></td>
                                    <td align="center" id="xm"><?= $xmhtml?>
                                        <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'小麦'])->one()['id'].'-0]', $xm, ['id' => 'xmarea', 'class' => 'form-control','ondblclick'=>"setGoodseed(".Plant::find()->where(['typename'=>'小麦'])->one()['id'].",0,$(this).val(),$(this).attr('id'),'xm')"]) ?>
                                    </td>
                                    <td align="center" id='mls'><?= $mlshtml?>
                                        <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'马铃薯'])->one()['id'].'-0]', $mls, ['id' => 'mlsarea', 'class' => 'form-control','ondblclick'=>"setGoodseed(".Plant::find()->where(['typename'=>'马铃薯'])->one()['id'].",0,$(this).val(),$(this).attr('id'),'mls)"]) ?>
                                    </td>
                                    <td align="center">
                                        <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'杂豆'])->one()['id'].'-0]', $zd, ['id' => 'zdarea', 'class' => 'form-control']) ?>
                                    </td>
                                    <td align="center">
                                        <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'北药'])->one()['id'].'-0]', $by, ['id' => 'byarea', 'class' => 'form-control']) ?>
                                    </td>
                                    <td align="center">
                                        <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'蓝莓'])->one()['id'].'-0]', $lm, ['id' => 'lmarea', 'class' => 'form-control']) ?>
                                    </td>
                                    <td align="center">
                                        <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'其它'])->one()['id'].'-0]', $other, ['id' => 'otherarea', 'class' => 'form-control']) ?>
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
                                        if(Plantingstructure::find()->where(['farms_id'=>$farms['id'],'lease_id'=>0,'year'=>User::getYear()])->sum('area')) {
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
                                <table border="1" width="100%" cellpadding="0" cellspacing="0" class="table table-bordered">
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
                                        $leaseAll = Plantingstructure::find()->where(['lease_id'=>$val['id'],'year'=>User::getYear()])->all();
                                        $leaseSum = Plantingstructure::find()->where(['lease_id'=>$val['id'],'year'=>User::getYear()])->sum('area');
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
                                        $ddcjfarmer = '';
                                        $ddcjlessee = '';
                                        $ymcjfarmer = '';
                                        $ymcjlessee = '';

//                                        var_dump($ddcjfarmer);
                                        foreach ($leaseAll as $v) {
                                            $plant = Plant::findOne($v['plant_id']);

                                            $ddstr = 'leasedd-'.$v['id'];
                                            $ymstr = 'leaseym-'.$v['id'];
                                            $xmstr = 'leasexm-'.$v['id'];
                                            $mlsstr = 'leasemls-'.$v['id'];
                                            switch ($plant->typename) {
                                                case '大豆':
                                                    $leasedd = $v['area'];
                                                    $goodseed_id = $v['goodseed_id'];

                                                    if($goodseed_id) {
                                                        $$ddstr = '<i class="fa fa-pagelines text-success"></i>';
                                                    }
                                                    break;
                                                case '玉米':
//                                                    $typeid = Subsidytypetofarm::find()->where(['mark'=>'ymcj'])->one()['id'];
//                                                    $reaio = Subsidyratio::find()->where(['farms_id'=>$val['farms_id'],'lease_id'=>$val['id'],'typeid'=>$typeid])->one();

                                                    $leaseym = $v['area'];
                                                    $goodseed_id = $v['goodseed_id'];

                                                    if($goodseed_id) {
                                                        $$ymstr = '<i class="fa fa-pagelines text-success"></i>';
                                                    }
                                                    break;
                                                case '小麦':
                                                    $leasexm = $v['area'];
                                                    $goodseed_id = $v['goodseed_id'];

                                                    if($goodseed_id) {
                                                        $$xmstr = '<i class="fa fa-pagelines text-success"></i>';
                                                    }
                                                    break;
                                                case '马铃薯':
                                                    $leasemls = $v['area'];
                                                    $goodseed_id = $v['goodseed_id'];

                                                    if($goodseed_id) {
                                                        $$mlsstr = '<i class="fa fa-pagelines text-success"></i>';
                                                    }
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
                                        $typeid = Subsidytypetofarm::find()->where(['mark'=>'ddcj'])->one()['id'];
                                        $reaio = Subsidyratio::find()->where(['farms_id'=>$val['farms_id'],'lease_id'=>$val['id'],'typeid'=>$typeid])->one();
//                                        var_dump($reaio);
                                        $ddcjfarmer = $reaio['farmer'];
                                        $ddcjlessee = $reaio['lessee'];
                                        $typeid = Subsidytypetofarm::find()->where(['mark'=>'ymcj'])->one()['id'];
                                        $reaio = Subsidyratio::find()->where(['farms_id'=>$val['farms_id'],'lease_id'=>$val['id'],'typeid'=>$typeid])->one();
                                        $ymcjfarmer = $reaio['farmer'];
                                        $ymcjlessee = $reaio['lessee'];
                                        echo Html::hiddenInput('leaseid-'.$val['id'],$val['id']);
                                        $goodseeds = yii\helpers\ArrayHelper::map(Goodseed::find()->all(),'id','plant_id');
                                        $goodseedArray = array_unique($goodseeds);
                                        foreach ($goodseedArray as $goodseed) {
                                            echo Html::hiddenInput('Plantingstructure[goodseed_id-'.$goodseed.'-'.$val['id'].']','',['id'=>'g-'.$goodseed.'-'.$val['id']]);
                                        }
                                        echo Html::hiddenInput('tempinput','',['id'=>'temp-input']);
                                        ?>

                                        <tr>
                                            <td align="center"><?= $val['lessee'] ?></td>
                                            <?= html::hiddenInput('leasearea-'.$val['id'],$val['lease_area'],['id'=>'leasearea-'.$val['id']])?>
                                            <td align="center" id="leasearea-<?= $val['id']?>"><?= $val['lease_area']?>(<sapn id="leaseinput-<?= $val['id']?>"><?= sprintf("%.2f", $leaseSum)?></sapn>)亩</td>
                                                <td align="center" id="leasedd-<?=$val['id']?>">
                                                    <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'大豆'])->one()['id']."-".$val['id']."]",$leasedd,['id'=>'leaseddarea'."_".$val['id'],'class'=>'form-control','onfocus'=>'leaseclick("ddarea",'.$val['id'].')','onchange'=>'leasechange("ddarea",'.$val['id'].')','onblur'=>'leasearea("ddarea",'.$val['id'].')','ondblclick'=>"setGoodseed(".Plant::find()->where(['typename'=>'大豆'])->one()['id'].",".$val['id'].",$(this).val(),$(this).attr('id'),'leasedd-".$val['id']."')"]) ?>
                                                </td>
                                                <td width="8%" align="center">
                                                    <?php
                                                    echo Html::hiddenInput('tempddcj_farmer'.$val['id'],$ddcjfarmer,['id'=>'tempddcj_farmer-'.$val['id']]);
                                                    if($leasedd) {
//                                                        echo html::dropDownList('leaseddcj_farmer' . "-" . $val['id'], $ddcjfarmer, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseddcj-farmer' . "_" . $val['id'], 'class' => 'form-control ddcj', 'onclick' => 'bfbset("leaseddcj","farmer",' . $val['id'] . ')', 'onchange' => 'bfb("leaseddcj","farmer",' . $val['id'] . ')']);
                                                        echo html::textInput('leaseddcj_farmer' . "-" . $val['id'], $ddcjfarmer, ['readonly'=>'readonly','id' => 'leaseddcj-farmer' . "_" . $val['id'], 'class' => 'form-control']);
                                                    } else {
//                                                        echo html::dropDownList('leaseddcj_farmer' . "-" . $val['id'], $ddcjfarmer, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseddcj-farmer' . "_" . $val['id'], 'class' => 'form-control ddcj', 'onclick' => 'bfbset("leaseddcj","farmer",' . $val['id'] . ')', 'onchange' => 'bfb("leaseddcj","farmer",' . $val['id'] . ')', 'style' => 'display:none']);
                                                        echo html::textInput('leaseddcj_farmer' . "-" . $val['id'], $ddcjfarmer, ['readonly'=>'readonly','id' => 'leaseddcj-farmer' . "_" . $val['id'], 'class' => 'form-control']);
                                                    }?></td>
                                                <td width="8%" align="center">
                                                    <?php
                                                    echo Html::hiddenInput('tempddcj_lessee'.$val['id'],$ddcjlessee,['id'=>'tempddcj_lessee-'.$val['id']]);
                                                    if($leasedd) {
//                                                        echo html::dropDownList('leaseddcj_lessee' . "-" . $val['id'], $ddcjlessee, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseddcj-lessee' . "_" . $val['id'], 'class' => 'form-control ddcj', 'onclick' => 'bfbset("leaseddcj","lessee",' . $val['id'] . ')', 'onchange' => 'bfb("leaseddcj","lessee",' . $val['id'] . ')']);
                                                        echo html::textInput('leaseddcj_lessee' . "-" . $val['id'], $ddcjlessee, ['readonly'=>'readonly','id' => 'leaseddcj-lessee' . "_" . $val['id'], 'class' => 'form-control']);
                                                    } else {
//                                                        echo html::dropDownList('leaseddcj_lessee' . "-" . $val['id'], $ddcjlessee, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseddcj-lessee' . "_" . $val['id'], 'class' => 'form-control ddcj', 'onclick' => 'bfbset("leaseddcj","lessee",' . $val['id'] . ')', 'onchange' => 'bfb("leaseddcj","lessee",' . $val['id'] . ')', 'style' => 'display:none']);
                                                        echo html::textInput('leaseddcj_lessee' . "-" . $val['id'], $ddcjlessee, ['readonly'=>'readonly','id' => 'leaseddcj-lessee' . "_" . $val['id'], 'class' => 'form-control']);
                                                    }?></td>
                                                <td align="center" id="leaseym-<?= $val['id']?>"><?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'玉米'])->one()['id']."-".$val['id']."]",$leaseym,['id'=>'leaseymarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("ymarea",'.$val['id'].')','onchange'=>'leasechange("ymarea",'.$val['id'].')','onblur'=>'leasearea("ymarea",'.$val['id'].')','ondblclick'=>"setGoodseed(".Plant::find()->where(['typename'=>'玉米'])->one()['id'].",".$val['id'].",$(this).val(),$(this).attr('id'),'leaseym-".$val['id']."')"]) ?></td>
                                                <td width="8%" align="center">
                                                    <?php
                                                    echo Html::hiddenInput('tempymcj_farmer'.$val['id'],$ymcjfarmer,['id'=>'tempymcj_farmer-'.$val['id']]);
                                                    if($leaseym) {
//                                                        echo html::dropDownList('leaseymcj_farmer' . "-" . $val['id'], $ymcjfarmer, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseymcj-farmer' . "_" . $val['id'], 'class' => 'form-control ymcj', 'onclick' => 'bfbset("leaseymcj","farmer",' . $val['id'] . ')', 'onchange' => 'bfb("leaseymcj","farmer",' . $val['id'] . ')']);
                                                        echo html::textInput('leaseymcj_farmer' . "-" . $val['id'], $ymcjfarmer, ['readonly'=>'readonly','id' => 'leaseymcj-farmer' . "_" . $val['id'], 'class' => 'form-control']);
                                                    } else {
//                                                        echo html::dropDownList('leaseymcj_farmer' . "-" . $val['id'], $ymcjfarmer, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseymcj-farmer' . "_" . $val['id'], 'class' => 'form-control ymcj', 'onclick' => 'bfbset("leaseymcj","farmer",' . $val['id'] . ')', 'onchange' => 'bfb("leaseymcj","farmer",' . $val['id'] . ')', 'style' => 'display:none']);
                                                        echo html::textInput('leaseymcj_farmer' . "-" . $val['id'], $ymcjfarmer, ['readonly'=>'readonly','id' => 'leaseymcj-farmer' . "_" . $val['id'], 'class' => 'form-control']);
                                                    }?></td>
                                                <td width="8%" align="center">
                                                    <?php
                                                    echo Html::hiddenInput('tempymcj_lessee'.$val['id'],$ymcjlessee,['id'=>'tempymcj_lessee-'.$val['id']]);
                                                    if($leaseym) {
//                                                        echo html::dropDownList('leaseymcj_lessee' . "-" . $val['id'], $ymcjlessee, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseymcj-lessee' . "_" . $val['id'], 'class' => 'form-control ymcj', 'onclick' => 'bfbset("leaseymcj","lessee",' . $val['id'] . ')', 'onchange' => 'bfb("leaseymcj","lessee",' . $val['id'] . ')']);
                                                        echo html::textInput('leaseymcj_lessee' . "-" . $val['id'], $ymcjlessee, ['readonly'=>'readonly','id' => 'leaseymcj-lessee' . "_" . $val['id'], 'class' => 'form-control']);
                                                    } else {
//                                                        echo html::dropDownList('leaseymcj_lessee' . "-" . $val['id'], $ymcjlessee, ['0%' => '0%', '10%' => '10%', '20%' => '20%', '30%' => '30%', '40%' => '40%', '50%' => '50%', '60%' => '60%', '70%' => '70%', '80%' => '80%', '90%' => '90%', '100%' => '100%'], ['id' => 'leaseymcj-lessee' . "_" . $val['id'], 'class' => 'form-control ymcj', 'onclick' => 'bfbset("leaseymcj","lessee",' . $val['id'] . ')', 'onchange' => 'bfb("leaseymcj","lessee",' . $val['id'] . ')', 'style' => 'display:none']);
                                                        echo html::textInput('leaseymcj_lessee' . "-" . $val['id'], $ymcjlessee, ['readonly'=>'readonly','id' => 'leaseymcj-lessee' . "_" . $val['id'], 'class' => 'form-control']);
                                                    }?></td>

                                                <td align="center" id="leasexm-<?= $val['id']?>">
                                                    <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'小麦'])->one()['id']."-".$val['id']."]",$leasexm,['id'=>'leasexmarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("xmarea",'.$val['id'].')','onchange'=>'leasechange("xmarea",'.$val['id'].')','onblur'=>'leasearea("xmarea",'.$val['id'].')','ondblclick'=>"setGoodseed(".Plant::find()->where(['typename'=>'小麦'])->one()['id'].",".$val['id'].",$(this).val(),$(this).attr('id'),'leasexm-".$val['id']."')"]) ?>
                                                </td>
                                                <td align="center" id="leasemls-<?= $val['id']?>">
                                                    <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'马铃薯'])->one()['id']."-".$val['id']."]",$leasemls,['id'=>'leasemlsarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("mlsarea",'.$val['id'].')','onchange'=>'leasechange("mlsarea",'.$val['id'].')','onblur'=>'leasearea("mlsarea",'.$val['id'].')','ondblclick'=>"setGoodseed(".Plant::find()->where(['typename'=>'马铃薯'])->one()['id'].",".$val['id'].",$(this).val(),$(this).attr('id'),'leasemls-".$val['id']."')"]) ?>
                                                </td>
                                                <td align="center">
                                                    <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'杂豆'])->one()['id']."-".$val['id']."]",$leasezd,['id'=>'leasezdarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("zdarea",'.$val['id'].')','onchange'=>'leasechange("zdarea",'.$val['id'].')','onblur'=>'leasearea("zdarea",'.$val['id'].')']) ?>
                                                </td>
                                                <td align="center">
                                                    <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'北药'])->one()['id']."-".$val['id']."]",$leaseby,['id'=>'leasebyarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("byarea",'.$val['id'].')','onchange'=>'leasechange("byarea",'.$val['id'].')','onblur'=>'leasearea("byarea",'.$val['id'].')']) ?>
                                                </td>
                                                <td align="center">
                                                    <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'蓝莓'])->one()['id']."-".$val['id']."]",$leaselm,['id'=>'leaselmarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("lmarea",'.$val['id'].')','onchange'=>'leasechange("lmarea",'.$val['id'].')','onblur'=>'leasearea("lmarea",'.$val['id'].')']) ?>
                                                </td>
                                                <td align="center">
                                                    <?= html::textInput('Plantingstructure['.Plant::find()->where(['typename'=>'其它'])->one()['id']."-".$val['id']."]",$leaseother,['id'=>'leaseotherarea'."_".$val['id'],'class'=>'form-control','onclick'=>'leaseclick("otherarea",'.$val['id'].')','onchange'=>'leasechange("otherarea",'.$val['id'].')','onblur'=>'leasearea("otherarea",'.$val['id'].')']) ?>
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

                                                <td>
                                                    <?= html::a('<span class="text text-white">清除</span>','#',['class'=>'btn btn-success','onclick'=>'deleteleasecontent('.$val['id'].')'])?>
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
<div id="goodseed" title="良种"></div>
<script>
    $(document).ready(function () {

        $("[data-mask]").inputmask();

        if(<?= $noarea?> == 0) {
            $('#farmerinfo').hide();
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
//                $.get('index.php?r=basedataverify/basedataverifydckinput', {farms_id:data.farms_id}, function (body) {
//                    $('#dialogMsg').html('');
//                    $('#dialogMsg').html(body);
//                });
                $('#ddarea').val(0);
                $('#ymarea').val(0);
                $('#xmarea').val(0);
                $('#mlsarea').val(0);
                $('#zdarea').val(0);
                $('#byarea').val(0);
                $('#lmarea').val(0);
                $('#otherarea').val(0);
                $('#ddcj-farmer').val('100%');
                $('#ddcj-lessee').val('0%');
                $('#ymcj-farmer').val('100%');
                $('#ymcj-lessee').val('0%');
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
            alert('只能输入数字');
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
            if($('#'+textid).val() > 0) {
                var farmerpercent = $('#tempddcj_farmer-' + id).val();
                var reg = /\d{1,}\.{0,1}\d{0,}/;
                var ms = farmerpercent.match(reg);
                var farmerbfb = ms[0];
                farmerbfb = farmerbfb / 100;
                var lesseepercent = $('#tempddcj_lessee-' + id).val();
                ms = lesseepercent.match(reg);
                var lesseebfb = ms[0];
                lesseebfb = lesseebfb / 100;
                var farmer = $('#' + textid).val() * farmerbfb;
                var lessee = $('#' + textid).val() * lesseebfb;
                $('#leaseddcj-farmer' + '_' + id).val(farmer.toFixed(2));
                $('#leaseddcj-lessee' + '_' + id).val(lessee.toFixed(2));
            }
        }
        if(zw == 'ymarea') {
            if($('#'+textid).val() > 0) {
                var farmerpercent = $('#tempymcj_farmer-' + id).val();
                var reg = /\d{1,}\.{0,1}\d{0,}/;
                var ms = farmerpercent.match(reg);
                var farmerbfb = ms[0];
                farmerbfb = farmerbfb / 100;
                var lesseepercent = $('#tempymcj_lessee-' + id).val();
                ms = lesseepercent.match(reg);
                var lesseebfb = ms[0];
                lesseebfb = lesseebfb * 1 / 100;
                var farmer = $('#' + textid).val() * farmerbfb;
                var lessee = $('#' + textid).val() * lesseebfb;
                $('#leaseymcj-farmer' + '_' + id).val(farmer.toFixed(2));
                $('#leaseymcj-lessee' + '_' + id).val(lessee.toFixed(2));
            }
        }
    }

    $('#ddarea').change(function () {
        if(!Number($(this).val()) && $(this).val() !== '') {
            alert('只能输入数字');
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
//                    var typename_id = $('#goodseedtype').val();
//                    var area = $("#Area").val();
                    var plant_id = $('#plant-id').val();
                    var planter = $('#Planter').val();
                    var type = $("#Type").val();
                    var input = $('#total_area').val();
                    var ID = $('#ID').val();
                    var tempid = $('#temp-id').val();
                    var type_id = new Array();
                    var area_num = new Array();
                    for(i=1;i<=tempid;i++) {
                        var t = $('#type_'+i).val();
                        if(t !== undefined || t !== '') {
                            type_id[i] = t;
                        }
                        var area = $('#area_'+i).val();
                        console.log(area);
                        if(area !== undefined || area !== '') {
                            area_num[i] = area;
                        }
                    }
                    console.log(area.num);
                    $.getJSON('index.php?r=goodseedinfo/goodseedinfosave',{'typename':type_id.join(','),'area':area_num.join(','),'plant_id':plant_id,'farms_id':<?= $farms['id']?>,'lease_id':planter,'total_area':input},function (data) {
                        $('#g-'+plant_id+'-'+planter).val(data.goodseedinfo_id);
                        console.log(type);
                        var html = $('#'+type).html();
                        $('#'+type).html('<i class="fa fa-pagelines text-success"></i>'+html);
                        $('#'+ID).val(input);
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
//    function setGoodseed(plant_id,planter,val,id,type)
//    {
////        var input = $(this).val();
//        var farms_id = <?//= $_GET['farms_id']?>//;
//        if(val > 0) {
//            $.get('index.php?r=goodseed/goodseedlistajax', {'farms_id':farms_id,'plant_id': plant_id, 'planter': planter,'type':type,'input':val,'id':id}, function (body) {
//                $('#goodseed').html(body);
//                $("#goodseed").dialog("open");
//            });
//        }
//    }
    $('#ymarea').change(function () {
        if(!Number($(this).val()) && $(this).val() !== '') {
            alert('只能输入数字');
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
            alert('只能输入数字');
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
            alert('只能输入数字');
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
            alert('只能输入数字');
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
            alert('只能输入数字');
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
            alert('只能输入数字');
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
            alert('只能输入数字');
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

            $('#ddcj-farmer').val($(this).val());
            $('#ddcj-lessee').val(0);

    });
    $('#ymarea').blur(function () {
        if($(this).val() == '') {
            $(this).val(0);
        }

            $('#ymcj-farmer').val($(this).val());
            $('#ymcj-lessee').val(0);

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