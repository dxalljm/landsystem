<?php
namespace frontend\controllers;
use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Processname;
use app\models\Reviewprocess;
use app\models\User;
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
.ttpoprint {
	font-family: "仿宋";
	font-size:20px;
}
.tablehead{ width:100%; height:30px; line-height:20px; text-align:center; float:left; font-size:30px; font-family:"黑体"}
</style>
<div class="reviewprocess-form">


    <?php $form = ActiveFormrdiv::begin(); ?>
    
    
    <section class="content">
    <div class="row">
        <div class="col-xs-12">
<!--             <div class="box"> -->

<!--                 <div class="box-body"> -->
    <div class="col-md-6" id="ttpoprint"> 

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
			    	
			    }?></table></td>
			    </tr>
			    <?php foreach ($process as $value) { 
			  	$role = Reviewprocess::getProcessRole($value);
			  	
			  	if($role['rolename'] == User::getItemname() or $role['sparerole'] == User::getItemname()) {
			  ?>
			  <tr>	  
			    <td align="center"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?></td>
			    <td colspan="5" align="left" class='content'><?php if($state) { echo html::radioList('isAgree',1,[1=>'同意',0=>'不同意'],['id'=>'agree','onclick'=>'CheckState("'.$value.'")']); echo $form->field($model,$value.'content')->textInput()->label(false)->error(false);}?></td>
			    </tr>
			  <?php }}?>
			  <tr>
			    <td align="center">备&nbsp;&nbsp;&nbsp;&nbsp;注</td>
			    <td colspan="5" align="center"><br><br><br><br>
			 </tr>
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
function CheckState(process) {
$('#agree').click(function(){
 	var val = $('input:radio[name="isAgree"]:checked').val();
	if(val == 0) {
		$('#reviewprocess-'+process+'content').css('display', 'inline')
	} else {
		$('#reviewprocess-'+process+'content').css('display', 'none')
	}
});
}
</script>