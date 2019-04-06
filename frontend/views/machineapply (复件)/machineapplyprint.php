<?php
namespace frontend\controllers;
use app\models\Farmerinfo;
use app\models\Insurancecompany;
use app\models\Machineapply;
use app\models\Machinescanning;
use frontend\helpers\cardidClass;
use frontend\helpers\datetozhongwen;
use frontend\helpers\imageClass;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Farms;
use app\models\Insurancedck;
use app\models\User;
use app\models\ManagementArea;
use yii\helpers\Url;

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
        <?php if($model->state == 1) {?>
    	   	&nbsp;&nbsp;<?= Html::Button('打印申请表', ['class' => 'btn btn-primary','onclick'=>'prn_preview4()']) ?>
			&nbsp;&nbsp;<?= Html::Button('打印相关材料', ['class' => 'btn btn-primary','onclick'=>'prn_preview5()']) ?>
			&nbsp;&nbsp;<?= Html::a('扫描相关证件',Url::to(['photograph/photographmachine','apply_id'=>$model->id]),['class' => 'btn btn-success']);?>
			&nbsp;&nbsp;<?= Html::a('撤销', Url::to(['machineapply/machineapplycacle', 'id' => $model->id]), ['class' => 'btn btn-danger','data' => [
				'confirm' => '是否确认撤销？',
				'method' => 'post',
			],]);?>
		<?php }?>
			
    	   	<?= html::a('返回',\Yii::$app->request->getReferrer(),['class'=>'btn btn-primary'])?>
    	</div>
			
			
<!--             <div class="box"> -->

<!--                 <div class="box-body"> -->
    <div class="col-md-6" id="ttpoprint">
