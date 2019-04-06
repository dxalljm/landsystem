<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "land_plant_price".
 *
 * @property integer $id
 * @property string $plant
 * @property double $price
 * @property integer $years
 */
class PlantPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'land_plant_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['years'], 'integer'],
            [['plant'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'plant' => '小麦',
            'price' => '价格/亩',
            'years' => '年度',
        ];
    }
}
