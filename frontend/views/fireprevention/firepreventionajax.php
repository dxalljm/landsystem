<?php
/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 2018/1/31
 * Time: 08:56
 */
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
?>
<?php
echo \app\models\Farms::showFarminfo2($farms_id);
?>
<?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table2-bordered table-hover" id="fire">
    
    <tr>
        <td align="center">签订防火合同</td>
        <td align="center">签订安全生产合同</td>
        <td align="center">签订环境保护合同</td>
    </tr>
    <tr>
        <td align="center"><?= Html::radioList('Fireprevention[firecontract]',$firecontract,[1=>'是',0=>'否'],['class'=>''])?></td>
        <td align="center"><?= Html::radioList('Fireprevention[safecontract]',$safecontract,[1=>'是',0=>'否'],['class'=>''])?></td>
        <td align="center"><?= Html::radioList('Fireprevention[environmental_agreement]',$environmental_agreement,[1=>'是',0=>'否'],['class'=>''])?></td>
    </tr>
</table>
<?php ActiveFormrdiv::end(); ?>