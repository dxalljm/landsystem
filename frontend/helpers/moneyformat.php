<?php
class MoneyFormat
{
	public static function num_format($num){
		if(!is_numeric($num)){
			return false;
		}
		$rvalue='';
		$num = explode('.',$num);//把整数和小数分开
		$rl = !isset($num['1']) ? '' : $num['1'];//小数部分的值
		$j = strlen($num[0]) % 3;//整数有多少位
		$sl = substr($num[0], 0, $j);//前面不满三位的数取出来
		$sr = substr($num[0], $j);//后面的满三位的数取出来
		$i = 0;
		while($i <= strlen($sr)){
			$rvalue = $rvalue.','.substr($sr, $i, 3);//三位三位取出再合并，按逗号隔开
			$i = $i + 3;
		}
		$rvalue = $sl.$rvalue;
		$rvalue = substr($rvalue,0,strlen($rvalue)-1);//去掉最后一个逗号
		$rvalue = explode(',',$rvalue);//分解成数组
		if($rvalue[0]==0){
			array_shift($rvalue);//如果第一个元素为0，删除第一个元素
		}
		$rv = $rvalue[0];//前面不满三位的数
		for($i = 1; $i < count($rvalue); $i++){
			$rv = $rv.','.$rvalue[$i];
		}
		if(!empty($rl)){
			$rvalue = $rv.'.'.$rl;//小数不为空，整数和小数合并
		}else{
			$rvalue = $rv;//小数为空，只有整数
		}
		return $rvalue;
	}
	
	public static function cny($ns)
	{
		static $cnums = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
		$cnyunits = array("圆","角","分"),
		$grees = array("拾","佰","仟","万","拾","佰","仟","亿");
		list($ns1,$ns2) = explode(".",$ns,2);
		$ns2 = array_filter(array($ns2[1],$ns2[0]));
		$ret = array_merge($ns2,array(implode("", self::_cny_map_unit(str_split($ns1), $grees)), ""));
		$ret = implode("",array_reverse(self::_cny_map_unit($ret,$cnyunits)));
		return str_replace(array_keys($cnums), $cnums,$ret);
	}
	
	public static function _cny_map_unit($list,$units)
	{
		$ul = count($units);
		$xs = array();
		foreach (array_reverse($list) as $x)
		{
			$l = count($xs);
			if($x!="0" || !($l%4))
			{
				$n=($x=='0'?'':$x).($units[($l-1)%$ul]);
			}
			else
			{
				$n=is_numeric($xs[0][0]) ? $x : '';
			}
			array_unshift($xs, $n);
		}
		return $xs;
	}
}