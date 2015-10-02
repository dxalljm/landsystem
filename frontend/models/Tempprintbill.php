<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tempprintbill}}".
 *
 * @property integer $id
 * @property string $farmername
 * @property double $standard
 * @property double $number
 * @property double $amountofmoney
 * @property string $bigamountofmoney
 * @property integer $nonumber
 */
class Tempprintbill extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tempprintbill}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	[['amountofmoneys'], 'number'],
            [['standard', 'number', 'nonumber','amountofmoney','farmername', 'bigamountofmoney'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'farmername' => '法人姓名',
            'standard' => '标准',
            'number' => '数量',
            'amountofmoney' => '金额',
            'bigamountofmoney' => '大写金额',
            'nonumber' => '票号',
			'create_at' => '创建日期',
        	'update_at' => '更新日期',  
        	'amountofmoneys' => '金额计算',      		
        ];
    }
}
