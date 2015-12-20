<?php
namespace frontend\controllers;
use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Reviewprocess;
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
			    <td width="18%" align="center">农场名称</td>
			    <td colspan="3" align="center"><?= $farm->farmname?></td>
			    <td width="23%" rowspan="5" align="center"><?php if($farmer) echo '&nbsp;'.Html::img($farmer->photo,['height'=>'130px']);?></td>
			  </tr>
			  <tr height="40px">
			    <td align="center">法人姓名</td>
			    <td width="20%" align="center"><?= $farm->farmername?></td>
			    <td width="20%" align="center">曾用名</td>
			    <td width="19%" align="center"><?php if($farmer) echo $farmer->farmerbeforename?></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">民族</td>
			    <td align="center"><?php if($farmer) echo $farmer->nation?></td>
			    <td align="center">政治面貌</td>
			    <td align="center"><?php if($farmer) echo $farmer->political_outlook?></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">文化程序</td>
			    <td align="center"><?php if($farmer) echo $farmer->cultural_degree?></td>
			    <td align="center">承包面积</td>
			    <td align="center"><?= $farm->measure?></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">身份证号码</td>
			    <td align="center"><?= $farm->cardid?></td>
			    <td align="center">联系电话</td>
			    <td align="center"><?= $farm->telephone?></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">合同号</td>
			    <td align="center"><?= $farm->contractnumber?></td>
			    <td align="center">地理坐标</td>
			    <td colspan="2" align="center"><?= $farm->longitude?>
			      
			      <?= $farm->latitude?></td>
			    </tr>
			  <tr>
			    <td align="center">农场位置</td>
			    <td colspan="4" align="left"><br><?= $farm->address?><br><br></td>
			    </tr>
			  <tr>
			 
			    <td align="center">宗地</td>
			    <td colspan="4" align="left"><br><table width="100%" border="0" align="right">
			    <?php if(!empty($newfarm->zongdi)) $zongdiArray = explode('、', $newfarm->zongdi); else $zongdiArray = [];?>
			    <?php for($i = 0;$i<count($zongdiArray);$i++) {
			    	
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
			    	
			    }?></table><br></td>
			    </tr>
			  <tr>
			    <td align="center"  valign="middle"><br>户籍所在地<br><br></td>
			    <td colspan="4" align="left"><br><?php if($farmer) echo $farmer->domicile?><br><br></td>              
			    </tr>
			  <tr>
			    <td align="center"><br>现住地<br><br></td>
			    <td colspan="4" align="left"  valign="middle"><br><?php if($farmer) echo $farmer->nowlive?><br><br>             
			    </tr>
			  <tr>
			    <td colspan="5" align="center"><br>家庭主要成员 <br><br></td>
			    </tr>
			    <tr>
			    <td align="center"><br>关系<br><br></td>
			    <td align="center"><br>姓名 <br><br> </td>                 
			    <td colspan="2" align="center"><br>身份证号码<br><br> </td>                    
			    <td align="center"><br>备注<br><br> </td>
			    </tr>
			    <?php if($members) {?>
			    <?php foreach ($members as $member) {?>
			  	<tr>
			    <td align="center"><br><?= $member['relationship']?><br><br></td>
			    <td align="center"><br><?= $member['membername']?><br><br></td>                        
			    <td colspan="2" align="center"><br><?= $member['cardid']?><br><br></td>                
			    <td align="center"><br><?= $member['remarks']?><br><br></td>
			    </tr>
			    <?php }}?>
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
		LODOP.SET_PRINT_MODE("PRINT_PAGE_PERCENT","Auto-Width");	
		LODOP.PREVIEW();	
	};		
	
	function CreateOnePage(){
		LODOP=getLodop();  
		LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_整页缩放打印输出");
		strCenterStyle="<style/>form {text-align: center}</style>"; 
		LODOP.ADD_PRINT_HTM("25mm","20mm","RightMargin:0mm","BottomMargin:9mm",strCenterStyle+document.getElementById("ttpoprint").innerHTML); //上下边距9mm，左右边距0mm
		//LODOP.ADD_PRINT_HTM("14%","10%","100%","90%","<body leftmargin=0 topmargin=0>"+document.getElementById('ttpoprint').innerHTML+"</body>");
		LODOP.SET_PRINT_STYLEA(0,"Horient",2);        
		LODOP.SET_PRINT_STYLEA(0,"Vorient",2);
		LODOP.SET_PREVIEW_WINDOW(0,0,0,0,0,"");	
	};	
</script>