<?php
namespace backend\helpers;
class MoneyFormat
{
	public static function num_format($num){
		if(!is_numeric($num)){
			return false;
		}
		if($num == 0.0)
			return $num;
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
	
/**
*数字金额转换成中文大写金额的函数
*String Int  $num  要转换的小写数字或小写字符串
*return 大写字母
*小数位为两位
**/
public static function cny($num){
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        //精确到分后面就不要了，所以只留两个小数位
        $num = round($num, 2); 
        //将数字转化为整数
        $num = $num * 100;
        if (strlen($num) > 10) {
                return "金额太大，请检查";
        } 
        $i = 0;
        $c = "";
        while (1) {
                if ($i == 0) {
                        //获取最后一位数字
                        $n = substr($num, strlen($num)-1, 1);
                } else {
                        $n = $num % 10;
                }
                //每次将最后一位数字转化为中文
                $p1 = substr($c1, 3 * $n, 3);
                $p2 = substr($c2, 3 * $i, 3);
                if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                        $c = $p1 . $p2 . $c;
                } else {
                        $c = $p1 . $c;
                }
                $i = $i + 1;
                //去掉数字最后一位了
                $num = $num / 10;
                $num = (int)$num;
                //结束循环
                if ($num == 0) {
                        break;
                } 
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
                //utf8一个汉字相当3个字符
                $m = substr($c, $j, 6);
                //处理数字中很多0的情况,每次循环去掉一个汉字“零”
                if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                        $left = substr($c, 0, $j);
                        $right = substr($c, $j + 3);
                        $c = $left . $right;
                        $j = $j-3;
                        $slen = $slen-3;
                } 
                $j = $j + 3;
        } 
        //这个是为了去掉类似23.0中最后一个“零”字
        if (substr($c, strlen($c)-3, 3) == '零') {
                $c = substr($c, 0, strlen($c)-3);
        }
        //将处理的汉字加上“整”
        if (empty($c)) {
                return "零元整";
        }else{
                return $c . "整";
        }
}

public static function big($num){
	$c1 = "零壹贰叁肆伍陆柒捌玖";
	$c2 = "分角元拾佰仟万拾佰仟亿";
	//精确到分后面就不要了，所以只留两个小数位
	$num = round($num, 2);
	//将数字转化为整数
	$num = $num * 100;
	if (strlen($num) > 10) {
		return "金额太大，请检查";
	}
	$i = 0;
	$c = "";
	while (1) {
		if ($i == 0) {
			//获取最后一位数字
			$n = substr($num, strlen($num)-1, 1);
		} else {
			$n = $num % 10;
		}
		//每次将最后一位数字转化为中文
		$p1 = substr($c1, 3 * $n, 3);
		$p2 = substr($c2, 3 * $i, 3);
		if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
			$c = $p1 . $p2 . $c;
		} else {
			$c = $p1 . $c;
		}
		$i = $i + 1;
		//去掉数字最后一位了
		$num = $num / 10;
		$num = (int)$num;
		//结束循环
		if ($num == 0) {
			break;
		}
	}
// 	$j = 0;
// 	$slen = strlen($c);
// 	while ($j < $slen) {
// 		//utf8一个汉字相当3个字符
// 		$m = substr($c, $j, 6);
// 		//处理数字中很多0的情况,每次循环去掉一个汉字“零”
// 		if ($m == '零' || $m == '零万' || $m == '零亿' || $m == '零零') {
// 			$left = substr($c, 0, $j);
// 			$right = substr($c, $j + 3);
// 			$c = $left . $right;
// 			$j = $j-3;
// 			$slen = $slen-3;
// 		}
// 		$j = $j + 3;
// 	}
// 	//这个是为了去掉类似23.0中最后一个“零”字
// 	if (substr($c, strlen($c)-3, 3) == '零') {
// 		$c = substr($c, 0, strlen($c)-3);
// 	}
	//将处理的汉字加上“整”
	if (empty($c)) {
		return "";
	}else{
		return $c . "整";
	}
}
}