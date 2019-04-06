<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pesticides}}".
 *
 * @property integer $id
 * @property string $pesticidename
 */
class Pesticides extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pesticides}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pesticidename'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'pesticidename' => '农药名称',
        ];
    }
}
