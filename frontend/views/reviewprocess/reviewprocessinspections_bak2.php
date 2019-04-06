<?php
namespace frontend\controllers;

use app\models\Help;
use app\models\Ttpozongdi;
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
	font-size: 15px;
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
#estateUndo {
	display: none;
}
#financeUndo {
	display: none;
}
#filereviewUndo {
	display: none;
}
#publicsecurityUndo {
	display: none;
}
#leaderUndo {
	display: none;
}
#mortgageUndo {
	display: none;
}
#regulationsUndo {
	display: none;
}
#projectUndo {
	display: none;
}
</style>
<?php
//var_dump(Processname::getIdentification($model));
//var_dump($model);exit;
?>
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
							<td align="left" bgcolor="#FFFFD5"><?php echo $newfarm->longitude;?></td>
							<td align="left" bgcolor="#FFFFD5"><?php echo $newfarm->latitude;?></td>
							<td width="15%" align="left" bgcolor="#FFFFD5"></td>
						</tr>

						<tr >
							<td align="center" bgcolor="#D0F5FF"><strong>原合同号</strong></td>
							<td align="left" bgcolor="#D0F5FF"><span class="italic">
									<?php
									if($ttpoModel->actionname == 'farmssplitcontinue') {
										echo Ttpozongdi::getSameFirstContractnumber($_GET['id']);
									} else
										echo $oldfarm->contractnumber;
									?>

     </span></td>
							<td align="center" bgcolor="#D0F5FF"><strong>现合同号</strong></td>
							<td align="left" bgcolor="#D0F5FF"><span class="italic">
       <?php if($ttpoModel->actionname !== 'farmssplitcontinue') { if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo '<s>'.$ttpoModel->oldcontractnumber.'</s>'; else {
			   echo $ttpoModel->oldchangecontractnumber;
	   }
	   } else {
		   echo Ttpozongdi::getSameLastContractnumber($_GET['id']);
	   }?>
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
			    <td colspan="3" align="left" bgcolor="#D0F5FF"><font color="red"><?php
						if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract')
							echo $oldfarm->contractarea;
						else {
//							if($ttpoModel['newchangenotclear']) {
								echo $ttpoModel['ttpoarea'] + $ttpoModel['newnotclear'] - $ttpoModel['newchangenotclear'];
//							}

						}?></font></td>
			    <td align='center' bgcolor="#FFFFD5"><font color="red">增加面积</font></td>
			    <td colspan="3" align="left" bgcolor="#FFFFD5"><font color="red"><?php
						if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract')
							echo $oldfarm->contractarea;
						else
							echo $ttpoModel['ttpoarea'];?></font></td>
		       </tr>
			  <tr >
			    <td align='right' bgcolor="#D0F5FF"><font color="red">剩余面积</font></td>
			    <td colspan="3" align="left" bgcolor="#D0F5FF"><font color="red"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo 0;else echo Farms::getContractnumberArea($ttpoModel['oldchangecontractnumber'])?></font></td>
			    <td align='center' bgcolor="#FFFFD5"><font color="red">原面积</font></td>
			    <td colspan="3" align="left" bgcolor="#FFFFD5"><font color="red"><?php if($ttpoModel['newcontractnumber']) echo Farms::getContractnumberArea($ttpoModel['newcontractnumber'])?></font></td>
		       </tr>
                <?php
				foreach ($process as $value) {
					$classname = 'app\\models\\' . ucfirst($value);
					$$value = $classname::find()->where(['reviewprocess_id'=>$model->id])->one();
					?>
					<tr>
						<td rowspan="2"><strong>
								<?php 
								if(Reviewprocess::isRole($value))
									echo Help::showHelp3(Tablefields::find()->where(['fields' => $value . 'content'])->one()['cfields'],'reviewprocessinspections-isAgree');
								else
									echo Tablefields::find()->where(['fields' => $value . 'content'])->one()['cfields'];
								?>
							</strong></td>
						<?php echo Reviewprocess::showReviewprocess($model, $value, $process); ?>
					</tr>
					<?php
				}
				?>
				<tr>
					<td align="center"><strong>备&nbsp;&nbsp;&nbsp;&nbsp;注</strong></td>
					<td colspan="7" align="center"><br><br><br><br>
				</tr>
			</table>
