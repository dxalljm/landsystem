<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%environment}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $management_area
 * @property double $contractarea
 * @property string $contractnumber
 * @property integer $state
 * @property integer $isgovernment
 */
class Environment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%environment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'management_area', 'state', 'isgovernment','create_at','update_at','year'], 'integer'],
            [['contractarea'], 'number'],
            [['contractnumber'], 'string', 'max' => 500]
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
            'management_area' => '管理区',
            'contractarea' => '合同面积',
            'contractnumber' => '合同号',
            'state' => '状态',
            'isgovernment' => '是否治理',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'year' => '年度',
        ];
    }
}
