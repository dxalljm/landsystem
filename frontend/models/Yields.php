<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%yield}}".
 *
 * @property integer $id
 * @property integer $planting_id
 * @property integer $farms_id
 * @property double $single
 */
class Yields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yields}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['planting_id', 'farms_id'], 'integer'],
            [['single'], 'number']
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
            'single' => '单产',
        ];
    }
}
