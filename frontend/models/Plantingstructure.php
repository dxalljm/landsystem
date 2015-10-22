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
    			$arrZongdi = explode('、', $value['zongdi']);
    			foreach ($arrZongdi as $val) {
    				$result[] = $val;
    			}
    		}
    	}
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
    	foreach($result as $key => $value) {
    		foreach ($over as $v) {
    			//echo $key.'==='.Lease::getZongdi($v)."<br>";
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
    	foreach ($result as $key=>$value) {
    		if($value !== 0.0)
    			$zongdi[] = $key.'('.$value.')';
    	}
    	//var_dump($zongdi);
    	return $zongdi;
    }

}
