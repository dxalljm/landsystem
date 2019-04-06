<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%subsidiestype}}".
 *
 * @property integer $id
 * @property string $typename
 * @property string $address
 */
class Subsidiestype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subsidiestype}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typename', 'urladdress'], 'string', 'max' => 500]
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
            'urladdress' => '访问地址',
        ];
    }
}
