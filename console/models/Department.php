<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%department}}".
 *
 * @property integer $id
 * @property string $departmentname
 * @property string $membership
 * @property string $operableaction
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%department}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['departmentname', 'membership', 'operableaction'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'departmentname' => '科室名称',
            'membership' => '隶属权限',
            'operableaction' => '可操作的动作',
        ];
    }
}
