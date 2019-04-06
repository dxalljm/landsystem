<?php
use app\models\Breedtype;
use app\models\Lease;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Goodseed;
use app\models\Plantinputproduct;
use app\models\Inputproduct;
use app\models\Plantpesticides;
use app\models\Pesticides;
use app\models\Insurance;
use yii\helpers\ArrayHelper;;
use app\models\Subsidytypetofarm;
use app\models\Breedinfo;
use app\models\User;
use app\models\Insurancecompany;
use app\models\Saleswhere;
?>
<style> .printTable {border: 1px solid black;border-style: solid;border-collapse: collapse;font-size:16px;}</style>
<table width="1063px" style="border: 0px">
     <tr>
         <td>
             <table width="700px" border="1" class="printTable">
                 <tr>
                     <td  rowspan=2 align="center" height="93px"><strong>防火工作</strong></td>
                     <td  align="center"><strong>防火合同</strong></td>
                     <td  align="center"><strong>安全生产合同</strong></td>
                     <td  align="center"><strong>环境保护合同</strong></td>
                     <td  align="center"><strong>野外作业证</strong></td>
                     <td  align="center"><strong>防火宣传</strong></td>
                     <td  align="center"><strong>检查记录</strong></td>
                 </tr>
                 <?php
                 if($fire) {?>
                     <tr>
                         <td  align="center"><?php if($fire['firecontract']) echo '签订'; else echo '未签订'; ?></td>
                         <td  align="center"><?php if($fire['safecontract']) echo '签订'; else echo '未签订'; ?></td>
                         <td  align="center"><?php if($fire['environmental_agreement']) echo '签订'; else echo '未签订'; ?></td>
                         <td  align="center"></td>
                         <td  align="center"></td>
                         <td  align="center"></td>
                     </tr>
                     <?php
                 } else {
                     ?>
                     <tr>
                         <td  align="center">&nbsp;</td>
                         <td  align="center">&nbsp;</td>
                         <td  align="center">&nbsp;</td>
                         <td  align="center">&nbsp;</td>
                         <td  align="center">&nbsp;</td>
                         <td  align="center">&nbsp;</td>
                     </tr>
                     <?php
                 }
                 ?>
                 <tr>
                     <td  rowspan=<?php if(empty($breedinfos)) echo 3; else echo count($breedinfos)+1?> align="center"><strong>畜牧养殖</strong></td>
                     <td align="center"><strong>牛</strong></td>
                     <td align="center"><strong>马</strong></td>
                     <td  align="center"><strong>猪</strong></td>
                     <td  align="center"><strong>羊</strong></td>
                     <td  align="center"><strong>禽</strong></td>
                     <td  align="center"><strong>其它</strong></td>
                 </tr>
                 <?php
                    $nuiID = Breedtype::find()->where(['typename'=>'牛'])->one()['id'];
                    $maID = Breedtype::find()->where(['typename'=>'马'])->one()['id'];
                    $zhuID = Breedtype::find()->where(['typename'=>'猪'])->one()['id'];
                    $yangID = Breedtype::find()->where(['typename'=>'羊'])->one()['id'];
                    $qinID = Breedtype::find()->where(['typename'=>'禽类'])->one()['id'];
                    $qinArray = ArrayHelper::map(Breedtype::find()->where(['father_id'=>$qinID])->all(),'id','id');
                    sort($qinArray);
                    $array = $qinArray;
//                    var_dump($qinArray);exit;
                    $allID = ArrayHelper::map(Breedtype::find()->all(),'id','id');
                    sort($allID);
                     $array[] = $nuiID;
                     $array[] = $maID;
                     $array[] = $zhuID;
                     $array[] = $yangID;
//                    var_dump($qinArray);exit;
                    $otherID = array_diff($allID,$array);
                    sort($otherID);
                    $niu = Breedinfo::find()->where(['breedtype_id'=>$nuiID,'farms_id'=>$farms_id,'year'=>User::getYear()])->sum('number');
                    $ma = Breedinfo::find()->where(['breedtype_id'=>$maID,'farms_id'=>$farms_id,'year'=>User::getYear()])->sum('number');
                    $zhu = Breedinfo::find()->where(['breedtype_id'=>$zhuID,'farms_id'=>$farms_id,'year'=>User::getYear()])->sum('number');
                    $yang = Breedinfo::find()->where(['breedtype_id'=>$yangID,'farms_id'=>$farms_id,'year'=>User::getYear()])->sum('number');
                    $qin = Breedinfo::find()->where(['breedtype_id'=>$qinArray,'farms_id'=>$farms_id,'year'=>User::getYear()])->sum('number');
