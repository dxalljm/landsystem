<?php

namespace app\models;

use Yii;
use app\models\Farms;
/**
 * This is the model class for table "{{%lease}}".
 *
 * @property integer $id
 * @property string $lease_area
 * @property string $lessee
 * @property string $plant_id
 */
class Lease extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lease}}';
    }

    /**
     * @inheritdoc
     */

    //得到1-100（123）中的面积123
    public static function getArea($Leasearea) 
    {
    	if(preg_match('/[0-9.]+?/',$Leasearea)) {
    		$areas = $Leasearea;
    	} else {
	    	$areas = 0;
	    	preg_match_all('/-([\s\S]*)\(([0-9.]+?)\)/', $Leasearea, $area);
			$areas = (float)$area[2][0];
    	}
    	return $areas;
    }
    //得到1-100（123）中的宗地号1-100
    public static function getZongdi($Leasearea) {
    	if(preg_match('/[0-9.]+?/',$Leasearea)) {
    		$zongdi = $Leasearea;
    	} else {
	   		$zongdi = preg_replace('/\(  [^\)]+?  \)/x', '', $Leasearea);
	    	return $zongdi;
    	}
    }
    
    //得到承租人的宗地信息
    public static function getLeaseArea($lease_id) 
    {
    	$strLeaseArea = self::find()->where(['id'=>$lease_id])->one()['lease_area'];
    	if(strstr($strLeaseArea, '、')) {
    		$arrayLeaseArea = explode('、', $strLeaseArea);
    	} else 
    		$arrayLeaseArea[] = $strLeaseArea;
    	return $arrayLeaseArea;
    }
    //得到所有当前农场已经租赁的信息
    public static function getAllLeaseArea($farms_id)
    {
    	$result = [];
    	$leases = self::find()->where(['farms_id'=>$farms_id])->all();
    	foreach($leases as $value) {
    		if(strstr($value['lease_area'], '、')) {
    			$array = explode('、', $value['lease_area']);
    			foreach ($array as $val) {
    				$result[] = $val;
    			}
    		}
    		else
    			$result[] = $value['lease_area'];
    	}
    	return $result;
    }
    
    //宗地转面积
    public static function zongdiToArea($zongdi)
    {
    	$area = Parcel::find()->where(['unifiedserialnumber'=>$zongdi])->one()['grossarea'];
    	return $area;
    }
    
    //所有getAllLeaseArea返回的宗地面积累加
    public static function AddAllLeaseArea($arrayZongdiArea)
    {
    	return self::zdareaChuLi($arrayZongdiArea);
    }
    //农场所有宗地（面积）
    public static function getFarmsZdarea($farms_id)
    {
    	$zdarea = false;
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
    	//var_dump($farm);
    	$farmzongdi = explode('、', $farm['zongdi']);
    	foreach($farmzongdi as $zongdi) {
    		$parcel = Parcel::find()->where(['unifiedserialnumber'=>$zongdi])->one()['grossarea'];
    		$zdarea[] = $zongdi.'('.$parcel.')';
    	}
    	//var_dump($farm->notclear);
    	if(!empty($farm->notclear))
    		$zdarea[] = 'not-clear('.$farm->notclear.')';
    	return $zdarea;
    }
    
   
    
    public static function scanOverZongdi($farms_id) 
    {
    	//$zdarea——农场所有宗地（面积）
    	$zdarea = self::getFarmsZdarea($farms_id);
    	//$lease——已经被租赁的所有宗地（面积）
    	$lease = self::find()->where(['farms_id'=>$farms_id])->all();
    	
    	if($lease) {
    		foreach ($lease as $value) {
    			$leasearea = explode('、',$value['lease_area']);
    			foreach($leasearea as $val) {
    				$OneArrayArea[] = $val;
    			}
    		}
    		//已经租赁的宗地与农场宗地进行比较，得到已经租赁的宗地，但不包括租赁一部分面积的宗地
    		$result = array_diff($zdarea,$OneArrayArea);
    		//var_dump($OneArrayArea);
    		//把相同宗地的面積進行累加
    		//var_dump($newOneArrayArea);
    		if(count($OneArrayArea)>1) {
	    		$lastLeaseArea = self::zdareaChuLi($OneArrayArea);
    		}
    		else
    			$lastLeaseArea = $OneArrayArea;
    		//var_dump($lastLeaseArea);
    		//得到需要顯示的地塊和剩余面积
    		foreach($lastLeaseArea as $key=>$value ) {
				foreach ( $zdarea as $k=>$za ) {
					// 判断宗地号是否一致，如果一致则判断面积是否相等，如果不相等则面积相减
					if (self::getZongdi ( $za ) == self::getZongdi ( $value )) {
						
						if (self::getArea ( $za ) !== self::getArea ( $value )) {
							//echo self::getArea ( $za ) .'=='. self::getArea ( $value ).'<br>';
							$areac = self::getArea ( $za ) - self::getArea ( $value );
							//echo $areac;
							$result [$k] = self::getZongdi ( $za ) . "(" . $areac . ")";
							//var_dump($result);
						}	
		    		}
	    		}
    		}
    		$result = array_diff($result,$lastLeaseArea);
    		//var_dump($result);
    		return $result;
    	}
    	else 
    		return $zdarea;
    	return false;
    }
   
    //把相同宗地面积进行累加，返回处理后的数组
    public static function zdareaChuLi($arrayArea)
    {
    	//var_dump($arrayArea);
    	for($i=0;$i<count($arrayArea);$i++) {
    		for($j=$i+1;$j<count($arrayArea);$j++) {
    			//echo self::getZongdi($arrayArea[$i]) .'=='. self::getZongdi($arrayArea[$j]).'<br>';
    			if(self::getZongdi($arrayArea[$i]) == self::getZongdi($arrayArea[$j])) {
    				$areaSum = self::getArea($arrayArea[$i])+self::getArea($arrayArea[$j]);
    				$arrayArea[$i] = self::getZongdi($arrayArea[$i]).'('.$areaSum.')';
    				unset($arrayArea[$j]);
    				sort($arrayArea);
    				//var_dump($arrayArea);
    				$arrayArea = self::zdareaChuLi($arrayArea);
    			}
    		}
    	}
    	//var_dump($arrayArea);
    	return $arrayArea;
    }
    
    public static function getNOZongdi($farms_id)
    {
    	$zongdiarr = Lease::scanOverZongdi($farms_id);
		//$arrayArea = [];
    	//$arrayArea = implode('、', $zongdiarr);
    	return $zongdiarr;
    }
    
    //得到已经租赁的面积
    public static function getOverArea($farms_id)
    {
    	$area = 0;
    	$arraylearearea = self::getAllLeaseArea($farms_id);
    	//var_dump($arraylearearea);
    	if(!empty($arraylearearea)) {
	    	foreach($arraylearearea as $value) {
	    		$arrayzongdiarea = explode('、', $value);
	    		foreach ($arrayzongdiarea as $val) {
	    			$area += self::getArea($val);
	    		}
	    	}
    	}
		//var_dump($area);
    	return $area;
    }
    //返回还没有租赁面积
    public static function getNoArea($farms_id)
    {
    	$farms = Farms::find()->where(['id'=>$farms_id])->one();
    	return $farms->measure - self::getOverArea($farms_id);
    }
	public function rules() 
    { 
        return [
            [['farms_id', 'years'], 'integer'],
            [['lessee_cardid', 'enddate'], 'string'],
            [['lease_area', 'lessee', 'lessee_telephone', 'begindate', 'photo'], 'string', 'max' => 500]
        ]; 
    } 
    //将数组中1-100(10),1-200(123)的面积进行累加
    public static function getListArea($Area)
    {
    	$result = 0;
    	if(is_array($Area))
    		$arrayArea = $Area;
    	else    	
    		$arrayArea = explode('、', $Area);
    	foreach($arrayArea as $value) {	
    		$result += self::getArea($value);
    	}
    	return $result;
    }

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'lease_area' => '租赁面积',
            'lessee' => '承租人',
            'farms_id' => '农场ID',
            'years' => '年度',
            'lessee_cardid' => '身份证号',
            'lessee_telephone' => '联系电话',
            'begindate' => '开始日期',
            'enddate' => '结束日期',
            'photo' => '近期照片',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
        ]; 
    }
}
