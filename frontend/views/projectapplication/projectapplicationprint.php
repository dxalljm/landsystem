<?php
namespace frontend\controllers;use app\models\User;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>

<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
       <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
        <div class="form-group">
    	   	&nbsp;&nbsp;<?= Html::Button('打印', ['class' => 'btn btn-primary','onclick'=>'prn_preview4()']) ?> 			
    	</div>
<!--             <div class="box"> -->

<!--                 <div class="box-body"> -->
    <div class="col-md-6" id="ttpoprint">
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:宋体;
	panose-1:2 1 6 0 3 1 1 1 1 1;}
@font-face
	{font-family:宋体;
	panose-1:2 1 6 0 3 1 1 1 1 1;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
@font-face
	{font-family:仿宋;
	panose-1:2 1 6 9 6 1 1 1 1 1;}
@font-face
	{font-family:"\@宋体";
	panose-1:2 1 6 0 3 1 1 1 1 1;}
@font-face
	{font-family:"\@仿宋";
	panose-1:2 1 6 9 6 1 1 1 1 1;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	text-align:justify;
	text-justify:inter-ideograph;
	font-size:10.5pt;
	font-family:"Calibri","sans-serif";}
.MsoChpDefault
	{font-family:"Calibri","sans-serif";}
 /* Page Definitions */
 @page WordSection1
	{size:595.3pt 841.9pt;
	margin:72.0pt 90.0pt 72.0pt 90.0pt;
	layout-grid:15.6pt;}
div.WordSection1
	{page:WordSection1;}
-->
</style>    
<!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <!-- /.user-block -->
             
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<div class=WordSection1 style='layout-grid:15.6pt'>

<p class=MsoNormal align=center style='text-align:center'><b><span
style='font-size:30.0pt;font-family:宋体'><?= $projecttypename?>建设申请</span></b></p>

<p class=MsoNormal><span lang=EN-US>&nbsp;</span></p>
<p class=MsoNormal><span lang=EN-US>&nbsp;</span></p>
<p class=MsoNormal style='text-indent:46.0pt;line-height:30.0pt'><span
style='font-size:22.0pt;font-family:仿宋;letter-spacing:0pt'>我是<?= $farm->farmername?>，农场法人<?= $farm->farmername?>，农场位于<?= $farm->address?>。</span></p><p class=MsoNormal style='text-indent:46.0pt;line-height:30.0pt'><span
style='font-size:22.0pt;font-family:仿宋;letter-spacing:0pt'>我申请<?= $model->content.$model->projectdata.$model->unit?>，<?php if($projecttypename == 13)?>我承诺严格按森林防火及林政部门要求建房，不非法占用林地及破坏森林资源，如违反自愿承担一节法律责任和经济责任。</span></p>

<p class=MsoNormal style='line-height:50.0pt'><span style='font-size:22.0pt;
font-family:仿宋;letter-spacing:0pt'>房舍坐标点：<?= $farm->longitude.'&nbsp;&nbsp;&nbsp;'.$farm->latitude?></span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal style='text-indent:32.0pt'><span style='font-size:22.0pt;
font-family:仿宋'>特此申请</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal align=right style='text-align:right'><span style='font-size:22.0pt;font-family:仿宋'>申请人：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
<p class=MsoNormal><span lang=EN-US>&nbsp;</span></p>
<p class=MsoNormal align=right style='text-align:right'><span style='font-size:22.0pt;font-family:仿宋'><?= date('Y年m月d日',$model->create_at)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>

</div>
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
// 		LODOP.ADD_PRINT_HTM("14%","10%","100%","90%","<body leftmargin=0 topmargin=0>"+document.getElementById('ttpoprint').innerHTML+"</body>");
		LODOP.SET_PRINT_STYLEA(0,"Horient",2);        
		LODOP.SET_PREVIEW_WINDOW(0,0,0,0,0,"");	
	};	
</script>