<?php

namespace frontend\helpers;
use yii\helpers\Html;
class photographDialog
{
	
	public static function showDialog($buttonName,$dialogID = 'dialog')
	{
	
		$html .= Html::button($buttonName,['class'=>'btn btn-success fileinput-button','id'=>$dialogID.'-link']);
		return $html;
	}	
	public static function showDialogClass($buttonName,$dialogClass = 'dialog',$farms_id=null)
	{
	
		// 		self::dialogHtml($dialogID);
		
		$html = Html::button($buttonName,['class'=>'btn btn-success fileinput-button','onclick'=>"openDialog(".$farms_id.")"]);
		return $html;
	}
	public static function dialogHtml($state = false)
	{
// 		echo Html::jsFile('js/photographDialog.js');
		echo '<div id="dialog">';
		echo '<form method="POST" action="--WEBBOT-SELF--" name="form1">';
	    echo '<input type="button" value="启动主" name="StartBtn" onClick="Start1_onclick()">';
	    echo '<input type="button" value="启动副" name="StopBtn" onClick="Start2_onclick()">';
	    echo '<input type="button" value="停止" name="StopBtn" onClick="Stop_onclick()">';
	    echo '<input type="button" value="设置切黑边" name="SaveTIFBtn" onClick="CutHB_onclick()">';
	    echo '<input type="button" value="设置矫正" name="SaveTIFBtn" onClick="Skew_onclick()">';
	    echo '<input type="button" value="删除JPG" name="DeleteJPGBtn" onClick="DeleteJPG_onclick()">';
	    echo '<input type="button" value="上传JPG" name="UpLoadBtn" onClick="UpLoadJPG_onclick()">';	      
	    echo '亮度:';
	    echo '<select id="BrightnessValue" onChange="SetBrightness(this)">';
	    echo '<option value="0" selected="selected">0</option>';
	    echo '<option value="10">10</option>';
	    echo '<option value="20">20</option>';
	    echo '<option value="30">30</option>';
	    echo '<option value="40">40</option>';
	    echo '<option value="50">50</option>';
	    echo '<option value="60">60</option>';
	    echo '<option value="70">70</option>';
	    echo '<option value="80">80</option>';
	    echo '<option value="90">90</option>';
	    echo '<option value="100">100</option>';
	    echo '</select>';
// 	    echo '<input type="button" value="设置"  onclick="SetBrightness_onclick()">';
	    echo '曝光度:';
	    echo '<select id="ExposureValue" onChange="SelectExposure(this)">';
	    echo '<option value="0">0</option>';
	    echo '<option value="10">10</option>';
	    echo '<option value="20">20</option>';
	    echo '<option value="30">30</option>';
	    echo '<option value="40">40</option>';
	    echo '<option value="50" selected="selected">50</option>';
	    echo '<option value="60">60</option>';
	    echo '<option value="70">70</option>';
	    echo '<option value="80">80</option>';
	    echo '<option value="90">90</option>';
	    echo '<option value="100">100</option>';
	    echo '</select>';
	    echo '<input type="button" value="主旋" onClick="rotateMain()">';
	    echo '<input type="button" value="硬件ID" onClick="getDeviceId()">';		    
	    echo '<input type="radio" value="3" name="mode" id="autoMode" onClick="selectAutoMode(this)"/>';
	    echo '<label for="autoMode">自动</label>';
	    echo '<input type="radio" value="1" name="mode" id="customMode" onClick="selectSfzMode(this)"/><label for="autoMode">自定义</label>';
	    
	    echo '</form>';
	    echo '<div style="text-align:center;" class="dialog_content">';
	    echo '<object classid="clsid:454C18E2-8B7D-43C6-8C17-B1825B49D7DE" id="captrue" width="600" height="450"></object>';
	    if($state) {
	    	echo Html::img('',['id'=>'showpic']);
	    }
    	echo '</div>';
		echo '</div>';
		echo html::hiddenInput('temp','',['id'=>'tempField']);
	}
}