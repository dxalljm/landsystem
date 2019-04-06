<?php
namespace frontend\controllers;
use app\models\Insurancecompany;
use frontend\helpers\datetozhongwen;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Farms;
use app\models\Insurancedck;
use app\models\User;
use app\models\ManagementArea;
use Yii;
use app\models\Department;
/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>

<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
       <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<div class="reviewprocess-form">
    
    
    <section class="content">
    <div class="row">
        <div class="col-xs-12">
			
        <div class="form-group">
        <?php if(User::getItemname('地产科') or User::getItemname('服务大厅')) {?>
    	   	&nbsp;&nbsp;<?= Html::Button('打印', ['class' => 'btn btn-primary','onclick'=>'prn_preview4()']) ?><?php }?>
    	   	<?= html::a('返回',\Yii::$app->request->getReferrer(),['class'=>'btn btn-primary'])?>
    	</div>
			
			
<!--             <div class="box"> -->

<!--                 <div class="box-body"> -->
    <div class="col-md-6" id="ttpoprint">
<style type="text/css">
.ttpoprint {
	font-family: "仿宋";
	font-size:15px;
	height:"600px";
}
.tablehead{ width:100%; height:30px; line-height:20px; text-align:center; float:left; font-size:30px; font-family:"黑体"}
</style>
<br>
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="tablehead"><?= date('Y')?>年种植业保险申请书</div>
            </div>
            <br>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="box-header">岭南生态农业示范区：<?= ManagementArea::getAreaname(Department::find()->where(['id'=>Yii::$app->user->identity->department_id])->one()['membership'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;单位：亩</div>
             <table width="100%" border="1" cellpadding="0" cellspacing="0" class="ttpoprint">
			  <tr height="30px">
			    <td width="10%" align="center">农场名称</td>
			    <td colspan="3" align="center"></td>
			    <td align="center">法人<br>
			      姓名</td>
			    <td colspan="2" align="center"></td>
			    <td width="8%" align="center">合同<br>
			      编号</td>
			    <td colspan="3" align="center"></td>
			    </tr>
			  <tr height="30px">
			    <td align="center">被保险人<br>
			      姓&nbsp;&nbsp;&nbsp;名</td>
			    <td colspan="2" align="center"></td>
			    <td align="center">身份证<br>
			      号&nbsp;码</td>
			    <td colspan="4" align="center"></td>
			    <td width="8%" align="center">联系<br>
			      电话</td>
			    <td colspan="2" align="center"></td>
			    </tr>
			  <tr height="30px">
			    <td rowspan="2" align="center">宜农林地<br>
			      面&nbsp;&nbsp;&nbsp;积</td>
			    <td width="9%" rowspan="2" align="center"></td>
			    <td width="9%" rowspan="2" align="center">种植<br>
			      结构</td>
			    <td width="9%" align="center">小麦</td>
			    <td width="9%" align="center">大豆</td>
			    <td width="9%" align="center">其它</td>
			    <td width="9%" rowspan="2" align="center">保险<br>
			      面积</td>
			    <td width="9%" rowspan="2" align="center"></td>
			    <td width="9%" align="center">小麦</td>
			    <td width="9%" align="center">大豆</td>
			    <td width="9%"  align="center">其它</td>
			    </tr>
			  <tr height="30px">
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			    </tr>
			   
			  <tr height="30px">
			    <td align="center"><p><br>
			      <br>
			      被<br>
			      保<br>
			      险<br>
			      人<br>
			      承<br>
			      诺</p>
			      <p><br>
			        <br>
		          </p></td>
			    <td colspan="10" align="left" valign="top">
			      <p><br>
			        &nbsp;&nbsp;&nbsp;&nbsp;我是岭南生态农业示范区<u><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></u> ，自愿参加<?= date('Y')?>年种植业保险，遵守农业保险相关法律、法规和政策性文件，自愿缴纳保费，履行保险协议相关义务，自愿选择保险公司为<u><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></u> 。本人保证参保作物品种、种植面积是本人种植和真实有效的，如出现虚假行为由本人承担一切责任。</p>
			        
			        </p><br>
			      <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;农场名称（盖章）：</p><br>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;投保人姓名（签字）：</p><br>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= date('Y年m月d日')?></p><br>
				</td>
			      </tr>
			     
			  <tr>
			  	<td height="30" align="center"><p>&nbsp;</p>
			  	  <p>管<br>
			  	    会<br>
			  	    职<br>
			  	    能<br>
			  	    科<br>
			  	    室<br>
			  	    意<br>
		  	      见</p>
			  	  <p>&nbsp;</p></td>
			  	<td colspan="10" align="left" valign="top"><p><br><br>
			  	  &nbsp;&nbsp;&nbsp;&nbsp;地产工作组意见：</p>
					<table border="0" width="100%">
						<tr>
							<td width="10%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td align="left">&nbsp;&nbsp;申报面积与保险面积一致</td>
						</tr>
						<tr>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td align="left">&nbsp;&nbsp;本人选择承保公司</td>
						</tr>
					</table>
			  	  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;负责人（签字）：</p>
			  	  <p><br>
					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			  	    <?= date('Y年m月d日')?>
			  	  </p>
			  	  <p>&nbsp;&nbsp;&nbsp;&nbsp;服务大厅意见：</p>
					<table border="0" width="100%">
						<tr>
							<td width="10%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td align="left">&nbsp;&nbsp;申报面积与保险面积一致</td>
						</tr>
						<tr>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td align="left">&nbsp;&nbsp;本人选择承保公司</td>
						</tr>
					</table>
			  	  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;负责人（签字）：</p>
			  	  <p><br>
					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			  	    <?= date('Y年m月d日')?>
			  	  </p><br></td>
			  	</tr>
			  <tr>
			  	<td height="30" align="center"><p>&nbsp;</p>
			  	  <p>备</p>
			  	  <p>注</p>
			  	  <p>&nbsp;</p></td>
			  	<td colspan="10" align="center"></td>
			  	</tr>
			</table>
			<br>
			
            </div>
            <!-- /.box-body -->
           
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

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
		LODOP.ADD_PRINT_HTM("5%","5%","100%","100%","<body leftmargin=20 topmargin=0>"+document.getElementById('ttpoprint').innerHTML+"</body>");
		LODOP.SET_PRINT_STYLEA(0,"Horient",3);        
		LODOP.SET_PRINT_STYLEA(0,"Vorient",3);
		LODOP.SET_PREVIEW_WINDOW(0,0,0,0,0,"");	
	};	
</script>