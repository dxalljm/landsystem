<?php
namespace frontend\controllers;
<<<<<<< HEAD
use app\models\Lease;
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Processname;
use app\models\Reviewprocess;
use app\models\User;
use Yii;
use app\models\Infrastructuretype;
use app\models\Tempauditing;
<<<<<<< HEAD
use app\models\Ttpozongdi;
=======
use app\models\Loan;
use app\models\Estate;
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>
<style type="text/css">
.table {
	/* 	font-family: "仿宋"; */
<<<<<<< HEAD
	font-size: 13px;
=======
	font-size: 16px;
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
	text-align: center;
}
.italic {
	font-style: italic;
}
.econtent {
	display: inline;
}
</style>
<div class="reviewprocess-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                </div>
                <div class="box-body">

    <?php $form = ActiveFormrdiv::begin(); ?>
    
 <?php if($class == 'farmstransfer') {?>
 <table class="table table-bordered table-hover">
<<<<<<< HEAD
     <tr>
         <td width="10%" align="center" bgcolor="#D0F5FF"><strong>原农场名称</strong></td>
         <td  colspan="3" align="left" bgcolor="#D0F5FF"><span class="italic"><?= $oldfarm->farmname?></span></td>
         <td width="12%" align="center" bgcolor="#FFFFD5"><strong>现农场名称</strong></td>
         <td  colspan="3" align="left" bgcolor="#FFFFD5"><span class="italic"><?= $newfarm->farmname?></span></td>
     </tr>
     <tr>
         <td align="center" bgcolor="#D0F5FF"><strong>原法人姓名</strong></td>
         <td align="left" colspan="3" bgcolor="#D0F5FF"><span class="italic"><?= $oldfarm->farmername?></span></td>
         <td align="center" bgcolor="#FFFFD5"><strong>现法人姓名</strong></td>
         <td align="left" colspan="3" bgcolor="#FFFFD5"><span class="italic"><?= $newfarm->farmername?></span></td>

     </tr>
     <tr>
         <td align="center" bgcolor="#D0F5FF"><strong>农场位置</strong></td>
         <td colspan="3" align="left" bgcolor="#D0F5FF"><?= $oldfarm->address?></td>
         <td align="center" bgcolor="#FFFFD5"><strong>农场位置</strong></td>
         <td colspan="3" align="left" bgcolor="#FFFFD5"><?= $newfarm->address?></td>
     </tr>
   <tr height="40px">
     <td align="center" bgcolor="#D0F5FF"><strong>地理坐标</strong></td>
     <td align="left" bgcolor="#D0F5FF"><?= $oldfarm->longitude?></td>
     <td align="left" bgcolor="#D0F5FF"><?= $oldfarm->latitude?></td>
     <td align="left" bgcolor="#D0F5FF"></td>
     <td align="center" bgcolor="#FFFFD5"><strong>地理坐标</strong></td>
     <td align="left" bgcolor="#FFFFD5"><?= $newfarm->longitude?></td>
     <td align="left" bgcolor="#FFFFD5"><?= $newfarm->latitude?></td>
     <td align="left" bgcolor="#FFFFD5"></td>
   </tr>
   <tr height="40px">
     <td align="center" bgcolor="#D0F5FF"><strong>现合同号</strong></td>
     <td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?php
//        if($ttpoModel->newchangecontractnumber) {
       if($ttpoModel->actionname == 'farmssplitcontinue') {
           echo Ttpozongdi::getSameLastContractnumber($_GET['id']);
       } else {
           if($ttpoModel->actionname !== 'farmstransfer' and $ttpoModel->actionname !== 'farmstransfermergecontract')
            echo $ttpoModel->oldchangecontractnumber;
       }
        ?>

     </span></td>
     <td align="center" bgcolor="#D0F5FF"><strong>原合同号</strong></td>
     <td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract')
           echo '<s>'.$ttpoModel->oldcontractnumber.'</s>';
       else
           echo $ttpoModel->oldcontractnumber;

//       var_dump($ttpoModel->actionname);

       ?>

     </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现合同号</strong></td>
     <td align="left" bgcolor="#FFFFD5"><span class="italic">
       <?= $ttpoModel->newchangecontractnumber?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>原合同号</strong></td>
     <td align="left" bgcolor="#FFFFD5"><span class="italic">
       <?= $ttpoModel->newcontractnumber?>
     </span></td>
   </tr>
   <tr height="40px">
     <td align="center" bgcolor="#D0F5FF"><strong>原法人身份证号</strong></td>
     <td colspan="3" align="left" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->cardid?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现法人身份证号</strong></td>
     <td colspan="3" align="left" bgcolor="#FFFFD5"><span class="italic">
=======
   <tr>
     <td width="11%" align="right" bgcolor="#D0F5FF"><strong>原农场名称</strong></td>
     <td width="17%" align="center" bgcolor="#D0F5FF"><strong>原法人</strong></td>
     <td width="20%" align="center" bgcolor="#D0F5FF"><strong>原面积</strong></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现农场名称</strong></td>
     <td width="20%" align="center" bgcolor="#FFFFD5"><strong>现法人</strong></td>
     <td width="21%" align="center" bgcolor="#FFFFD5"><strong>现面积</strong></td>
   </tr>
   <tr>
     <td align="right" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->farmname?>
     </span></td>
     <td align="center" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->farmername?>
     </span></td>
     <td align="center" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->contractarea?>
       亩</span></td>
     <td align="center" bgcolor="#FFFFD5"><span class="italic">
       <?= $newfarm->farmname?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><span class="italic">
       <?= $newfarm->farmername?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><span class="italic">
       <?php if($newttpozongdi['newchangecontractnumber']) echo Farms::getContractnumberArea($newttpozongdi['newchangecontractnumber']);?>
       亩</span></td>
   </tr>
   <tr>
     <td align="right" bgcolor="#D0F5FF"><strong>农场位置</strong></td>
     <td colspan="2" align="left" bgcolor="#D0F5FF"><?= $oldfarm->address?></td>
     <td align="center" bgcolor="#FFFFD5"><strong>农场位置</strong></td>
     <td colspan="2" align="left" bgcolor="#FFFFD5"><?= $newfarm->address?></td>
   </tr>
   <tr height="40px">
     <td align="right" bgcolor="#D0F5FF"><strong>地理坐标</strong></td>
     <td align="left" bgcolor="#D0F5FF"><?= $oldfarm->longitude?></td>
     <td align="left" bgcolor="#D0F5FF"><?= $oldfarm->latitude?></td>
     <td align="center" bgcolor="#FFFFD5"><strong>地理坐标</strong></td>
     <td align="left" bgcolor="#FFFFD5"><?= $newfarm->longitude?></td>
     <td align="left" bgcolor="#FFFFD5"><?= $newfarm->latitude?></td>
   </tr>
   <tr height="40px">
     <td align="right" bgcolor="#D0F5FF"><strong>原合同号</strong></td>
     <td colspan="2" align="left" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->contractnumber?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现合同号</strong></td>
     <td colspan="2" align="left" bgcolor="#FFFFD5"><span class="italic">
       <?= $newttpozongdi->newchangecontractnumber?>
     </span></td>
   </tr>
   <tr height="40px">
     <td align="right" bgcolor="#D0F5FF"><strong>原法人身份证号</strong></td>
     <td colspan="2" align="left" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->cardid?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现法人身份证号</strong></td>
     <td colspan="2" align="left" bgcolor="#FFFFD5"><span class="italic">
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
       <?= $newfarm->cardid?>
     </span></td>
   </tr>
   <tr height="40px">
<<<<<<< HEAD
     <td align="center" bgcolor="#D0F5FF"><strong>现合同面积</strong></td>
     <td align="left" bgcolor="#D0F5FF"><?php if($ttpoModel->actionname !== 'farmstransfer' and $ttpoModel->actionname !== 'farmstransfermergecontract') { if (Ttpozongdi::getSameLastContractnumber($_GET['id'])) echo Farms::getContractnumberArea($ttpoModel->oldchangecontractnumber); else echo 0;}?></td>
     <td align="center" bgcolor="#D0F5FF"><strong>原合同面积</strong></td>
     <td align="left" bgcolor="#D0F5FF"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo '<s>'.Farms::getContractnumberArea($ttpoModel->oldcontractnumber).'</s>'; else echo Farms::getContractnumberArea($ttpoModel->oldcontractnumber);?></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现合同面积</strong></td>
     <td align="left" bgcolor="#FFFFD5"><?= Farms::getContractnumberArea($ttpoModel->newchangecontractnumber)?></td>
      <td align="center" bgcolor="#FFFFD5"><strong>原合同面积</strong></td>
     <td align="left" bgcolor="#FFFFD5"><?php echo Farms::getContractnumberArea($ttpoModel->newcontractnumber);?></td>
   </tr>
   <tr height="40px">
     <td align="center" bgcolor="#D0F5FF"><strong>现宗地面积</strong></td>
     <td align="left" bgcolor="#D0F5FF"><?php if (Ttpozongdi::getSameLastContractnumber($_GET['id'])) echo $ttpoModel->oldchangemeasure; else echo 0;?></td>
     <td align="center" bgcolor="#D0F5FF"><strong>原宗地面积</strong></td>
     <td align="left" bgcolor="#D0F5FF"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract')  echo '<s>'.$ttpoModel->oldmeasure.'</s>'; else echo $ttpoModel->oldmeasure;?></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现宗地面积</strong></td>
     <td align="left" bgcolor="#FFFFD5"><?= $ttpoModel->newchangemeasure?></td>
     <td align="center" bgcolor="#FFFFD5"><strong>原宗地面积</strong></td>
     <td align="left" bgcolor="#FFFFD5"><?= $ttpoModel->newmeasure?></td>
     
   </tr>
   <tr height="40px">
     <td align="center" bgcolor="#D0F5FF"><strong>原宗地信息</strong></td>
     <?php if(!empty($ttpoModel['oldzongdi'])) $zongdiArray = explode('、', $ttpoModel['oldzongdi']); else $zongdiArray = [];?>
     <td colspan="3" align="left" bgcolor="#D0F5FF">
         <table width="100%" border="0" align="center">
             <?php
             if($ttpoModel->oldzongdi) {
                 $zongdiArray = explode('、', $ttpoModel->oldzongdi);
                 $ttpozongdi = explode('、', $ttpoModel->ttpozongdi);
                 foreach ($zongdiArray as $key => $zongdi) {
                     foreach ($ttpozongdi as $tkey => $tzongdi) {
                         if(Lease::getZongdi($zongdi) == Lease::getZongdi($tzongdi)) {
                             $cha = Lease::getArea($zongdi) - Lease::getArea($tzongdi);

                             if(intval($cha) > 0)
                                 $zongdiArray[$key] = Lease::getZongdi($zongdi)."(".$cha.")";
                         }
                     }
                 }}
             for($i = 0;$i<count($zongdiArray);$i++) {
                 if($i%3 == 0) {
                     echo '<tr height="10">';
                     echo '<td align="left">';
                     if(Farms::zongdiBJ($ttpoModel->ttpozongdi,Lease::getZongdi($zongdiArray[$i]))) {
                         echo '<span class="italic text-red">'.$zongdiArray[$i].'<small class="fa fa-minus pull-center text-sm text-red"></small></span>';
                     } else
                         echo '<span class="italic">'.$zongdiArray[$i].'</span>';
                     echo '</td>';
                 } else {
                     echo '<td align="left">';
                     if(Farms::zongdiBJ($ttpoModel->ttpozongdi,Lease::getZongdi($zongdiArray[$i]))) {
                         echo '<span class="italic text-red">'.$zongdiArray[$i].'<small class="fa fa-minus pull-center text-sm text-red"></small></span>';
                     } else
                         echo '<span class="italic">'.$zongdiArray[$i].'</span>';
                     echo '</td>';
                 }

             }?>
         </table>
     </td>
     <td align="center" bgcolor="#FFFFD5"><strong>现宗地信息</strong></td>
     <td colspan="3" align="left" bgcolor="#FFFFD5">
         <table width="100%" border="0" align="center">
             <?php if(!empty($ttpoModel['newchangezongdi'])) $zongdiArray = explode('、', $ttpoModel->newchangezongdi); else $zongdiArray = [];?>
             <?php

             for($i = 0;$i<count($zongdiArray);$i++) {
                 if($i%3 == 0) {
                     echo '<tr height="10">';
                     echo '<td align="left">';
                     if(Farms::zongdiBJ($ttpoModel->ttpozongdi,$zongdiArray[$i])) {
                         echo '<span class="italic text-red">'.$zongdiArray[$i].'<small class="fa fa-plus pull-center text-sm text-red"></small></span>';
                     } else
                         echo '<span class="italic">'.$zongdiArray[$i].'</span>';
                     echo '</td>';
                 } else {
                     echo '<td align="left">';
                     if(Farms::zongdiBJ($ttpoModel->ttpozongdi,$zongdiArray[$i])) {
                         echo '<span class="italic text-red">'.$zongdiArray[$i].'</span><small class="fa fa-plus pull-center text-sm text-red"></small>';
                     } else
                         echo '<span class="italic">'.$zongdiArray[$i].'</span>';
                     echo '</td>';
                 }

             }?>
         </table></td>
   </tr>
   <tr height="40px">
     <td align="center" bgcolor="#D0F5FF"><strong>现未明确地块</strong></td>
     <td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?php if($ttpoModel->actionname !== 'farmstransfer' and $ttpoModel->actionname !== 'farmstransfermergecontract') echo $oldfarm->notclear;?>
     </span></td>
      <td align="center" bgcolor="#D0F5FF"><strong>原未明确地块</strong></td>
     <td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo '<s>'.$ttpoModel->oldnotclear.'</s>'; else echo $ttpoModel->oldchangenotclear;?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现未明确地块</strong></td>
     <td align="left" bgcolor="#FFFFD5"><span class="italic">
       <?= $ttpoModel->newchangenotclear;?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>原未明确地块</strong></td>
     <td align="left" bgcolor="#FFFFD5"><span class="italic">
       <?= $ttpoModel->newnotclear;?>
     </span></td>
   </tr>
   <tr height="40px">
     <td align="center" bgcolor="#D0F5FF"><strong>现未明确状态地块</strong></td>
     <td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->notstate;?>
     </span></td>
     <td align="center" bgcolor="#D0F5FF"><strong>原未明确状态地块</strong></td>
     <td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo '<s>'.$ttpoModel->oldnotstate.'</s>'; else echo $ttpoModel->oldchangenotstate;?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现未明确状态地块</strong></td>
     <td align="left" bgcolor="#FFFFD5"><span class="italic">
       <?= $ttpoModel->newchangenotstate;?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>原未明确状态地块</strong></td>
     <td align="left" bgcolor="#FFFFD5"><span class="italic">
=======
     <td align="right" bgcolor="#D0F5FF"><strong>原合同面积</strong></td>
     <td colspan="2" align="left" bgcolor="#D0F5FF"><?= $oldfarm->contractarea?></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现合同面积</strong></td>
     <td colspan="2" align="left" bgcolor="#FFFFD5">&nbsp;</td>
   </tr>
   <tr height="40px">
     <td align="right" bgcolor="#D0F5FF"><strong>原宗地面积</strong></td>
     <td colspan="2" align="left" bgcolor="#D0F5FF"><?= $oldfarm->measure?></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现宗地面积</strong></td>
     <td colspan="2" align="left" bgcolor="#FFFFD5">&nbsp;</td>
   </tr>
   <tr height="40px">
     <td align="right" bgcolor="#D0F5FF"><strong>原宗地信息</strong></td>
     <?php if(!empty($oldttpozongdi['oldzongdi'])) $zongdiArray = explode('、', $oldttpozongdi['oldzongdi']); else $zongdiArray = [];?>
     <td colspan="2" align="left" bgcolor="#D0F5FF"><table width="100%" border="0" align="right">
       <?php for($i = 0;$i<count($zongdiArray);$i++) {
			    	if($i%5 == 0) {
			    		echo '<tr height="10">';
			    		echo '<td align="left">';
			    		echo '<span class="italic">'.$zongdiArray[$i].'</span>';
			    		echo '</td>';
			    	} else {
			    		echo '<td align="left">';
			    		echo '<span class="italic">'.$zongdiArray[$i].'</span>';
			    		echo '</td>';
			    	}
			    	
			    }?>
     </table>
       </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现宗地信息</strong></td>
     <td colspan="2" align="left" bgcolor="#FFFFD5"><table width="100%" border="0" align="center">
       <?php if(!empty($newttpozongdi['newchangezongdi'])) $zongdiArray = explode('、', $newttpozongdi['newchangezongdi']); else $zongdiArray = [];?>
       <?php 
			    for($i = 0;$i<count($zongdiArray);$i++) {
			    	if($i%5 == 0) {
			    		echo '<tr height="10">';
			    		echo '<td align="left">';
			    		echo '<span class="italic">'.$zongdiArray[$i].'</span>';
			    		echo '</td>';
			    	} else {
			    		echo '<td align="left">';
			    		echo '<span class="italic">'.$zongdiArray[$i].'</span>';
			    		echo '</td>';
			    	}
			    	
			    }?>
     </table></td>
   </tr>
   <tr height="40px">
     <td align="right" bgcolor="#D0F5FF"><strong>原未明确地块</strong></td>
     <td colspan="2" align="left" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->notclear;?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现未明确地块</strong></td>
     <td colspan="2" align="left" bgcolor="#FFFFD5"><span class="italic">
       <?= $newfarm->notclear;?>
     </span></td>
   </tr>
   <tr height="40px">
     <td align="right" bgcolor="#D0F5FF"><strong>原未明确状态地块</strong></td>
     <td colspan="2" align="left" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->notstate;?>
     </span></td>
     <td align="center" bgcolor="#FFFFD5"><strong>现未明确状态地块</strong></td>
     <td colspan="2" align="left" bgcolor="#FFFFD5"><span class="italic">
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
       <?= $newfarm->notstate;?>
     </span></td>
   </tr>
   <tr height="40px">
<<<<<<< HEAD
     <td align="center" bgcolor="#D0F5FF"><font color="red">减少面积</font></td>
     
     <td colspan="3" align="left" bgcolor="#D0F5FF"><font color="red">
       <?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract')
           echo Farms::getContractnumberArea($ttpoModel['oldcontractnumber']);
       else
           echo $ttpoModel['ttpoarea'] + $ttpoModel['newchangenotclear'] - $ttpoModel['newnotclear'];?>
     </font></td>
     <td align='center' bgcolor="#FFFFD5"><font color="red">增加面积</font></td>
     <td colspan="3" align="left" bgcolor="#FFFFD5"><font color="red">
       <?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract')
           echo Farms::getContractnumberArea($ttpoModel['oldcontractnumber']);
       else
           echo $ttpoModel['ttpoarea'] + $ttpoModel['newchangenotclear'] - $ttpoModel['newnotclear'];?>
     </font></td>
   </tr>
   <tr height="40px">
     <td align="center" bgcolor="#D0F5FF"><font color="red">剩余面积</font></td>
     <td colspan="3" align="left" bgcolor="#D0F5FF"><font color="red">
       <?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract')
           echo 0;
       else {
           if($ttpoModel->actionname == 'farmssplitcontinue')
               echo Farms::getContractnumberArea(Ttpozongdi::getSameLastContractnumber($_GET['id']));
           else
            echo Farms::getContractnumberArea($ttpoModel['oldchangecontractnumber']);
       }

       ?>
     </font></td>
     <td align='center' bgcolor="#FFFFD5"><font color="red">原面积</font></td>
     <td colspan="3" align="left" bgcolor="#FFFFD5"><font color="red">
       <?php if($ttpoModel['newcontractnumber']) echo Farms::getContractnumberArea($ttpoModel['newcontractnumber']);?>
     </font></td>
   </tr>
   <?php
//   var_dump($model);
   foreach ($process as $value) {
=======
     <td width="11%" align='right' bgcolor="#D0F5FF"><font color="red">减少宗地</font></td>
     <td colspan="2" align="left" bgcolor="#D0F5FF"><table width="100%" border="0" align="center">
       <?php if(!empty($newttpozongdi['ttpozongdi'])) $zongdiArray = explode('、', $newttpozongdi['ttpozongdi']); else $zongdiArray = [];?>
       <?php 
			    for($i = 0;$i<count($zongdiArray);$i++) {
			    	if($i%5 == 0) {
			    		echo '<tr height="10">';
			    		echo '<td align="left">';
			    		echo '<font color="red"><span class="italic">'.$zongdiArray[$i].'</span></font>';
			    		echo '</td>';
			    	} else {
			    		echo '<td align="left">';
			    		echo '<font color="red"><span class="italic">'.$zongdiArray[$i].'</span></font>';
			    		echo '</td>';
			    	}
			    	
			    }?>
     </table></td>
     <td width="11%" align='center' bgcolor="#FFFFD5"><font color="red">增加宗地</font></td>
     <td colspan="2" align="left" bgcolor="#FFFFD5"><table width="100%" border="0" align="center">
       <?php if(!empty($newttpozongdi['ttpozongdi'])) $zongdiArray = explode('、', $newttpozongdi['ttpozongdi']); else $zongdiArray = [];?>
       <?php 
			    for($i = 0;$i<count($zongdiArray);$i++) {
			    	if($i%5 == 0) {
			    		echo '<tr height="10">';
			    		echo '<td align="left">';
			    		echo '<font color="red"><span class="italic">'.$zongdiArray[$i].'</span></font>';
			    		echo '</td>';
			    	} else {
			    		echo '<td align="left">';
			    		echo '<font color="red"><span class="italic">'.$zongdiArray[$i].'</span></font>';
			    		echo '</td>';
			    	}
			    	
			    }?>
     </table></td>
   </tr>
   <tr height="40px">
     <td align='right' bgcolor="#D0F5FF"><font color="red">减少面积</font></td>
     <?php if(!empty($newttpozongdi['newchangezongdi'])) $zongdiArray = explode('、', $newttpozongdi['newchangezongdi']); else $zongdiArray = [];?>
     <td colspan="2" align="left" bgcolor="#D0F5FF"><font color="red">
       <?= $newttpozongdi['ttpoarea']?>
     </font></td>
     <td align='center' bgcolor="#FFFFD5"><font color="red">增加面积</font></td>
     <td colspan="2" align="left" bgcolor="#FFFFD5"><font color="red">
       <?= $newttpozongdi['ttpoarea']?>
     </font></td>
   </tr>
   <tr height="40px">
     <td align='right' bgcolor="#D0F5FF"><font color="red">剩余面积</font></td>
     <td colspan="2" align="left" bgcolor="#D0F5FF"><font color="red">
       <?php if($newttpozongdi['oldchangecontractnumber']) echo Farms::getContractnumberArea($newttpozongdi['oldchangecontractnumber']);?>
     </font></td>
     <td align='center' bgcolor="#FFFFD5"><font color="red">原面积</font></td>
     <td colspan="2" align="left" bgcolor="#FFFFD5"><font color="red">
       <?php if($newttpozongdi['newcontractnumber']) echo Farms::getContractnumberArea($newttpozongdi['newcontractnumber']);?>
     </font></td>
   </tr>
   <?php foreach ($process as $value) { 
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f

			  ?>
   
   <tr>
     <td rowspan="2"><strong>
       <?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>
     </strong></td>
<<<<<<< HEAD
       <?php echo Reviewprocess::showReviewprocess($model, $value, $process); ?>
   </tr>
   <?php }?>

     <tr>
         <td align="center"><strong>备&nbsp;&nbsp;&nbsp;&nbsp;注</strong></td>
         <td colspan="5" align="center"><br />
             <br />
             <br />
             <br /></td>
     </tr>
 </table>
                    <?php }?>
<?php if($class == 'projectapplication') {?>
<table class="table table-bordered table-hover">
  <tr>
    <td align="center"><strong>项目名称：</strong></td>
    <td><span class="italic"><?= Infrastructuretype::find()->where(['id'=>$project['projecttype']])->one()['typename']?></span></td>
    <td align="center"><strong>申请人：</strong></td>
    <td><span class="italic"><?= $farm->farmername;?></span></td>
    <td align="center"><strong>农场名称：</strong></td>
=======
     <?php 
			  					  $classname = 'app\\models\\'.ucfirst($value);
			  				      	$lists = $classname::attributesList();
			  				      	$result = $classname::find()->where(['reviewprocess_id'=>$model->id])->one();
			  				      	if($lists) {
			  				      	if($result) {
			  				      		echo '<td colspan="5" align="left">';
										echo '<font color="#00CC66">';
			  				      		echo '<table>';
			  					      	foreach ($lists as $key=>$list) {
			  					      		if(!strstr($key,'isAgree')) {
				  					      		echo '<tr>';
				  					      		echo '<td>';
				  					      		
				  					      		if($result[$key]) {
				  					      			echo '<strong>是<i class="fa fa-check-square-o"></i></strong>&nbsp;&nbsp;';
				  					      			echo '<strong>否<i class="fa fa-square-o"></i></strong>';
				  					      		} else { 
				  					      			echo '<strong>是<i class="fa fa-square-o"></i></string>&nbsp;&nbsp;';
				  					      			echo '<strong>否<i class="fa fa-check-square-o"></i></strong>';
				  					      		}
				  					      		echo '</td>';
				  					      		echo '<td>';
				  					      		echo '&nbsp;&nbsp;'.$list;
				  					      		echo '</td>';
				  					      		echo '<td>';
// 				  					      		if(!strstr($key,'isAgree')) {
				  					      		if($result[$key.'content'])
				  					      			echo '&nbsp;&nbsp;<font color="red"><strong>情况说明：'.$result[$key.'content'].'</strong></font>';
// 				  					      		}
				  					      		echo "</td>";
				  					      		echo '</tr>';
				  					      	}}
				  					      	echo '</table>';
											echo '</font>';
				  					      	echo '</td>';
			  				      	}
			  				      	}
			  				      ?>
   </tr>
   <tr>
     <td colspan="5" align="left" ><?php
			  		              if($model->$value == 1) {
			  		              	echo '<font color="#0033FF"><strong>';
			  				    	echo Reviewprocess::state($model->$value);
			  				    	echo '</strong></font>';
			  		              } else 
			  		              	echo Reviewprocess::state($model->$value);
			  				    	echo "<br>";
			  				    	if(!$model->$value) {
			  				    		echo '<font color="#FF0000"><strong>';
			  				    		$modelStr = $value.'content';
			  				    		echo '</strong></font>';
			  				    		echo "原由：".$model->$modelStr;
			  				    	}
			  				    ?></td>
   </tr>
   <?php }?>
   <?php }?>
   <tr>
     <td align="center"><strong>备&nbsp;&nbsp;&nbsp;&nbsp;注</strong></td>
     <td colspan="5" align="center"><br />
       <br />
       <br />
       <br /></td>
   </tr>
 </table>

