<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%disputetype}}".
 *
 * @property integer $id
 * @property string $typename
 */
class Disputetype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%disputetype}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typename'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'typename' => '类型名称',
        ];
    }
}
