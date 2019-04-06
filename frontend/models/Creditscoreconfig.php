<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%creditscoreconfig}}".
 *
 * @property integer $id
 * @property string $action
 * @property string $describe
 * @property integer $condition
 * @property integer $add
 * @property integer $sub
 */
class Creditscoreconfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%creditscoreconfig}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['condition', 'add', 'sub'], 'integer'],
            [['action', 'describe'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'action' => '动作',
            'describe' => '描述',
            'condition' => '判断条件',
            'add' => '加分',
            'sub' => '减分',
        ];
    }
}
