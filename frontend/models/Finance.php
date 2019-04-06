<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%finance}}".
 *
 * @property integer $id
 * @property integer $isqcbf
 * @property string $isqcbfcontent
 * @property integer $other
 * @property string $othercontent
 */
class Finance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isqcbf', 'other','reviewprocess_id','financeisAgree'], 'integer'],
            [['isqcbfcontent', 'othercontent','financeisAgreecontent','financeundo'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'isqcbf' => '是否欠缴宜农林地承包费',
            'isqcbfcontent' => '情况说明',
            'other' => '是否存在其他款项',
            'othercontent' => '情况说明',
        	'reviewprocess_id' => '流程ID',
        	'financeisAgree' => '是否同意',
        	'financeisAgreecontent'=>'情况说明',
            'financeundo' => '退回',
        ];
    }
    
    public static function attributesList()
    {
    	return [
    		'isqcbf' => '是否欠缴宜农林地承包费',
            'other' => '是否存在其他款项',
//    		'financeisAgree' => ''
    	];
    }
    
    public static function attributesKey()
    {
    	return [
    			'isqcbf',
    			'other',
    			'financeisAgree'
    	];
    }

    public static function loanAttributesList()
    {
        return [
            'isqcbf' => '是否欠缴宜农林地承包费',
            'other' => '是否存在其他款项',
//    		'financeisAgree' => ''
        ];
    }

    public static function loanAttributesKey()
    {
        return [
            'isqcbf',
            'other',
            'financeisAgree'
        ];
    }
}
