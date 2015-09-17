<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%plantingstructure}}".
 *
 * @property integer $id
 * @property integer $plant_id
 * @property double $area
 * @property integer $inputproduct_id
 * @property integer $pesticides_id
 * @property integer $is_goodseed
 * @property integer $goodseed_id
 * @property string $zongdi
 * @property integer $farms_id
 */
class Plantingstructure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plantingstructure}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plant_id', 'inputproduct_id', 'pesticides_id', 'pconsumption', 'farms_id', 'lease_id'], 'integer'],
            [['area'], 'number'],
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
            'plant_id' => '种植结构',
            'area' => '种植面积',
            'inputproduct_id' => '化肥使用情况',
            'pesticides_id' => '农药使用情况',
            'pconsumption' => '农药用量',
            'goodseed_id' => '良种使用信息',
            'zongdi' => '宗地',
            'farms_id' => '农场ID',
        	'lease_id' => '承租人ID',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        ];
    }
}
