<?php
namespace frontend\controllers;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Farms;
use app\models\Estate;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>

<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
       <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<div class="reviewprocess-form">


    <?php $form = ActiveFormrdiv::begin(); ?>
    
    
    <section class="content">
    <div class="row">
        <div class="col-xs-12">
        <div class="form-group">
    	   	&nbsp;&nbsp;<?= Html::Button('打印', ['class' => 'btn btn-primary','onclick'=>'prn_preview4()']) ?> 			
    	</div>
<!--             <div class="box"> -->

<!--                 <div class="box-body"> -->
    <div class="col-md-6" id="ttpoprint">
<style type="text/css">
.ttpoprint {
	font-family: "仿宋";
	font-size:15px;
}
.tablehead{ width:100%; height:30px; line-height:20px; text-align:center; float:left; font-size:30px; font-family:"黑体"}
</style>    
<br>
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="tablehead">宜农林地承包经营权转让审批表</span>
              </div>
              <!-- /.user-block -->
             
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <table width="100%" border="1" cellpadding="0" cellspacing="0" class="ttpoprint">
			  <tr height="30px">
			    <td width="16%" align="center">原农场名称</td>
			    <td width="16%" align="center">原法人</td>
			    <td width="16%" align="center">原面积</td>
			    <td width="16%" align="center">现农场名称</td>
			    <td width="17%" align="center">现法人</td>
			    <td width="19%" align="center">现面积</td>
			  </tr>
			  <tr height="30px">
			    <td align="center"><?= $oldfarm->farmname?></td>
			    <td align="center"><?= $oldfarm->farmername?></td>
			    <td align="center"><?= $oldfarm->contractarea?></td>
			    <td align="center"><?= $newfarm->farmname?></td>
			    <td align="center"><?= $newfarm->farmername?></td>
			    <td align="center"><?= Farms::getContractnumberArea($newttpozongdi->newchangecontractnumber)?></td>
			  </tr>
			  <tr height="30px">
			    <td align="center">原合同号</td>
			    <td colspan="2" align="center"><?= $oldfarm->contractnumber?></td>
			    <td align="center">现合同号</td>
			    <td colspan="2" align="center"><?= $newttpozongdi->newchangecontractnumber?></td>
			    </tr>
			  <tr height="30px">
			    <td align="center">原法人身份证号</td>
			    <td colspan="2" align="center"><?= $oldfarm->cardid?></td>
			    <td align="center">现法人身份证号</td>
			    <td colspan="2" align="center"><?= $newfarm->cardid?></td>
			    </tr>
			  <tr height="30px">
			    <td align="center">原宗地信息</td><?php if(!empty($oldttpozongdi->oldzongdi)) $zongdiArray = explode('、', $oldttpozongdi->oldzongdi); if($oldfarm->notclear) $zongdiArray[] = '未明确地块面积('.$oldfarm->notclear.')';if($oldfarm->notstate) $zongdiArray[] = '未明确状态面积('.$oldfarm->notstate.')';?>
			    
			    <td colspan="2" align="center"><table width="100%" border="0" align="right"><?php for($i = 0;$i<count($zongdiArray);$i++) {
// 			    	echo $i%6;
			    	if($i%2 == 0) {
			    		echo '<tr height="2">';
			    		echo '<td>';
			    		echo '<font size="2">'.$zongdiArray[$i].'</font>';
			    		echo '</td>';
			    	} else {
			    		echo '<td>';
			    		echo '<font size="2">'.$zongdiArray[$i].'</font>';
			    		echo '</td>';
			    	} 	
			    }?></table></td>
			    <td align="center">现宗地信息</td>
                <?php if(!empty($newttpozongdi->newchangezongdi)) $zongdiArray = explode('、', $newttpozongdi->newchangezongdi); if($newttpozongdi->newchangenotclear) $zongdiArray[] = '未明确地块面积('.$newttpozongdi->newchangenotclear.')';if($newttpozongdi->newchangenotstate) $zongdiArray[] = '未明确状态面积('.$newttpozongdi->newchangenotstate.')';?>
			    <td colspan="2" align="center"><table width="100%" border="0" align="center"><?php for($i = 0;$i<count($zongdiArray);$i++) {
			    	if($i%2 == 0) {
			    		echo '<tr height="2">';
			    		echo '<td>';
			    		echo '<font size="2">'.$zongdiArray[$i].'</font>';
			    		echo '</td>';
			    	} else {
			    		echo '<td>';
			    		echo '<font size="2">'.$zongdiArray[$i].'</font>';
			    		echo '</td>';
			    	}
			    	
			    }?></table></td>
			    </tr>
			  <tr>
			    <td height="30px" align="center">减少宗地</td>
			    <td colspan="2" align="center">
                <table width="100%" border="0" align="right">
			      <?php 
				$zongdiArray = explode('、', $newttpozongdi->ttpozongdi);
				for($i = 0;$i<count($zongdiArray);$i++) {
// 			    	echo $i%6;
			    	if($i%2 == 0) {
			    		echo '<tr height="2">';
			    		echo '<td>';
			    		echo '<font size="2">'.$zongdiArray[$i].'</font>';
			    		echo '</td>';
			    	} else {
			    		echo '<td>';
			    		echo '<font size="2">'.$zongdiArray[$i].'</font>';
			    		echo '</td>';
			    	} 	
			    }?></table>
			      </td>
			    <td align="center">新增宗地</td>
			    <td colspan="2" align="center">
			      <table width="100%" border="0" align="right">
			        <?php 
				$zongdiArray = explode('、', $newttpozongdi->ttpozongdi);
				for($i = 0;$i<count($zongdiArray);$i++) {
// 			    	echo $i%6;
			    	if($i%2 == 0) {
			    		echo '<tr height="2">';
			    		echo '<td>';
			    		echo '<font size="2">'.$zongdiArray[$i].'</font>';
			    		echo '</td>';
			    	} else {
			    		echo '<td>';
			    		echo '<font size="2">'.$zongdiArray[$i].'</font>';
			    		echo '</td>';
			    	} 	
			    }?></table>
			      </td>
			    </tr>
			  <tr>
			  	<td height="30" align="center">减少面积</td>
			  	<td colspan="2" align="center"><?= $newttpozongdi->ttpoarea?></td>
			  	<td align="center">新增面积</td>
			  	<td colspan="2" align="center"><?= $newttpozongdi->ttpoarea?></td>
			  	</tr>
			  <tr>
			  	<td height="30" align="center">剩余面积</td>
			  	<td colspan="2" align="center"><?= Farms::getContractnumberArea($newttpozongdi->oldchangecontractnumber)?></td>
			  	<td align="center">原面积</td>
			  	<td colspan="2" align="center"><?php if($newttpozongdi->newcontractnumber) echo Farms::getContractnumberArea($newttpozongdi->newcontractnumber)?></td>
			  	</tr>
			  <?php 
			  
			  foreach ($process as $value) { ?>
			  	<tr>
			  	<td align="center"><strong><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?></strong></td>	
			  	<td colspan="5" align="left">		  				      
			  				      <?php 
			  				      if($value == 'leader')
			  				      	echo "<br><br>";
			  					  $classname = 'app\\models\\'.ucfirst($value);
			  				      	$lists = $classname::attributesList();
			  				      	$result = Estate::find()->where(['reviewprocess_id'=>$_GET['reviewprocessid']])->one();
// 			  				      	$lists[] = 'leader';
// 			  				      	var_dump($result);
// 			  				      	if($lists) {
// 			  				      	if($result) {
			  				      		
			  				      		echo '<table>';
			  					      	foreach ($lists as $key=>$list) {
			  					      		echo '<tr>';
			  					      		echo '<td>';
			  					      		if($value == 'estate') {
				  					      		if($result[$key]) {
				  					      			echo '<font size="2"><strong>是</strong>&nbsp;</font>';
				  					      		} else { 
				  					      			echo '<font size="2"><strong>否</strong></font>';
				  					      		}
			  					      		} else {
				  					      		echo '<font size="2"><strong>是<i class="fa fa-square-o"></i></strong>&nbsp;</font>';
				  					      		echo '<font size="2"><strong>否<i class="fa fa-square-o"></i></strong></font>';
			  					      		}
			  					      		
			  					      		echo '</td>';
			  					      		echo '<td>';
			  					      		
			  					      		echo '<font size="2">&nbsp;'.$list."</font>";
			  					      		echo '</td>';
			  					      		echo '<td>';
			  					      		if($value == 'estate') {
			  					      			if($result[$key."content"])
			  					      				echo '&nbsp;<font size="2"><font ><strong>情况说明：'.$result[$key."content"].'</strong></font>';
			  					      		} 
			  					      		echo "</td>";
			  					      		echo '</tr>';
			  					      	}
			  					      	echo '</table>';
			  					      	
// 			  				      	}
// 			  				      	}
			  				      ?>
			  				      <br>
			  				      <font size="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;签字：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;日 </font></p>
			  				      <font size="1"><p></p></font>
			  				      </td>
			  			       </tr>
			  <?php }?>
			  
			</table> 
			<br>
			
            </div>
            <!-- /.box-body -->
           
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

    

    <?php ActiveFormrdiv::end(); ?>
<!--                 </div> -->
<!--             </div> -->
        </div>
    </div>
</section>
</div>

<script language="javascript" type="text/javascript">
    var LODOP; //声明为全局变量 
	
	function prn_preview4() {	
		CreateOnePage();	
		LODOP.SET_PRINT_MODE("PRINT_PAGE_PERCENT","Full-Page");	
		LODOP.PREVIEW();	
	};		
	
	function CreateOnePage(){
		LODOP=getLodop();  
		LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_整页缩放打印输出");
		strCenterStyle="<style>#ttpoprint {text-align: center}</style>"; 
		LODOP.ADD_PRINT_HTM("5%","5%","100%","95%","<body leftmargin=20 topmargin=0>"+document.getElementById('ttpoprint').innerHTML+"</body>");
		LODOP.SET_PRINT_STYLEA(0,"Horient",3);        
		LODOP.SET_PRINT_STYLEA(0,"Vorient",2);
		LODOP.SET_PREVIEW_WINDOW(0,0,0,0,0,"");	
	};	
</script>