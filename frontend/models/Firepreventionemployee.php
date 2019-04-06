<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%fireprevention_employee}}".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property integer $is_smoking
 * @property integer $is_retarded
 * @property string $create_at
 * @property string $update_at
 */
class Firepreventionemployee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fireprevention_employee}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'is_smoking', 'is_retarded','create_at','update_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'employee_id' => '雇工人员ID',
            'is_smoking' => '是否吸烟',
            'is_retarded' => '是否智障',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
        ];
    }
}
