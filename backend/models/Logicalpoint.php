<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%logicalpoint}}".
 *
 * @property integer $id
 * @property string $actionname
 * @property string $processname
 */
class Logicalpoint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%logicalpoint}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['actionname', 'processname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'actionname' => '方法名称',
            'processname' => '流程名称',
        ];
    }
}
