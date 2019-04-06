<?php

namespace console\models;

use Yii;
use console\models\Projectapplication;
use console\models\Projecttype;
use console\models\Infrastructuretype;

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
            [['create_at', 'update_at', 'is_agree','farms_id','management_area','reviewprocess_id'], 'integer'],
            [['projecttype'], 'string', 'max' => 500],
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
        ];
    }
    
    public static function getFarmRows($params)
    {
    	$where = [];
    	foreach ($params['projectapplicationSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$row = Projectapplication::find ()->where ($where)->count ();
    	return $row;
    }
    
    public static function getFarmerrows($params)
    {
    	$where = [];
    	foreach ($params['projectapplicationSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$yields = Projectapplication::find ()->where ($where)->all ();
    	//     	var_dump($farms);exit;
    	$data = [];
    	foreach($yields as $value) {
    		$farmallid[] = $value['farms_id'];    		
    	}
    	$farms = Farms::find()->where(['id'=>$farmallid])->all();
    	foreach ($farms as $value) {
    		$data[] = ['farmername'=>$value['farmername'],'cardid'=>$value['cardid']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
    	}
    	else
    		return 0;;
    }
    
    public static function getProjecttype($params)
    {
    	$where = [];
    	foreach ($params['projectapplicationSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$yields = Projectapplication::find ()->where ($where)->all ();
    	//     	var_dump($farms);exit;
    	$data = [];
    	foreach($yields as $value) {
    		$data[] = ['typeid'=>$value['projecttype']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
    	}
    	else
    		return 0;;
    }
    
    public static function getProjectapplicationcache($userid)
    {
    	$where = Farms::getUserManagementArea($userid);
// 		var_dump($where);
//     	$project = Projectapplication::find()->where(['management_area'=>$where])->all();
    	$type = self::getTypenamelist($userid)['id'];
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
		
    	foreach ($data as $key => $value) {
    		$result[] = [
    			'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id'=>$key])->one()['areaname']),
    			'type' => 'bar',
    			'stack' => $key,
    			'data' => $value,
    		];
    	}
//     	var_dump($result);
    	$jsonData = json_encode ($result);
    	 
    	return $jsonData;
    }
    public static function getTypenamelist($userid)
    {
    	$where = Farms::getUserManagementArea($userid);

    	$input = Projectapplication::find()->where(['management_area'=>$where])->all();
    	//     	var_dump($input);exit;
    	$data = [];
    	$result = ['id'=>[],'projecttype'=>[]];
    	foreach ($input as $value) {
    		$data[] = ['id'=>Infrastructuretype::find()->where(['id'=>$value['projecttype']])->one()['id']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		foreach ($newdata as $value) {
    			$result['id'][] = $value['id'];
    			$result['projecttype'][] = Infrastructuretype::find()->where(['id' => $value['id']])->one()['typename'];
    			$result['unit'][Infrastructuretype::find()->where(['id' => $value['id']])->one()['typename']] = Projectapplication::find()->where(['projecttype'=>$value['id']])->one()['unit'];
    		}
    	}
//     	    	var_dump($result);
    	return $result;
    }
}
