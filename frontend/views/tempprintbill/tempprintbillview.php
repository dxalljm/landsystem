<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tempprintbill */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'tempprintbill'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['tempprintbillindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
       <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<div class="tempprintbill-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
<script language="javascript" type="text/javascript">   
        var LODOP; //声明为全局变量 
	function prn1_preview() {	
		CreateOneFormPage();	
		LODOP.PREVIEW();	
	};
	function prn1_print() {		
		CreateOneFormPage();
		LODOP.PRINT();	
	};
	function prn1_printA() {		
		CreateOneFormPage();
		LODOP.PRINTA(); 	
	};	
	function CreateOneFormPage(){
		LODOP=getLodop();  
		LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_表单一");
		LODOP.SET_PRINT_STYLE("FontSize",18);
		LODOP.SET_PRINT_STYLE("Bold",1);
		LODOP.ADD_PRINT_TEXT(50,231,260,39,"打印页面部分内容");
		LODOP.ADD_PRINT_HTM(88,200,350,600,document.getElementById("form1").innerHTML);
	};	                     
	function prn2_preview() {	
		CreateTwoFormPage();	
		LODOP.PREVIEW();	
	};
	function prn2_manage() {	
		CreateTwoFormPage();
		LODOP.PRINT_SETUP();	
	};	
	function CreateTwoFormPage(){
		LODOP=getLodop();  
		LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_表单二");
		LODOP.ADD_PRINT_RECT(70,27,634,242,0,1);
		LODOP.ADD_PRINT_TEXT(29,236,279,38,"页面内容改变布局打印");
		LODOP.SET_PRINT_STYLEA(2,"FontSize",18);
		LODOP.SET_PRINT_STYLEA(2,"Bold",1);
		LODOP.ADD_PRINT_HTM(88,40,321,185,document.getElementById("form1").innerHTML);
		LODOP.ADD_PRINT_HTM(87,355,285,187,document.getElementById("form2").innerHTML);
		LODOP.ADD_PRINT_TEXT(319,58,500,30,"注：其中《表单一》按显示大小，《表单二》在程序控制宽度(285px)内自适应调整");
	};              
	function prn3_preview(){
		LODOP=getLodop();  
		LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_全页");
		LODOP.ADD_PRINT_HTM(0,0,"100%","100%",document.documentElement.innerHTML);
		LODOP.PREVIEW();	
	};	
</script> 
                
                </div>
            </div>
        </div>
    </div>
</section>
</div>
