<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%machineoffarm}}".
 *
 * @property integer $id
 * @property integer $machine_id
 * @property integer $farms_id
 */
class Machineoffarm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%machineoffarm}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['machine_id', 'farms_id'], 'integer'],
        	[['machinename'],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'machine_id' => '农机ID',
            'farms_id' => '农场ID',
        	'machinename' => '机具名称',
        ];
    }
}
