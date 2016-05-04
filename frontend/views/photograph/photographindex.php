<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\ManagementArea;
use frontend\helpers\imageClass;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\helpers\Url;
?>
<link rel="stylesheet" href="css/imageShow/lrtk.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.imgbox.pack.js"></script>

<div class="photograph-form">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                       <?= $farm->farmname.'-'.$farm->farmername?>
                    </h3>
                </div>
                <div class="box-body">

<table>
  <tr>
	    <td valign="top"> 
	    <?= html::dropDownList('select',$select,$selectItem,['class'=>'form-control','id'=>'selectID'])?>
	    </td>
	    <td>&nbsp;&nbsp;</td>
	  <td  valign="top">
	  <form method="POST" action="--WEBBOT-SELF--" name="form1">
	      <input type="button" value="启动主" name="StartBtn" onClick="Start1_onclick()">
	      <input type="button" value="启动副" name="StopBtn" onClick="Start2_onclick()">
	      <input type="button" value="停止" name="StopBtn" onClick="Stop_onclick()">
	      <input type="button" value="设置切黑边" name="SaveTIFBtn" onClick="CutHB_onclick()">
	      <input type="button" value="设置矫正" name="SaveTIFBtn" onClick="Skew_onclick()">
	      
	      <input type="button" value="删除JPG" name="DeleteJPGBtn" onClick="DeleteJPG_onclick()">
	      <input type="button" value="上传JPG" name="UpLoadBtn" onClick="UpLoadJPG_onclick()">
	      
	      亮度:<input type="text" value="20" style="width:30px;" id="BrightnessValue"/>
	      <input type="button" value="设置"  onclick="SetBrightness_onclick()">
	      
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
	      <input type="radio" value="3" name="mode" id="autoMode" onClick="selectAutoMode(this)"/>
	      <label for="autoMode">自动</label>
	      <input type="radio" value="1" name="mode" id="customMode" onClick="selectSfzMode(this)"/><label for="autoMode">自定义</label>
	      <?= Html::textInput('checkboxValue',0,['id'=>'electronic-id'])?>
	      </form>
	    <br>
<div id="content">
	    <?php 
// 	    var_dump($ea);
	    echo '<table id="imageTable" border="1">';
	    
// 	    var_dump($select);
	    	if($select == 'electronicarchives-archivesimage') {
	    		
		    	if($ea) {
					$n = 0;
		    		for($i=0;$i<count($ea);$i++) {
		    			$m=$i*2;
		    			echo '<tr id="imageTr'.$m.'">';
		    			for($j=0;$j<count($ea[$i]);$j++) {
		    				$imageInfo = imageClass::getImageInfo( iconv("UTF-8","gbk//TRANSLIT", $ea[$i][$j]['archivesimage']));
		    				$width = floor($imageInfo['width']/15);
		    				$height = floor($imageInfo['height']/15);
	// 	    				var_dump($imageInfo);
		    				echo '<td>';
		    				echo '<a class="zoom" title="" href="'.$ea[$i][$j]['archivesimage'].'" onclick="Zoom("image'.$ea[$i][$j]['pagenumber'].'")"><img alt="" src="'.$ea[$i][$j]['archivesimage'].'" width="'.$width.'"/></a>';
// 		    				echo '<div><p>&nbsp;</p></div>';
		    				echo '</td>';
		    			}
		    			echo '</tr>';
		    			$m= $m + 1;
		    			$n=$m;
		    			echo '<tr id="imageTr'.$m.'">';
		    			for($j=0;$j<count($ea[$i]);$j++) {
		    				echo '<td align="center">';
			    			//echo Html::a('<i class="fa fa-close text-red"></i>',Url::to(['electronicarchives/electronicarchivesdelete','id'=>$ea[$i][$j]['id']]));
			    			echo '</td>';
		    			}
		    			echo '</tr>';
		    		}
		    	}	    		
	    	} else { 
	    		echo '<tr>';
	    		echo '<td>';
	    		echo '&nbsp;'.Html::img($photo,['width'=>$photoInfo['width'],'height'=>$photoInfo['height'],'id'=>'photoShow']); 
	    		echo '</td>';
	    		echo '</tr>';
	    	}	    	
	    	echo '</table>';
	    ?></td>
	  <td>&nbsp;</td>
	  <td><div style="text-align:center;" >
	    <object classid="clsid:454C18E2-8B7D-43C6-8C17-B1825B49D7DE" id="captrue"  width="480" height="360" >
	      </object>
	  </div></td>
	  </tr>
	</table>
		
		<div id="credit"></div>
