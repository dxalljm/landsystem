<?php

namespace app\models;

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
            [['departmentname', 'membership','leader','sectionchief','chippackage'], 'string', 'max' => 500]
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
        	'leader' => '分管领导',
        	'sectionchief' => '科长',
        	'chippackage' => '包片负责人',
        ];
    }
}
