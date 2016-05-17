<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%insurancecompany}}".
 *
 * @property integer $id
 * @property string $companynname
 */
class Insurancecompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%insurancecompany}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['companynname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'companynname' => '保险公司名称',
        ];
    }
}
