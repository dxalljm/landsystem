<?php

namespace app\models;

use Yii;
use app\models\Farms;
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
	public $platingstructureObject;
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

	public static function getPlantname($totalData = NULL)
    {
    	$result = ['id'=>[],'typename'=>[]];
    	$data = [];
    	if(empty($totalData)) {
    		$where = Farms::getManagementArea()['id'];
    		$planting = Plantingstructure::find()->where(['management_area'=>$where])->all();
    		foreach ($planting as $value) {
    			$data[] = ['id'=>$value['plant_id']];
    		}
    	} else {	
	    	foreach($totalData->getModels() as $value) {
	//     		var_dump($value->attributes);exit;
	    		$data[] = ['id'=>$value->attributes['plant_id']];
	    	}
    	}
	    if($data) {
	    	$newdata = Farms::unique_arr($data);
	    	foreach ($newdata as $value) {
	    		$result['id'][] = $value['id'];
	    		$result['typename'][] = Plant::find()->where(['id' => $value['id']])->one()['cropname'];
	    	}
	    }
    	
    	return $result;
    }
    public static function getGoodseedname($totalData)
    {
		
    	$data = [];
    	foreach($totalData->getModels() as $value) {
//     		var_dump($value);exit;
    		if($value->attributes['goodseed_id'])
    			$data[] = ['id'=>$value->attributes['goodseed_id']];
    	}
//     	var_dump($data);exit;
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		foreach ($newdata as $value) {
    			if($value['id']) {
	    			$result['id'][] = $value['id'];
	    			$result['typename'][] = Goodseed::find()->where(['id' => $value['id']])->one()['plant_model'];
    			}
    		}
    	} else 
    		return false;
//     	    	var_dump($result);exit;
    	return $result;
    }
    public static function farmSearch($str)
    {
    
    	if (preg_match ("/^[A-Za-z]/", $str)) {
    		$tj = ['like','pinyin',$str];
    	} else {
    		$tj = ['like','farmname',$str];
    	}
    		
    	return $tj;
    }
    
    public static function farmerSearch($str)
    {
    
    	if (preg_match ("/^[A-Za-z]/", $str)) {
    		$tj = ['like','farmerpinyin',$str];
    	} else {
    		$tj = ['like','farmername',$str];
    	}
    	//     	var_dump($tj);exit;
    	return $tj;
    }
    public static function setWhere($params)
    {
    	if($params['plantingstructureSearch']['management_area'] == 0){
    		$management_area = [1,2,3,4,5,6,7];
    	} else
    		$management_area = $params['plantingstructureSearch']['management_area'];
    }
    public static function getPlantingstructure($totalData)
    {
//     	var_dump($totalData->query->where);exit;
    	$data = [];
    	$result = [];
//     	$areaNum = 0;
    	$area = 0.0;
		$plantid = self::getPlantname($totalData);
		if(isset($totalData->query->where[1]['management_area']))
			$management_area = $totalData->query->where[1]['management_area'];
		else 
			$management_area = [1,2,3,4,5,6,7];
		if(is_array($management_area)) {
			foreach ($management_area as $value) {
// 				array_search($value,$totalData->getModels());
// 			}
			$plantArea = [];
			foreach ($plantid['id'] as $val) {
				foreach ($totalData->getModels() as $v) {
					$area += $v->attributes['area'];
				}
				$plantArea[] = (float)sprintf("%.2f", $area/10000);
			}
			// 				var_dump($plantArea);
			$result[] = [
					'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id'=>$value])->one()['areaname']),
					'type' => 'bar',
					'stack' => $value,
					'data' => $plantArea
			];
			}
		} else {
    		$plantArea = [];
    		foreach ($plantid['id'] as $val) {
//     			var_dump($oldp);
    			$plantingstructure->where = $oldp;
    			$plantingstructure->andFilterWhere(['plant_id'=>$val]);
//     			var_dump($totalData->getModels());exit;
    			$area = $plantingstructure->sum('area');
//     			var_dump($area);
    			$plantArea[] = (float)sprintf("%.2f", $area/10000);
    		}
//     		var_dump($plantArea);
    		$result[] = [
    				'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id'=>$management_area])->one()['areaname']),
    				'type' => 'bar',
    				'stack' => $management_area,
    				'data' => $plantArea
    		];
		}
