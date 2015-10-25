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
            [['planting_id','farms_id'], 'integer'],
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
        ];
    }
    
    public static function getVolume($planting_id)
    {
    	$addSingle = 0;
    	$yields = Yields::find()->where(['planting_id'=>$planting_id])->one();
    	$allSingle = $yields->single*Plantingstructure::find()->where(['id'=>$planting_id])->one()['area'];
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