<style type="text/css">
.ttpoprint {
	font-family: "仿宋";
	font-size:20px;
	height:"600px";
}
.tablehead{ width:100%; height:30px; line-height:20px; text-align:center; float:left; font-size:40px; font-family:"黑体"}
.tablehead2{ width:100%; height:30px; line-height:20px; text-align:left; float:left; font-size:20px; font-family:"黑体"}
</style>
<br>
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
				<br>
              <div class="tablehead"><?= date('Y')?>年农业机械购置补贴申请表</div>
				<br>
            </div>
            <br>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="tablehead2">岭南管委会&nbsp;——&nbsp;<span style="font-size: 15px"><?= ManagementArea::getAreanameOne($model->management_area)?></span></div>
             <table width="100%"  border="1" cellpadding="0" cellspacing="0" class="ttpoprint"">
			  <tr height="100px">
			    <td width="10%" align="center">姓名</td>
			    <td  align="center"><?= $model->farmername?></td>
			    <td align="center" width="14%" >年龄</td>
			    <td align="center" width="14%" ><?= $model->age?></td>
			    <td width="14%" colspan="2" align="center">性别</td>
			    <td align="center" colspan="2"><?php if($model->sex) echo $model->sex; else echo cardidClass::get_sex($model->cardid);?></td>
			    </tr>
			  <tr height="100px">
			    <td align="center" width="17%">户籍所在地<br>(身份证地址)</td>
			    <td colspan="9" align="left">&nbsp;&nbsp;<?= $model->domicile?></td>
			    </tr>
			  <tr height="100px">
			    <td align="center">身份证号</td>
			    <td align="center" colspan="2"><?= $model->cardid?></td>
			    <td align="center">联系电话</td>
			    <td align="center" colspan="6"><?= $model->telephone?></td>
			  </tr>
				 <tr height="100px">
					 <td align="center">机具名称</td>
					 <td align="center" colspan="2"><?php if(empty($machine->filename)) echo $machine->productname;else echo $machine->filename;?></td>
					 <td align="center">生产厂家</td>
					 <td align="center" colspan="6"><?= $machine->enterprisename?></td>
				 </tr>
				 <tr height="100px">
					 <td align="center">规格型号</td>
					 <td align="center" colspan="2"><?= $machine->implementmodel?></td>
					 <td align="center">补贴额度(元)</td>
					 <td align="center" colspan="6"><?= $model->subsidymoney?></td>
				 </tr>
			  <tr height="200px">
			    <td align="center">申请人签字<br>(购机者)</td>
			    <td colspan="9" align="left" valign="top">
			  </tr>
				 <tr height="100px">
					 <td align="center">地产组签字</td>
					 <td colspan="9" align="left" valign="top">
				 </tr>
				<tr height="100px">
					<td align="center">服务大厅签字</td>
					<td colspan="9" align="left" valign="top">
				</tr>
				<tr height="100px">
					<td align="center">地产科签字</td>
					<td colspan="9" align="left" valign="top">
				</tr>
				 <tr height="100px">
					 <td align="center">项目科签字</td>
					 <td colspan="9" align="left" valign="top">
				 </tr>
			</table>
				<span class="text text-left ttpoprint">购机者签字前确认知晓补贴政策,并已仔细阅读政策告知确认表。</span>
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
<?php
$farmerinfo = Farmerinfo::find()->where(['cardid'=>$model->cardid])->one();
$scans = Machinescanning::find()->where(['cardid'=>$model->cardid])->all();
?>
<script language="javascript" type="text/javascript">
    var LODOP; //声明为全局变量 
	
	function prn_preview4() {	
		CreateOnePage();	
		LODOP.SET_PRINT_MODE("PRINT_PAGE_PERCENT","Full-Page");
		LODOP.PREVIEW();
	};

	function prn_preview5() {
		CreateMachinePage();
//		LODOP.SET_PRINT_MODE("PRINT_PAGE_PERCENT","Full-Page");
		LODOP.PREVIEW();
	};

	function sj()
	{
		LODOP=getLodop();
		LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_打印图片2");
		LODOP.SET_PRINT_PAGESIZE(2,0,0,"A4");
		LODOP.PRINT_DESIGN();
	}

	function CreateOnePage(){
		LODOP=getLodop();  
		LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_整页缩放打印输出");
		strCenterStyle="<style>#ttpoprint {text-align: center}</style>"; 
		LODOP.ADD_PRINT_HTM("3%","5%","100%","100%","<body leftmargin=20 topmargin=0>"+document.getElementById('ttpoprint').innerHTML+"</body>");
		LODOP.SET_PRINT_STYLEA(0,"Horient",3);        
		LODOP.SET_PRINT_STYLEA(0,"Vorient",3);
		LODOP.SET_PREVIEW_WINDOW(0,0,0,0,0,"");	
	};

	function CreateMachinePage() {
		LODOP=getLodop();
		LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_打印图片2");
//		LODOP.NewPage();
		LODOP.ADD_PRINT_IMAGE(120,130,200,200,"<img border='1' src='http://192.168.1.10/<?= $farmerinfo['photo']?>'/>");
		LODOP.SET_PRINT_STYLEA(0,"Stretch",2);
		LODOP.ADD_PRINT_IMAGE(427,130,327,327,"<img border='1' src='http://192.168.1.10/<?= $farmerinfo['cardpic']?>' />");
		LODOP.SET_PRINT_STYLEA(0,"Stretch",2);
		LODOP.ADD_PRINT_IMAGE(800,130,327,327,"<img border='1' src='http://192.168.1.10/<?= $farmerinfo['cardpicback']?>' />");
		LODOP.SET_PRINT_STYLEA(0,"Stretch",2);//(可变形)扩展缩放模式
		<?php
			foreach($scans as $img) {
			$imginfo = imageClass::getImageInfo($img['scanimage']);
			$width = $imginfo['width']*0.25;
			$height = $imginfo['height']*0.25;
			if($width < 800) {
				$left = (800 - $width) / 2;
			} else {
				$left = 0;
			}
			if($height < 1000) {
				$top = (1000 - $height) / 2;
			} else {
				$top = 0;
			}
		?>
		LODOP.NewPage();
		LODOP.ADD_PRINT_IMAGE(<?= $top?>,<?= $left?>,'<?= $width?>','<?= $height?>',"<img border='1' src='http://192.168.1.10/<?= $img['scanimage']?>' width='<?= $width?>' height='<?= $height?>'/>");
		LODOP.SET_PRINT_STYLEA(0,"Stretch",2);
		<?php }?>

	};
</script>