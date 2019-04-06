<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%politicaloutlook}}".
 *
 * @property integer $id
 * @property string $typename
 */
class Politicaloutlook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%politicaloutlook}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typename'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'typename' => 'Typename',
        ];
    }
}
