<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%plantingstructure}}".
 *
 * @property integer $id
 * @property integer $plant_id
 * @property double $area
 * @property integer $inputproduct_id
 * @property integer $pesticides_id
 * @property integer $is_goodseed
 * @property integer $goodseed_id
 * @property string $zongdi
 * @property integer $farms_id
 */
class Plantingstructure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plantingstructure}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plant_id', 'plant_father','farms_id', 'lease_id'], 'integer'],
            [['area'], 'number'],
            [['zongdi'], 'string'],
        	[['goodseed_id'],'safe'],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'plant_id' => '种植结构',
        	'plant_father'=>'种植结构父ID',
            'area' => '种植面积',
            'goodseed_id' => '良种使用信息',
            'zongdi' => '宗地',
            'farms_id' => '农场ID',
        	'lease_id' => '承租人ID',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        ];
    }
   
    
    //得到已经填写种植信息的宗地
    public static function getOverZongdi($lease_id,$farms_id)
    {
    	$result = [];
    	$plantings = Plantingstructure::find()->where(['lease_id'=>$lease_id,'farms_id'=>$farms_id])->all();
    	if($plantings) {
    		foreach ($plantings as $value) {
    			if(!strstr($value['zongdi'],'-')) {
	    			$result[$value['zongdi']] = $value['area'];
    			} else {
	    			$arrZongdi = explode('、', $value['zongdi']);
	    			foreach ($arrZongdi as $val) {
	    				$result[] = $val;
	    			}
    			}
    		}
    	}
//     	var_dump($result);
//     	exit;
    	return $result;
    }
    
    public static function getNoZongdi($lease_id,$farms_id)
    {
    	if($lease_id == 0) {
    		$over = self::getOverZongdi($lease_id, $farms_id);
    		$all = Lease::getNOZongdi($farms_id);
    		if($over) {
    			$result = self::getLastArea($over, $all);
    		} else 
    			$result = $all;
    	} else {
	    	$over = self::getOverZongdi($lease_id, $farms_id);
	    	$all = Lease::getLeaseArea($lease_id);
	    	$result = self::getLastArea($over, $all);
    	}
    	return $result;
    }
    
    //处理种植结构剩余宗地面积 $over=已经有种植结构的地块，$all为当前承租人的所有地块
    public static function getLastArea($over,$all)
    {
//     	    	var_dump($all);
//     	    	var_dump($over);
//     	    	exit;
    	foreach ($all as $val) {
    		$result[Lease::getZongdi($val)] = Lease::getArea($val);
    	}
// 		var_dump($result);
// 		var_dump($over);
// 		exit;
    	foreach($result as $key => $value) {
    		
	    	foreach ($over as $k => $v) {
	    		if(!strstr($v,'(')) {
    				$result[$k] = $k - $v;
    			} else {
	    			if($key == Lease::getZongdi($v)) {
	    				
	    				if($value == Lease::getArea($v)) {
	    					unset($result[$key]);
	    				} else {
	    					$area = $result[$key] - Lease::getArea($v);
							$result[$key] = $area;
	    				}
	    			}
    			}
	    	}		
    	}
    	$zongdi = [];
    	foreach ($result as $key=>$value) {
//     		if(preg_match('/^\d+\.?/iU', $value)) {
//     			$zongdi[0] = $value;
//     		} else {
// 	    		if($value !== 0.0 and $key !== '')
// 	    			$zongdi[] = $key.'('.$value.')';
// 	    		else 
// 	    			$zongdi[] = $value;
//     		}
			$zongdi[] = $key.'('.$value.')';
    	}
//     	var_dump($zongdi);
//     	exit;
    	return $zongdi;
    }

    public static function getPlantingstructure()
    {
    	$cacheKey = 'plantingstructure-hcharts';
    	$result = Yii::$app->cache->get ( $cacheKey );
    	if (! empty ( $result )) {
    		return $result;
    	}
    	$data = [];
    	$result = [];
    	$areaNum = 0;
    	foreach ( Farms::getManagementArea ()['id'] as $value ) {
    		$areaNum++;
    		$farm = Farms::find()->where(['management_area'=>$value])->all();
    		foreach ($farm as $val) {
    			$planting = Plantingstructure::find()->where(['farms_id'=>$val['id']])->all();
    			foreach ($planting as $v) {
    				$plantname = Plant::find()->where(['id'=>$v['plant_id']])->one()['cropname'];
    				$data[$value][$plantname][] = $v['area'];
    			}
    		}
    	}
    	if($data) {
	    	foreach ($data as $key => $value) {
	    		$areaSum = 0.0;
	    		foreach($value as $k => $val) {
		    		foreach ($val as $v) {
		    			$areaSum += $v;
		    		}
	    		}
	    		for($i=1;$i<=$areaNum;$i++) {
	    			if($i == $k) {
	    				$d[$i] = $areaSum;
	    			} else {
	    				$d[$i] = 0;
	    			}
	    		}
	    		$result[] = ['name'=>$key,'data'=>[$d]];
	    	}
    	}
// 		var_dump($result);
    	$jsonData = json_encode ( [
    			'result' => $result
    	] );
    	Yii::$app->cache->set ( $cacheKey, $jsonData, 1 );
    	
    	return $result;
    }
}
