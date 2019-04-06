<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%inputproduct}}".
 *
 * @property integer $id
 * @property integer $father_id
 * @property string $fertilizer
 */
class Inputproduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%inputproduct}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['father_id'], 'integer'],
            [['fertilizer'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'father_id' => '类别',
            'fertilizer' => '肥料',
        ];
    }
}
