<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%sales}}".
 *
 * @property integer $id
 * @property integer $planting_id
 * @property string $whereabouts
 * @property double $volume
 * @property double $price
 */
class Sales extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['planting_id','farms_id','plant_id','create_at','update_at','management_area','year','state'], 'integer'],
            [['volume', 'price'], 'number'],
            [['whereabouts'],'string'],
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
        	'plant_id' => '种植类型',
            'whereabouts' => '销售去向',
            'volume' => '销售量',
            'price' => '价格',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'management_area' => '管理区',
            'year' => '年度',
            'state' => '农场状态',
        ];
    }
    
    public static function getVolume($planting_id)
    {
        $allSingle = 0;
		$plantingstructurecheck = Plantingstructurecheck::find()->where(['id'=>$planting_id])->one();
        $sales = Sales::find()->where(['planting_id'=>$planting_id])->sum('volume');
    	$yields = Yieldbase::find()->where(['plant_id'=>$plantingstructurecheck['plant_id'],'year'=>User::getLastYear()])->one();
    	if($yields)
    		$allSingle = $yields->yield*$plantingstructurecheck['area'];
    	$result = bcsub($allSingle,$sales,2);
    	return $result;
    }
}
