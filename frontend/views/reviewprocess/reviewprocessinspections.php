<?php
namespace frontend\controllers;
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
use app\models\Loan;
use app\models\Estate;
/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>
<style type="text/css">
.table {
	/* 	font-family: "仿宋"; */
	font-size: 16px;
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
			  <tr height="40px">
			    <td align="center"><strong>原农场名称</strong></td>
			    <td width="13%" align="center"><strong>原法人</strong></td>
			    <td width="17%" align="center"><strong>原面积</strong></td>
			    <td width="22%" align="center"><strong>现农场名称</strong></td>
			    <td width="14%" align="center"><strong>现法人</strong></td>
			    <td width="14%" align="center"><strong>现面积</strong></td>
			  </tr>
			  <tr height="40px">
			    <td align="center"><span class="italic"><?= $oldfarm->farmname?></span></td>
			    <td align="center"><span class="italic"><?= $oldfarm->farmername?></span></td>
			    <td align="center"><span class="italic"><?= $oldfarm->contractarea?>亩</span></td>
			    <td align="center"><span class="italic"><?= $newfarm->farmname?></span></td>
			    <td align="center"><span class="italic"><?= $newfarm->farmername?></span></td>
			    <td align="center"><span class="italic"><?= $newfarm->contractarea?>亩</span></td>
			  </tr>
			  <tr height="40px">
			    <td align="center"><strong>原合同号</strong></td>
			    <td colspan="2" align="center"><span class="italic"><?= $oldfarm->contractnumber?></span></td>
			    <td align="center"><strong>现合同号</strong></td>
			    <td colspan="2" align="center"><span class="italic"><?= $newfarm->contractnumber?></span></td>
		       </tr>
			  <tr height="40px">
			    <td align="center"><strong>原法人身份证</strong>号</td>
			    <td colspan="2" align="center"><span class="italic"><?= $oldfarm->cardid?></span></td>
			    <td align="center"><strong>现法人身份证号</strong></td>
			    <td colspan="2" align="center"><span class="italic"><?= $newfarm->cardid?></span></td>
		       </tr>
			  <tr height="40px">
			    <td align="center"><strong>原宗地信息</strong></td><?php if(!empty($oldttpozongdi['oldzongdi'])) $zongdiArray = explode('、', $oldttpozongdi['oldzongdi']); else $zongdiArray = [];?>
			    <td colspan="5" align="center"><table width="100%" border="0" align="right"><?php for($i = 0;$i<count($zongdiArray);$i++) {
			    	if($i%6 == 0) {
			    		echo '<tr height="10">';
			    		echo '<td align="left">';
			    		echo '<span class="italic">'.$zongdiArray[$i].'</span>';
			    		echo '</td>';
			    	} else {
			    		echo '<td align="left">';
			    		echo '<span class="italic">'.$zongdiArray[$i].'</span>';
			    		echo '</td>';
			    	}
			    	
			    }?></table></span></td>
		       </tr>
			  <tr height="40px">
			    <td align="center"><strong>原未明确地块</strong></td>
			    <td colspan="2" align="center"><span class="italic"><?= $oldfarm->notclear;?></span></td>
			    <td align="center"><strong>原未明确状态地块</strong></td>
			    <td colspan="2" align="center"><span class="italic"><?= $oldfarm->notstate;?></span></td>
		       </tr>
			  <tr height="40px">
			    <td align="center"><strong>现宗地信息</strong></td><?php if(!empty($newttpozongdi['zongdi'])) $zongdiArray = explode('、', $newttpozongdi['zongdi']); else $zongdiArray = [];?>
			    <td colspan="5" align="center"><table width="100%" border="0" align="center">
			    <?php for($i = 0;$i<count($zongdiArray);$i++) {
			    	if($i%6 == 0) {
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
			    <tr>
			      <td align="center"><strong>现未明确地块</strong></td>
			      <td colspan="2" align="left" class='content'><span class="italic"><?= $newfarm->notclear;?></span></td>
			      <td align="center" class='content'><strong>现未明确状态地块</strong></td>
			      <td colspan="2" align="left" class='content'><span class="italic"><?= $newfarm->notstate;?></span></td>
		       </tr>
                <?php foreach ($process as $value) { 
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
// 			  		var_dump($itemname);
			  ?>
			    <tr>
			    <td rowspan="2"><strong>
			      <?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>
			    </strong></td>
			      <td colspan="5" align="left">
			      <table>
			      
			      <?php 
				  $classname = 'app\\models\\'.ucfirst($value);
			      	$lists = $classname::attributesList();
			      	foreach ($lists as $key=>$list) {
			      		echo '<tr>';
			      		echo '<td>';
			      		echo Html::radioList($key,'0',['否','是'],['onclick'=>'showContent("'.$key.'")']);
			      		echo '</td>';
			      		echo '<td>';
			      		echo '&nbsp;&nbsp;'.$list;
			      		echo '</td>';
			      		echo '<td>';
			      		echo Html::textarea($key.'content','',['class'=>'econtent','rows'=>1,'cols'=>100,'id'=>$key.'content']);
			      		echo "</td>";
			      		echo '</tr>';
			      	}
			      ?>
			      
			      </table>
			      </td>
		       </tr>
			   
		       
               <tr>
	              <td colspan="5" align="left" class='content'><?php 
			    if($model->$value == 2 or $model->$value == 0) {
			    	if($model->$value == 2)
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
			  <tr>
			    <td align="center"><strong>备&nbsp;&nbsp;&nbsp;&nbsp;注</strong></td>
			    <td colspan="5" align="center"><br><br><br><br>
			 </tr>
			</table>
<?php }?>
<?php if($class == 'projectapplication') {?>
<table class="table table-bordered table-hover">
  <tr>
    <td align="right"><strong>项目名称：</strong></td>
    <td><span class="italic"><?= Infrastructuretype::find()->where(['id'=>$project['projecttype']])->one()['typename']?></span></td>
    <td align="right"><strong>申请人：</strong></td>
    <td><span class="italic"><?= $farm->farmername;?></span></td>
    <td align="right"><strong>农场名称：</strong></td>
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
    <td align="right"><strong>申请内容：</strong></td>
    <td colspan="5"><span class="italic"><?= $project['content'].$project['projectdata'].$project['unit']?></td>
    </tr>
  <tr>
    <td align="right"><span class="italic"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>：</td>
			    <td colspan="5" align="left" class='content'>
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
    <td align="right"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>：</td>
			    <td colspan="5" align="left" class='content'>
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
</table>
<?php }?>
			<br>
	<div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
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