<?php

namespace app\models;

use Yii;
use app\models\Farms;
/**
 * This is the model class for table "{{%Plantingstructurecheck}}".
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
class Plantingstructurecheck extends \yii\db\ActiveRecord
{
	public $platingstructureObject;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plantingstructurecheck}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plant_id', 'plant_father','farms_id', 'lease_id','management_area','issame','state'], 'integer'],
            [['area','contractarea'], 'number'],
            [['zongdi','year','verifydate'], 'string'],
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
			'issame' => '与调查数据',
			'year' => '年度',
			'verifydate' => '核实日期',
			'contractarea' => '合同面积',
			'state' => '合同状态',
        ];
    }

    public static function getALlsum()
    {
    	$sum = 0.0;
    	$planting = Plantingstructurecheck::find()->all();
    	foreach ($planting as $value) {
    		$sum+=$value['area'];
    	}
    	return $sum;
    }

    //得到已经填写种植信息的宗地
    public static function getOverArea($lease_id,$farms_id)
    {
		$isCZ = Plantingstructurecheck::find()->where(['lease_id'=>$lease_id,'farms_id'=>$farms_id])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
    	if($isCZ)
		{
			$result = Plantingstructurecheck::find()->where(['lease_id'=>$lease_id,'farms_id'=>$farms_id])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->sum('area');
			if($result)
				return $result;
			else
				return 0;
		} else
			return 0;

    }
	public static function getLeaseArea($farms_id)
	{
		$result = Lease::find()->where(['farms_id'=>$farms_id])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->sum('lease_area');
		if($result)
			return $result;
		else
			return 0.0;
	}
	public static function getNoArea($lease_id,$farms_id)
	{
		$over = self::getOverArea($lease_id, $farms_id);
		$leaseArea = self::getLeaseArea($farms_id);
		$plantingAllArea = Plantingstructurecheck::getAllArea($lease_id,$farms_id);
		$contractarea = Farms::getContractarea($farms_id);
		if($leaseArea) {
			$farmerArea = bcsub($contractarea,$leaseArea,2);
		} else {
			$farmerArea = $contractarea;
		}
		if($over)
			$result = abs(bcsub($farmerArea,$over,2));
		else
			$result = $farmerArea;
		if($result == 0)
			return $contractarea;
// 		var_dump($leaseArea);var_dump($contractarea);exit;
		return $result;
	}

    public static function getAllArea($lease_id,$farms_id)
    {
    	$planting = self::find()->where(['farms_id'=>$farms_id,'lease_id'=>$lease_id])->all();
    	$result = 0.0;
    	foreach ($planting as $value) {
    		$result += $value['area'];
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
    		$planting = Plantingstructurecheck::find()->where(['management_area'=>$where])->all();
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
	    		$result['typename'][] = Plant::find()->where(['id' => $value['id']])->one()['typename'];
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
	    			$result['typename'][] = Goodseed::find()->where(['id' => $value['id']])->one()['typename'];
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
    	if($params['PlantingstructurecheckSearch']['management_area'] == 0){
    		$management_area = [1,2,3,4,5,6,7];
    	} else
    		$management_area = $params['PlantingstructurecheckSearch']['management_area'];
    }
    public static function getPlantingstructurecheck($totalData)
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
    			$Plantingstructurecheck->where = $oldp;
    			$Plantingstructurecheck->andFilterWhere(['plant_id'=>$val]);
//     			var_dump($totalData->getModels());exit;
    			$area = $Plantingstructurecheck->sum('area');
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
		$Plantingstructurecheck = Plantingstructurecheck::find();
    	if(is_array($management_area)) {
    		foreach ($management_area as $value) {
    			$plantArea = [];
    			foreach ($plantid['id'] as $val) {
    				$Plantingstructurecheck->where = $oldp;
    				$Plantingstructurecheck->andFilterWhere(['goodseed_id'=>$val,'management_area'=>$value]);
    				$area = $Plantingstructurecheck->sum('area');
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
    		$Plantingstructurecheck->andFilterWhere(['management_area'=>$management_area]);
    		foreach ($plantid['id'] as $val) {
    			$Plantingstructurecheck->where = $oldp;
    			$Plantingstructurecheck->andFilterWhere(['goodseed_id'=>$val]);
    			//     			var_dump($Plantingstructurecheck->where);
    			$area = $Plantingstructurecheck->sum('area');
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
    	$planting = Plantingstructurecheck::find();
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
    	if(is_array($params)) {
	   		if($params['PlantingstructurecheckSearch']['management_area'] == 0){
		    		$management_area = [1,2,3,4,5,6,7];
		    	} else
		    		$management_area = $params['PlantingstructurecheckSearch']['management_area'];
		    	$where = [];
		    	$Farm = Farms::find();
		    	$Plantingstructurecheck = Plantingstructurecheck::find ();
		    	$data = [];
		    	$Plantingstructurecheck->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
		    	$Plantingstructurecheck->andFilterWhere(['management_area'=>$management_area]);
		    	$farmid = [];
		    	if(isset($params['PlantingstructurecheckSearch']['farms_id']) or isset($params['PlantingstructurecheckSearch']['farmer_id'])) {
		    		$farm = Farms::find();
		    		$farm->andFilterWhere(['management_area'=>$management_area]);
		    	}
	    		if(isset($params['PlantingstructurecheckSearch']['farms_id'])) {
		    		$farm->andFilterWhere(self::farmSearch($params['PlantingstructurecheckSearch']['farms_id']));

		    	}
		    	if(isset($params['PlantingstructurecheckSearch']['farmer_id'])) {
		    		$farm->andFilterWhere(self::farmerSearch($params['PlantingstructurecheckSearch']['farmer_id']));
		    	}
		    	if(isset($farm)) {
			    	foreach ($farm->all() as $value) {
			    		$farmid[] = $value['id'];
			    	}
		    		$Plantingstructurecheck->andFilterWhere(['farms_id'=>$farmid]);
		    	}
		    	if(isset($params['PlantingstructurecheckSearch']['plant_id']))
		    		$Plantingstructurecheck->andFilterWhere(['plant_id'=>$params['PlantingstructurecheckSearch']['plant_id']]);


		    	if(isset($params['PlantingstructurecheckSearch']['goodseed_id']))
		    		$Plantingstructurecheck->andFilterWhere(['goodseed_id'=>$params['PlantingstructurecheckSearch']['goodseed_id']]);

		    	if(isset($params['PlantingstructurecheckSearch']['area']))
		    		$Plantingstructurecheck->andFilterWhere(self::areaWhere($params['PlantingstructurecheckSearch']['area']));
	    	$area = $Plantingstructurecheck->sum ('area');
    	} else {
    		$area = Plantingstructurecheck::find()->where(['farms_id'=>$params])->andFilterWhere(['between','create_at',strtotime(User::getYear().'-01-01'),strtotime(User::getYear().'-12-31')])->sum('area');
    		return $area;
    	}
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
	    			// 	    		$result[$value['id']] = Plant::find()->where(['id'=>$value['id']])->one()['typename'];
	    		}
	    		$type = Goodseed::find()->where(['id'=>$allid])->all();
	    		
	    	}
    	}
//     	var_dump($type);exit;
    	foreach ($type as $value) {
    		$result[$value['id']] = Plant::find()->where(['id'=>$value['plant_id']])->one()['typename'].'->'.$value['typename'];
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
