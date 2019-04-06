<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%disaster}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $disastertype_id
 * @property double $disasterarea
 * @property string $disasterplant
 * @property double $insurancearea
 * @property double $yieldreduction
 * @property double $socmoney
 */
class Disaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%disaster}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'disastertype_id','isinsurance','create_at','update_at','management_area'], 'integer'],
            [['disasterarea', 'insurancearea', 'yieldreduction', 'socmoney'], 'number'],
            [['disasterplant'], 'string', 'max' => 500]
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
            'disastertype_id' => '灾害类型',
            'disasterarea' => '受灾面积',
            'disasterplant' => '受灾作物',
            'insurancearea' => '受保面积',
            'yieldreduction' => '减产量',
            'socmoney' => '理赔金额',
        	'isinsurance' => '是否保险',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'management_area' => '管理区',
        ];
    }
    
    public static function getFarmRows($params)
    {
    	$where = [];
    	foreach ($params['disasterSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$row = Yields::find ()->where ($where)->count ();
    	return $row;
    }
    
	public static function getFarmerrows($params)
    {
    	$where = [];
    	foreach ($params['disasterSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$yields = Yields::find ()->where ($where)->all ();
    	//     	var_dump($farms);exit;
    	$data = [];
    	foreach($yields as $value) {
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
    
    public static function getTypeRows($params)
    {
    	$where = [];
    	foreach ($params['disasterSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$result = Disaster::find ()->where ($where)->all ();
    	$data = [];
    	foreach($result as $value) {
    		$type = Disastertype::find()->where(['id'=>$value['disastertype_id']])->one();
    		if($type)
    			$data[] = ['id'=>$type['typename']];
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
    	$where = [];
    	foreach ($params['disasterSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$result = Disaster::find ()->where ($where)->all ();
    	$sum = 0.0;
    	foreach($result as $value) {
    		$sum += $value['disasterarea'];
    	}
    	return (float)sprintf("%.2f", $sum/10000);
    }
    
    public static function getPlantRows($params)
    {
    	$where = [];
    	foreach ($params['disasterSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$result = Disaster::find ()->where ($where)->all ();
    	$data = [];
    	foreach($result as $value) {
    		$data[] = ['id'=>Plant::find()->where(['id'=>$value['disasterplant']])->one()['typename']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
    	}
    	else
    		return 0;
    }
    
    public static function getBX($params)
    {
    	$where = [];
    	foreach ($params['disasterSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$result = Disaster::find ()->where ($where)->all ();
    	$sum = 0;
    	foreach($result as $value) {
    		if($value['isinsurance'])
    			$sum++;
    	}
    	return $sum;
    }
}
