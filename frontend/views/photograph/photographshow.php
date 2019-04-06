<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\ManagementArea;

use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
?>


<div class="photograph-form">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

<form method="POST" action="--WEBBOT-SELF--" name="form1">
  <input type="button" value="启动主" name="StartBtn" onClick="Start1_onclick()">
  <input type="button" value="启动副" name="StopBtn" onClick="Start2_onclick()">
  <input type="button" value="停止" name="StopBtn" onClick="Stop_onclick()">
  <input type="button" value="设置切黑边" name="SaveTIFBtn" onClick="CutHB_onclick()">
  <input type="button" value="设置矫正" name="SaveTIFBtn" onClick="Skew_onclick()">

  <input type="button" value="删除JPG" name="DeleteJPGBtn" onClick="DeleteJPG_onclick()">
  <input type="button" value="上传JPG" name="UpLoadBtn" onClick="UpLoadJPG_onclick()">
  
  </p>
  亮度:<input type="text" value="20" style="width:30px;" id="BrightnessValue"/>
  <input type="button" value="设置"  onclick="SetBrightness_onclick()">
  对比度:<input type="text" value="20" style="width:30px;" id="ContrastValue"/>
  <input type="button" value="设置" onClick="SetContrast_onclick()"> 
  曝光度:
  <select id="ExposureValue" onChange="SelectExposure(this)">
	<option value="0">0</option>
	<option value="10">10</option>
	<option value="20">20</option>
	<option value="30">30</option>
	<option value="40">40</option>
	<option value="50" selected="selected">50</option>
	<option value="60">60</option>
	<option value="70">70</option>
	<option value="80">80</option>
	<option value="90">90</option>
	<option value="100">100</option>
  </select>
  
  <input type="button" value="主旋" onClick="rotateMain()">
  
  <input type="button" value="硬件ID" onClick="getDeviceId()">
    
  <input type="radio" value="3" name="mode" id="autoMode" onClick="selectAutoMode(this)"/><label for="autoMode">自动</label>
  <input type="radio" value="0" name="mode" id="defaultMode" onClick="selectDefaultMode(this)"/><label for="autoMode">默认</label>
  <input type="radio" value="4" name="mode" id="sfzMode" onClick="selectSfzMode(this)"/><label for="autoMode">证件</label>
  <input type="radio" value="1" name="mode" id="customMode" onClick="selectSfzMode(this)"/><label for="autoMode">自定义</label>
<p>

</form>
<div style="text-align:center;" >
<object classid="clsid:454C18E2-8B7D-43C6-8C17-B1825B49D7DE" id="captrue"  width="480" height="360" ></object>
</div>
                 </div>
            </div>
        </div>
    </div>
</section>
</div>
<script language="JavaScript1.2">
  function Start1_onclick()
  {
        var str=captrue.bStopPlay();  	
        var str = captrue.bStartPlay();
  }
  function Start2_onclick()
  {
	  var str=captrue.bStopPlay();
	  var str = captrue.bStartPlay2(0);
  }
  function Stop_onclick()
  {
  	var str=captrue.bStopPlay();
  }

	function SaveJPG_onclick()
	{
        var str=captrue.bSaveJPG("d:\\","JPG");
	}
	
	function UpLoadJPG_onclick()
	{
		var str=captrue.bUpLoadImage("D:\\wamp\\www\\landsystem\\frontend\\web", "127.0.0.1", 8080, "/shop/servlet/uploadServlet");
	}
	
	function DeleteJPG_onclick()
	{
	 
          var str=captrue.bDeleteFile("D:\\JPG.jpg");
	}
		
	function SaveGray_onclick()
	{
		captrue.vSetRotate(90);
		var str=captrue.bSaveGray("D:\\","gray");
	
	}
	function SaveTifJPG_onclick()
	{
		captrue.vSetRotate(180);
		var str=captrue.bSaveTifJPG("D:\\","tifJPG");
	}
	function SaveTIF_onclick()
	{
		captrue.vSetRotate(270);
		captrue.vSetDPI(200,200);
		captrue.vSetTIFPara (0.3,0.59,0.11,0.2);
		var str=captrue.bSaveTIF24Bit("D:\\","TIF", 0);
	}
	
	function SaveMulTIF_onclick()
	{
		captrue.vSetDPI(200,200);
		var str=captrue.bSaveTIF24Bit("D:\\","MulTIF", 1);
	}
	function ParaSet_onclick()
	{
		var str=captrue.displayVideoPara();
	}
	function ParaSetPIN_onclick()
	{
		var str=captrue.vSetCapturePin();
  		captrue.bStartPlay();
	}
	function CutHB_onclick()
	{
		var str=captrue.vSetDelHBFlag(1);
	}
	function Skew_onclick()
	{
		var str=captrue.vSetSkewFlag(1);
	}
	function StartPDF_onclick()
	{
		var pdffileName = document.getElementById("pdffileName").value;
		if(pdffileName == "")
		{
			pdffileName =  "pdffile";
		}
		var str=captrue.bSavePDFStart("D:\\", pdffileName);
	}
	function ColorPDF_onclick()
	{
		var str=captrue.bSavePDFColorPage();
	}
	function BWPDF_onclick()
	{
		var str=captrue.bSavePDFBWPage();
	}
	
	function EndPDF_onclick()
	{
		var str=captrue.bSavePDFEnd();
	}	 
	
	function SetBrightness_onclick(){
		var BrightnessValue = document.getElementById("BrightnessValue").value;
		captrue.vSetBrightness(BrightnessValue);
	}
	
	function SetContrast_onclick(){
		var ContrastValue = document.getElementById("ContrastValue").value;
		captrue.vSetContrast(ContrastValue);
	}
	
	function rotateMain(){
		  var str=captrue.bStopPlay();  	
        var str = captrue.bStartPlayRotate(270);
		
	}
	
	function getDeviceId()
	{
		var deviceId = captrue.sGetDevicesId();
		alert(deviceId);
	}
	
	function selectAutoMode(el)
	{
		captrue.bSetMode(el.value);
	}
	
	function selectDefaultMode(el)
	{
		captrue.bSetMode(el.value);
	}
      
	function selectSfzMode(el)
	{
		captrue.bSetMode(el.value);
		if(el.value == 1)
		{
			captrue.bSetImageArea(1000,1000,8000,8000);
		}
	}	
	
	function SelectExposure(el)
	{
		captrue.vSetExposure(el.value);
	}

</script>

