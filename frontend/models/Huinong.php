<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%huinong}}".
 *
 * @property integer $id
 * @property string $subsidiestype_id
 * @property double $subsidiesarea
 * @property double $subsidiesmoney
 */
class Huinong extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%huinong}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	[['typeid'],'integer'],
            [['subsidiesarea', 'subsidiesmoney'], 'number'],
            [['subsidiestype_id'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'subsidiestype_id' => '补贴类型',
        	'typeid' => '补贴种类',
            'subsidiesarea' => '补贴面积',
            'subsidiesmoney' => '补贴金额',
        ];
    }
}
