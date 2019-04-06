<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%machinescanning}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property string $cardid
 * @property integer $create_at
 * @property integer $update_at
 * @property string $scanimage
 * @property integer $pagenumber
 */
class Machinescanning extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%machinescanning}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'create_at', 'update_at', 'pagenumber','machineapplymachine_id'], 'integer'],
            [['cardid', 'scanimage'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'farms_id' => '农场ID',
            'cardid' => '法人身份证',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'scanimage' => '扫描件',
            'pagenumber' => '页码',
            'machineapplymachine_id' => '已申请农机ID'
        ];
    }
}
