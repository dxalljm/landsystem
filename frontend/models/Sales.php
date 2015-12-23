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
            [['planting_id','farms_id','create_at','update_at','management_area'], 'integer'],
            [['volume', 'price'], 'number'],
            [['whereabouts'], 'string', 'max' => 500]
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
            'whereabouts' => '销售去向',
            'volume' => '销售量',
            'price' => '价格',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'management_area' => '管理区',
        ];
    }
    
    public static function getVolume($planting_id)
    {
    	$addSingle = 0;
    	$yields = Yields::find()->where(['planting_id'=>$planting_id])->one();
    	if($yields)
    		$allSingle = $yields->single*Plantingstructure::find()->where(['id'=>$planting_id])->one()['area'];
    	else
    		$allSingle = 0;
    	$sales = Sales::find()->where(['planting_id'=>$planting_id])->all();
    	if($sales) {
    		foreach ($sales as $value) {
    			$addSingle += $value['volume'];
    		}
    	}
    	$allSingle -= $addSingle;
    	return $allSingle;
    }
}
