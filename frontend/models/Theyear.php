<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%theyear}}".
 *
 * @property integer $id
 * @property integer $years
 */
class Theyear extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%theyear}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['years'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'years' => '年度',
        ];
    }
}
