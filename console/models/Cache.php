c<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%cache}}".
 *
 * @property integer $id
 * @property string $actionname
 * @property string $content
 */
class Cache extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cache}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['actionname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'user_id' => '用户ID',
            'content' => '备注',
        ];
    }
}
