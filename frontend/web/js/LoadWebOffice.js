var s = "";
if(navigator.userAgent.indexOf("MSIE")>0){
	s = "<OBJECT id='WebOffice1' align='middle' style='LEFT: 0px; WIDTH: 100%; TOP: 0px; HEIGHT:100%'"
		+ "classid=clsid:E77E049B-23FC-4DB8-B756-60529A35FAD5 codebase='WebOffice.cab#Version=7,0,1,0'>"
		+ "<PARAM NAME='_ExtentX' VALUE='6350'>"
		+ "<PARAM NAME='_ExtentY' VALUE='6350'>"
		+ "</OBJECT>";
}

if(navigator.userAgent.indexOf("Chrome")>0){
	s = "<object id='WebOffice1' type='application/x-itst-activex' align='baseline' border='0'"
		+ "style='LEFT: 0px; WIDTH: 100%; TOP: 0px; HEIGHT: 100%'"
		+ "clsid='{E77E049B-23FC-4DB8-B756-60529A35FAD5}'"
		+ "event_NotifyCtrlReady='WebOffice1_NotifyCtrlReady'>"
		+ "</object>";	
}

if(navigator.userAgent.indexOf("Firefox")>0){
	s = "<object id='WebOffice1' type='application/x-itst-activex' align='baseline' border='0'"
		+ "style='LEFT: 0px; WIDTH: 100%; TOP: 0px; HEIGHT: 100%'" 
		+ "clsid='{E77E049B-23FC-4DB8-B756-60529A35FAD5}'"
		+ "event_NotifyCtrlReady='WebOffice1_NotifyCtrlReady'>"
		+ "</object>";	
}
document.write(s) 