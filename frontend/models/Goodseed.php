<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goodseed}}".
 *
 * @property integer $id
 * @property integer $plant_id
 * @property string $plant_model
 * @property double $planting_area
 * @property double $plant_measurearea
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
            [['plant_id'], 'integer'],
            [['planting_area', 'plant_measurearea'], 'number'],
            [['plant_model'], 'string', 'max' => 500]
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
            'plant_model' => '农作物型号',
            'planting_area' => '种植区域',
            'plant_measurearea' => '种植面积',
        ];
    }
}
