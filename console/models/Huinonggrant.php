<?php

namespace console\models;

use Yii;
use console\models\Huinong;
use console\models\Plant;
use console\models\Goodseed;
/**
 * This is the model class for table "{{%huinonggrant}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $huinong_id
 * @property double $money
 * @property double $area
 * @property integer $state
 * @property string $note
 */
class Huinonggrant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%huinonggrant}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'huinong_id', 'state','create_at','update_at','lease_id'], 'integer'],
            [['money', 'area'], 'number'],
            [['note'], 'string']
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
            'huinong_id' => '惠农政策ID',
            'money' => '补贴金额',
        	'lease_id' => '租赁者ID',
            'area' => '种植面积',
            'state' => '状态',
            'note' => '备注',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        ];
    }
    
    public static function getHuinonggrantinfo($userid)
    {
    	
    	$areaid = Farms::getUserManagementArea($userid);
    	$sum = [];
    	$allSum = [];
    	$result = [];
    	foreach ($areaid  as $key => $value ) {
    		$farms = Farms::find()->where(['management_area'=>$value]);
    		$huinong = Huinong::getHuinonginfo();
    		$huinonggrantSum = 0.0;
    		foreach ($huinong as $val) {
//     			var_dump($val);
    			if($val['subsidiestype_id'] == 'plant') {
    				$name = Plant::find()->where(['id'=>$val['typeid']])->one()['cropname'];
    			}
    			if($val['subsidiestype_id'] == 'goodseed') {
    				$goodseed =  Goodseed::find()->where(['id'=>$val['typeid']])->one();
    				$name = Plant::find()->where(['id'=>$goodseed['plant_id']])->one()['cropname'].'/'.$goodseed['plant_model'];
    			}
    			$allSum[$val['subsidiestype_id']]['name'] = $name;
    			$allSum[$val['subsidiestype_id']]['key'] = $val['subsidiestype_id'];
    			$allSum[$val['subsidiestype_id']]['data'][$key] = (float)sprintf("%.2f", $val['subsidiesmoney'] * $farms->sum('measure')/10000);
    			$allSum[$val['subsidiestype_id']]['stack'] = $val['subsidiestype_id']; 
    			foreach ($farms->all() as $v) {
    				$huinonggrant = Huinonggrant::find()->where(['farms_id'=>$v['id'],'huinong_id'=>$val['id'],'state'=>1])->one();
    				$huinonggrantSum += $huinonggrant['money'];
    			}
    			$sum[$val['subsidiestype_id']]['name'] = $name;
    			$sum[$val['subsidiestype_id']]['key'] = $val['subsidiestype_id'];
    			$sum[$val['subsidiestype_id']]['data'][$key] = (float)sprintf("%.2f", $huinonggrantSum/10000);
    			$sum[$val['subsidiestype_id']]['stack'] = $val['subsidiestype_id'];
    		}	
    	}
//     	var_dump($allSum);
    	
    	foreach ($allSum as $value) {
    			$result[] = 
	    			[
	    			// 					'color' => '#FFF',
	    					'name' => '应发',  
	    					'type' => 'bar',  
	    					'data' => $value['data'],
	    					'stack'=>'sum',
							'itemStyle'=> [
							'normal'=> [
								'color'=>'#fff',
								'barBorderColor'=> 'tomato',
								'barBorderWidth'=> 3,
								'barBorderRadius'=>0,
								'label' => [
									'show'=> true,
									'position'=> 'top',
			// 						'formatter'=> '{c}',
									'textStyle'=>[
										'color'=> 'tomato'
									]
								]
							]
						],
    			];
    	}
    	foreach($sum as $value) {
    		//     		var_dump($value['key']);exit;
    		$result[] =
    		[
    				// 					'color' => '#FFF',
    				'name' => '实发',
    				'type' => 'bar',
    				'data' => $value['data'],
    				'stack'=>'sum',
    				'barCategoryGap'=>'50%',
    				'itemStyle'=>[
    						'normal'=> [
    						'color'=> 'tomato',
    						'barBorderColor'=> 'tomato',
    						'barBorderWidth'=> 3,
    						'barBorderRadius'=>0,
    						'label'=>[
    								'show'=> true,
    								'position'=> 'insideTop'
    						]
    						]
    				],
    		];
    	}
//     	var_dump($result);
    	$jsonData = json_encode ($result);
		return $jsonData;
    }
}
