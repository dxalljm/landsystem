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
                 <tr>
                     <td  align="center">&nbsp;</td>
                     <td  align="center">&nbsp;</td>
                     <td  align="center">&nbsp;</td>
                     <td  align="center">&nbsp;</td>
                     <td  align="center">&nbsp;</td>
                     <td  align="center">&nbsp;</td>
                 </tr>
                 <tr>
                     <td  rowspan=<?php if(empty($breedinfos)) echo 3; else echo count($breedinfos)+1?> align="center"><strong>畜牧养殖</strong></td>
                     <td align="center"><strong>牛</strong></td>
                     <td align="center"><strong>马</strong></td>
                     <td  align="center"><strong>猪</strong></td>
                     <td  align="center"><strong>羊</strong></td>
                     <td  align="center"><strong>禽</strong></td>
                     <td  align="center"><strong>其它</strong></td>
                 </tr>
                 <tr>
                     <td align="center">&nbsp;</td>
                     <td align="center"></td>
                     <td  align="center"></td>
                     <td  align="center"></td>
                     <td  align="center"></td>
                     <td  align="center"></td>
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
                         for($i=0;$i<4;$i++) {
                             ?>
                             <tr>
                                 <td align="center">&nbsp;</td>
                                 <td align="center">&nbsp;</td>
                                 <td align="center">&nbsp;</td>
                                 <td align="center">&nbsp;</td>
                             </tr>
                         <?php }
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
                    <td  align="center" >&nbsp;</td>
                    <td  align="center" ></td>
                    <td  align="center" ></td>
                    <td  align="center" ></td>
                </tr>
                <tr>
                    <td  align="center" >&nbsp;</td>
                    <td  align="center" ></td>
                    <td  align="center" ></td>
                    <td  align="center" ></td>
                </tr>
                <tr>
                    <td  align="center" >&nbsp;</td>
                    <td  align="center" ></td>
                    <td  align="center" ></td>
                    <td  align="center" ></td>
                </tr>
                <tr>
                    <td  align="center" >&nbsp;</td>
                    <td  align="center" ></td>
                    <td  align="center" ></td>
                    <td  align="center" ></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" class="printTable" border="1" >
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
                    <td align="center">&nbsp;</td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" class="printTable" border="1" >
                <tr>
                    <td align="center" rowspan="2"><strong>种植者</strong></td>
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
                for($i=1;$i<=5;$i++) {
                    ?>
                    <tr>
                        <td  align="center">&nbsp;</td>
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
                <tr>
                    <td align="center">&nbsp;</td>
                    <td  align="center">&nbsp;</td>
                    <td  align="center">&nbsp;</td>
                    <td  align="center">&nbsp;</td>
                    <td  align="center">&nbsp;</td>
                    <td  align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" valign="top">
            <table width="100%" class="printTable" border="1" >
                <tr>
                    <td align="center" width="9.8%"><strong>备注</strong></td>
                    <td>&nbsp;<br><br><br>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
