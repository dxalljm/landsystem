<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%farmer}}".
 *
 * @property integer $id
 * @property string $farmername
 * @property string $cardid
 * @property integer $farms_id
 */
class Farmer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%farmer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id'], 'integer'],
            [['farmername', 'cardid'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'farmername' => '农场主',
            'cardid' => '身份证号',
            'farms_id' => '农场ID',
        ];
    }
}
