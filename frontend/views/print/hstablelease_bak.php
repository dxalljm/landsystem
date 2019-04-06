<?php
namespace frontend\controllers;

use app\models\Plant;
use app\models\Plantingstructure;
use app\models\Lease;
use app\models\User;

?>
<style> table,td,th {border-collapse: collapse;}</style>
<table>
    <tr>
        <td>
            <table width="1050px" border="1">
                <tr>
                    <td align="center" rowspan="3" width="8%"><strong>种植者<br>姓名</strong></td>
                    <td align="center" rowspan="3" width="17%"><strong>身份证号码</strong></td>
                    <td align="center" rowspan="3" width="12%"><strong>联系电话</strong></td>
                    <td align="center" colspan="11"><strong>计&nbsp;&nbsp;&nbsp;&nbsp;划&nbsp;&nbsp;&nbsp;&nbsp;种&nbsp;&nbsp;&nbsp;&nbsp;植&nbsp;&nbsp;&nbsp;&nbsp;结&nbsp;&nbsp;&nbsp;&nbsp;构（亩）</strong></td>
                </tr>
                <tr>
                    <td align="center" rowspan="2" width="9%"><strong>总种植面积</strong><strong></strong></td>
                    <td align="center" rowspan="0" colspan="2" width=""><strong>大豆</strong></td>
                    <td align="center" rowspan="2" width="" width="7%"><strong>小麦</strong><strong></strong></td>
                    <td align="center" rowspan="0" colspan="2" width=""><strong>玉米</strong></td>
                    <td align="center" rowspan="2" width=""><strong>马铃薯</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>杂豆</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>北药</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>蓝莓</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>其他</strong><strong></strong></td>
                </tr

                ><tr>
                    <td width="5%" height="30" align="center" ><strong>面积</strong></td>
                    <td width="10%" align="center" ><strong>地块号</strong></td>
                    <td width="5%" align="center" ><strong>面积</strong></td>
                    <td width="10%" align="center" ><strong>地块号</strong></td>
                </tr>
                <?php
                $rows = 0;
                if($farmerPlantings) {
                    $rows = 1;
                    $zzzName = $farm['farmername'];
                    $zzzCardid = $farm['cardid'];
                    $zzzTel = $farm['telephone'];
                    $zzArea = sprintf('%.2f',Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'year'=>User::getYear()])->sum('area'));
                    $dd = Plant::find()->where(['typename'=>'大豆'])->one();
                    $ddP = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'plant_id'=>$dd['id'],'year'=>User::getYear()]);
                    $ddArea = sprintf('%.2f',$ddP->sum('area'));
                    $ddZongdi = $ddP->one()['zongdi'];
                    $xm = Plant::find()->where(['typename'=>'小麦'])->one();
                    $xmArea = sprintf('%.2f',Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'plant_id'=>$xm['id'],'year'=>User::getYear()])->sum('area'));
                    $ym = Plant::find()->where(['typename'=>'玉米'])->one();
                    $ymP = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'plant_id'=>$ym['id'],'year'=>User::getYear()]);
                    $ymArea = sprintf('%.2f',$ymP->sum('area'));
                    $ymZongdi = $ymP->one()['zongdi'];
                    $mls = Plant::find()->where(['typename'=>'马铃薯'])->one();
                    $mlsArea = sprintf('%.2f',Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'plant_id'=>$mls['id'],'year'=>User::getYear()])->sum('area'));
                    $zd = Plant::find()->where(['typename'=>'杂豆'])->one();
                    $zdArea = sprintf('%.2f',Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'plant_id'=>$zd['id'],'year'=>User::getYear()])->sum('area'));
                    $by = Plant::find()->where(['typename'=>'北药'])->one();
                    $byArea = sprintf('%.2f',Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'plant_id'=>$by['id'],'year'=>User::getYear()])->sum('area'));
                    $lm = Plant::find()->where(['typename'=>'蓝莓'])->one();
                    $lmArea = sprintf('%.2f',Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'plant_id'=>$lm['id'],'year'=>User::getYear()])->sum('area'));
                    $other = Plant::find()->where(['typename'=>'其他'])->one();
                    $otherArea = sprintf('%.2f',Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'plant_id'=>$other['id'],'year'=>User::getYear()])->sum('area'));
                    ?>
                    <tr>
                        <td align="center" height="30"><strong><?= $zzzName?></strong></td>
                        <td align="center"><strong><?= $zzzCardid?></strong></td>
                        <td align="center"><strong><?= $zzzTel?></strong></td>
                        <td align="center"><strong><?= $zzArea?></strong></td>
                        <td align="center"><strong><?= $ddArea?></strong></td>
                        <td align="center"><strong><?= $ddZongdi?></strong></td>
                        <td align="center"><strong><?= $xmArea?></strong></td>
                        <td align="center"><strong><?= $ymArea?></strong></td>
                        <td align="center"><strong><?= $ymZongdi?></strong></td>
                        <td align="center"><strong><?= $mlsArea?></strong></td>
                        <td align="center"><strong><?= $zdArea?></strong></td>
                        <td align="center"><strong><?= $byArea?></strong></td>
                        <td align="center"><strong><?= $lmArea?></strong></td>
                        <td align="center"><strong><?= $otherArea?></strong></td>
                    </tr>
                <?php }
                if($leasePlantings) {
                    $rows += count($leasePlantings);
                    foreach ($leasePlantings as $planting) {
                        $lease = Lease::find()->where(['id' => $planting['lease_id']])->one();
                        $zzzName = $lease['lessee'];
                        $zzzCardid = $lease['lessee_cardid'];
                        $zzzTel = $lease['lessee_telephone'];
                        $zzArea = sprintf('%.2f', Plantingstructure::find()->where(['farms_id' => $farms_id, 'lease_id' => $lease['id'], 'year' => User::getYear()])->sum('area'));
                        $dd = Plant::find()->where(['typename' => '大豆'])->one();
                        $ddP = Plantingstructure::find()->where(['farms_id' => $farms_id, 'lease_id' => $lease['id'], 'plant_id' => $dd['id'], 'year' => User::getYear()]);
                        $ddArea = sprintf('%.2f', $ddP->sum('area'));
                        $ddZongdi = $ddP->one()['zongdi'];
                        $xm = Plant::find()->where(['typename' => '小麦'])->one();
                        $xmArea = sprintf('%.2f', Plantingstructure::find()->where(['farms_id' => $farms_id, 'lease_id' => $lease['id'], 'plant_id' => $xm['id'], 'year' => User::getYear()])->sum('area'));
                        $ym = Plant::find()->where(['typename' => '玉米'])->one();
                        $ymP = Plantingstructure::find()->where(['farms_id' => $farms_id, 'lease_id' => $lease['id'], 'plant_id' => $ym['id'], 'year' => User::getYear()]);
                        $ymArea = sprintf('%.2f', $ymP->sum('area'));
                        $ymZongdi = $ymP->one()['zongdi'];
                        $mls = Plant::find()->where(['typename' => '马铃薯'])->one();
                        $mlsArea = sprintf('%.2f', Plantingstructure::find()->where(['farms_id' => $farms_id, 'lease_id' => $lease['id'], 'plant_id' => $mls['id'], 'year' => User::getYear()])->sum('area'));
                        $zd = Plant::find()->where(['typename' => '杂豆'])->one();
                        $zdArea = sprintf('%.2f', Plantingstructure::find()->where(['farms_id' => $farms_id, 'lease_id' => $lease['id'], 'plant_id' => $zd['id'], 'year' => User::getYear()])->sum('area'));
                        $by = Plant::find()->where(['typename' => '北药'])->one();
                        $byArea = sprintf('%.2f', Plantingstructure::find()->where(['farms_id' => $farms_id, 'lease_id' => $lease['id'], 'plant_id' => $by['id'], 'year' => User::getYear()])->sum('area'));
                        $lm = Plant::find()->where(['typename' => '蓝莓'])->one();
                        $lmArea = sprintf('%.2f', Plantingstructure::find()->where(['farms_id' => $farms_id, 'lease_id' => $lease['id'], 'plant_id' => $lm['id'], 'year' => User::getYear()])->sum('area'));
                        $other = Plant::find()->where(['typename' => '其他'])->one();
                        $otherArea = sprintf('%.2f', Plantingstructure::find()->where(['farms_id' => $farms_id, 'lease_id' => $lease['id'], 'plant_id' => $other['id'], 'year' => User::getYear()])->sum('area'));
                        ?>
                        <tr>
                            <td align="center" height="30"><strong><?= $zzzName ?></strong></td>
                            <td align="center"><strong><?= $zzzCardid ?></strong></td>
                            <td align="center"><strong><?= $zzzTel ?></strong></td>
                            <td align="center"><strong><?= $zzArea ?></strong></td>
                            <td align="center"><strong><?= $ddArea ?></strong></td>
                            <td align="center"><strong><?= $ddZongdi ?></strong></td>
                            <td align="center"><strong><?= $xmArea ?></strong></td>
                            <td align="center"><strong><?= $ymArea ?></strong></td>
                            <td align="center"><strong><?= $ymZongdi ?></strong></td>
                            <td align="center"><strong><?= $mlsArea ?></strong></td>
                            <td align="center"><strong><?= $zdArea ?></strong></td>
                            <td align="center"><strong><?= $byArea ?></strong></td>
                            <td align="center"><strong><?= $lmArea ?></strong></td>
                            <td align="center"><strong><?= $otherArea ?></strong></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="1050px" border="1">
                <tr>
                    <td align="center" rowspan="3" width="8%"><strong>种植者<br>姓名</strong></td>
                    <td align="center" rowspan="3" width="17%"><strong>身份证号码</strong></td>
                    <td align="center" rowspan="3" width="12%"><strong>联系电话</strong></td>
                    <td align="center" colspan="11"><strong>核&nbsp;&nbsp;&nbsp;&nbsp;实&nbsp;&nbsp;&nbsp;&nbsp;种&nbsp;&nbsp;&nbsp;&nbsp;植&nbsp;&nbsp;&nbsp;&nbsp;结&nbsp;&nbsp;&nbsp;&nbsp;构（亩）</strong></td>
                </tr>
                <tr>
                    <td align="center" rowspan="2" width="9%"><strong>总种植面积</strong><strong></strong></td>
                    <td align="center" rowspan="0" colspan="2" width=""><strong>大豆</strong></td>
                    <td align="center" rowspan="2" width="" width="7%"><strong>小麦</strong><strong></strong></td>
                    <td align="center" rowspan="0" colspan="2" width=""><strong>玉米</strong></td>
                    <td align="center" rowspan="2" width=""><strong>马铃薯</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>杂豆</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>北药</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>蓝莓</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>其他</strong><strong></strong></td>
                </tr><tr>
                    <td width="5%" height="30" align="center" ><strong>面积</strong></td>
                    <td width="10%" align="center" ><strong>地块号</strong></td>
                    <td width="5%" align="center" ><strong>面积</strong></td>
                    <td width="10%" align="center" ><strong>地块号</strong></td>
                </tr>
                <?php
                    if($rows == 1) {
                        $max = 6;
                    } else {
                        $max = 7-$rows;
                    }
                for($i=0;$i<$max;$i++) {
                ?>
                <tr>
                    <td align="center" height="30"><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                    <td align="center" ><strong></strong></td>
                </tr>
                <?php }?>
                <tr>
                    <td align="center" height="80"><strong>备注</strong></td>
                    <td align="center" colspan="14"><strong></strong></td>
                </tr>

            </table>
        </td>
    </tr>
</table>


