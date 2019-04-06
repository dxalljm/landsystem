<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%makeovertype}}".
 *
 * @property integer $id
 * @property string $typename
 */
class Makeovertype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%makeovertype}}';
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
