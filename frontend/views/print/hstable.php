<?php
namespace frontend\controllers;

use app\models\Plant;
use app\models\Plantingstructure;
use app\models\Lease;
use app\models\User;

?>
<style>
    table,td,th {
        border-collapse: collapse;
        font-size: 17px
    }
    .nnn {
        border-left-style:hidden;
        border-right-style:hidden;
        border-top-style:hidden;
    }
</style>
<table>
    <tr>
        <td>
            <table width="1050px" border="1">
                <tr class="nnn">
                    <td align="center" colspan="14" ><strong>计&nbsp;&nbsp;&nbsp;&nbsp;划&nbsp;&nbsp;&nbsp;&nbsp;种&nbsp;&nbsp;&nbsp;&nbsp;植&nbsp;&nbsp;&nbsp;&nbsp;结&nbsp;&nbsp;&nbsp;&nbsp;构</strong></td>
                </tr>
                <tr>
                    <td align="center" rowspan="2" width="6%"><strong>种植者<br>姓名</strong></td>
                    <td align="center" rowspan="2" width="15%"><strong>身份证号码</strong></td>
                    <td align="center" rowspan="2" width="10%"><strong>联系电话</strong></td>
                    <td align="center" rowspan="2" width="9%"><strong>总种植面积</strong><strong></strong></td>
                    <td align="center" rowspan="0" colspan="2" width=""><strong>大豆</strong></td>
                    <td align="center" rowspan="0" colspan="2" width=""><strong>玉米</strong></td>
                    <td align="center" rowspan="2" width="" width="7%"><strong>小麦</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>马铃薯</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>杂豆</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>北药</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>蓝莓</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>其他</strong><strong></strong></td>
                </tr>
                <tr>
                    <td width="8%" height="40" align="center" ><strong>面积</strong></td>
                    <td width="8%" align="center" ><strong>地块</strong></td>
                    <td width="8%" align="center" ><strong>面积</strong></td>
                    <td width="8%" align="center" ><strong>地块</strong></td>
                </tr>
                <?php
                $rows = 0;
                $zzzName = $farm['farmername'];
                $zzzCardid = $farm['cardid'];
                $zzzTel = $farm['telephone'];
                if($farmerPlantings) {
                    $rows = 1;

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
                        <td align="center" height="40"><?= $zzzName?></td>
                        <td align="center"><?= $zzzCardid?></td>
                        <td align="center"><?= $zzzTel?></td>
                        <td align="center"><?= $zzArea?></td>
                        <td align="center"><?= $ddArea?></td>
                        <td align="center"><?= $ddZongdi?></td>
                        <td align="center"><?= $ymArea?></td>
                        <td align="center"><?= $ymZongdi?></td>
                        <td align="center"><?= $xmArea?></td>
                        <td align="center"><?= $mlsArea?></td>
                        <td align="center"><?= $zdArea?></td>
                        <td align="center"><?= $byArea?></td>
                        <td align="center"><?= $lmArea?></td>
                        <td align="center"><?= $otherArea?></td>
                    </tr>
                <?php }
                ?>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="1050px" border="1">
                <tr class="nnn">
                    <td align="center" colspan="14"><strong>核&nbsp;&nbsp;&nbsp;&nbsp;实&nbsp;&nbsp;&nbsp;&nbsp;种&nbsp;&nbsp;&nbsp;&nbsp;植&nbsp;&nbsp;&nbsp;&nbsp;结&nbsp;&nbsp;&nbsp;&nbsp;构</strong></td>
                </tr>
                <tr>
                    <td align="center" rowspan="2" width="6%"><strong>种植者<br>姓名</strong></td>
                    <td align="center" rowspan="2" width="15%"><strong>身份证号码</strong></td>
                    <td align="center" rowspan="2" width="10%"><strong>联系电话</strong></td>
                    <td align="center" rowspan="2" width="9%"><strong>总种植面积</strong><strong></strong></td>
                    <td align="center" rowspan="0" colspan="2" width=""><strong>大豆 A</strong></td>
                    <td align="center" rowspan="0" colspan="2" width=""><strong>玉米 B</strong></td>
                    <td align="center" rowspan="2" width="" width="7%"><strong>小麦<br>C</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>马铃薯<br>D</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>杂豆<br>E</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>北药<br>F</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>蓝莓<br>G</strong><strong></strong></td>
                    <td align="center" rowspan="2" width=""><strong>其他<br>H</strong><strong></strong></td>
                </tr>
                <tr>
                    <td width="7%" height="40" align="center" ><strong>面积</strong></td>
                    <td width="7%" align="center" ><strong>地块</strong></td>
                    <td width="7%" align="center" ><strong>面积</strong></td>
                    <td width="7%" align="center" ><strong>地块</strong></td>
                </tr>
                    <tr>
                        <td align="center" height="40"><?= $zzzName?></td>
                        <td align="center"><?= $zzzCardid?></td>
                        <td align="center"><?= $zzzTel?></td>
                        <td align="center" ></td>
                        <td align="center" ></td>
                        <td align="center" ></td>
                        <td align="center" ></td>
                        <td align="center" width="8%"></td>
                        <td align="center" ></td>
                        <td align="center" ></td>
                        <td align="center" ></td>
                        <td align="center" ></td>
                        <td align="center" ></td>
                        <td align="center" ></td>
                    </tr>
                <tr>
                    <td align="center" height="40"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center" ></td>
                    <td align="center" ></td>
                    <td align="center" ></td>
                    <td align="center" ></td>
                    <td align="center" ></td>
                    <td align="center" ></td>
                    <td align="center" ></td>
                    <td align="center" ></td>
                    <td align="center" ></td>
                    <td align="center" ></td>
                    <td align="center" ></td>
                </tr>


            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="1050px" border="1">
                <tr>
                    <td align="center" rowspan="2" width="10%">
                        宗地<br>（<?= $zongdiArray['num']?>）</strong>
                    </td>
                </tr>
                <tr>
                    <td valign="top" height="170px">
                        <table  width="100%" border="0" align="right">
                            <?php for($i = 0;$i<count($zongdiArray['zongdi']);$i++) {
// 			    	echo $i%6;
                                if($i%5 == 0) {
                                    echo '<tr>';
                                    echo '<td height="25px">';
                                    echo $zongdiArray['zongdi'][$i];
                                    echo '</td>';
                                } else {
                                    echo '<td>';
                                    echo $zongdiArray['zongdi'][$i];
                                    echo '</td>';
                                }
                            }?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" height="30"><strong>备注</strong></td>
                    <td align="center"></td>
                </tr>
            </table>
        </td>
    </tr>

</table>


