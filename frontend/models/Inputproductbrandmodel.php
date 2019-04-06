<?php

namespace app\models;

use Yii;
use yii\helpers\Json;
/**
 * This is the model class for table "{{%Inputproductbrandmodel}}".
 *
 * @property integer $id
 * @property integer $inputproduct_id
 * @property string $brand
 * @property string $model
 */
class Inputproductbrandmodel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%inputproductbrandmodel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inputproduct_id'], 'integer'],
            [['brand', 'model', 'brandpinyin'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'inputproduct_id' => '化肥使用情况',
            'brand' => '品牌',
            'model' => '型号',
        	'brandpinyin' => '品牌拼音',
        ];
    }
    
    public static function getBrandmodel()
    {
    	$result = '';
    	$brandmodel = self::find()->all();
    	foreach ($brandmodel as $value) {
    		$result[] = [
    			'value' => $value['brandpinyin'],
    			'data' => $value['brand'].'-'.$value['model'],
    		];
    	}
    	return $jsonData = Json::encode($result);
    }
}
