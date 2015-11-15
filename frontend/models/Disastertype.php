<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%disastertype}}".
 *
 * @property integer $id
 * @property string $typename
 */
class Disastertype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%disastertype}}';
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
