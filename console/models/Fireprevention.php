<?php

namespace app\models;

use Yii;
use console\models\Farms;
/**
 * This is the model class for table "{{%fireprevention}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property string $firecontract
 * @property string $safecontract
 * @property string $environmental_agreement
 * @property string $firetools
 * @property string $mechanical_fire_cover
 * @property string $chimney_fire_cover
 * @property string $isolation_belt
 * @property string $propagandist
 * @property string $fire_administrator
 * @property string $cooker
 * @property string $fieldpermit
 * @property string $propaganda_firecontract
 * @property string $leaflets
 * @property string $employee_firecontract
 * @property string $rectification_record
 * @property string $equipmentpic
 * @property string $peoplepic
 * @property string $facilitiespic
 */
class Fireprevention extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fireprevention}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id','management_area'], 'integer'],
            [['firecontract', 'safecontract', 'environmental_agreement', 'firetools', 'mechanical_fire_cover', 'chimney_fire_cover', 'isolation_belt', 'propagandist', 'fire_administrator', 'cooker', 'fieldpermit', 'propaganda_firecontract', 'leaflets', 'employee_firecontract', 'rectification_record', 'equipmentpic', 'peoplepic', 'facilitiespic','year'], 'string', 'max' => 500]
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
        	'management_area' => '管理区',
            'firecontract' => '防火合同',
            'safecontract' => '安全生产合同',
            'environmental_agreement' => '环境保护协议',
            'firetools' => '扑火工具',
            'mechanical_fire_cover' => '机械设备防火罩',
            'chimney_fire_cover' => '烟囱防火罩',
            'isolation_belt' => '房屋防火隔离带',
            'propagandist' => '防火义务宣管员',
            'fire_administrator' => '一盒火管理员',
            'cooker' => '液化气灶具',
            'fieldpermit' => '野外作业许可证',
            'propaganda_firecontract' => '消防宣传合同',
            'leaflets' => '防火宣传单',
            'employee_firecontract' => '雇工防火合同',
            'rectification_record' => '防火检查整改记录',
            'equipmentpic' => '设备照片',
            'peoplepic' => '人员照片',
            'facilitiespic' => '设施照片',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'year' => '年度',
        ];
    }
    
    public static function setPercent()
    {
    	return [
	    	'firecontract' => 10,
	    	'safecontract' => 10,
	    	'environmental_agreement' => 10,
	    	'firetools' => 3,
	    	'mechanical_fire_cover' => 3,
	    	'chimney_fire_cover' => 3,
	    	'isolation_belt' => 3,
	    	'propagandist' => 3,
	    	'fire_administrator' => 5,
	    	'cooker' => 3,
	    	'fieldpermit' => 10,
	    	'propaganda_firecontract' => 5,
	    	'leaflets' => 10,
	    	'employee_firecontract' => 3,
	    	'rectification_record' => 10,
	    	'equipmentpic' => 3,
	    	'peoplepic' => 3,
	    	'facilitiespic' => 3,
    	];
    }
    
    public static function getFinishedField()
    {
    	return [
    			'firecontract' => 10,
    			'safecontract' => 10,
    			'environmental_agreement' => 10,
    			'fieldpermit' => 10,
    			'leaflets' => 10,
    			'rectification_record' => 10,
    		];
    }
    
    public static function getPercent($model,$re = 'number') {
    	$percent = 0;
    	foreach (self::setPercent() as $key => $value) {
    		if($model->$key) {
    			$percent += $value; 
    		}
    	}
    	if(empty($re))
    		return $percent.'%';
    	else
    		return $percent;
    }
    public static function getFinir($id)
    {
    	$yes = 0;
    	$no = 0;
    	$array = ['firecontract', 'safecontract', 'environmental_agreement', 'firetools', 'mechanical_fire_cover', 'chimney_fire_cover', 'isolation_belt', 'propagandist', 'fire_administrator', 'cooker', 'fieldpermit', 'propaganda_firecontract', 'leaflets', 'employee_firecontract', 'rectification_record', 'equipmentpic', 'peoplepic', 'facilitiespic'];
    	foreach ($array as $value) {
    		$fire = Fireprevention::find()->where(['id'=>$id])->one();
    		if($fire[$value])
    			$yes++;
    	}
    	return (float)sprintf("%.2f", $yes/count($array))*100;
    }
    
    public static function getBfblist($userid)
    {
    	$i = 0;
    	$all = [];
    	$finished = [];
//     	$percent = 0;
//     	$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
//     	$amountsColor = ['#fedfdf','#feeedf','#fefddf','#e1fedf','#dffcfe','#dfe3fe','#fedffe'];
//     	var_dump(Farms::getUserManagementArea($userid));
    	foreach ( Farms::getUserManagementArea($userid) as $value ) {
    		$farms = Farms::find()->where(['management_area'=>$value,'state'=>[1,2,3,4,5]])->all();
    		$percent = 0;
			foreach ($farms as $farm) {
				$fire = Fireprevention::find ()->where ( [
						'farms_id' => $farm['id'],
						'year' => date('Y'),
				] )->one();
				$isFinished = 0;
				foreach (self::getFinishedField() as $key => $value) {
					if($fire) {
						$isFinished += $fire[$key];
					}
				}
				if($isFinished == 6) {
					$percent++;
				}
			}
    		
//     		if($fires) {
    			$all[] = count($farms);
    			$finished[] = $percent;
//     		} else {
//     			$all[] = 100;
//     			$finished[] = (float)0;
//     		}
//     		if($value == 7) {
//     			var_dump($percent);
//     		}
    	}

    	return json_encode (['all'=>$all,'real'=>$finished]);
//     		$result = [[
//     				'name'=>'已完成',
//     				'type'=>'bar',
//     				'stack'=>'sum',
//     				'barCategoryGap'=>'50%',
//     				'itemStyle'=>[
//     						'normal'=> [
//     								'color'=> 'tomato',
//     								'barBorderColor'=> 'tomato',
//     								'barBorderWidth'=> 3,
// 					'barBorderRadius'=>0,
//     								'label'=>[
//     								'show'=> true,
//     								'position'=> 'insideTop'
//     						]
//     				]
//     		],
//     				'data'=>$finished,
//     		],
//     				[
//     				'name'=>'应完成',
//     				'type'=>'bar',
//     				'stack'=>'sum',
// 			'itemStyle'=> [
//     				'normal'=> [
//     						'color'=>'#fff',
// 					'barBorderColor'=> 'tomato',
// 					'barBorderWidth'=> 3,
// 					'barBorderRadius'=>0,
// 					'label' => [
//     								'show'=> false,
// 						'position'=> 'top',
//     							// 						'formatter'=> '{c}/10000.toFixed(2)',
//     								'textStyle'=>[
//     								'color'=> 'tomato'
//     						]
//     				]
//     		]
//     		],
//     				'data'=>$all,
//     			]];
    }
    
    public static function getAllbfb($userid)
    {
    	$i = 0;
		$finished = [];
		$allfire = 0;
		$percent = 0;
// 		$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
// 		$amountsColor = ['#fedfdf','#feeedf','#fefddf','#e1fedf','#dffcfe','#dfe3fe','#fedffe'];
		foreach ( Farms::getUserManagementArea($userid) as $value ) {
			$farms = Farms::find()->where(['management_area'=>$value,'state'=>[1,2,3,4,5]])->all();
			
			foreach ($farms as $farm) {
				$fire = Fireprevention::find ()->where ( [
						'farms_id' => $farm['id'],
						'year' => date('Y'),
				] )->one();
			$isFinished = 0;
				foreach (self::getFinishedField() as $key => $value) {
					if($fire) {
						$isFinished += $fire[$key];
					}
				}
				if($isFinished == 6) {
					$percent++;
				}
			}
			$allfire += count($farms);
		}
// 		echo '-----';
// 		var_dump($userid);
// 		if($allfire) {
// 			var_dump($percent/$allfire);
// 		} else {
// 			var_dump(0);
// 		}
// 		echo '))';
		if($allfire) {
			return (float)sprintf("%.2f", $percent/$allfire);
		} else {
			return 0;
		}

    }
}