//     	var_dump($result);
    	$jsonData = json_encode ($result);
    	
    	return $jsonData;
    }
    
    public static function getGoodseedEcharts($totalData)
    {
    	
    	$data = [];
    	$result = [];
    	$areaNum = 0;
    	$plant = [];
    	$goodseed = [];
    	$goodseedresult = [];
    	$plantresult = [];
    	$plantid = self::getPlantname($totalData);
		$oldp = $totalData->query->where;
		if(isset($totalData->query->where[1]['management_area']))
			$management_area = $totalData->query->where[1]['management_area'];
		else 
			$management_area = [1,2,3,4,5,6,7];
		$Plantingstructure = Plantingstructure::find();
    	if(is_array($management_area)) {
    		foreach ($management_area as $value) {
    			$plantArea = [];
    			foreach ($plantid['id'] as $val) {
    				$Plantingstructure->where = $oldp;
    				$Plantingstructure->andFilterWhere(['goodseed_id'=>$val,'management_area'=>$value]);
    				$area = $Plantingstructure->sum('area');
    				$plantArea[] = (float)sprintf("%.2f", $area/10000);
    			}
    			// 				var_dump($plantArea);
    			$result[] = [
    					'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id'=>$value])->one()['areaname']),
    					'type' => 'bar',
    					'stack' => $value,
    					'data' => $plantArea
    			];
    		}
    	} else {
    		$plantArea = [];
    		$Plantingstructure->andFilterWhere(['management_area'=>$management_area]);
    		foreach ($plantid['id'] as $val) {
    			$Plantingstructure->where = $oldp;
    			$Plantingstructure->andFilterWhere(['goodseed_id'=>$val]);
    			//     			var_dump($Plantingstructure->where);
    			$area = $Plantingstructure->sum('area');
    			//     			var_dump($area);
    			$plantArea[] = (float)sprintf("%.2f", $area/10000);
    		}
    		//     		var_dump($plantArea);
    		$result[] = [
    				'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id'=>$management_area])->one()['areaname']),
    				'type' => 'bar',
    				'stack' => $management_area,
    				'data' => $plantArea
    		];
    	}
    	//     	var_dump($result);
    	$jsonData = json_encode ($result);
    	 
    	return $jsonData;
    }
    
    public static function areaWhere($str = NULL)
    {
    	
    	if(!empty($str)) {
    		preg_match_all('/(.*)([0-9]+?)/iU', $str, $where);
    		//print_r($where);
    
    		// 		string(2) ">="
    		// 		string(3) "300"
    		if($where[1][0] == '>' or $where[1][0] == '>=')
    			$tj = ['between', 'area', (float)$where[2][0],(float)99999.0];
    		if($where[1][0] == '<' or $where[1][0] == '<=')
    			$tj = ['between', 'area', (float)0.0,(float)$where[2][0]];
    		if($where[1][0] == '')
    			$tj = ['like', 'area', $str];
    	} else
    		$tj = ['like', 'area', $str];
    	//var_dump($tj);
    	return $tj;
    }
    public static function getFarmRows($totalData)
    {
    	
	   	foreach ($totalData->getModels() as $value) {
	   		$data[] = ['id'=>$value['farms_id']];
	   	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		$result = count($newdata);
    	}
    	else
    		$result = 0;
    	return $result;
    }
    
    public static function getPlanter($totalData)
    {
// 		var_dump($totalData);exit;	
    	foreach ($totalData->getModels() as $value) {
//     		var_dump($val);exit;
    		$allid[] = ['id'=>$value->attributes['farms_id']];
    	}
    	$farms = Farms::find()->where(['id'=>$allid])->all();
    	foreach ($farms as $val) {
    		$data[] = ['farmername'=>$val['farmername'],'cardid'=>$val['cardid']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		$result = count($newdata);
    	}
    	else
    		$result = 0;
//     	Yii::$app->cache->set ( $cacheKey, $result, 84600 );
    	return $result;
    }
    public static function getFarmerRows($totalData)
    {
    	$p = self::getPlanter($totalData);
    	$f = self::getLeaseRows($totalData);
    	return $p-$f;
    }
    public static function getLeaseRows($totalData)
    {
    	
    	foreach($totalData->getModels() as $value) {
    		$allid[] = $value->attributes['lease_id'];
    	}
    	$lease = Lease::find()->where(['id'=>$allid])->all();
    	foreach ($lease as $val) {
    		$data[] = ['lessee'=>$val['lessee'],'lessee_cardid'=>$val['lessee_cardid']];
    	}

    		
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		$result = count($newdata);
    	}
    	else
    		$result = 0;
//     	Yii::$app->cache->set ( $cacheKey, $result, 84600 );
    	return $result;
    }
    public static function getPlantRowsMenu($where)
    {
    	$data = [];
    	$planting = Plantingstructure::find();
    	$planting->andFilterWhere(['between','update_at',$where['begindate'],$where['enddate']]);
    	$planting->andFilterWhere(['management_area'=>$where['management_area']]);
    	foreach($planting->all() as $value) {
    		$data[] = ['id'=>$value['plant_id']];
    	}
    	 
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		//     		var_dump(count($newdata));exit;
    		return count($newdata);
    	}
    	else
    		return 0;
    	 
    }
    public static function getPlantRows($totalData)
    {
// 		var_dump($totalData);exit;
    	foreach($totalData->getModels() as $value) {
    		$data[] = ['id'=>$value->attributes['plant_id']];
    	}
    	
		if($data) {
    		$newdata = Farms::unique_arr($data);
//     		var_dump(count($newdata));exit;
    		return count($newdata);
		}
		else 
			return 0;
    	
    }
    
    public static function getGoodseedRows($totalData)
    {
    	
    	foreach($totalData as $value) {
    		if($value['goodseed_id'])
    			$data[] = ['id'=>$value->attributes['goodseed_id']];
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
   		if($params['plantingstructureSearch']['management_area'] == 0){
	    		$management_area = [1,2,3,4,5,6,7];
	    	} else
	    		$management_area = $params['plantingstructureSearch']['management_area'];
	    	$where = [];
	    	$Farm = Farms::find();
	    	$Plantingstructure = Plantingstructure::find ();
	    	$data = [];
	    	$Plantingstructure->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
	    	$Plantingstructure->andFilterWhere(['management_area'=>$management_area]);
	    	$farmid = [];
	    	if(isset($params['plantingstructureSearch']['farms_id']) or isset($params['plantingstructureSearch']['farmer_id'])) {
	    		$farm = Farms::find();
	    		$farm->andFilterWhere(['management_area'=>$management_area]);
	    	}
    		if(isset($params['plantingstructureSearch']['farms_id'])) {
	    		$farm->andFilterWhere(self::farmSearch($params['plantingstructureSearch']['farms_id']));
	    		 
	    	}
	    	if(isset($params['plantingstructureSearch']['farmer_id'])) {
	    		$farm->andFilterWhere(self::farmerSearch($params['plantingstructureSearch']['farmer_id']));
	    	}
	    	if(isset($farm)) {
		    	foreach ($farm->all() as $value) {
		    		$farmid[] = $value['id'];
		    	}
	    		$Plantingstructure->andFilterWhere(['farms_id'=>$farmid]);
	    	}
	    	if(isset($params['plantingstructureSearch']['plant_id']))
	    		$Plantingstructure->andFilterWhere(['plant_id'=>$params['plantingstructureSearch']['plant_id']]);
	    	 
	    	
	    	if(isset($params['plantingstructureSearch']['goodseed_id']))
	    		$Plantingstructure->andFilterWhere(['goodseed_id'=>$params['plantingstructureSearch']['goodseed_id']]);
	    	 
	    	if(isset($params['plantingstructureSearch']['area']))
	    		$Plantingstructure->andFilterWhere(self::areaWhere($params['plantingstructureSearch']['area']));
    	$area = $Plantingstructure->sum ('area');
    	return (float)sprintf("%.2f", $area/10000);
    }
    
    public static function getAllname($totalData)
    {
    	$result = [];
    	$data = [];
//     	var_dump($totalData->query->where);exit;
    	if(isset($totalData->query->where['plant_id']) and $totalData->query->where !== 0 and $totalData->query->where !== '') {
    		$type = Goodseed::find()->where(['plant_id'=>$totalData->query->where['plant_id']])->all();
    	} else {
	    	foreach($totalData->getModels() as $value) {
	    		if($value->attributes['goodseed_id'])
	    			$data[] = ['id'=>$value->attributes['goodseed_id']];
	    	}
	    	if($data) {
	    		$newdata = Farms::unique_arr($data);
	    		foreach($newdata as $value) {
	    			$allid[] = $value['id'];
	    			//     		var_dump($value);exit;
	    			// 	    		$result[$value['id']] = Plant::find()->where(['id'=>$value['id']])->one()['cropname'];
	    		}
	    		$type = Goodseed::find()->where(['id'=>$allid])->all();
	    		
	    	}
    	}
//     	var_dump($type);exit;
    	foreach ($type as $value) {
    		$result[$value['id']] = Plant::find()->where(['id'=>$value['plant_id']])->one()['cropname'].'->'.$value['plant_model'];
    	}
//     	var_dump($result);
    	return $result;
    }
    
    public static function getNameOne($totalData,$id)
    {
    	if($id == 0)
    		return null;
    	$data = self::getAllname($totalData);
    	return $data[$id];
    }
}
