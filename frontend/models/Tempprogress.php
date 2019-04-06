<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tempprogress}}".
 *
 * @property integer $id
 * @property integer $temp_id
 */
class Tempprogress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tempprogress}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['temp_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'temp_id' => ' 临时ID',
        ];
    }
}
