<?php

namespace console\models;

use Yii;
use console\models\Farms;
use console\models\Plantinputproduct;
use console\models\Lease;
use console\models\Inputproduct;

//use frontend\helpers\eActionColumn;

/**
 * This is the model class for table "{{%plantinputproduct}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $lessee_id
 * @property integer $father_id
 * @property integer $son_id
 * @property integer $inputproduct_id
 * @property double $pconsumption
 * @property string $zongdi
 * @property integer $plant_id
 */


// $e = new eActionColumn();
// var_dump($e);
// exit;

class Plantinputproduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plantinputproduct}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'lessee_id', 'father_id', 'son_id', 'inputproduct_id', 'plant_id','planting_id'], 'integer'],
            [['pconsumption'], 'number'],
            [['zongdi'], 'string', 'max' => 500]
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
            'lessee_id' => '承租人ID',
        	'planting_id' => '种植结构ID',
            'father_id' => '类别',
            'son_id' => '子类ID',
            'inputproduct_id' => '化肥使用情况',
            'pconsumption' => '农药用量',
            'zongdi' => '宗地',
            'plant_id' => '种植结构',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        ];
    }
    
    public static function getInputproduct($userid)
    {
    	$where = Farms::getUserManagementArea(15);
    	$farms = Farms::find()->where(['management_area'=>$where])->all();
    	foreach ($farms as $farm) {
    		$farmid[] = $farm['id'];
    	}
    	$input = Plantinputproduct::find()->where(['farms_id'=>$farmid])->all();
//     	var_dump($input);exit;
    	$data = [];
    	$lastresult = [];
    	foreach ($input as $value) {
    		$data[$value['inputproduct_id']][] = Lease::getArea($value['zongdi'])*$value['pconsumption'];
    	}
    	$sum = 0.0;
//     	var_dump($val);exit;
    	foreach ($data as $key => $val) {
    		foreach ($val as $v) {
    			$sum += $v;
    		}
    		
    		$name = Inputproduct::find()->where(['id'=>$key])->one()['fertilizer'];
    		$lastresult[] = (float)sprintf("%.2f", $sum/10000);
    	}
    	$result = [[
    			'type' => 'column',
    			'name' => '投入品',
    			'data' => $lastresult,
    			'dataLabels'=> [
    					'enabled'=> true,
    					'rotation'=> 0,
    					'color'=> '#FFFFFF',
    					'align'=> 'center',
    					'x'=> 0,
    					'y'=> 0,
    					'style'=> [
    							'fontSize'=> '13px',
    							'fontFamily'=> 'Verdana, sans-serif',
    							'textShadow'=> '0 0 3px black'
    					]
    			],
    			'tooltip' => [
    					'shared' => true,
    					'formatter' => ''
    			]
    	]];
    	$jsonData = json_encode ( [
    			'result' => $result
    	] );
    	
    	return $jsonData;
    }
    
    public static function getTypenamelist($userid)
    {
    	$result = [];
    	$where = Farms::getUserManagementArea(15);
    	$farms = Farms::find()->where(['management_area'=>$where])->all();
    	foreach ($farms as $farm) {
    		$farmid[] = $farm['id'];
    	}
    	$input = Plantinputproduct::find()->where(['farms_id'=>$farmid])->all();
//     	var_dump($input);exit;
    	$data = [];
    	$lastresult = [];
    	foreach ($input as $value) {
    		$data[$value['inputproduct_id']][] = Lease::getArea($value['zongdi'])*$value['pconsumption'];
    	}
    	$sum = 0.0;
//     	var_dump($val);exit;
    	foreach ($data as $key => $val) {
    		$name = Inputproduct::find()->where(['id'=>$key])->one()['fertilizer'];
    		$result[] = $name;
    	}

//     	var_dump($result);exit;
    	return $result;
    }
}
