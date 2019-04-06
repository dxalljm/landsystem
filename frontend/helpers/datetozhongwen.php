<?php
/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 16/6/2
 * Time: 17:23
 */

namespace frontend\helpers;

class datetozhongwen
{
    public static function dateto($create_at = NULL)
    {
    	if($create_at)
    		return self::year_str(date('Y',$create_at)).'年'.self::md(date('n',$create_at))."月".self::md(date('j',$create_at))."日";
    	else
        	return self::year_str(date('Y')).'年'.self::md(date('n'))."月".self::md(date('j'))."日";
    }

    private static function year_str($y)
    {
        $string = '';
        for($i=0;$i<strlen($y);$i++)
            $string.=self::str_num(substr($y,$i,1));
        return $string;
    }

    private static function md($str)
    {
        $num = [];
        for($i=0;$i<strlen($str);$i++) {
            $num[]=substr($str,$i,1);
        };
        //将多个数字,截取成单个数字,存为数组形式
        if($str<10)  $string=self::str_num($num[0]);//小于10的,表明只有一位数,调用self::str_num函数直接转换
        elseif($str==10) $string="十";//等于10的,直接赋值十
        elseif($str<20) $string="十".self::str_num($num[1]);//对于11至19的数,第一位数直接赋值为"十",第二位数调用self::str_num函数直接转换
        elseif($str>=20&&$str%10==0) $string=self::str_num($num[0])."十";//对20,30....,将第一位数调用self::str_num函数直接转换,第二位数赋值为"十"
        else  $string=self::str_num($num[0])."十".self::str_num($num[1]);//其它的数调用tr_num函数直接转换第一位和第二北位数,中间补"十"
        return $string;
    }

    private static function str_num($str1)
    {
        //将数字转成汉字对应的数
        switch($str1)
        {
            case 1:$str_n="一";break;
            case 2: $str_n="二";break;
            case 3:$str_n="三";break;
            case 4:$str_n="四";break;
            case 5:$str_n="五";break;
            case 6:$str_n="六"; break;
            case 7:$str_n="七";break;
            case 8:$str_n="八";break;
            case 9:$str_n="九";break;
            case 0:$str_n="零";break;
        }
        return $str_n;
    }

}
?>