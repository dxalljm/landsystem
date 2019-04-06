<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "land_nation".
 *
 * @property integer $id
 * @property string $nationname
 */
class Nation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'land_nation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nationname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'nationname' => '民族名称',
        ];
    }
}
