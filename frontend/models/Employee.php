<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%employee}}".
 *
 * @property integer $id
 * @property integer $father_id
 * @property string $employeetype
 * @property string $employeename
 * @property string $cardid
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%employee}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['father_id'], 'integer'],
            [['employeetype', 'employeename', 'cardid'], 'string', 'max' => 500]
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
            'employeetype' => '雇工类型',
            'employeename' => '雇工姓名',
            'cardid' => '身份证号',
        ];
    }
}
