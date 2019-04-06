<?php
namespace frontend\helpers;

class MacAddress
{
	var $return_array = array(); // 返回带有MAC地址的字串数组
	
	var $mac_addr;	
	 
	
	function getIP() /*获取客户端IP*/
	{
		if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else if (@$_SERVER["HTTP_CLIENT_IP"])
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		else if (@$_SERVER["REMOTE_ADDR"])
			$ip = $_SERVER["REMOTE_ADDR"];
		else if (@getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (@getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (@getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else
			$ip = "Unknown";
		return $ip;
	}
	
	function getMac($os_type = PHP_OS){
	
		switch ( strtolower($os_type) ){
	
			case "linux":
	
				$this->forLinux();
	
				break;
	
			case "solaris":
	
				break;
	
			case "unix":
	
				break;
	
			case "aix":
	
				break;
	
			default:
	
				$this->forWindows();
	
				break;
	
	
	
		}
	
		$temp_array = array();
	
		foreach ( $this->return_array as $value ){
	
			if (preg_match("/[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f]/i",$value,	$temp_array )) {
	
				$this->mac_addr = $temp_array[0];
	
				break;
	
			}
		}
	
		unset($temp_array);
	
		return $this->mac_addr;
	
	}
	
	
	
	
	
	function forWindows(){
	
		@exec("ipconfig /all", $this->return_array);
	
		if ( $this->return_array )
	
			return $this->return_array;
	
		else{
	
			$ipconfig = $_SERVER["WINDIR"]."\system32\ipconfig.exe";
	
			if ( is_file($ipconfig) )
	
				@exec($ipconfig." /all", $this->return_array);
	
			else
	
				@exec($_SERVER["WINDIR"]."\system\ipconfig.exe /all", $this->return_array);
	
//			var_dump($this->return_array);exit;
			return $this->return_array;
	
		}
	
		}
	
	
	
	
	
	
	
		function forLinux(){
	
		@exec("ifconfig -a", $this->return_array);
	
		return $this->return_array;
	
		}

		function getClientMac()
		{
			@exec("arp -a",$array); //执行arp -a命令，结果放到数组$array中
			foreach($array as $value){
			//匹配结果放到数组$mac_array
				if(strpos($value,$_SERVER["REMOTE_ADDR"]) && preg_match("/(:?[0-9A-F]{2}[:-]){5}[0-9A-F]{2}/i",$value,$mac_array)){
					$mac = $mac_array[0];
					break;
				}
			}
			return $mac;
		}
}