//                 var_dump($qin);exit
                    $other = Breedinfo::find()->where(['breedtype_id'=>$otherID,'farms_id'=>$farms_id,'year'=>User::getYear()])->sum('number');
                 ?>
                         <tr>
                             <td align="center"><strong><?= $niu==''?'&nbsp;':$niu?></strong></td>
                             <td align="center"><strong><?= $ma?></strong></td>
                             <td  align="center"><strong><?= $zhu?></strong></td>
                             <td  align="center"><strong><?= $yang?></strong></td>
                             <td  align="center"><strong><?= $qin?></strong></td>
                             <td  align="center"><strong><?= $other?></strong></td>
                         </tr>

             </table>
         </td>
         <td>

                 <table width="350px" style="height: auto" class="printTable" border="1" >
                     <tr>
                         <td colspan="4" align="center" height="32px"><strong><?= User::getLastYear()?>农产品销售情况</strong></td>
                     </tr>
                     <tr>
                         <td align="center"><strong>序号</strong></td>
                         <td align="center"><strong>农产品</strong></td>
                         <td align="center"><strong>销售去向</strong></td>
                         <td align="center"><strong>产量</strong></td>
                     </tr>
                     <?php
                     foreach($sales as $key => $sale) {
                         ?>
                         <tr>
                             <td align="center"><?= $key+1?></td>
                             <td align="center"><?= Plant::find()->where(['id'=>$sale['plant_id']])->one()['typename']?></td>
                             <td align="center"><?= Saleswhere::find()->where(['id'=>$sale['whereabouts']])->one()['wherename']?></td>
                             <td align="center"><?= $sale['volume']?>斤</td>
                         </tr>
                         <?php
                     }
                     if(count($sales) < 4) {
                         for($i=0;$i<4-count($sales);$i++) {
                             ?>

                             <tr>
                                 <td align="center">&nbsp;</td>
                                 <td align="center">&nbsp;</td>
                                 <td align="center">&nbsp;</td>
                                 <td align="center">&nbsp;</td>
                             </tr>
                         <?php }}
                     ?>
                 </table>
         </td>
     </tr>
    <tr>
        <td colspan="2">
            <table width="100%" border="1" class="printTable">
                <tr>
                    <td align="center"><strong>种植者姓名</strong></td>
                    <td align="center"><strong>身份证号</strong></td>
                    <td align="center"><strong>电话</strong></td>
                    <td align="center"><strong>种植面积</strong></td>
                </tr>
                <tr>
                    <td  align="center" ><?= $farm['farmername']?></td>
                    <td  align="center" ><?= $farm['cardid']?></td>
                    <td  align="center" ><?= $farm['telephone']?></td>
                    <td  align="center" ><?= Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'lease_id'=>0])->sum('area')?>亩</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" class="printTable" border="1" >
                <?php
                $zhzbID = Subsidytypetofarm::find()->where(['mark'=>'zhzb'])->one()['id'];
                $ddcjID = Subsidytypetofarm::find()->where(['mark'=>'ddcj'])->one()['id'];
                $ymcjID = Subsidytypetofarm::find()->where(['mark'=>'ymcj'])->one()['id'];
                $newID = Subsidytypetofarm::find()->where(['mark'=>'new'])->one()['id'];
                $ddID = Plant::find()->where(['typename' => '大豆'])->one()['id'];
                $ymID = Plant::find()->where(['typename' => '玉米'])->one()['id'];
                $ddArea = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'year'=>User::getYear(),'plant_id'=>$ddID])->one()['area'];
                $ymArea = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'year'=>User::getYear(),'plant_id'=>$ymID])->one()['area'];
                ?>
                <tr>
                    <td width="10%" align="center" colspan=2 rowspan="3"><strong>补贴比率<br>分配</strong></td>
                    <td align="center" colspan="2">综合直补</td>
                    <td align="center" colspan="2">大豆差价补贴</td>
                    <td align="center" colspan="2">玉米差价补贴</td>
                    <td align="center" colspan="2">新增其他补贴</td>
                </tr>
                <tr>
                    <td align="center">法人</td>
                    <td align="center">种植者</td>
                    <td align="center">法人</td>
                    <td align="center">种植者</td>
                    <td align="center">法人</td>
                    <td align="center">种植者</td>
                    <td align="center">法人</td>
                    <td align="center">种植者</td>
                </tr>
                <tr>
                    <td align="center">100%</td>
                    <td align="center">0%</td>
                    <td align="center"><?= $ddArea == ''?'100%':$ddArea.'亩'?></td>
                    <td align="center"><?= $ddArea == ''?'0%':'0亩'?></td>
                    <td align="center"><?= $ymArea== ''?'100%':$ymArea.'0亩'?></td>
                    <td align="center"><?= $ymArea== ''?'0%':'0亩'?></td>
                    <td align="center">100%</td>
                    <td align="center">0%</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" class="printTable" border="1" >
                <tr>
                    <td align="center" colspan="2"><strong>作物</strong></td>
                    <td align="center" ><strong>良种使用</strong></td>
                    <td align="center" colspan="2"><strong>化肥使用情况</strong></td>
                    <td align="center" colspan="2"><strong>农药使用情况</strong></td>
                </tr>
                <tr>
                    <td  align="center"><strong>名称</strong></td>
                    <td  align="center"><strong>面积</strong></td>
                    <td  align="center"><strong>名称</strong></td>
                    <td align="center" colspan="2"><strong>名称(数量)</strong></td>
                    <td align="center" colspan="2"><strong>名称(数量)</strong></td>
                </tr>
                <?php

                foreach ($isFarmerPlanting as $planting) {
                    $plant = Plant::find()->where(['id'=>$planting['plant_id']])->one();
                    $goodseed = Goodseed::find()->where(['id'=>$planting['goodseed_id']])->one();
                    $input = Plantinputproduct::find()->where(['lessee_id'=>0,'plant_id'=>$planting['plant_id'],'planting_id'=>$planting['id']])->all();
                    $inputStr = [];
                    foreach($input as $in) {
                        $inputStr[] = Inputproduct::find()->where(['id'=>$in['inputproduct_id']])->one()['fertilizer'].'('.$in['pconsumption'].'斤)';
                    }
                    $pes = Plantpesticides::find()->where(['lessee_id'=>0,'plant_id'=>$planting['plant_id'],'planting_id'=>$planting['id']])->all();
                    $pesStr = [];
                    foreach($pes as $p) {
                        $pesStr[] = Pesticides::find()->where(['id'=>$p['pesticides_id']])->one()['pesticidename'].'('.$p['pconsumption'].'斤)';
                    }

                    ?>
                    <tr>
                        <td  align="center"><?= $plant['typename']?></td>
                        <td  align="center"><?= $planting['area']?>亩</td>
                        <td  align="center"><?= $goodseed['typename']?></td>
                        <td align="center" colspan="2"><?= implode('/',$inputStr)?></td>
                        <td align="center" colspan="2"><?= implode('/',$pesStr)?></td>
                    </tr>
                <?php }
                for($i=1;$i<=3-count($isFarmerPlanting);$i++) {
                    ?>
                    <tr>
                        <td  align="center">&nbsp;</td>
                        <td  align="center">&nbsp;</td>
                        <td  align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                <?php }?>
        </table>
    </td>
