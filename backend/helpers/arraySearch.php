<?php
namespace backend\helpers;
class arraySearch extends baseArraySearch
{
// 	public static $object;
	/*
	 * $array = ['management_area'=>1] or ['management_area'=>[1,2,3,4,5,6,7]
	 */
	public static function find($object) {
		return new baseArraySearch($object);		
	}
	
}