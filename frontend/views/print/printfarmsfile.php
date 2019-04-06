<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>农场档案</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">

<OBJECT id=WebOffice1 height="768" width="100%" style="LEFT: 0px; TOP: 0px" 
classid='clsid:E77E049B-23FC-4DB8-B756-60529A35FAD5' codebase='WebOffice.cab#Version=7,0,1,0'>
	<PARAM NAME="_ExtentX" VALUE="6350">
	<PARAM NAME="_ExtentY" VALUE="6350">
</OBJECT> 
<!-- --------------------=== 调用Weboffice初始化方法 ===--------------------- -->

<?php 
	$url = '/farmsfile/'.$filename;
?>
<SCRIPT LANGUAGE=javascript FOR=WebOffice1 EVENT=NotifyCtrlReady>
<!--
 WebOffice1_NotifyCtrlReady()   ;//要执行的初始化方法      
//-->
 var webObj=document.getElementById("WebOffice1");

 webObj.HideMenuItem(0x01 + 0x02 + 0x20 +0x4000); //Hide it

 document.all.WebOffice1.HideMenuArea('hideall','','','');
 function WebOffice1_NotifyCtrlReady() {
	 	
	    $loadfile = document.all.WebOffice1.LoadOriginalFile("<?= $url?>", "doc");
	    document.all.WebOffice1.SetFieldValue("cardidpic","<?= 'http:://front.lngwh.gov:8001/landsystem/frontend/web/'.iconv("UTF-8","gbk//TRANSLIT", $cardpic)?>","::JPG::");
// 	    alert(strFieldValue);
	    //AddPicture('cardidpic','d:\\wamp\\www\\landsystem\\frontend\\web\\photo_gallery\\1455778879.jpg',5);
} 
 function AddPicture(strMarkName,strBmpPath,vType)
 {
 //定义一个对象，用来存储ActiveDocument对象
          var obj;
          obj = new Object(document.all.WebOffice1.GetDocumentObject());
          if(obj !=null){
                var pBookMarks;
 // VAB接口获取书签集合
                    pBookMarks = obj.Bookmarks;
                    var pBookM;
                    alert(pBookMarks);
 // VAB接口获取书签strMarkName
                    pBookM = pBookMarks(strMarkName);
                    var pRange;
 // VAB接口获取书签strMarkName的Range对象
                    pRange = pBookM.Range;
                    var pRangeInlines; 
 // VAB接口获取书签strMarkName的Range对象的InlineShapes对象
                    pRangeInlines = pRange.InlineShapes;
                    var pRangeInline; 
 // VAB接口通过InlineShapes对象向文档中插入图片
                    pRangeInline = pRangeInlines.AddPicture(strBmpPath);  
 //设置图片的样式，5为浮动在文字上面
                    pRangeInline.ConvertToShape().WrapFormat.TYPE = vType;
                    delete obj; 
    }
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
