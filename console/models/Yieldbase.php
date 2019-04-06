<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%yieldbase}}".
 *
 * @property integer $id
 * @property integer $plant_id
 * @property double $yield
 * @property string $year
 */
class Yieldbase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yieldbase}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plant_id'], 'integer'],
            [['yield'], 'number'],
            [['year'], 'string', 'max' => 500]
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
            'yield' => '产量',
            'year' => '年度',
        ];
    }
}
