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
            [['plant_id', 'plant_father','farms_id', 'lease_id','management_area'], 'integer'],
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
        	'management_area' => '管理区',
        ];
    }
   
    public static function getALlsum()
    {
    	$sum = 0.0;
    	$planting = Plantingstructure::find()->all();
    	foreach ($planting as $value) {
    		$sum+=$value['area'];
    	}
    	return $sum;
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

    public static function getPlantname()
    {
    	$area = Farms::getManagementArea ();
    	foreach ( $area['id'] as $key => $value ) {
			// 农场区域
			
// 			$array['areaname'] = $area['areaname'][$key];
			
			$farm = Farms::find()->where(['management_area'=>$value])->all();
    		foreach ($farm as $val) {
    			$plantsum = 0;
    			$goodseedsum = 0;
    			$planting = Plantingstructure::find()->where(['farms_id'=>$val['id']])->all();
    			foreach ($planting as $v) {
    				$plantname = Plant::find()->where(['id'=>$v['plant_id']])->one()['cropname'];
    				$data[$plantname] = $plantname;
    			}
    		}
    	}
    	foreach ($data as $value) {
    		$result[] = $value;
    	}
    	return $result;
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
    	$plant = [];
    	$goodseed = [];
		$area = Farms::getManagementArea ();
    	foreach ( $area['id'] as $key => $value ) {
    		$areaNum++;

			// 农场区域
			
// 			$array['areaname'] = $area['areaname'][$key];
			
			$farm = Farms::find()->where(['management_area'=>$value])->all();
    		foreach ($farm as $val) {
    			$plantsum = 0;
    			$goodseedsum = 0;
    			$planting = Plantingstructure::find()->where(['farms_id'=>$val['id']])->all();
    			foreach ($planting as $v) {
    				$plantname = Plant::find()->where(['id'=>$v['plant_id']])->one()['cropname'];
    				
					$plant['name'] = '作物';
					$goodseed['name'] = '良种';
					$plantsum += $v['area'];
					$plant['data'][$plantname][] = $plantsum;
					if($v['goodseed_id'] !== 0)
						$goodseedarea = $v['area'];
					else
						$goodseedarea = 0.0;
					$goodseedsum += $goodseedarea;
					$goodseed['data'][$plantname][] = $goodseedsum;
// 					var_dump($goodseed);
    			}
    		}
    	}
// 		var_dump($goodseed);exit;
		// 作物统计
		$plantdata = [];
		$goodseeddata = [];
		// 总数计算
		foreach ($plant['data'] as $key => $value) {
			$area = 0;
			foreach($value as $ke => $val) {
	// 			var_dump($val);
				if (!isset($val)) {
					$planting[$key] = 0.00;
				}
				$area += $val;
				$plantdata[$key] = $area;
			}
		}
		foreach ($goodseed['data'] as $key => $value) {
			$area = 0;
			foreach($value as $ke => $val) {
				// 			var_dump($val);
				if (!isset($val)) {
					$planting[$key] = 0.00;
				}
				$area += $val;
				$goodseeddata[$key] = $area;
			}
		}
		foreach($plantdata as $value) {
			$plantresult[] = $value;
		}
		foreach($goodseeddata as $value) {
			$goodseedresult[] = $value;
		}
		$result = [
				[
						'color' => '#bdfdc9',
						'name' => '作物',
						'data' => $plantresult,
						'dataLabels' => [
								'enabled' => false,
								'rotation' => 0,
								'color' => '#FFFFFF',
								'align' => 'center',
								'x' => 0,
								'y' => 0,
								'style' => [
										'fontSize' => '13px',
										'fontFamily' => 'Verdana, sans-serif',
										'textShadow' => '0 0 3px black'
								]
						]
				],
				[
						'color' => '#02c927',
						'name' => '良种',
						'data' => $goodseedresult,
						'dataLabels' => [
								'enabled' => true,
								'rotation' => 0,
								'color' => '#FFFFFF',
								'align' => 'center',
								'x' => 0,
								'y' => 0,
								'style' => [
										'fontSize' => '13px',
										'fontFamily' => 'Verdana, sans-serif',
										'textShadow' => '0 0 3px black'
								]
						]
				]
		];
// 		var_dump($result);

    	$jsonData = json_encode ( [
    			'result' => $result
    	] );
    	Yii::$app->cache->set ( $cacheKey, $jsonData, 1 );
    	
    	return $jsonData;
    }
    
    public static function getFarmRows($params)
    {
    	$where['management_area'] = $params['plantingstructureSearch']['management_area'];
    	$row = Plantingstructure::find ()->where ($where)->count ();
    	return $row;
    }
    
    public static function getFarmerrows($params)
    {
    	$where = ['management_area'=>$params['plantingstructureSearch']['management_area']];
    	$Plantingstructure = Plantingstructure::find ()->where ($where)->all ();
    	//     	var_dump($farms);exit;
    	$data = [];
    	foreach($Plantingstructure as $value) {
    		$farm = Farms::find()->where(['id'=>$value['farms_id']])->one();
    		$data[] = ['farmername'=>$farm['farmername'],'cardid'=>$farm['cardid']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
    	}
    	else
    		return 0;;
    }
    
    public static function getLeaseRows($params)
    {
    	$where = ['management_area'=>$params['plantingstructureSearch']['management_area']];
    	$Plantingstructure = Plantingstructure::find ()->where ($where)->all ();
    	$data = [];
    	foreach($Plantingstructure as $value) {
    		$lease = Lease::find()->where(['id'=>$value['lease_id']])->one();
    		if($lease)
    			$data[] = ['lessee'=>$lease['lessee'],'lessee_cardid'=>$lease['lessee_cardid']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
		}
		else 
			return 0;
    }
    
    public static function getPlantRows($params)
    {
    	$where = ['management_area'=>$params['plantingstructureSearch']['management_area']];
    	$Plantingstructure = Plantingstructure::find ()->where ($where)->all ();
    	$data = [];
    	foreach($Plantingstructure as $value) {
    		$data[] = ['id'=>$value['plant_id']];
    	}
		if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
		}
		else 
			return 0;
    	
    }
    
    public static function getGoodseedRows($params)
    {
    	$where = ['management_area'=>$params['plantingstructureSearch']['management_area']];
    	$Plantingstructure = Plantingstructure::find ()->where ($where)->all ();
    	$data = [];
    	foreach($Plantingstructure as $value) {
    		if($value['goodseed_id'] !== 0)
    			$data[] = ['id'=>$value['goodseed_id']];
    	}
		if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
		}
		else 
			return 0;
    }
    
    public static function getArea($params)
    {
    	$where = ['management_area'=>$params['plantingstructureSearch']['management_area']];
    	$area = Plantingstructure::find ()->where ($where)->sum ('area');
    	return (float)sprintf("%.2f", $area/10000);
    }
}
