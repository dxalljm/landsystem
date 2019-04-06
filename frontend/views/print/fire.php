<?php
use app\models\Breedtype;
?>
<style> table,td,th {border: 1px solid black;border-style: solid;border-collapse: collapse;font-size: 18px}</style>
<table width="1050px" border="1">
    <tr>
        <td  rowspan=2 align="center"><strong>防火工作</strong></td>
        <td  align="center"><strong>签订防火合同</strong></td>
        <td  align="center"><strong>签订安全生产合同</strong></td>
        <td  align="center"><strong>签订环境保护合同</strong></td>
        <td  align="center"><strong>签订野外作业证</strong></td>
        <td  align="center"><strong>签订防火宣传</strong></td>
        <td  align="center"><strong>检查记录</strong></td>
    </tr>
    <?php
    if($fire) {?>
        <tr>
            <td  align="center"><?php if($fire['firecontract']) echo '是'; else echo '否'; ?></td>
            <td  align="center"><?php if($fire['safecontract']) echo '是'; else echo '否'; ?></td>
            <td  align="center"><?php if($fire['environmental_agreement']) echo '是'; else echo '否'; ?></td>
            <td  align="center"><?php if($fire['fieldpermit']) echo '是'; else echo '否'; ?></td>
            <td  align="center"><?php if($fire['leaflets']) echo '是'; else echo '否'; ?></td>
            <td  align="center"><?php if($fire['rectification_record']) echo '是'; else echo '否'; ?></td>
        </tr>
        <?php
    } else {
        ?>
        <tr>
            <td  align="center">是&nbsp;&nbsp;否</td>
            <td  align="center">是&nbsp;&nbsp;否</td>
            <td  align="center">是&nbsp;&nbsp;否</td>
            <td  align="center">是&nbsp;&nbsp;否</td>
            <td  align="center">是&nbsp;&nbsp;否</td>
            <td  align="center">是&nbsp;&nbsp;否</td>
        </tr>
        <?php
    }
    ?>
    <tr>
        <td  rowspan=<?php if(empty($breedinfos)) echo 3; else echo count($breedinfos)+1?> align="center"><strong>畜牧养殖</strong></td>
        <td align="center"><strong>序号</strong></td>
        <td align="center"><strong>畜牧类型</strong></td>
        <td  align="center"><strong>养殖数量</strong></td>
        <td  align="center"><strong>基础投资</strong></td>
        <td  colspan="2" align="center"><strong>圈舍面积</strong></td>
    </tr>
    <?php
    if($breedinfos) {
        foreach($breedinfos as $key => $breedinfo) {
            $type = Breedtype::find()->where(['id'=>$breedinfo['breedtype_id']])->one();
            ?>
            <tr>
                <td width="15%"  align="center"><?= ++$key?></td>
                <td   align="center"><?= $type['typename']?></td>
                <td   align="center"><?= $breedinfo['number'].'&nbsp;'.$type['unit']?></td>
                <td   align="center"><?= $breedinfo['basicinvestment']?></td>
                <td   colspan="2" align="center"><?= $breedinfo['housingarea']?></td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td width="15%"  align="center">&nbsp;</td>
            <td   align="center">&nbsp;</td>
            <td   align="center">&nbsp;</td>
            <td   align="center">&nbsp;</td>
            <td   colspan="2" align="center">&nbsp;</td>
        </tr>
        <tr>
            <td width="15%"  align="center">&nbsp;</td>
            <td   align="center">&nbsp;</td>
            <td   align="center">&nbsp;</td>
            <td   align="center">&nbsp;</td>
            <td   colspan="2" align="center">&nbsp;</td>
        </tr>
    <?php }?>
</table>

