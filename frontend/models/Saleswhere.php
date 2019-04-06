<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%saleswhere}}".
 *
 * @property integer $id
 * @property string $wherename
 */
class Saleswhere extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%saleswhere}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wherename'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'wherename' => '销售去向',
        ];
    }
}
