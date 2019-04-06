<?php

namespace app\models;

use app\models\User;
use Yii;
use app\models\Farms;
use yii\helpers\ArrayHelper;

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
            [['plant_id', 'plant_father','farms_id', 'lease_id','management_area','issame','state','isbank','bankstate','same_id','planter'], 'integer'],
            [['area','contractarea'], 'number'],
            [['zongdi','year','verifydate','lesseepinyin'], 'string'],
        	[['goodseed_id'],'safe'],
			[['plant_id'],'required']
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
			'isbank' => '是否填写银行卡号',
			'bankstate' => '银行审核状态',
			'same_id' => '相同时计划种植结构ID',
			'planter' => '是否为法人种植',
			'lesseepinyin' => '种植者拼音'
        ];
    }

	public static function getPlan_checkGoodseedname($totalData = null,$state = 'filter')
	{
		$result = [];
		$data = [];
		if(empty($totalData)) {
			$where = Farms::getManagementArea()['id'];
			$plan = Plantingstructure::find()->where(['management_area'=>$where,'year'=>User::getYear()])->all();
			$plan_plantid = [];
			foreach ($plan as $value) {
				if($value['goodseed_id']) {
					$plan_plantid[] = $value['goodseed_id'];
				}
			}
			$newplan_plantid = array_unique($plan_plantid);
			$check = Plantingstructurecheck::find()->where(['management_area'=>$where,'year'=>User::getYear()])->all();
			$check_plantid = [];
			foreach ($check as $value) {
				if($value['goodseed_id']) {
					$plan_plantid[] = $value['goodseed_id'];
				}
			}
			$newcheck_plantid = array_unique($check_plantid);
			$merge_data = array_merge($newplan_plantid,$newcheck_plantid);
			$data = array_unique($merge_data);
			foreach ($data as $value) {
				$name[] = Goodseed::findOne($value)->typename;
			}
			return ['id'=>$data,'typename'=>$name];
		} else {
			foreach($totalData->getModels() as $value) {
				if(!empty($value->attributes['goodseed_id'])) {
					$plantid[] = $value->attributes['goodseed_id'];
					$plantname[] = Goodseed::findOne($value->attributes['goodseed_id'])->typename;
					if($state == 'filter') {
						$result[$value->attributes['goodseed_id']] = Goodseed::findOne($value->attributes['goodseed_id'])->typename;
					}
				}
			}
			$data = array_unique($plantid);
			foreach ($data as $value) {
				$name[] = Goodseed::findOne($value)->typename;
			}
			if($state == 'filter') {
				return $result;
			}
			return ['id'=>$data,'typename'=>$name];
		}
	}

	//判断流转前的用户是否已经填写种植结构复核信息
	public static function isPlant($farmsidArray)
	{
		$planting = Plantingstructurecheck::find()->where(['farms_id'=>$farmsidArray,'year'=>User::getYear()])->count();
		if($planting) {
			return true;
		}
		return false;
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
		$result = Plantingstructurecheck::find()->where(['lease_id'=>$lease_id,'farms_id'=>$farms_id,'year'=>User::getYear()])->sum('area');
		if($result)
			return sprintf('%.2f',$result);
		else
			return 0;


    }
	
	public static function unUseZongdi($farms_id,$check_id=null)
	{
		$zongdi = [];
		$ps = Plantingstructurecheck::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
		foreach ($ps as $p) {
			if($p['id'] !== (int)$check_id) {
				$zongdi = array_merge_recursive($zongdi, explode('、', $p['zongdi']));
			}
		}
		$unzongdi = [];
		$farm = Farms::findOne($farms_id);
		foreach (explode('、',$farm['zongdi']) as $zd) {
			$temp = Parcel::in_parcel($zd,$zongdi);
			if(bccomp($temp['area'],0) == 1) {
				$unzongdi[] = $temp['value'];
			}
		}
//		var_dump($unzongdi);exit;
		return $unzongdi;
	}
	//合并宗地(面积合并)
	public static function mergeZongdi($farms_id,$check_id)
	{
		$array = self::unUseZongdi($farms_id);
		$c = Plantingstructurecheck::findOne($check_id);
		if(empty($c['zongdi'])) {
			return $array;
		}
		$result = Parcel::zongdi_merge_area(explode('、',$c['zongdi']),$array);
		return $result;
	}
	//返回给入宗地面积之和
	public static function zongdiAreaSum($farms_id,$check_id)
	{
		$sum = 0;
		foreach (self::mergeZongdi($farms_id,$check_id) as $zongdi) {
			$sum += Lease::getArea($zongdi);
		}
		return sprintf('%.2f',$sum);
	}

	public static function getLeaseArea($farms_id,$lease_id=null)
	{
		if(empty($lease_id) or $lease_id == 0) {
			$contractarea = Farms::findOne($farms_id)->contractarea;
			$lease = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->sum('lease_area');
			if($lease) {
				$result = bcsub($contractarea,$lease,2);
			} else {
				$result = $contractarea;
			}
//			$result = Lease::find()->where(['farms_id' => $farms_id, 'year' => User::getYear()])->sum('lease_area');
		} else {
			$result = Lease::find()->where(['id' => $lease_id])->sum('lease_area');
		}
		if($result)
			return sprintf('%.2f',$result);
		else
			return 0.0;
	}
	public static function getNoArea($lease_id,$farms_id)
	{
		$over = self::getOverArea($lease_id, $farms_id);
//		var_dump($over);
		$leaseArea = self::getLeaseArea($farms_id,$lease_id);
//		var_dump($leaseArea);exit;
		$contractarea = Farms::getContractarea($farms_id);
		//如果等于0,则表示全部租赁了
//		if(bccomp($contractarea,$leaseArea) == 0) {
//			if($over)
//				$result = abs(bcsub($leaseArea,$over,2));
//			else
//				$result = $leaseArea;
//		} else {
//			if ($leaseArea) {
//				$farmerArea = bcsub($contractarea, $leaseArea, 2);
//			} else {
//				$farmerArea = $contractarea;
//			}
//			if($over)
//				$result = abs(bcsub($farmerArea,$over,2));
//			else
//				$result = $farmerArea;
//		}
		$result = bcsub($leaseArea,$over,2);
//		if($result == 0)
//			return $contractarea;
// 		var_dump($leaseArea);var_dump($contractarea);exit;
//		var_dump($result);exit;
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

	public static function getUserPlantname($user_id=null,$state='all')
	{
		$result = [];
		$data = [];
		if(empty($user_id)) {
			$where = Farms::getManagementArea()['id'];
		} else {
			$where = Farms::getUserManagementArea($user_id)['id'];
		}
		$planting = Plantingstructurecheck::find()->where(['management_area'=>$where,'year'=>User::getLastYear()])->all();
		foreach ($planting as $value) {
			$data[] = ['id'=>$value['plant_id']];
		}

		if($data) {
			$newdata = Farms::unique_arr($data);
			foreach ($newdata as $value) {
				$result[$value['id']] =  Plant::find()->where(['id' => $value['id']])->one()['typename'];
			}
		}
		if($state == 'all') {
			return $result;
		} else {
			foreach ($result as $value) {
				$new[] = $value;
			}
			return $new;
		}
	}

	public static function getPlantname($totalData = NULL)
    {
    	$result = [];
    	$data = [];
    	if(empty($totalData)) {
    		$where = Farms::getManagementArea()['id'];
    		$planting = Plantingstructurecheck::find()->where(['management_area'=>$where,'year'=>User::getYear()])->all();
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
	    		$result[$value['id']] =  Plant::find()->where(['id' => $value['id']])->one()['typename'];
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
		$Plantingstructurecheck = Plantingstructurecheck::find();
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
    			foreach ($plantid as $id => $val) {
    				$Plantingstructurecheck->where = $oldp;
    				$Plantingstructurecheck->andFilterWhere(['goodseed_id'=>$id,'management_area'=>$value]);
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
		$type = [];
	    	foreach($totalData->getModels() as $value) {
	    		if($value->attributes['plant_id'])
	    			$data[] = ['id'=>$value->attributes['plant_id']];
	    	}
	    	if($data) {
	    		$newdata = Farms::unique_arr($data);
	    		foreach($newdata as $value) {
	    			$allid[] = $value['id'];
	    			//     		var_dump($value);exit;
	    			// 	    		$result[$value['id']] = Plant::find()->where(['id'=>$value['id']])->one()['typename'];
	    		}
	    		$type = Plant::find()->where(['id'=>$allid])->all();

	    	}

//     	var_dump($type);exit;
    	foreach ($type as $value) {
    		$result[$value['id']] = Plant::find()->where(['id'=>$value['id']])->one()['typename'];
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

	public static function getPlantingstructure()
	{
		$result = [];
		$areaNum = 0;
		$plantArea['fact']['data'][] = 0;
		$area = Farms::getManagementArea()['id'];
//		$plantid = self::getPlantname();
//		$plantid = Plant::find()->where('father_id>1')->all();
////		var_dump($plantid);
////		foreach ( $area as $key => $value ) {
////			$areaNum++;
////			// 农场区域
//			$plantArea = [];
//			foreach ($plantid as $val) {
//				$planSum = Plantingstructure::find()->where(['management_area'=>$area,'plant_id'=>$val['id'],'year'=>User::getYear()])->sum('area');
//				$factSum = Plantingstructurecheck::find()->where(['management_area'=>$area,'plant_id'=>$val['id'],'year'=>User::getYear()])->sum('area');
//				if(empty($planSum)) {
//					$planSum = 0;
//				}
//				if(empty($factSum)) {
//					$factSum = 0;
//				}
//				$plantArea['plan'][$val['id']][] = (float)sprintf("%.2f", $planSum);
//				$plantArea['fact'][$val['id']][] = (float)sprintf("%.2f", $factSum);
//			}
//			$result[] = $plantArea;
//		}
//		var_dump($plantArea);exit;
//		 ($plantArea);
		$plants = Plant::getPlanAllname();
		foreach ($plants as $plant_id => $typename) {
			$plansum = sprintf("%.2f", Plantingstructure::find()->where(['management_area'=>$area,'year'=>User::getYear()])->andWhere(['plant_id'=>$plant_id])->sum('area'));
			$plantArea['plan']['name'][] = $typename;
			$plantArea['plan']['data'][] = ['value'=>$plansum,'name'=>$typename];
		}
		$plantsFact = Plant::getCheckAllname();
		foreach ($plantsFact as $plant_id => $typename) {
			$factsum = sprintf("%.2f", Plantingstructurecheck::find()->where(['management_area'=>$area,'year'=>User::getYear()])->andWhere(['plant_id'=>$plant_id])->sum('area'));
			$plantArea['fact']['name'][] = $typename;
			$plantArea['fact']['data'][] = ['value'=>$factsum,'name'=>$typename.'('.$factsum.')'];
		}
//		if(empty($plantArea)) {
//			$plantArea['fact']['data'][] = 0;
//		}
		return $plantArea;
	}

	public static function getPlantGoodseedSum() {
		$plant = [];
		$goodseed = [];
		$planSum = 0.0;
		$planGoodseedSum = 0.0;
		$factSum = 0.0;
		$factGoodseedSum = 0.0;
		$area = Farms::getManagementArea()['id'];
		$plan = Plantingstructure::find()->where(['management_area'=>$area,'year'=>User::getYear()])->all();
		foreach ($plan as $v) {
			$planSum += $v['area'];
			if($v['goodseed_id']) {
				$goodseedarea = $v['area'];
			} else {
				$goodseedarea = 0.0;
			}

			$planGoodseedSum += $goodseedarea;
			// 					var_dump($goodseed);
		}
		$fact = Plantingstructurecheck::find()->where(['management_area'=>$area,'year'=>User::getYear()])->all();
		foreach ($fact as $v) {
			$factSum += $v['area'];
			if($v['goodseed_id']) {
				$goodseedarea = $v['area'];
			} else {
				$goodseedarea = 0.0;
			}

			$factGoodseedSum += $goodseedarea;
			// 					var_dump($goodseed);
		}
		return ['plan'=>['plantSum'=>(float)sprintf("%.2f", $planSum),'goodseedSum'=>(float)sprintf("%.2f", $planGoodseedSum)],'fact'=>['plantSum'=>(float)sprintf('%.2f',$factSum),'goodseedSum'=>(float)sprintf("%.2f", $factGoodseedSum)]];
//		return ['plantSum'=>(float)sprintf("%.2f", $plantsum),'goodseedSum'=>(float)sprintf("%.2f", $goodseedsum)];
	}

//	public static function getUserPlantname()
//	{
//		$result['id'] = [];
//		$result['plantname'] = [];
//		$where = Farms::getManagementArea()['id'];
////     	var_dump($userid);
////     	var_dump($where);
//		$Plantingstructure = Plantingstructurecheck::find ()->where (['management_area' => $where,'year'=>User::getYear()])->all ();
//		$data = [];
//		foreach($Plantingstructure as $value) {
//			$data[] = ['id'=>$value['plant_id']];
//		}
//		if($data) {
//			$newdata = Farms::unique_arr($data);
//			foreach ($newdata as $value) {
//				$result['id'][] = $value;
//				$result['plantname'][] = Plant::find()->where(['id' => $value])->one()['typename'];
//			}
//		}
//		return $result;
//	}


}
