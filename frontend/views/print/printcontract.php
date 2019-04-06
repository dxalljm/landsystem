<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>承包合同</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">

<OBJECT id=WebOffice1 height="768" width="100%" style="LEFT: 0px; TOP: 0px" 
classid='clsid:E77E049B-23FC-4DB8-B756-60529A35FAD5' codebase='WebOffice.cab#Version=7,0,1,0'>
	<PARAM NAME="_ExtentX" VALUE="6350">
	<PARAM NAME="_ExtentY" VALUE="6350">
</OBJECT> 
<!-- --------------------=== 调用Weboffice初始化方法 ===--------------------- -->

<?php 
	$url = '/contract_file/'.$filename;
?>
<SCRIPT LANGUAGE=javascript FOR=WebOffice1 EVENT=NotifyCtrlReady>
<!--
 WebOffice1_NotifyCtrlReady()   ;//要执行的初始化方法      
//-->
 var webObj=document.getElementById("WebOffice1");

 webObj.HideMenuItem(0x01 + 0x02 + 0x20 +0x4000); //Hide it

 document.all.WebOffice1.HideMenuArea('hideall','','','');
 function WebOffice1_NotifyCtrlReady() {
	    document.all.WebOffice1.LoadOriginalFile("<?= $url?>", "doc");
	   
} 
 function zhiPrint(){
		try{
			var webObj=document.getElementById("WebOffice1");
			webObj.PrintDoc(0);
		}catch(e){
			alert("�쳣\r\nError:"+e+"\r\nError Code:"+e.number+"\r\nError Des:"+e.description);
		}
	}
</SCRIPT>
