<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\User;

/**
 * This is the model class for table "{{%goodseed}}".
 *
 * @property integer $id
 * @property integer $plant_id
 * @property string $typename
 */
class Goodseed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goodseed}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plant_id','state'], 'integer'],
            [['typename'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'plant_id' => '种植结构',
            'typename' => '农作物型号',
            'state' => '状态'
        ];
    }
    
    public static function getPlantGoodseed($plant_id)
    {
		$result = [];
    	$totalData = Plantingstructure::find()->where(['management_area'=>Farms::getManagementArea()['id'],'year'=>User::getYear()])->all();
    	foreach($totalData as $value) {
//     		var_dump($value->attributes);
    		$data[] = ['id'=>$value['goodseed_id']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		foreach($newdata as $value) {
    			$allid[] = $value['id'];
    			//     		var_dump($value);exit;
    			// 	    		$result[$value['id']] = Plant::find()->where(['id'=>$value['id']])->one()['typename'];
    		}
    		$plants = Goodseed::find()->where(['id'=>$allid,'plant_id'=>$plant_id])->all();
    		foreach ($plants as $value) {
    			$result[$value->id] = $value->typename;
    		}
    	}
    	return $result;
    }

    public static function isGoodseed($plant_id)
    {
        $goodseed = Goodseed::find()->where(['plant_id'=>$plant_id])->count();
        if($goodseed) {
            return $goodseed;
        }
        return 0;
    }
}
