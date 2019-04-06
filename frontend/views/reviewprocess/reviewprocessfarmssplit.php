<?php
namespace frontend\controllers;
use app\models\Ttpo;
use app\models\Ttpozongdi;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Farms;
use app\models\Estate;
use app\models\User;
use app\models\Lease;
use app\models\ManagementArea;
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
	border: 1px;
}
.ttpotitle{
	font-family: "黑体";
	font-size:15px;
}
.zongdi5{
	font-size:5px;
}
.zongdi6{
	font-size:6px;
}
.zongdi7{
	font-size:7px;
}
.zongdi8{
	font-size:8px;
}
.zongdi9{
	font-size:9px;
}
.zongdi10{
	font-size:10px;
}
.zongdi11{
	font-size:11px;
}
.zongdi12{
	font-size:12px;
}
.zongdi13{
	font-size:13px;
}
.zongdi14
	font-size:14px;
}
.zongdi15{
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
            <span class="ttpotitle">管理区：<?= ManagementArea::getManagementareaName($oldfarm->id)?><?php 
				if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') 
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;整体转让';
				if($ttpoModel->actionname == 'farmssplit' or $ttpoModel->actionname == 'farmstozongdi') 
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;部分转让';
				if($ttpoModel->actionname == 'farmssplitcontinue')
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;部分转让('.Ttpozongdi::getSameCount($_GET['reviewprocessid']).'-'.Ttpozongdi::getSameCountRow($_GET['reviewprocessid']).')';
				?></span>
             <table width="100%" border="1" cellpadding="0" cellspacing="0" class="ttpoprint table table-bordered">
				 <tr>
					 <td colspan="3" class="ttpotitle" align="center" bgcolor=""><strong>出&nbsp;&nbsp让&nbsp;&nbsp方</strong></td>
					 <td colspan="3" class="ttpotitle" align="center"><strong>受&nbsp;&nbsp让&nbsp;&nbsp方</strong></td>
				 </tr>
			  <tr height="30px">
			    <td width="18%" align="center" bgcolor="" class="ttpotitle">农场名称</td>
			    <td width="16%" align="center" bgcolor="" class="ttpotitle">法人</td>
			    <td width="16%" align="center" bgcolor="" class="ttpotitle">面积</td>
			    <td width="18%" align="center" class="ttpotitle">农场名称</td>
			    <td width="17%" align="center" class="ttpotitle">法人</td>
			    <td width="19%" align="center" class="ttpotitle">面积</td>
			  </tr>
			  <tr height="30px">
			    <td align="center" bgcolor=""><?= $oldfarm->farmname?></td>
			    <td align="center" bgcolor=""><?= $oldfarm->farmername?></td>
			    <td align="center" bgcolor=""><?php if($ttpoModel->samefarms_id) echo Farms::find()->where(['id'=>$ttpoModel->samefarms_id])->one()['contractarea']; else echo $oldfarm->contractarea;?></td>
			    <td align="center"><?= $newfarm->farmname?></td>
			    <td align="center"><?= $newfarm->farmername?></td>
			    <td align="center"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmssplit' or $ttpoModel->actionname == 'farmssplitcontinue') echo Farms::getContractnumberArea($ttpoModel->newchangecontractnumber); else echo Farms::getContractnumberArea($ttpoModel->newcontractnumber);?></td>
			  </tr>
			  <tr height="30px">
			    <td align="center" bgcolor="" class="ttpotitle">原合同号</td>
			    <td colspan="2" align="center" bgcolor=""><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract')
                        echo '<s>'.$ttpoModel->oldcontractnumber.'</s>';
                    else
                        echo $ttpoModel->oldcontractnumber;?></td>
			    <td align="center" class="ttpotitle"><?php if($ttpoModel->actionname == 'farmstransfer') echo '现合同号'; else echo '原合同号';?></td>
			    <td colspan="2" align="center"><?php if($ttpoModel->actionname == 'farmstransfer') echo $ttpoModel->newchangecontractnumber; else echo $ttpoModel->newcontractnumber;?></td>
			    </tr>
			  <tr height="30px">
			    <td align="center" bgcolor="" class="ttpotitle">法人身份证号</td>
			    <td colspan="2" align="center" bgcolor=""><?= $oldfarm->cardid?></td>
			    <td align="center" class="ttpotitle">法人身份证号</td>
			    <td colspan="2" align="center"><?= $newfarm->cardid?></td>
			    </tr>
			   
			  <tr height="30px">
			    <td align="center" bgcolor="" class="ttpotitle">宗地信息</td>
			    <?php if(!empty($ttpoModel->oldzongdi)) $zongdiArray = explode('、', $ttpoModel->oldzongdi); else $zongdiArray = ''; if($oldfarm->notclear) $oldnotclear = '未明确地块面积('.$oldfarm->notclear.')'; else $oldnotclear = '';if($oldfarm->notstate) $oldnotstate = '未明确状态面积('.$oldfarm->notstate.')'; else $oldnotstate = '';?>
			    <?php 
			    $tableclass = 'zongdi13';
			    if($ttpoModel->oldzongdi) {
			    	
			    	$ttpooldzongdiRows = count(explode('、', $ttpoModel->oldzongdi));
// 			    	var_dump(round(($ttpooldzongdiRows-15)/3));
			  		if($ttpooldzongdiRows > 16) {
// 			  			if(round(($ttpooldzongdiRows-15)/3) > 15) {
			  				$size = round(($ttpooldzongdiRows-15)/2/2);
// 			  			} else {
// 			  				$size = 15 - round(($ttpooldzongdiRows-15)/3);
// 			  			}
			  			
// 			  			var_dump($size);
			  			$tableclass = 'zongdi'.$size;
			  		}
// 			  		var_dump($tableclass);
			    }
			    ?>
			    <td colspan="2" align="center" bgcolor="">
			    <table width="100%" border="0" class="<?= $tableclass?>">
			    <?php
				if($ttpoModel->oldzongdi) {
					$zongdiArray = explode('、', $ttpoModel->oldzongdi);
					$ttpozongdi = explode('、', $ttpoModel->ttpozongdi);
// 					if(count($zongdiArray) > 16)
// 						$size = count($zongdiArray) - 
					
					foreach ($zongdiArray as $key => $zongdi) {
						foreach ($ttpozongdi as $tkey => $tzongdi) {
							if(Lease::getZongdi($zongdi) == Lease::getZongdi($tzongdi)) {
								$cha = Lease::getArea($zongdi) - Lease::getArea($tzongdi);

								if(intval($cha) > 0)
									$zongdiArray[$key] = Lease::getZongdi($zongdi)."(".$cha.")";
							}
						}
					}}
			    					if($zongdiArray) {
			    					for($i = 0;$i<count($zongdiArray);$i++) {
										if($i%2 == 0) {
											echo '<tr height="10">';
											echo '<td align="left">';
											if(strstr($ttpoModel->ttpozongdi,Lease::getZongdi($zongdiArray[$i]))) {
												echo '<span class="italic text-red">'.$zongdiArray[$i].'-</span>';
											} else
												echo '<span class="italic">'.$zongdiArray[$i].'</span>';
											echo '</td>';
										} else {
											echo '<td align="left">';
											if(strstr($ttpoModel->ttpozongdi,Lease::getZongdi($zongdiArray[$i]))) {
												echo '<span class="italic text-red">'.$zongdiArray[$i].'-</span>';
											} else
												echo '<span class="italic">'.$zongdiArray[$i].'</span>';
											echo '</td>';
										}										
									}}
									if($oldnotclear) {
										echo '<tr height="10">';
										echo '<td align="left" colspan="2">';
										if($ttpoModel->oldnotclear !== $ttpoModel->oldchangenotclear) {
											echo '<span class="italic text-red">'.$oldnotclear.'-</span>';
										} else
											echo '<span class="italic">'.$oldnotclear.'</span>';
//										echo $oldnotclear;
										echo '</td>';
										echo '</tr>';
									}
									if($oldnotstate) {
										echo '<tr height="10">';
										echo '<td align="left" colspan="2">';
										if($ttpoModel->actionname == 'farmstransfermergecontract') {
											echo '<span class="italic text-red">'.$oldnotstate.'-</span>';
										} else {
											if ($ttpoModel->oldnotstate !== $ttpoModel->oldchangenotstate) {
												echo '<span class="italic text-red">' . $oldnotstate . '-</span>';
											} else
												echo '<span class="italic">' . $oldnotstate . '</span>';
										}
//										echo $oldnotstate;
										echo '</td>';
										echo '</tr>';
									}
									?></table></td><?php $zongdiArray = [];?>
			    <td align="center" class="ttpotitle">宗地信息</td>
                <?php if(!empty($ttpoModel->newchangezongdi)) {
                		$zongdiArray = explode('、', $ttpoModel->newchangezongdi);
	                	if(count($zongdiArray) > 16) {
				  			$size = 15 - round((count($zongdiArray)-15)/3);
// 				  			var_dump($size);
				  			$tableclass = 'zongdi'.$size;
				  		} else {
				  			$tableclass = 'zongdi13';
				  		}
                	}
                	else 
                		$zongdiArray = ''; 
                	if($ttpoModel->newchangenotclear) 
                		$newchangenotclear = '未明确地块面积('.$ttpoModel->newchangenotclear.')'; 
                	else $newchangenotclear = '';
                	if($ttpoModel->newchangenotstate) 
                		$newchangenotstate = '未明确状态面积('.$ttpoModel->newchangenotstate.')';
                	else $newchangenotstate = '';?>
			    <td colspan="2" align="center">
			    <table width="100%" border="0" class="<?= $tableclass?>">
			    <?php 
// 			    var_dump($ttpoModel->ttpozongdi);
//				var_dump($zongdiArray);
								if($zongdiArray) {
									for($i = 0;$i<count($zongdiArray);$i++) {
										if($i%2 == 0) {
											echo '<tr height="10">';
											echo '<td align="left">';
//											var_dump(strstr($ttpoModel->ttpozongdi,$zongdiArray[$i]));
//											var_dump()
											if(Farms::zongdiBJ($ttpoModel->ttpozongdi,$zongdiArray[$i])) {
//												echo 'zzz';
												echo '<span class="italic text-red">'.$zongdiArray[$i].'+</span>';
											} else {
//												echo 'jjj';
												echo '<span class="italic">' . $zongdiArray[$i] . '</span>';
											}
											echo '</td>';
										} else {
											echo '<td align="left">';
											if(Farms::zongdiBJ($ttpoModel->ttpozongdi,$zongdiArray[$i])) {
												echo '<span class="italic text-red">'.$zongdiArray[$i].'+</span>';
											} else
												echo '<span class="italic">'.$zongdiArray[$i].'</span>';
											echo '</td>';
										}
										
									}
								}
									if($newchangenotclear) {
										echo '<tr height="10">';
										echo '<td align="left" colspan="2">';
										if($ttpoModel->newnotclear !== $ttpoModel->newchangenotclear) {
											echo '<span class="italic text-red">'.$newchangenotclear.'+</span>';
										} else
											echo '<span class="italic">'.$newchangenotclear.'</span>';
//										echo $newchangenotclear;
										echo '</td>';
										echo '</tr>';
									}
									if($newchangenotstate) {
										echo '<tr height="10">';
										echo '<td align="left" colspan="2">';
										if($ttpoModel->newnotstate !== $ttpoModel->newchangenotstate) {
											echo '<span class="italic text-red">'.$newchangenotstate.'+</span>';
										} else
											echo '<span class="italic">'.$newchangenotstate.'</span>';
//										echo $newchangenotstate;
										echo '</td>';
										echo '</tr>';
									}
									?></table></td>
			    </tr>
			     <?php if($ttpoModel->actionname !== 'farmstransfer') {?>
			  
			  <tr>
			  	<td height="30" align="center" bgcolor="" class="ttpotitle">减少面积</td>
			  	<td colspan="2" align="center" bgcolor=""><?php
					if($ttpoModel->actionname == 'farmssplitcontinue')
						echo bcsub(Farms::getContractnumberArea(Ttpozongdi::getSameFirstContractnumber($_GET['reviewprocessid'])),Farms::getContractnumberArea(Ttpozongdi::getSameLastContractnumber($_GET['reviewprocessid'])),2);
					else {
					if($ttpoModel->actionname == 'farmstransfermergecontract') {
						echo $ttpoModel->ttpoarea;
					} else {
						if($ttpoModel->newchangenotclear == $ttpoModel->newnotclear) {
//							var_dump('1');
							echo $ttpoModel->ttpoarea;
						} else {
//							var_dump($ttpoModel->newchangenotclear);
							echo $ttpoModel->ttpoarea;
						}
					}}?></td>
			  	<td align="center" class="ttpotitle">新增面积</td>
			  	<td colspan="2" align="center"><?php
					if($ttpoModel->actionname == 'farmstransfermergecontract')
						echo $ttpoModel->ttpoarea;
					else {
						if($ttpoModel->newchangenotclear == $ttpoModel->newnotclear)
							echo $ttpoModel->ttpoarea;
						else
							echo $ttpoModel->ttpoarea;}?></td>
			  	</tr>
				 <tr>
					 <td height="30" align="center" bgcolor="" class="ttpotitle">现面积</td>
					 <td colspan="2" align="center" bgcolor=""><?php
						 if($ttpoModel->actionname !== 'farmstransfermergecontract') {
							 if($ttpoModel->actionname == 'farmssplitcontinue') {
							 	echo Farms::getContractnumberArea(Ttpozongdi::getSameLastContractnumber($_GET['reviewprocessid']));
							 } else {
								 echo Farms::getContractnumberArea($ttpoModel->oldchangecontractnumber);
							 }}?></td>
					 <td align="center" class="ttpotitle"><?php if($ttpoModel->actionname == 'farmstransfermergecontract' or $ttpoModel->actionname == 'farmstozongdi') echo '现面积'; else echo '原面积';?></td>
					 <td colspan="2" align="center"><?php if($ttpoModel->actionname == 'farmstransfermergecontract' or $ttpoModel->actionname == 'farmstozongdi') echo Farms::getContractnumberArea($ttpoModel->newchangecontractnumber); else echo Farms::getContractnumberArea($ttpoModel->newcontractnumber);?></td>
				 </tr>
				 <?php }?>
				 <?php
				 	if($ttpoModel->actionname == 'farmssplit' or $ttpoModel->actionname == 'farmstozongdi' or $ttpoModel->actionname == 'farmssplitcontinue' or $ttpoModel->actionname == 'farmstransfermergecontract') {
				 ?>
				<tr height="30px">
				 	 <td align="center" bgcolor="" class="ttpotitle">现合同号</td>
					 <td colspan="2" align="center" bgcolor=""><?php
						 if($ttpoModel->actionname !== 'farmstransfermergecontract'){
							if($ttpoModel->actionname == 'farmssplitcontinue')
								echo Ttpozongdi::getSameLastContractnumber($_GET['reviewprocessid']);
							 else
							 	echo $ttpoModel->oldchangecontractnumber;}?></td>
					 <td align="center" class="ttpotitle">现合同号</td>
					 <td colspan="2" align="center">
						 <?php //if($ttpoModel->actionname == 'farmstransfermergecontract' or $ttpoModel->actionname == 'farmstozongdi') echo $ttpoModel->newcontractnumber; else echo $ttpoModel->newchangecontractnumber;?>

						 <?= $ttpoModel->newchangecontractnumber?></td>
				 </tr>
				<?php }?>
			  <?php 
			  
			  foreach ($process as $value) { ?>
			  	<tr>
			  	<td align="center" class="ttpotitle"><strong><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?></strong></td>	
			  	<td colspan="5" align="left">		  				      
			  				      <?php
								  $classname = 'app\\models\\'.ucfirst($value);
								  $lists = $classname::attributesList();
								  //								  var_dump($lists);exit;
								  $result = $classname::find()->where(['reviewprocess_id'=>$_GET['reviewprocessid']])->one();
								  echo '<table>';
			  				      if($value == 'leader') {
//									  echo "<br><br>";
									  echo '<tr>';
									  echo '<td>';
									  if($result) {
										  if ($result[$value . 'isAgree']) {
											  echo '<font size="2"><strong>同意</strong>&nbsp;</font>';
// 											  echo '<font size="2"><strong>不同意<i class="fa fa-square-o"></i></strong></font>';
										  } else {
// 											  echo '<font size="2"><strong>同意<i class="fa fa-square-o"></i></strong>&nbsp;</font>';
											  echo '<font size="2"><strong>不同意</strong></font>';
										  }
									  } else {
										  echo '<font size="2"><strong>同意<i class="fa fa-square-o"></i></strong>&nbsp;</font>';
										  echo '<font size="2"><strong>不同意<i class="fa fa-square-o"></i></strong></font>';
									  }
									  echo '</td>';
									  echo '<td>';

									  echo '</td>';
									  echo '</tr>';
									  echo '</table>';
								  } else {
									  foreach ($lists as $key => $list) {
										  echo '<tr>';
										  echo '<td>';
										  if ($result) {
											  if ($result[$key]) {
												  echo '<font size="2"><strong>是</strong>&nbsp;</font>';
// 												  echo '<font size="2"><strong>否<i class="fa fa-square-o"></i></strong></font>';
											  } else {
// 												  echo '<font size="2"><strong>是<i class="fa fa-square-o"></i></strong>&nbsp;</font>';
												  echo '<font size="2"><strong>否</i></strong></font>';
											  }
										  } else {
											  echo '<font size="2"><strong>是<i class="fa fa-square-o"></i></strong>&nbsp;</font>';
											  echo '<font size="2"><strong>否<i class="fa fa-square-o"></i></strong></font>';
										  }

										  echo '</td>';
										  echo '<td>';

										  echo '<font size="2">&nbsp;' . $list . "</font>";
										  echo '</td>';
										  echo '</tr>';

										  if ($value == 'estate') {
											  echo '<tr>';
											  echo '<td>';
											  if ($result[$key . "content"])
												  echo '<font size="2"><font ><strong>情况说明：</strong></font>';
											  echo '</td>';
											  echo '<td>';
											  echo $result[$key . "content"];
											  echo '</td>';
											  echo "</tr>";
										  }


									  }
									  echo '<tr>';
									  echo '<td>';
									  if ($result) {
										  if ($result[$value . 'isAgree']) {
											  echo '<font size="2"><strong>同意</i></strong>&nbsp;</font>';
// 											  echo '<font size="2"><strong>不同意<i class="fa fa-square-o"></i></strong></font>';
										  } else {
// 											  echo '<font size="2"><strong>同意<i class="fa fa-square-o"></i></strong>&nbsp;</font>';
											  echo '<font size="2"><strong>不同意</i></strong></font>';
										  }
									  } else {
										  echo '<font size="2"><strong>同意<i class="fa fa-square-o"></i></strong>&nbsp;</font>';
										  echo '<font size="2"><strong>不同意<i class="fa fa-square-o"></i></strong></font>';
									  }
									  echo '</td>';
									  echo '<td>';

									  echo '</td>';
									  echo '</tr>';
									  echo '</table>';
								  }
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
		LODOP.ADD_PRINT_HTM("5%","5%","100%","96%","<body leftmargin=20 topmargin=0>"+document.getElementById('ttpoprint').innerHTML+"</body>");
		LODOP.SET_PRINT_STYLEA(0,"Horient",3);        
		LODOP.SET_PRINT_STYLEA(0,"Vorient",2);
		LODOP.SET_PREVIEW_WINDOW(0,0,0,0,0,"");	
	};	
</script>