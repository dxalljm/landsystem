<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%bank_account}}".
 *
 * @property integer $id
 * @property string $accountnumber
 * @property integer $farmer_id
 * @property string $bank
 */
class BankAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bank_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farmer_id'], 'integer'],
            [['accountnumber', 'bank'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'accountnumber' => '账号',
            'farmer_id' => '法人ID',
            'bank' => '所属银行',
        ];
    }
}
