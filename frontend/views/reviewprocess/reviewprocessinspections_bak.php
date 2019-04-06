<?php
namespace frontend\controllers;use app\models\User;
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
use app\models\Lease;
use app\models\Farms;
use app\models\Backprocess;
/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>
<style type="text/css">
.table {
	/* 	font-family: "仿宋"; */
	font-size: 12px;
	text-align: center;
}
.italic {
	font-style: italic;
}
.econtent {
	display: none;
}
#reviewprocess-undo {
	display: none;
}
#UndoContent {
	display: none;
}
#Undo {
	display: none;
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
						<tr>
							<td width="10%" align="center" bgcolor="#D0F5FF"><strong>原农场名称</strong></td>
							<td colspan="3" align="left" bgcolor="#D0F5FF"><span class="italic"><?= $oldfarm->farmname?></span></td>
							<td width="10%" align="center" bgcolor="#FFFFD5"><strong>现农场名称</strong></td>
							<td colspan="3" align="left" bgcolor="#FFFFD5"><span class="italic"><?= $newfarm->farmname?></span></td>
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
						<tr >
							<td align="center" bgcolor="#D0F5FF"><strong>地理坐标</strong></td>
							<td align="left" bgcolor="#D0F5FF"><?= $oldfarm->longitude?></td>
							<td align="left" bgcolor="#D0F5FF"><?= $oldfarm->latitude?></td>
							<td align="left" bgcolor="#D0F5FF"></td>
							<td align="center" bgcolor="#FFFFD5"><strong>地理坐标</strong></td>
							<td align="left" bgcolor="#FFFFD5"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo $oldfarm->longitude;else echo $newfarm->longitude;?></td>
							<td align="left" bgcolor="#FFFFD5"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo $oldfarm->latitude;else echo $newfarm->latitude;?></td>
							<td width="15%" align="left" bgcolor="#FFFFD5"></td>
						</tr>

						<tr >
							<td align="center" bgcolor="#D0F5FF"><strong>原合同号</strong></td>
							<td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->contractnumber?>
     </span></td>
							<td align="center" bgcolor="#D0F5FF"><strong>现合同号</strong></td>
							<td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo '<s>'.$ttpoModel->oldcontractnumber.'</s>'; else echo $ttpoModel->oldchangecontractnumber;?></s>
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
						<tr >
							<td align="center" bgcolor="#D0F5FF"><strong>原法人身份证号</strong></td>
							<td colspan="3" align="left" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->cardid?>
     </span></td>
							<td align="center" bgcolor="#FFFFD5"><strong>现法人身份证号</strong></td>
							<td colspan="3" align="left" bgcolor="#FFFFD5"><span class="italic">
       <?= $newfarm->cardid?>
     </span></td>
						</tr>
						<tr >
							<td align="center" bgcolor="#D0F5FF"><strong>原合同面积</strong></td>
							<td align="left" bgcolor="#D0F5FF"><?= $oldfarm->contractarea?></td>
							<td align="center" bgcolor="#D0F5FF"><strong>现合同面积</strong></td>
							<td align="left" bgcolor="#D0F5FF"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo '<s>'.Farms::getContractnumberArea($ttpoModel->oldcontractnumber).'</s>'; else echo Farms::getContractnumberArea($ttpoModel->oldchangecontractnumber);?></td>
							<td align="center" bgcolor="#FFFFD5"><strong>现合同面积</strong></td>
							<td align="left" bgcolor="#FFFFD5"><?= Farms::getContractnumberArea($ttpoModel->newchangecontractnumber)?></td>
							<td align="center" bgcolor="#FFFFD5"><strong>原合同面积</strong></td>
							<td align="left" bgcolor="#FFFFD5"><?= Farms::getContractnumberArea($ttpoModel->newcontractnumber)?></td>
						</tr>
						<tr >
							<td align="center" bgcolor="#D0F5FF"><strong>原宗地面积</strong></td>
							<td align="left" bgcolor="#D0F5FF"><?= $oldfarm->measure?></td>
							<td align="center" bgcolor="#D0F5FF"><strong>现宗地面积</strong></td>
							<td align="left" bgcolor="#D0F5FF"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract')  echo '<s>'.$ttpoModel->oldmeasure.'</s>'; else echo $ttpoModel->oldchangemeasure;?></td>
							<td align="center" bgcolor="#FFFFD5"><strong>现宗地面积</strong></td>
							<td align="left" bgcolor="#FFFFD5"><?= $ttpoModel->newchangemeasure?></td>
							<td align="center" bgcolor="#FFFFD5"><strong>原宗地面积</strong></td>
							<td align="left" bgcolor="#FFFFD5"><?= $ttpoModel->newmeasure?></td>

						</tr>
						<tr >
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
											if(strstr($ttpoModel->ttpozongdi,Lease::getZongdi($zongdiArray[$i]))) {
												echo '<span class="italic text-red">'.$zongdiArray[$i].'<small class="fa fa-minus pull-center text-sm text-red"></small></span>';
											} else
												echo '<span class="italic">'.$zongdiArray[$i].'</span>';
											echo '</td>';
										} else {
											echo '<td align="left">';
											if(strstr($ttpoModel->ttpozongdi,Lease::getZongdi($zongdiArray[$i]))) {
												echo '<span class="italic text-red">'.$zongdiArray[$i].'<small class="fa fa-minus pull-center text-sm text-red"></small></span>';
											} else
												echo '<span class="italic">'.$zongdiArray[$i].'</span>';
											echo '</td>';
										}

									}?>
								</table>
								</span></td>
							<td align="center" bgcolor="#FFFFD5"><strong>现宗地信息</strong></td>
							<td colspan="3" align="left" bgcolor="#FFFFD5">
								<table width="100%" border="0" align="center">
									<?php if(!empty($ttpoModel['newchangezongdi'])) $zongdiArray = explode('、', $ttpoModel->newchangezongdi); else $zongdiArray = [];?>
									<?php
									for($i = 0;$i<count($zongdiArray);$i++) {
										if($i%3 == 0) {
											echo '<tr height="10">';
											echo '<td align="left">';
											if(strstr($ttpoModel->ttpozongdi,Lease::getZongdi($zongdiArray[$i]))) {
												echo '<span class="italic text-red">'.$zongdiArray[$i].'<small class="fa fa-plus pull-center text-sm text-red"></small></span>';
											} else
												echo '<span class="italic">'.$zongdiArray[$i].'</span>';
											echo '</td>';
										} else {
											echo '<td align="left">';
											if(strstr($ttpoModel->ttpozongdi,Lease::getZongdi($zongdiArray[$i]))) {
												echo '<span class="italic text-red">'.$zongdiArray[$i].'</span><small class="fa fa-plus pull-center text-sm text-red"></small>';
											} else
												echo '<span class="italic">'.$zongdiArray[$i].'</span>';
											echo '</td>';
										}

									}?>
								</table></td>
						</tr>
						<tr >
							<td align="center" bgcolor="#D0F5FF"><strong>原未明确地块</strong></td>
							<td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?= $ttpoModel->oldnotclear;?>
     </span></td>
							<td align="center" bgcolor="#D0F5FF"><strong>现未明确地块</strong></td>
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
						<tr >
							<td align="center" bgcolor="#D0F5FF"><strong>原未明确状态地块</strong></td>
							<td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?= $oldfarm->notstate;?>
     </span></td>
							<td align="center" bgcolor="#D0F5FF"><strong>现未明确状态地块</strong></td>
							<td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo '<s>'.$ttpoModel->oldnotstate.'</s>'; else echo $ttpoModel->oldchangenotstate;?>
     </span></td>
							<td align="center" bgcolor="#FFFFD5"><strong>现未明确状态地块</strong></td>
							<td align="left" bgcolor="#FFFFD5"><span class="italic">
       <?= $ttpoModel->newchangenotstate;?>
     </span></td>
							<td align="center" bgcolor="#FFFFD5"><strong>原未明确状态地块</strong></td>
							<td align="left" bgcolor="#FFFFD5"><span class="italic">
       <?= $newfarm->notstate;?>
     </span></td>
						</tr>

			  <tr >
			    <td align='right' bgcolor="#D0F5FF"><font color="red">减少面积</font></td>
			    <?php if(!empty($ttpoModel['newchangezongdi'])) $zongdiArray = explode('、', $ttpoModel['newchangezongdi']); else $zongdiArray = [];?>
			    <td colspan="3" align="left" bgcolor="#D0F5FF"><font color="red"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo $oldfarm->contractarea; else echo $ttpoModel['ttpoarea']?></font></td>
			    <td align='center' bgcolor="#FFFFD5"><font color="red">增加面积</font></td>
			    <td colspan="3" align="left" bgcolor="#FFFFD5"><font color="red"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo $oldfarm->contractarea; else echo $ttpoModel['ttpoarea']?></font></td>
		       </tr>
			  <tr >
			    <td align='right' bgcolor="#D0F5FF"><font color="red">剩余面积</font></td>
			    <td colspan="3" align="left" bgcolor="#D0F5FF"><font color="red"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo 0;else echo Farms::getContractnumberArea($ttpoModel['oldchangecontractnumber'])?></font></td>
			    <td align='center' bgcolor="#FFFFD5"><font color="red">原面积</font></td>
			    <td colspan="3" align="left" bgcolor="#FFFFD5"><font color="red"><?php if($ttpoModel['newcontractnumber']) echo Farms::getContractnumberArea($ttpoModel['newcontractnumber'])?></font></td>
		       </tr>
                <?php

				foreach ($process as $value) {
// 			    	var_dump($value);
			    //获取当前流程的角色信息
			  	$role = Reviewprocess::getProcessRole($value);
// 			  	var_dump($role);
			  	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
			  	$tempitem = '';

			  	if($temp)
			  		$tempitem = User::getUserItemname($temp['user_id']);
			  	$itemname = User::getItemname();

			  	//审核角色或临时授权角色与当前用户角色相同，则显示该条目
			  	if($role['rolename'] == $itemname or $role['rolename'] == $tempitem) {

			  ?>
			    <tr>
			    <td rowspan="2"><strong>
			      <?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>
			    </strong></td>
			      <?php
				  if($model->$value == 2 or $model->$value == 8) {
					  $classname = 'app\\models\\' . ucfirst($model->undo);
					  $classdata = $classname::find()->where(['reviewprocess_id'=>$model->id])->one();
					  $lists = $classname::attributesList();
						  if ($lists) {
							  echo '<td colspan="7" align="left">';
							  echo '<table class="table">';
							  foreach ($lists as $key => $list) {
								  echo '<tr>';
								  echo '<td width="100">';
								  echo Html::radioList($key,  $classdata[$key], ['否', '是'], ['onChange' => 'showContent("' . $key . '","' . $model->undo . '")', 'class' => 'radiolist']);
								  echo '</td>';
								  echo '<td colspan="2" align="left" width="40%">';
								  echo '&nbsp;&nbsp;' . $list;
								  echo '</td>';
								  echo '<td id="' . $key . '-add" colspan="2">';
								  // 				      		echo '&nbsp;&nbsp;'.Html::textarea($key.'content','',['rows'=>1,'cols'=>80,'id'=>$key.'content','disabled'=>'disabled']);
								  echo "</td>";
								  echo '</tr>';
							  }
							  echo '<tr>';
							  echo '<td width="150">';
							  echo Html::radioList($model->undo . 'isAgree', '', [1 => '同意', 0 => '不同意'], ['onChange' => 'showContent("' . $model->undo . 'isAgree' . '","' . $model->undo . '")', 'class' => 'radiolist' . $model->undo]);
							  echo '</td>';
							  echo '<td align="center" width="10%" id="' . $model->undo . 'isAgree' . '-text">';
							  echo '</td>';
							  echo '<td>';
							  echo Html::dropDownList('undo', '', Reviewprocess::showProccessList($process), ['class' => 'form-control', 'id' => 'Undo']);
							  echo '</td>';
							  echo '<td id="' . $model->undo . 'isAgree' . '-add">';
							  echo "</td>";
							  echo '</tr>';
							  echo '</table>';
							  echo '</td>';
						  }
					  echo '<tr>';
				  } else {
			  					  $classname = 'app\\models\\'.ucfirst($value);
			  				      	$lists = $classname::attributesList();
			  				      	$result = $classname::find()->where(['reviewprocess_id'=>$model->id])->one();
			  				      	if($lists) {
										if($result) {
											echo '<td colspan="7" align="left">';
											echo '<font color="#00CC66">';

											echo '<table>';
											foreach ($lists as $key => $list) {
												if(!strstr($key,'isAgree')) {
													echo '<tr>';
													echo '<td>';
													if ($result[$key]) {
														echo '<strong>是<i class="fa fa-check-square-o"></i></strong>&nbsp;&nbsp;';
														echo '<strong>否<i class="fa fa-square-o"></i></strong>';
													} else {
														echo '<strong>是<i class="fa fa-square-o"></i></string>&nbsp;&nbsp;';
														echo '<strong>否<i class="fa fa-check-square-o"></i></strong>';
													}
													echo '</td>';
													echo '<td>';
													echo '&nbsp;&nbsp;' . $list;
													echo '</td>';
													echo '<td>';
													if ($result[$key . 'content'])
														echo '&nbsp;&nbsp;<font color="red"><strong>情况说明：' . $result[$key . 'content'] . '</strong></font>';
													echo "</td>";
													echo '</tr>';
												}
											}
											echo '</table>';

											echo '</font>';
											echo '</td>';
										}
									}
			  				      ?>
					  </tr>
					  <tr>
						  <td colspan="7" align="left" ><?php
							  if($model->$value == 1) {
								  echo '<font color="#0033FF"><strong>';
								  echo Reviewprocess::state($model->$value);
								  echo '</strong></font>';
							  } else {
								  echo '<font color="#0033FF"><strong>';
								  echo Reviewprocess::state($model->$value);
								  echo '</strong></font>';
							  }
							  echo "<br>";
							  if(!$model->$value) {
								  echo '<font color="#FF0000"><strong>';
								  $modelStr = $value.'content';
								  echo "退回".Processname::getDepartment($model->undo)."<br>原由：".$model->$modelStr;
								  echo '</strong></font>';
							  }
							  ?></td>
					  </tr>
				  <?php }

			      	echo $form->field($model,$value)->hiddenInput()->label(false)->error(false);
			      	echo $form->field($model,$value.'content')->hiddenInput()->label(false)->error(false);
			      	echo $form->field($model,$value.'time')->hiddenInput(['value'=>time()])->label(false)->error(false);
			      ?>

			  <?php } else {?>
			  	<tr>
			  	<td rowspan="2"><strong>
			  				      <?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>
			  				    </strong></td>
			  				      <?php
			  					  $classname = 'app\\models\\'.ucfirst($value);
			  				      	$lists = $classname::attributesList();
			  				      	$result = $classname::find()->where(['reviewprocess_id'=>$model->id])->one();
			  				      	if($lists) {
			  				      	if($result) {
			  				      		echo '<td colspan="7" align="left">';
										echo '<font color="#00CC66">';

											echo '<table>';
											foreach ($lists as $key => $list) {
												if(!strstr($key,'isAgree')) {
													echo '<tr>';
													echo '<td>';
													if ($result[$key]) {
														echo '<strong>是<i class="fa fa-check-square-o"></i></strong>&nbsp;&nbsp;';
														echo '<strong>否<i class="fa fa-square-o"></i></strong>';
													} else {
														echo '<strong>是<i class="fa fa-square-o"></i></string>&nbsp;&nbsp;';
														echo '<strong>否<i class="fa fa-check-square-o"></i></strong>';
													}
													echo '</td>';
													echo '<td>';
													echo '&nbsp;&nbsp;' . $list;
													echo '</td>';
													echo '<td>';
													if ($result[$key . 'content'])
														echo '&nbsp;&nbsp;<font color="red"><strong>情况说明：' . $result[$key . 'content'] . '</strong></font>';
													echo "</td>";
													echo '</tr>';
												}
											}
											echo '</table>';

										echo '</font>';
			  					      	echo '</td>';
			  				      	}
			  				      	}
			  				      ?>
			  			       </tr>


			  	               <tr>
			  		              <td colspan="7" align="left" ><?php
									  if($model->$value == 1) {
										  echo '<font color="#0033FF"><strong>';
										  echo Reviewprocess::state($model->$value);
										  echo '</strong></font>';
									  } else {
										  echo '<font color="#0033FF"><strong>';
										  echo Reviewprocess::state($model->$value);
										  echo '</strong></font>';
									  }
									  echo "<br>";
									  if(!$model->$value) {
										  echo '<font color="#FF0000"><strong>';
										  $modelStr = $value.'content';
										  echo "退回".Processname::getDepartment($model->undo)."<br>原由：".$model->$modelStr;
										  echo '</strong></font>';
									  }
			  				    ?></td>
			  	               </tr>
			  				  <?php }?>
			  <?php }?>
			  <tr>
			    <td align="center"><strong>备&nbsp;&nbsp;&nbsp;&nbsp;注</strong></td>
			    <td colspan="7" align="center"><br><br><br><br>
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
			  		$itemname = User::getItemname();
			  	if($role['rolename'] == $itemname or $role['sparerole'] == User::getItemname()) {
			  ?>
  <tr>
    <td align="center"><strong>申请内容：</strong></td>
    <td colspan="5"><span class="italic"><?= $project['content'].$project['projectdata'].$project['unit']?></td>
    </tr>
  <tr>
    <td align="center"><span class="italic"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>：</td>
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
<?php if($class == 'loan') {?>

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
    <td><span class="italic"><?= '自'.$loan['begindate'].'至'.$loan['enddate'].'止'?></span></td>
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
			  		$itemname = User::getItemname();
			  	if($role['rolename'] == $itemname) {
			  		
			  ?>

  <tr>
    <td align="center"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>：</td>
			    <td colspan="5" align="left" >
			    <?php 
			    if($model->$value == 2 or $model->$value == 0) {
			    	
// 			    		$model->$value = 1; 
			    	echo $form->field($model,$value)->radioList([1=>'同意',0=>'不同意'],['onclick'=>'CheckState("'.$value.'")'])->label(false)->error(false); 
			    	echo $form->field($model,$value.'content')->textarea(['rows'=>10,'disabled'=>'disabled'])->label(false)->error(false);
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
<?php }?>
			<br>
	<div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary','id'=>'submitbutton','disabled'=>'disabled']) ?>
    </div>

        <!-- /.col -->

    

    <?php ActiveFormrdiv::end(); ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
<script language="javascript" type="text/javascript">
// alert($('#tjsqjbzscontent').prop('disabled'));
// alert($('#tjsqjbzs-content').val());
// $('input:radio[name="isdydk"]:eq(1)').attr("checked",'checked');
// $('#isdydkcontent').css('display','none');
// $('#isdydkcontent').attr('disabled',true);
// // $('input:radio[name="sfdj"]:eq(1)').attr("checked",'checked');
// $('#sfdjcontent').css('display','none');
// $('#sfdjcontent').attr('disabled',true);
// // $('input:radio[name="isqcbf"]:eq(1)').attr("checked",'checked');
// $('#isqcbfcontent').css('display','none');
// $('#isqcbfcontent').attr('disabled',true);
// // $('input:radio[name="other"]:eq(1)').attr("checked",'checked');
// $('#othercontent').css('display','none');
// $('#othercontent').attr('disabled',true);
// // $('input:radio[name="sfyzy"]:eq(1)').attr("checked",'checked');
// $('#sfyzycontent').css('display','none');
// $('#sfyzycontent').attr('disabled',true);

function showContent(key,process)
{
	$('#'+key+'content').remove();
	var state = true;
	state = contentListState();
// 	alert($('input:radio[name="'+key+'"]:checked').val());
	if(key == 'isdydk' || key == 'sfdj' || key == 'isqcbf' || key == 'other' || key == 'sfyzy' || key.indexOf('isAgree')>=0) {

// 		alert($('input:radio[name="'+key+'"]').prop('checked'));
		if($('input:radio[name="'+key+'"]').prop('checked') == false) {
			$('#'+key+'-text').append('事由：');
			if($('#'+key+'content').val() == undefined) {
				var html = '<textarea id="'+key+'content" name="'+key+'content" rows="1" cols="50" class="isText form-control"></textarea>';
				$('#'+key+'-add').append(html);

				$('#'+key+'content').focus();
				state = contentListState();
				$('#'+key+'content').keyup(function(e){
					if(key.indexOf('isAgree')>=0) {
						$('#reviewprocess-'+process+'content').val($(this).val());
					}
					state = contentListState();
// 					alert(state);
					$('#submitbutton').attr('disabled',state);
				});
			}
		}
	} else {
		
		if($('input:radio[name="'+key+'"]').prop('checked') == true) {
			alert('222');
// 			alert($('#'+key+'content').val());
			if($('#'+key+'content').val() == undefined) {
				var html = '<textarea id="'+key+'content" name="'+key+'content" rows="1" cols="50" class="isText form-control"></textarea>';
// 				$('#submitbutton').attr('disabled',true);
				$('#'+key+'-add').append(html);
				$('#'+key+'content').focus();
				state = contentListState();
				$('#'+key+'content').keyup(function(e){
					state = contentListState();
					$('#submitbutton').attr('disabled',state);
				});
			}
		}
	}
	if(key.indexOf('isAgree')>=0){
		$('#Undo').show();
		$('#reviewprocess-'+process).val($('input:radio[name="'+key+'"]:checked').val());
		if($('input:radio[name="'+key+'"]:checked').val() == 1) {
			$('#'+key+'-text').html("");
			if($('#Undo').val() == 'prompt') {

			}
			$('#reviewprocess-' + process + 'content').val('');
			$('#Undo').hide();
		}
	}
	alert(state);
	$('#submitbutton').attr('disabled',state);
	
}

function radioListState()
{
	var arr = new Array();
	var str = "<?= Processname::getIdentification()?>";
	arr = str.split(',');
	var state = false
	$.each(arr,function(){
// 		alert($('input:radio[name="'+this+'"]:checked').val());
		if($('input:radio[name="'+this+'"]:checked').val() == undefined)
			state = true;
// 		if(this == 'isdydk' || this == 'sfdj' || this == 'isqcbf' || this == 'other' || this == 'sfyzy') {
// 			if($('input:radio[name="'+this+'"]:checked').val() == 1) {
// 				state = true;				
// 			}
// 		} else {
// 			if($('input:radio[name="'+this+'"]:checked').val() == 0) {
// 				state = true;				
// 			}
// 		}	
	});
// 	alert(state);
	return state;
}

function contentListState()
{
	var state = radioListState();
// 	alert(state);
	if(state === false) {
// 		alert($(".isText").val());
		$(".isText").each(function(){
// 			alert($(this).val());
			if($(this).val() == "") {
			　　state = true;
			}
		});
	}
	return state;
}
function processListState()
{
	var str = "<?= implode(',', $process)?>";
	arr = str.split(',');
	var state = true;
	$.each(arr,function(){
		if($('#reviewprocess-'+this).val() != undefined) {	
			if($('input:radio[name="Reviewprocess['+this+']"]:checked').val() == 1) {
				state = false;
			} else {
// 				alert(state);
				if($('#reviewprocess-'+this+'content').val() == '')
						state = true;
					else
						state = false;
			}
		}			
	});
// 	alert(state);
	return state;
}

</script>