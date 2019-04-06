<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%projectapplication}}".
 *
 * @property integer $id
 * @property string $projecttype
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $is_agree
 */
class Projectapplication extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%projectapplication}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_at', 'update_at', 'is_agree','farms_id','management_area','reviewprocess_id','farmstate'], 'integer'],
        	[['projectdata'],'number'],
            [['projecttype','unit','year'], 'string', 'max' => 500],
        	[['content'],'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
        	'farms_id' => '农场ID',
            'projecttype' => '项目类型',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'is_agree' => '是否立项',
        	'content' => '申请内容',
        	'management_area' => '管理区ID',
        	'reviewprocess' => '流程ID',
        	'projectdata' => '数量',
        	'unit' => '单位',
			'year' => '年度',
			'farmstate' => '农场状态'
        ];
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
    public static function getFarmRows($params)
    {
		if(isset($params['projectapplicationSearch']['management_area'])) {
			if ($params['projectapplicationSearch']['management_area'] == 0) {
				$management_area = [1, 2, 3, 4, 5, 6, 7];
			} else
				$management_area = $params['projectapplicationSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
		}
    	$where = [];
    	$Farm = Farms::find();
    	$Projectapplication = Projectapplication::find ();
    	$data = [];
    	$Projectapplication->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	$Projectapplication->andFilterWhere(['management_area'=>$management_area]);
    	$farmid = [];
    	$farm = Farms::find();
    	$farm->andFilterWhere(['management_area'=>$management_area]);
    	if(isset($params['plantingstructureSearch']['farms_id'])) {
	    	$farm->andFilterWhere(self::farmSearch($params['plantingstructureSearch']['farms_id']));
	    		 
	    }
	    if(isset($params['plantingstructureSearch']['farmer_id'])) {
	    	$farm->andFilterWhere(self::farmerSearch($params['plantingstructureSearch']['farmer_id']));
	    }
    	foreach ($farm->all() as $value) {
    		$farmid[] = $value['id'];
    	}
    	if($farmid) 
    		$Projectapplication->andFilterWhere(['farms_id'=>$farmid]);
    	//     	var_dump($farmid);exit;
    	if(isset($params['projectapplicationSearch']['projecttype']))
    		$Projectapplication->andFilterWhere(['projecttype'=>$params['projectapplicationSearch']['projecttype']]);
    	
    	foreach($Projectapplication->all() as $value) {
    		$data[] = ['id'=>$value['farms_id']];
    	}
//     	var_dump($data);
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		$result = count($newdata);
    	}
    	else
    		$result = 0;
    	return $result;
    }
    
    public static function getFarmerrows($params)
    {
		if(isset($params['projectapplicationSearch']['management_area'])) {
			if ($params['projectapplicationSearch']['management_area'] == 0) {
				$management_area = [1, 2, 3, 4, 5, 6, 7];
			} else
				$management_area = $params['projectapplicationSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
		}
    	$where = [];
    	$Farm = Farms::find();
    	$Projectapplication = Projectapplication::find ();
    	$data = [];
    	$Projectapplication->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	$Projectapplication->andFilterWhere(['management_area'=>$management_area]);
    	$farmid = [];

    	//     	var_dump($farmid);exit;
    	if(isset($params['projectapplicationSearch']['projecttype']))
    		$Projectapplication->andFilterWhere(['projecttype'=>$params['projectapplicationSearch']['projecttype']]);
		$data = [ ];
		$farm = Farms::find();
		if(isset($params['plantingstructureSearch']['farms_id'])) {
			$farm->andFilterWhere(self::farmSearch($params['plantingstructureSearch']['farms_id']));
		
		}
		if(isset($params['plantingstructureSearch']['farmer_id'])) {
			$farm->andFilterWhere(self::farmerSearch($params['plantingstructureSearch']['farmer_id']));
		}
		foreach ($Projectapplication->all() as $value) {
			$farmid[] = $value['farms_id'];
		}
		foreach ( $farm->where(['id'=>$farmid])->all() as $value ) {
			$data [] = [ 
					'farmername' => $value ['farmername'],
					'cardid' => $value ['cardid'] 
			];
		}
		if ($data) {
			$newdata = Farms::unique_arr ( $data );
			return count ( $newdata );
		} else
			return 0;
	}
    
    public static function getProjecttype($params)
    {
		if(isset($params['projectapplicationSearch']['management_area'])) {
			if ($params['projectapplicationSearch']['management_area'] == 0) {
				$management_area = [1, 2, 3, 4, 5, 6, 7];
			} else
				$management_area = $params['projectapplicationSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
		}
    	$where = [];
    	$Farm = Farms::find();
    	$Projectapplication = Projectapplication::find ();
    	$data = [];
    	$Projectapplication->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	$Projectapplication->andFilterWhere(['management_area'=>$management_area]);
    	$farmid = [];

    	//     	var_dump($farmid);exit;
    	if(isset($params['projectapplicationSearch']['projecttype']))
    		$Projectapplication->andFilterWhere(['projecttype'=>$params['projectapplicationSearch']['projecttype']]);
		$data = [ ];
		$farm = Farms::find();
		if(isset($params['plantingstructureSearch']['farms_id'])) {
			$farm->andFilterWhere(self::farmSearch($params['plantingstructureSearch']['farms_id']));
		
		}
		if(isset($params['plantingstructureSearch']['farmer_id'])) {
			$farm->andFilterWhere(self::farmerSearch($params['plantingstructureSearch']['farmer_id']));
		}
    	foreach($Projectapplication->all() as $value) {
    		$data[] = ['typeid'=>$value['projecttype']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
    	}
    	else
    		return 0;;
    }
    public static function getProjecttypename($params)
    {
		if(isset($params['projectapplicationSearch']['management_area'])) {
			if ($params['projectapplicationSearch']['management_area'] == 0) {
				$management_area = [1, 2, 3, 4, 5, 6, 7];
			} else
				$management_area = $params['projectapplicationSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
		}
    	$where = [];
    	$Farm = Farms::find();
    	$Projectapplication = Projectapplication::find ();
    	$data = [];
    	$Projectapplication->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	$Projectapplication->andFilterWhere(['management_area'=>$management_area]);
    	$farmid = [];
    
    	//     	var_dump($farmid);exit;
    	if(isset($params['projectapplicationSearch']['projecttype']))
    		$Projectapplication->andFilterWhere(['projecttype'=>$params['projectapplicationSearch']['projecttype']]);
    	$data = [ ];
    	$farm = Farms::find();
    	if(isset($params['plantingstructureSearch']['farms_id'])) {
    		$farm->andFilterWhere(self::farmSearch($params['plantingstructureSearch']['farms_id']));
    
    	}
    	if(isset($params['plantingstructureSearch']['farmer_id'])) {
    		$farm->andFilterWhere(self::farmerSearch($params['plantingstructureSearch']['farmer_id']));
    	}
    	foreach($Projectapplication->all() as $value) {
    		$data['id'][] = $value['projecttype'];
    		$data['typename'][] = Infrastructuretype::find()->where(['id'=>$value['projecttype']])->one()['typename'];
    	}
    	return $data;
    }
    public static function getTypenamelist($params = NULL)
    {
		if(isset($params['projectapplicationSearch']['management_area'])) {
			if ($params['projectapplicationSearch']['management_area'] == 0) {
				$management_area = [1, 2, 3, 4, 5, 6, 7];
			} else
				$management_area = $params['projectapplicationSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
		}
    	$where = [];
    	$Farm = Farms::find();
    	$Projectapplication = Projectapplication::find ();
    	$data = [];
//    	$Projectapplication->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	$Projectapplication->andFilterWhere(['management_area'=>$management_area,'year'=>User::getYear()]);
    	$farmid = [];
    
    	//     	var_dump($farmid);exit;
    	if(isset($params['projectapplicationSearch']['projecttype']))
    		$Projectapplication->andFilterWhere(['projecttype'=>$params['projectapplicationSearch']['projecttype']]);
    	$data = [ ];
    	$farm = Farms::find();
    	if(isset($params['plantingstructureSearch']['farms_id'])) {
    		$farm->andFilterWhere(self::farmSearch($params['plantingstructureSearch']['farms_id']));
    
    	}
    	if(isset($params['plantingstructureSearch']['farmer_id'])) {
    		$farm->andFilterWhere(self::farmerSearch($params['plantingstructureSearch']['farmer_id']));
    	}
    	$data = [];
    	$result = ['id'=>[],'projecttype'=>[],'unit'=>[]];
    	foreach ($Projectapplication->all() as $value) {
    		$data[] = ['id'=>$value['projecttype']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		foreach ($newdata as $value) {
    			$result['id'][] = $value['id'];
    			$result['projecttype'][] = Infrastructuretype::find()->where(['id' => $value['id']])->one()['typename'];
    			$typename = Infrastructuretype::find()->where(['id' => $value['id']])->one()['typename'];
    			if($typename)
    				$unitkey = $typename;
    			else
    				$unitkey = 0;
    			$result['unit'][$unitkey] = Projectapplication::find()->where(['projecttype'=>$value['id']])->one()['unit'];
    		}
    	}
    	//     	    	var_dump($result);
    	return $result;
    }
    public static function getProjectapplication($params) 
    {
		if(isset($params['projectapplicationSearch']['management_area'])) {
			if ($params['projectapplicationSearch']['management_area'] == 0) {
				$management_area = [1, 2, 3, 4, 5, 6, 7];
			} else
				$management_area = $params['projectapplicationSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
		}
    	$where = [];
    	$Farm = Farms::find();
    	$Projectapplication = Projectapplication::find ();
    	$data = [];
    	$Projectapplication->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	$Projectapplication->andFilterWhere(['management_area'=>$management_area]);
    	$farmid = [];
    	
    	//     	var_dump($farmid);exit;
    	if(isset($params['projectapplicationSearch']['projecttype']))
    		$Projectapplication->andFilterWhere(['projecttype'=>$params['projectapplicationSearch']['projecttype']]);
    	$data = [ ];
    	$farm = Farms::find();
    	if(isset($params['projectapplicationSearch']['farms_id'])) {
    		$farm->andFilterWhere(self::farmSearch($params['projectapplicationSearch']['farms_id']));
    	
    	}
    	if(isset($params['projectapplicationSearch']['farmer_id'])) {
    		$farm->andFilterWhere(self::farmerSearch($params['projectapplicationSearch']['farmer_id']));
    	}
    	$projecttype = self::getTypenamelist($params);
    	$oldp = $Projectapplication->where;
    	if(is_array($management_area)) {
    		foreach ($management_area as $value) {
    			$plantArea = [];
    			foreach ($projecttype['id'] as $val) {
    				$Projectapplication->where = $oldp;
    				$Projectapplication->andFilterWhere(['projecttype'=>$val,'management_area'=>$value]);
    				$nums = $Projectapplication->sum('projectdata');
    				$plantArea[] = $nums;
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
    		foreach ($projecttype['id'] as $val) {
    			$Projectapplication->where = $oldp;
    			$Projectapplication->andFilterWhere(['projecttype'=>$val,'management_area'=>$management_area]);
    			//     			var_dump($Plantingstructure->where);
    			$area = $Projectapplication->sum('projectdata');
    			//     			var_dump($area);
    			$plantArea[] = $area;
    		}
    		//     		var_dump($plantArea);
    		$result[] = [
    				'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id'=>$management_area])->one()['areaname']),
    				'type' => 'bar',
    				'stack' => $val,
    				'data' => $plantArea
    		];
    	}
    	return json_encode($result);
    }
    
    public static function getUnit()
    {
    	$data = [];
    	$project = Projectapplication::find()->all();
    	foreach ($project as $value) {
    		$data[$value['unit']] = $value['unit'];
    	}
    	$result = array_unique($data);
    	return $result;
    }

	public static function getProjectapplicationcache()
	{
		$where = Farms::getManagementArea()['id'];
		$type = self::getTypename()['id'];
//     	var_dump($type);
		//     	var_dump($input);exit;
		$data = [];
		$result = [];
		$dw = [];
		foreach ($where as $areaid) {
			foreach ($type as $value) {
				$sum = Projectapplication::find()->where(['management_area'=>$areaid,'projecttype'=>$value,'state'=>1])->sum('projectdata');
				if($sum)
					$data[$areaid][] = (float)$sum;
				else
					$data[$areaid][] = 0.0;
			}
		}
		if($data) {
			foreach ($data as $key => $value) {
				$result[] = [
					'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id' => $key])->one()['areaname']),
					'type' => 'bar',
					'stack' => $key,
					'data' => $value,
				];
			}
		} else {
			foreach ($where as $areaid) {
				$result[] = [
					'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id' => $areaid])->one()['areaname']),
					'type' => 'bar',
					'stack' => $areaid,
					'data' => [],
				];
			}
		}
//     	var_dump($result);
		$jsonData = json_encode ($result);

		return $jsonData;
	}

	public static function getTypename()
	{
		$where = Farms::getManagementArea()['id'];

		$input = Projectapplication::find()->where(['management_area'=>$where,'year'=>User::getYear()])->all();
		//     	var_dump($input);exit;
		$data = [];
		$result = ['id'=>[],'projecttype'=>[],'unit'=>[]];
		foreach ($input as $value) {
			$data[] = ['id'=>Infrastructuretype::find()->where(['id'=>$value['projecttype']])->one()['id']];
		}
		if($data) {
			$newdata = Farms::unique_arr($data);
			foreach ($newdata as $value) {
				$result['id'][] = $value['id'];
				$result['projecttype'][] = Infrastructuretype::find()->where(['id' => $value['id']])->one()['typename'];
				$result['unit'][] = Projectapplication::find()->where(['projecttype'=>$value['id']])->one()['unit'];
			}
		}
//     	    	var_dump($result);
		return $result;
	}
}
