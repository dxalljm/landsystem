<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%contractnumber}}".
 *
 * @property integer $id
 * @property string $contractnumber
 * @property string $lifeyear
 */
class Contractnumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contractnumber}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lifeyear'], 'string'],
            [['contractnumber'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'contractnumber' => '合同号',
            'lifeyear' => '年限',
        ];
    }
}
