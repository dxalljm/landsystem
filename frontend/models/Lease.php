<?php

namespace app\models;

use Yii;
use app\models\Farms;
use app\models\Theyear;
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
    
    public function rules()
    {
    	return [
    			[['farms_id', 'years'], 'integer'],
    			[['lessee_cardid', 'enddate', 'rentpaymode', 'policyholder', 'insured', 'huinongascription', 'address'], 'string'],
    			[['rent'], 'number'],
    			[['lease_area', 'lessee', 'lessee_telephone', 'begindate', 'photo', 'create_at', 'update_at'], 'string', 'max' => 500]
    	];
    }
    

    //得到1-100（123）中的面积123
    public static function getArea($Leasearea)
    {
		if(is_numeric($Leasearea)) {
			return $Leasearea;
		}
    	$areas = 0;
//		$Leasearea = '200';
//		$Leasearea = '2.1';
//		$Leasearea = '3.';

    	//if(preg_match('/^\d+\.?/iU', $Leasearea)) {
    	if(strstr($Leasearea,'(')) {
	    	
			preg_match_all('/-([\s\S]*)\(([0-9.]+?)\)/', $Leasearea, $area);
			//var_dump($area[2][0]);

			$areas = (float)$area[2][0];
    	} else {
    		$areas = (float)$Leasearea;
    	}
//     	var_dump($areas);
    	return $areas;
    }
    //得到1-100（123）中的宗地号1-100
    public static function getZongdi($Leasearea) {
    	if(preg_match('/^(([0-9]+.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*.[0-9]+)|([0-9]*[1-9][0-9]*))$/',$Leasearea)) {
    		$zongdi = $Leasearea;
    	} else {
	   		$zongdi = preg_replace('/\(  [^\)]+?  \)/x', '', $Leasearea);
	    	
    	}
    	return $zongdi;
    }
    
    //得到承租人的宗地信息
    public static function getLeaseArea($lease_id) 
    {
    	$LeaseArea = self::find()->where(['id'=>$lease_id])->one()['lease_area'];

    	return $LeaseArea;
    }
    //得到所有当前农场已经租赁的面积
    public static function getAllLeaseArea($farms_id)
    {
    	$result = 0.0;
    	$leases = self::find()->where(['farms_id'=>$farms_id])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
    	foreach($leases as $value) {
    			$result += $value['lease_area'];
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
    	//$zdarea = false;
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
    	if(!empty($farm['zongdi']))
    		$farmzongdi = explode('、', $farm['zongdi']);
    	
    	//var_dump($farm->notclear);
    	if(!empty($farm->notclear))
    		$farmzongdi[] = 'not-clear('.$farm->notclear.')';
    	
    	return $farmzongdi;
    }
    public static function getFarmsMeasure($farms_id)
    {
    	$measure = 0.0;
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
    	if(!empty($farm['zongdi']))
    		$measure += $farm['measure'];
    	if(!empty($farm['notclear']))
    		$measure += $farm['notclear'];
    	return $measure;
    }
   //通过给定的宗地号，返回宗地（面积）
   public static function ZongdiFormat($zongdi)
   {
   		$zdarea = $zongdi.'('.self::zongdiToArea($zongdi).')';
   		return $zdarea;
   }
    
    public static function scanOverArea($farms_id) 
    {
    	$leasearea = 0.0;
    	//$zdarea——农场所有宗地（面积）
    	$zdarea = self::getFarmsMeasure($farms_id);
    	//$lease——已经被租赁的所有宗地（面积）
    	$lease = self::find()->where(['farms_id'=>$farms_id])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
    	$result = 0;
    	if($lease) {
    		
    		foreach ($lease as $value) {
    			$leasearea += $value['lease_area'];
    		}
    	}
    	return $zdarea - $leasearea;
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
    	$zongdiarr = Lease::scanOverArea($farms_id);
		//var_dump($zongdiarr);
    	return $zongdiarr;
    }
    //返回还没有租赁面积
    public static function getNoArea($farms_id)
    {
    	$farms = Farms::find()->where(['id'=>$farms_id])->one();
    	$allarea = $farms->measure + $farms->notclear;
    	return bcsub($farms->measure, self::getAllLeaseArea($farms_id),2);
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
    		//var_dump($value);
    		$result += self::getArea($value);
    	}
//     	var_dump($result);
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
            'rent' => '租金',
            'rentpaymode' => '租金缴纳方式',
            'policyholder' => '投保人',
            'insured' => '被保险人',
            'huinongascription' => '惠农补贴归属',
            'address' => '住址',
        ]; 
    }
}
