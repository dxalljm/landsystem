<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%plant}}".
 *
 * @property integer $id
 * @property string $cropname
 */
class Plant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plant}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cropname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'cropname' => '作物名称',
        ];
    }
}
