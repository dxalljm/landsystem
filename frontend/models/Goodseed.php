<?php

namespace app\models;

use Yii;

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
            [['plant_id'], 'integer'],
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
        ];
    }
}
