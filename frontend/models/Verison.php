<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%verison}}".
 *
 * @property integer $id
 * @property string $ver
 */
class Verison extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%verison}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ver'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ver' => 'Ver',
        ];
    }
}