<?php } ?>
<?php if($class == 'projectapplication') {?>
<table class="table table-bordered table-hover">
  <tr>
    <td align="right"><strong>项目名称：</strong></td>
    <td align="left"><span class="italic"><?= Infrastructuretype::find()->where(['id'=>$project['projecttype']])->one()['typename']?></span></td>
    <td align="right"><strong>申请人：</strong></td>
    <td align="left"><span class="italic"><?= $farm->farmername;?></span></td>
    <td align="right"><strong>农场名称：</strong></td>
    <td align="left"><span class="italic"><?= $farm->farmname ?></span></td>
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
			  		$itemname = Yii::$app->getUser()->getIdentity()->level;
			  	if($role['level_id'] == $itemname and $role['department_id'] == Yii::$app->getUser()->getIdentity()->department_id) {
			  ?>
  <tr>
    <td align="right"><strong>申请内容：</strong></td>
    <td colspan="5" align="left"><span class="italic"><?= $project['content'].$project['projectdata'].$project['unit']?></td>
    </tr>
  <tr>
    <td align="right" rowspan="2"><span class="italic"><strong>分管领导：</strong></td>
					<?php echo Reviewprocess::showReviewprocess($model, $value, $process); ?>
    </tr>
    <?php }}?>
	<tr>
		<td align="right"><strong>备&nbsp;&nbsp;&nbsp;&nbsp;注</strong></td>
		<td colspan="7" align="center"><br><br><br><br>
	</tr>
</table>
<span class="italic"></span>
<?php }?>
<?php if($class == 'loancreate') {?>

<table class="table table-bordered table-hover">
  <tr>
    <td align="right"><strong>农场名称：</strong></td>
    <td align="left"><span class="italic"><?= $farm->farmname ?></span></td>
    <td align="right"><strong>抵押人：</strong></td>
    <td align="left"><span class="italic"><?= $farm->farmername;?></span></td>
    <td align="right"><strong>抵押银行：</strong></td>
    <td align="left"><span class="italic"><?= $loan['mortgagebank']?></span></td>
  </tr>
    <tr>
    <td align="right"><strong>抵押面积：</strong></td>
    <td align="left"><span class="italic"><?= $loan['mortgagearea']?></span></td>
    <td align="right"><strong>抵押金额：</strong></td>
    <td align="left"><span class="italic"><?= $loan['mortgagemoney']?></span></td>
    <td align="right"><strong>抵押期限：</strong></td>
    <td align="left"><span class="italic"><?= $loan['begindate'].'　~　'.$loan['enddate']?></span></td>
    </tr>
  <?php
//var_dump($process);
   foreach ($process as $value) {
			    //获取当前流程的角色信息
//			  	$role = Reviewprocess::getProcessRole($value);
//			  	//审核角色或备分审核角色与当前用户角色相同，则显示该条目
//			  	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
//			  	if($temp)
//			  		$itemname = User::getUserItemname($temp['user_id']);
//			  	else
//			  		$itemname = Yii::$app->getUser()->getIdentity()->level;
//			  	if($role['level_id'] == $itemname and $role['department_id'] == Yii::$app->getUser()->getIdentity()->department_id) {

			  ?>

  <tr>
    <td align="right" rowspan="2"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>：</td>
	  <?php echo Reviewprocess::showReviewprocess($model, $value, $process); ?>
    </tr>
    <?php }?>
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

function showContent(key,process,content)
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
				var html = '<textarea id="'+key+'content" name="'+key+'content" rows="1" cols="50" class="isText form-control">'+content+'</textarea>';
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
		} else {
			$('#'+key+'-text').empty();
		}
	} else {

		if($('input:radio[name="'+key+'"]').prop('checked') == true) {
// 			alert($('#'+key+'content').val());
			if($('#'+key+'content').val() == undefined) {
				var html = '<textarea id="'+key+'content" name="'+key+'content" rows="1" cols="50" class="isText form-control">'+content+'</textarea>';
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
		$('#'+process+'Undo').show();
		$('#reviewprocess-'+process).val($('input:radio[name="'+key+'"]:checked').val());
		if($('input:radio[name="'+key+'"]:checked').val() == 1) {
			$('#reviewprocess-' + process + 'content').val('');
			$('#'+process+'Undo').hide();
		}
	}
	$('#submitbutton').attr('disabled',state);

}

function radioListState()
{
	var arr = new Array();
	var str = "<?= Processname::getIdentification($model)?>";
	arr = str.split(',');
	var state = false
	$.each(arr,function(){
// 		alert(this +'=='+$('input:radio[name="'+this+'"]:checked').val());
		if($('input:radio[name="'+this+'"]:checked').val() == undefined) {
			state = true;
		}
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