</div>
<!-- 代码 结束 -->
<br>


              </div>
            </div>
        </div>
    </div>
</section>
</div>

<script>
jQuery('#selectID').change(function(){
    var input = $(this).val();
    $.get("/landsystem/frontend/web/index.php?r=photograph/photographindex",{farms_id:<?= $_GET['farms_id']?>,select:input},function (data) {
		$('body').html(data);
	});
});
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
        var str=captrue.bSaveJPG("d:\\123\\","JPG");
	}

	function checkBoxclick(eid)
	{
		$("#electronic-id").val(eid);
	}
	
	function UpLoadJPG_onclick()
	{
		var save = captrue.bSaveJPG("d:\\","JPG");
		if(save) {
			eid = $('#electronic-id').val();
	        $.getJSON('index.php?r=photogallery/photograph', {farms_id: <?= $_GET['farms_id']?>,select:$('#selectID').val(),eid:eid}, function (data) {
// 	           	alert(data.url);
				var select = $('#selectID').val();
				if(select == 'electronicarchives-archivesimage') {
					var width = Math.floor(data.info['width']/15);
					var height = Math.floor(data.info['height']/15);
// 					alert(data.page);
					if(data.page % 4 == 0)
						var tr = Math.floor(data.page/5)*2;
					else						
						var tr = Math.floor(data.page / 4) * 2;
					var tr1 = tr;
					var tr2 = tr*1 + 1;
// alert(tr);
					if(data.page % 4 == 1) {
						
						$('#imageTable').each(function(){
							var html = '<tr id="imageTr'+tr1+'"></tr><tr id="imageTr'+tr2+'"></tr>';
							$(this).append(html);
						});
					} 
					if(data.page == 1) {
						$('#imageTable').each(function(){
							var html = '<tr id="imageTr'+tr1+'"></tr><tr id="imageTr'+tr2+'"></tr>';
							$(this).append(html);
						});
					}
// 					alert(eid);
					if(eid == '0') {
// 						alert('fff');
						$('#imageTr'+tr1).each(function(){
// 							alert(data.url);
							var html = '<td id="td-'+tr1+'-'+data.id+'">&nbsp;<img src="'+data.url+'" width='+width+' height='+height+' id="img'+data.id+'"></td>';
							$(this).append(html);
						});
						$('#imageTr'+tr2).each(function(){
// 							alert(tr2);
							var html2 = '<td align="center" id="td-'+tr2+'-'+data.id+'"><a href="#" onclick="deleteImg('+data.id+','+tr1+','+tr2+')" id="delete'+data.id+'"><i class="fa fa-close text-red" id="cha'+data.id+'"></i></a>&nbsp;&nbsp;<label><input type="radio" name="radiogroup" id="imgCheckbox'+data.id+'" onclick="checkBoxclick('+data.id+')"/>重新上传</label></td>';
							$(this).append(html2);
						});	
						alert(data.id);
						var deleteid = data.id - 1;						
						$('#cha'+deleteid).attr('class','');
					} else {
						alert(data.id);
						$('#img'+data.id).attr('src',data.url);
						$('#img'+data.id).attr('width',width);
			        	$('#img'+data.id).attr('height',height);	
			        	$('#electronic-id').val(0);
			        	$('#imgCheckbox'+data.id).attr("checked",false);
					}
					
					
				} else {
					var width = Math.floor(data.info['width']/10);
					var height = Math.floor(data.info['height']/10);
		        	$('#photoShow').attr('src', data.url);
		        	$('#photoShow').attr('width',width);
		        	$('#photoShow').attr('height',height);
				}
				captrue.bDeleteFile("d:\\JPG.jpg");       			
	        });
		}
	}

	function deleteImg(id,tr1,tr2)
	{
		 $.getJSON('index.php?r=electronicarchives/electronicarchivesdelete', {id: id}, function (data) {
			$('#img'+data.id).remove();
			$('#td-'+tr1+'-'+data.id).remove();
			$('#td-'+tr2+'-'+data.id).remove();
			var addid = id - 1;
			$('#cha'+addid).attr('class','fa fa-close text-red');
		});
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
<script type="text/javascript">
// 		function Zoom() {
// 			$(document).ready(function() {
				$(".zoom").imgbox();
// 			});
// 		}
	</script>