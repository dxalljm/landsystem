<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%event}}".
 *
 * @property integer $id
 * @property string $controller
 * @property string $action
 * @property string $event_id
 * @property string $eventdescribe
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%event}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controller', 'action', 'event_id', 'eventdescribe'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'controller' => '控制器名称',
            'action' => '动作',
            'event_id' => '事件名称',
            'eventdescribe' => '事件描述',
        ];
    }
}
