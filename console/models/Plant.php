<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%plant}}".
 *
 * @property integer $id
 * @property string $cropname
 * @property integer $father_id
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
            [['father_id'], 'integer'],
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
            'father_id' => '类别',
        ];
    }
}
