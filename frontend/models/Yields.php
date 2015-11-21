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
            [['planting_id', 'farms_id','create_at','update_at'], 'integer'],
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
        	'create_at' => '创建日期',
        	'update_at' => '更新日期'
        ];
    }
}
