<?php
namespace frontend\controllers;
use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Processname;
use app\models\Reviewprocess;
use app\models\User;
use Yii;
use app\models\Infrastructuretype;
use app\models\Tempauditing;
/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>
<style type="text/css">
#reviewprocess-estatecontent { display:none }
#reviewprocess-financecontent { display:none }
#reviewprocess-filereviewcontent { display:none }
#reviewprocess-regulationscontent { display:none }
#reviewprocess-publicsecuritycontent { display:none }
#reviewprocess-leadercontent { display:none }
#reviewprocess-mortgagecontent { display:none }
#reviewprocess-steeringgroupcontent { display:none }
.table {
/* 	font-family: "仿宋"; */
	font-size:16px;
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
			    <td align="center">原农场名称</td>
			    <td width="13%" align="center">原法人</td>
			    <td width="17%" align="center">原面积</td>
			    <td width="22%" align="center">现农场名称</td>
			    <td width="14%" align="center">现法人</td>
			    <td width="14%" align="center">现面积</td>
			  </tr>
			  <tr height="40px">
			    <td align="center"><?= $oldfarm->farmname?></td>
			    <td align="center"><?= $oldfarm->farmername?></td>
			    <td align="center"><?= $oldfarm->measure?>亩</td>
			    <td align="center"><?= $newfarm->farmname?></td>
			    <td align="center"><?= $newfarm->farmername?></td>
			    <td align="center"><?= $newfarm->measure?>亩</td>
			  </tr>
			  <tr height="40px">
			    <td align="center">原合同号</td>
			    <td colspan="2" align="center"><?= $oldfarm->contractnumber?></td>
			    <td align="center">现合同号</td>
			    <td colspan="2" align="center"><?= $newfarm->contractnumber?></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">原法人身份证号</td>
			    <td colspan="2" align="center"><?= $oldfarm->cardid?></td>
			    <td align="center">现法人身份证号</td>
			    <td colspan="2" align="center"><?= $newfarm->cardid?></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">原宗地信息</td><?php if(!empty($oldfarm->zongdi)) $zongdiArray = explode('、', $oldfarm->zongdi); else $zongdiArray = [];?>
			    <td colspan="5" align="center"><table width="100%" border="0" align="right"><?php for($i = 0;$i<count($zongdiArray);$i++) {
			    	
			    	if($i == 0 or $i%6 == 0) {
			    		echo '<tr>';
			    	} else {
			    		echo '<td>';
			    		echo $zongdiArray[$i];
			    		echo '</td>';
			    	}
			    	if($i == 0 or $i%6 == 0) {
			    		echo '</tr>';
			    	}
			    	
			    }?></table></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">原未明确地块</td>
			    <td colspan="5" align="center">&nbsp;</td>
		       </tr>
			  <tr height="40px">
			    <td align="center">现宗地信息</td><?php if(!empty($newfarm->zongdi)) $zongdiArray = explode('、', $newfarm->zongdi); else $zongdiArray = [];?>
			    <td colspan="5" align="center"><table width="100%" border="0" align="center"><?php for($i = 0;$i<count($zongdiArray);$i++) {
			    	
			    	if($i == 0 or $i%6 == 0) {
			    		echo '<tr>';
			    	} else {
			    		echo '<td>';
			    		echo $zongdiArray[$i];
			    		echo '</td>';
			    	}
			    	if($i == 0 or $i%6 == 0) {
			    		echo '</tr>';
			    	}
			    	
			    }?>
			    
			    </table></td>
			    </tr>
			    <tr>
			      <td align="center">现未明确地块</td>
			      <td colspan="5" align="left" class='content'>&nbsp;</td>
		       </tr>
			    <?php foreach ($process as $value) { 
// 			    	var_dump($value);
			    //获取当前流程的角色信息
			  	$role = Reviewprocess::getProcessRole($value);
// 			  	var_dump($role);
			  	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
			  	if($temp)
			  		$tempitem = User::getUserItemname($temp['user_id']);
			  	$itemname = User::getItemname();
			  	//审核角色或临时授权角色与当前用户角色相同，则显示该条目
			  	if($role['rolename'] == $itemname or $role['rolename'] == $tempitem) {
// 			  		var_dump($itemname);
			  ?>
		       
		       <tr>	  
			    <td align="center"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?></td>
			    <td colspan="5" align="left" class='content'>
			    <?php 
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
			    <td align="center">备&nbsp;&nbsp;&nbsp;&nbsp;注</td>
			    <td colspan="5" align="center"><br><br><br><br>
			 </tr>
			</table>
<?php }?>
<?php if($class == 'projectapplication') {?>
<table class="table table-bordered table-hover">
  <tr>
    <td align="right">项目名称：</td>
    <td><?= Infrastructuretype::find()->where(['id'=>$project['projecttype']])->one()['typename']?></td>
    <td align="right">申请人：</td>
    <td><?= $farm->farmername;?></td>
    <td align="right">农场名称：</td>
    <td><?= $farm->farmname ?></td>
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
    <td align="right">申请内容：</td>
    <td colspan="5"><?= $project['content'].$project['projectdata'].$project['unit']?></td>
    </tr>
  <tr>
    <td align="right"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?>：</td>
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
if($('input:radio[name="Reviewprocess[estate]"]:checked').val() == 0){
	$('#reviewprocess-estatecontent').css('display', 'inline');
}
if($('input:radio[name="Reviewprocess[finance]"]:checked').val() == 0){
	$('#reviewprocess-financecontent').css('display', 'inline');
}
if($('input:radio[name="Reviewprocess[filereview]"]:checked').val() == 0){
	$('#reviewprocess-filereviewcontent').css('display', 'inline');
}
if($('input:radio[name="Reviewprocess[regulations]"]:checked').val() == 0){
	$('#reviewprocess-regulationscontent').css('display', 'inline');
}
if($('input:radio[name="Reviewprocess[publicsecurity]"]:checked').val() == 0){
	$('#reviewprocess-publicsecuritycontent').css('display', 'inline');
}
if($('input:radio[name="Reviewprocess[leader]"]:checked').val() == 0){
	$('#reviewprocess-leadercontent').css('display', 'inline');
}
if($('input:radio[name="Reviewprocess[mortgage]"]:checked').val() == 0){
	$('#reviewprocess-mortgagecontent').css('display', 'inline');
}
if($('input:radio[name="Reviewprocess[steeringgroup]"]:checked').val() == 0){
	$('#reviewprocess-steeringgroupcontent').css('display', 'inline');
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