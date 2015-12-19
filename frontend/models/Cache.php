<?php

namespace app\models;

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
            [['content'], 'string'],
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
            'actionname' => '方法名称',
            'content' => '备注',
        ];
    }
}
