<?php
use app\models\Breedtype;
use app\models\Farmerinfo;
use app\models\Lease;
?>
<style> table,td,th {border: 1px solid black;border-style: solid;border-collapse: collapse;font-size: 18px}</style>
<?php
$farmerinfo = Farmerinfo::findOne($farm['cardid']);
$lease = Lease::find()->where(['farms_id'=>$farm['id'],'year'=>\app\models\User::getYear()]);
if($farmerinfo['political_outlook'] == '党员'){
    $zhibuLabel = '所在支部';
    $zhibu = $farmerinfo['zhibu'];
} else {
    $zhibuLabel = '&nbsp;&nbsp;&nbsp;&nbsp;';
    $zhibu = '&nbsp;&nbsp;&nbsp;&nbsp;';
}
?>
<table width="1056px" border="1">
    <tr>
        <td align="center"><strong>农场名称</strong></td>
        <td align="center"><?= $farm['farmname']?></td>
        <td align="center"><strong>合同号</strong></td>
        <td align="center"><?= $farm['contractnumber']?></td>
        <td align="center"><strong>合同面积</strong></td>
        <td align="center"><?= $farm['contractarea']?></td>
        <td align="center"><strong>政治面貌</strong></td>
        <td align="center"><?= $farmerinfo['political_outlook']?></td>
        <td align="center"><strong><?= $zhibuLabel?></strong></td>
        <td align="center"><?= $zhibu?></td>
    </tr>
    <tr>
        <td align="center"><strong>法人姓名</strong></td>
        <td align="center"><?= $farm['farmername']?></td>
        <td align="center"><strong>身份证号</strong></td>
        <td align="center"><?= $farm['cardid']?></td>
        <td align="center"><strong>联系电话</strong></td>
        <td align="center"><?= $farm['telephone']?></td>
        <td align="center"><strong>是否租赁</strong></td>
        <td align="center" colspan="3"><?php if($isLease) echo $lease->count().'人，共'.$lease->sum('lease_area').'亩';else echo '否'?></td>
    </tr>
    <tr>
        <td align="center">农场位置</td>
        <td colspan="5"><?= $farm['address']?></td>
        <td align="center"><strong>农场坐标</strong></td>
        <td align="center" colspan="4"><?php if(!preg_match('/([0-9])/', $farm['longitude'])) echo 'E___°__\'__.__"'.'&nbsp;&nbsp;'.'N___°__\'__.__"'; else echo $farm['longitude'].'&nbsp;&nbsp;'.$farm['latitude']?></td>
    </tr>
</table>

