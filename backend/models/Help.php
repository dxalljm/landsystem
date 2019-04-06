<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%help}}".
 *
 * @property integer $id
 * @property string $mark
 * @property string $content
 * @property string $title
 */
class Help extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%help}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['mark', 'title'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'mark' => '标识',
            'content' => '备注',
            'title' => '标题',
        ];
    }
}
