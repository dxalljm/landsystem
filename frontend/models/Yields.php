<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%yield}}".
 *
 * @property integer $id
 * @property integer $planting_id
 * @property integer $farms_id
 * @property double $single
 */
class Yields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yields}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['planting_id', 'farms_id','create_at','update_at','management_area'], 'integer'],
            [['single'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'planting_id' => '种植结构ID',
            'farms_id' => '农场ID',
            'single' => '单产',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'management_area'=> '管理区',
        ];
    }
    
    public static function getAllname()
    {
    	$result = [];
    	$where = Farms::getManagementArea()['id'];
    	$yields = Yields::find ()->where (['management_area'=>$where])->all ();
    	$data = [];
    	foreach($yields as $value) {
    		$data[] = ['id'=>Plantingstructure::find()->where(['id'=>$value['planting_id']])->one()['plant_id']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		foreach($newdata as $value) {
    			//     		var_dump($value);exit;
    			$result[$value['id']] = Plant::find()->where(['id'=>$value['id']])->one()['cropname'];
    		}
    	}
    	return $result;
    }
    
    public static function getNameOne($id)
    {
    	$data = self::getAllname();
    	return $data[$id];
    }
    
    public static function getFarmRows($params)
    {
    	$where = [];
    	foreach ($params['yieldsSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$row = Yields::find ()->where ($where)->count ();
    	return $row;
    }
    
    public static function getFarmerrows($params)
    {
    	$where = [];
    	foreach ($params['yieldsSearch'] as $key => $value) {
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
    
    public static function getPlantRows($params)
    {
    	$where = [];
    	foreach ($params['yieldsSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$yields = Yields::find ()->where ($where)->all ();
    	$data = [];
    	foreach($yields as $value) {
    		$planting = Plantingstructure::find()->where(['id'=>$value['planting_id']])->one();
    		if($planting)
    			$data[] = ['plant_id'=>Plant::find()->where(['id'=>$planting['plant_id']])->one()['cropname']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
    	}
    	else
    		return 0;
    }
   
    
    public static function getPlantG($params)
    {
    	$where = [];
    	foreach ($params['yieldsSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$yields = Yields::find ()->where ($where)->all ();
    	$sum = 0.0;
    	foreach($yields as $value) {
    		$sum += $value['single'];
    	}
    	return (float)sprintf("%.2f", $sum/10000);
    }
    
    public static function getPlantA($params)
    {
    	$where = [];
    	foreach ($params['yieldsSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$yields = Yields::find ()->where ($where)->all ();
    	$sum = 0.0;
    	foreach($yields as $value) {
    		$planting = Plantingstructure::find()->where(['id'=>$value['planting_id']])->one();
    		$sum += $value['single']*$planting['area'];
    	}
    	return (float)sprintf("%.2f", $sum/10000);
    }
    
    public static function getArea($params)
    {
    	$sum = 0.0;
   		$where = [];
    	foreach ($params['yieldsSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$yields = Yields::find ()->where ($where)->all();
    	foreach ($yields as $value) {
    		$planting = Plantingstructure::find()->where(['id'=>$value['planting_id']])->one();
    		$sum += $planting['area'];
    	}
    	return (float)sprintf("%.2f", $sum/10000);
    }
}
