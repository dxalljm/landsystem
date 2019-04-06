<?php
namespace frontend\controllers;
use app\models\User;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Tables;
use app\models\Tablefields;
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
	font-size:20px;
}
.tablehead{ width:100%; height:30px; line-height:20px; text-align:center; float:left; font-size:30px; font-family:"黑体"}
</style>    

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
			    <td align="center"><?= $oldfarm->measure?></td>
			    <td align="center"><?= $newfarm->farmname?></td>
			    <td align="center"><?= $newfarm->farmername?></td>
			    <td align="center"><?= $newfarm->measure?></td>
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
			    <td align="center">原宗地信息</td><?php if(!empty($oldttpozongdi->oldzongdi)) $zongdiArray = explode('、', $oldttpozongdi->oldzongdi); else $zongdiArray = [];?>
			    
			    <td colspan="5" align="center"><table width="100%" border="0" align="right"><?php for($i = 0;$i<count($zongdiArray);$i++) {
// 			    	echo $i%6;
			    	if($i%5 == 0) {
			    		echo '<tr>';
			    		echo '<td>';
			    		echo $zongdiArray[$i];
			    		echo '</td>';
			    	} else {
			    		echo '<td>';
			    		echo $zongdiArray[$i];
			    		echo '</td>';
			    	} 	
			    }?></table></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">现宗地信息</td><?php if(!empty($newttpozongdi->zongdi)) $zongdiArray = explode('、', $newttpozongdi->zongdi); else $zongdiArray = [];?>
			    <td colspan="5" align="center"><table width="100%" border="0" align="center"><?php for($i = 0;$i<count($zongdiArray);$i++) {
			    	if($i%5 == 0) {
			    		echo '<tr>';
			    		echo '<td>';
			    		echo $zongdiArray[$i];
			    		echo '</td>';
			    	} else {
			    		echo '<td>';
			    		echo $zongdiArray[$i];
			    		echo '</td>';
			    	}
			    	
			    }?></table></td>
			    </tr>
			  <tr>
			  <?php foreach ($process as $value) { ?>
			  
			    <td align="center"><?= Tablefields::find()->where(['fields'=>$value.'content'])->one()['cfields']?></td>
			    <td colspan="5" align="right"><?php echo '<br><br><br><br>';?>
			    
		        <p>签字：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;日 </p></td>
			    </tr>
			  <?php }?>
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
    var LODOP; //声明为全局变量 
	
	function prn_preview4() {	
		CreateOnePage();	
		LODOP.SET_PRINT_MODE("PRINT_PAGE_PERCENT","Full-Page");	
		LODOP.PREVIEW();	
	};		
	
	function CreateOnePage(){
		LODOP=getLodop();  
		LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_整页缩放打印输出");
		strCenterStyle="<style/>form {text-align: center}</style>"; 
		LODOP.ADD_PRINT_HTM("14%","10%","100%","90%","<body leftmargin=0 topmargin=0>"+document.getElementById('ttpoprint').innerHTML+"</body>");
		LODOP.SET_PRINT_STYLEA(0,"Horient",2);        
		LODOP.SET_PRINT_STYLEA(0,"Vorient",2);
		LODOP.SET_PREVIEW_WINDOW(0,0,0,0,0,"");	
	};	
</script>