<?php if($class == 'projectapplication') {?>
<table class="table table-bordered table-hover">
  <tr>
    <td align="right"><strong>项目名称：</strong></td>
    <td><span class="italic"><?= Infrastructuretype::find()->where(['id'=>$project['projecttype']])->one()['typename']?></span></td>
    <td align="right"><strong>申请人：</strong></td>
    <td><span class="italic"><?= $farm->farmername;?></span></td>
    <td align="right"><strong>农场名称：</strong></td>
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    <td><span class="italic"><?= $farm->farmname ?></span></td>
  </tr>
  <?php 
   foreach ($process as $value) { 
			    //获取当前流程的角色信息
			  	$role = Reviewprocess::getProcessRole($value);
			  	//审核角色或备分审核角色与当前用户角色相同，则显示该条目
			  	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
			  	if($temp) 
			  		$itemname = User::getUserItemname($temp['user_id']);
			  	else 
<<<<<<< HEAD
			  		$itemname = Yii::$app->getUser()->getIdentity()->level;
			  	if($role['level_id'] == $itemname and $role['department_id'] == Yii::$app->getUser()->getIdentity()->department_id){
			  ?>
  <tr>
    <td align="center"><strong>申请内容：</strong></td>
    <td colspan="5"><span class="italic"><?= $project['content'].$project['projectdata'].$project['unit']?></td>
    </tr>
  <tr>
    <td align="center"><span class="italic"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>：</td>
=======
			  		$itemname = User::getItemname();
			  	if($role['rolename'] == $itemname or $role['sparerole'] == User::getItemname()) {
			  ?>
  <tr>
    <td align="right"><strong>申请内容：</strong></td>
    <td colspan="5"><span class="italic"><?= $project['content'].$project['projectdata'].$project['unit']?></td>
    </tr>
  <tr>
    <td align="right"><span class="italic"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>：</td>
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
			    <td colspan="5" align="left" >
			    <?php 
			    if($model->$value == 2 or $model->$value == 0) {
			    	if($model->$value == 3)
			    		$model->$value = 1; 
			    	echo $form->field($model,$value)->radioList([1=>'同意',0=>'不同意'],['onclick'=>'CheckState("'.$value.'")'])->label(false)->error(false); 
			    	echo $form->field($model,$value.'content')->textInput()->label(false)->error(false);
			    	echo $form->field($model,$value.'time')->hiddenInput(['value'=>time()])->label(false)->error(false);
			    } else {
			    	echo Reviewprocess::state($model->$value);
			    	echo "<br>";
			    	if(!$model->$value) {
			    		$modelStr = $value.'content';
			    		echo "原由：".$model->$modelStr;
			    	}
			    }?></td>
    </tr>
    <?php }}?>
</table>
<span class="italic"></span>
<?php }?>
<<<<<<< HEAD
<?php if($class == 'loancreate') {?>

<table class="table table-bordered table-hover">
  <tr>
    <td align="center"><strong>农场名称：</strong></td>
    <td><span class="italic"><?= $farm->farmname ?></span></td>
    <td align="center"><strong>抵押人</strong></td>
    <td><span class="italic"><?= $farm->farmername;?></span></td>
    <td align="center"><strong>抵押银行</strong></td>
    <td><span class="italic"><?= $loan['mortgagebank']?></span></td>
  </tr>
    <tr>
    <td align="center"><strong>抵押面积</strong></td>
    <td><span class="italic"><?= $loan['mortgagearea']?></span></td>
    <td align="center"><strong>抵押金额</strong></td>
    <td><span class="italic"><?= $loan['mortgagemoney']?></span></td>
    <td align="center"><strong>抵押期限</strong></td>
=======
<?php if($class == 'loan') {?>

<table class="table table-bordered table-hover">
  <tr>
    <td align="right"><strong>农场名称：</strong></td>
    <td><span class="italic"><?= $farm->farmname ?></span></td>
    <td align="right"><strong>抵押人</strong></td>
    <td><span class="italic"><?= $farm->farmername;?></span></td>
    <td align="right"><strong>抵押银行</strong></td>
    <td><span class="italic"><?= $loan['mortgagebank']?></span></td>
  </tr>
    <tr>
    <td align="right"><strong>抵押面积</strong></td>
    <td><span class="italic"><?= $loan['mortgagearea']?></span></td>
    <td align="right"><strong>抵押金额</strong></td>
    <td><span class="italic"><?= $loan['mortgagemoney']?></span></td>
    <td align="right"><strong>抵押期限</strong></td>
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    <td><span class="italic"><?= '自'.$loan['begindate'].'至'.$loan['enddate'].'止'?></span></td>
    </tr>
  <?php 

   foreach ($process as $value) { 
			    //获取当前流程的角色信息
<<<<<<< HEAD

=======
			  	$role = Reviewprocess::getProcessRole($value);
			  	//审核角色或备分审核角色与当前用户角色相同，则显示该条目
			  	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
			  	if($temp) 
			  		$itemname = User::getUserItemname($temp['user_id']);
			  	else 
			  		$itemname = User::getItemname();
			  	if($role['rolename'] == $itemname) {
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
			  		
			  ?>

  <tr>
<<<<<<< HEAD
    <td align="center" rowspan="2"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>：</td>
                <?php echo Reviewprocess::showReviewprocess($model, $value, $process); ?>

    </tr>
    <?php }?>
=======
    <td align="right"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>：</td>
			    <td colspan="5" align="left" >
			    <?php 
			    if($model->$value == 2 or $model->$value == 0) {
			    	
			    		$model->$value = 1; 
			    	echo $form->field($model,$value)->radioList([1=>'同意',0=>'不同意'],['onclick'=>'CheckState("'.$value.'")'])->label(false)->error(false); 
			    	echo $form->field($model,$value.'content')->textarea(['rows'=>10])->label(false)->error(false);
			    	echo $form->field($model,$value.'time')->hiddenInput(['value'=>time()])->label(false)->error(false);
			    } else {
			    	echo Reviewprocess::state($model->$value);
			    	echo "<br>";
			    	if(!$model->$value) {
			    		$modelStr = $value.'content';
			    		echo "原由：".$model->$modelStr;
			    	}
			    }?></td>
    </tr>
    <?php }}?>
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
</table>
<?php }?>

        <!-- /.col -->

    

    <?php ActiveFormrdiv::end(); ?>
<<<<<<< HEAD

    <?php if(User::getItemname('地产科') and !empty($ttpoModel)) echo Html::a('打印', ['reviewprocess/reviewprocessfarmssplit','reviewprocessid'=>$ttpoModel['reviewprocess_id']], ['class' => 'btn btn-success']);?>
    <?= Html::a('返回', yii::$app->request->getReferrer(), ['class' => 'btn btn-success'])?>
=======
    <?php echo Html::a('打印', ['reviewprocess/reviewprocessfarmssplit','oldfarmsid'=>$oldttpozongdi->oldfarms_id,'newfarmsid'=>$newttpozongdi->newfarms_id,'reviewprocessid'=>$oldttpozongdi['reviewprocess_id']], ['class' => 'btn btn-success']);?>
    <?= Html::a('返回', ['reviewprocess/reviewprocessindex'], ['class' => 'btn btn-success'])?>
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
              </div>
            </div>
        </div>
    </div>
</section>
</div>

<script language="javascript" type="text/javascript">
function showContent(key)
{
// 	alert($('input:radio[name="'+key+'"]:checked').val());
	if($('input:radio[name="'+key+'"]:checked').val() == 1) {
		$('#'+key+'content').css('display','none');
	} else
		$('#'+key+'content').css('display','inline');
	
}
			    
if($('input:radio[name="Reviewprocess[estate]"]:checked').val() == 0){
	$('#reviewprocess-estatecontent').css('display', 'inline');
} else {
	$('#reviewprocess-estatecontent').css('display', 'none');
}
if($('input:radio[name="Reviewprocess[finance]"]:checked').val() == 0){
	$('#reviewprocess-financecontent').css('display', 'inline');
} else {
	$('#reviewprocess-financecontent').css('display', 'none');
}
if($('input:radio[name="Reviewprocess[filereview]"]:checked').val() == 0){
	$('#reviewprocess-filereviewcontent').css('display', 'inline');
} else {
	$('#reviewprocess-filereviewcontent').css('display', 'none');
}
if($('input:radio[name="Reviewprocess[regulations]"]:checked').val() == 0){
	$('#reviewprocess-regulationscontent').css('display', 'inline');
} else {
	$('#reviewprocess-regulationscontent').css('display', 'none');
}
if($('input:radio[name="Reviewprocess[publicsecurity]"]:checked').val() == 0){
	$('#reviewprocess-publicsecuritycontent').css('display', 'inline');
} else {
	$('#reviewprocess-publicsecuritycontent').css('display', 'none');
}
if($('input:radio[name="Reviewprocess[leader]"]:checked').val() == 0){
	$('#reviewprocess-leadercontent').css('display', 'inline');
} else {
	$('#reviewprocess-leadercontent').css('display', 'none');
}
if($('input:radio[name="Reviewprocess[mortgage]"]:checked').val() == 0){
	$('#reviewprocess-mortgagecontent').css('display', 'inline');
} else {
	$('#reviewprocess-mortgagecontent').css('display', 'none');
}
if($('input:radio[name="Reviewprocess[steeringgroup]"]:checked').val() == 0){
	$('#reviewprocess-steeringgroupcontent').css('display', 'inline');
} else {
	$('#reviewprocess-steeringgroupcontent').css('display', 'none');
}
function CheckState(process) {
$('#reviewprocess-'+process).click(function(){
 	var val = $('input:radio[name="Reviewprocess['+process+']"]:checked').val();
	if(val == 0) {
		$('#reviewprocess-'+process+'content').css('display', 'inline');
	} else {
		$('#reviewprocess-'+process+'content').css('display', 'none');
	}
});
}
</script>