</tr>
<tr>
    <td colspan="2">
        <table width="100%" class="printTable" border="1" >
                <tr>
                    <td  rowspan=2 align="center"><strong>种植业保险</strong></td>
                    <td align="center"><strong>投保人</strong></td>
                    <td  align="center"><strong>被保险人</strong></td>
                    <td  align="center"><strong>承保公司</strong></td>
                    <td  align="center"><strong>投保面积 </strong></td>
                    <td  align="center"><strong>大豆</strong></td>
                    <td  align="center"><strong>小麦</strong></td>
                    <td align="center"><strong>其它</strong></td>
                </tr>
                <?php $insurance = Insurance::find()->where(['year'=>User::getYear(),'farms_id'=>$farms_id,'lease_id'=>0])->one();
                if($insurance) {
                    ?>
                    <tr>
                        <td align="center"><?= $insurance['policyholder']?></td>
                        <td  align="center"><?= $insurance['policyholder']?></td>
                        <td  align="center"><?= Insurancecompany::find()->where(['id'=>$insurance['company_id']])->one()['companynname']?></td>
                        <td  align="center"><?= $insurance['insuredarea']?>亩</td>
                        <td  align="center"><?= $insurance['insuredsoybean']?>亩</td>
                        <td  align="center"><?= $insurance['insuredwheat']?>亩</td>
                        <td align="center"><?= $insurance['insuredother']?>亩</td>
                    </tr>
                <?php } else {?>
                    <tr>
                        <td align="center">&nbsp;</td>
                        <td  align="center">&nbsp;</td>
                        <td  align="center">&nbsp;</td>
                        <td  align="center">&nbsp;</td>
                        <td  align="center">&nbsp;</td>
                        <td  align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                <?php }?>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" valign="top">
            <table width="100%" class="printTable" border="1" >
                <tr>
                    <td align="center" width="9.8%"><strong>备注</strong></td>
                    <td>&nbsp;<?php
                        $n = 4;
                        if(count($isFarmerPlanting) > 3) {
                            $m = count($isFarmerPlanting) - 3;
                            $n = 3 - $m;
                        }
                        for($i=1;$i<=$n;$i++) {
                            echo '<br>';
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
