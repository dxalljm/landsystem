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
<<<<<<< HEAD
            [['isqcbfcontent', 'othercontent','financeisAgreecontent','financeundo'], 'string']
=======
            [['isqcbfcontent', 'othercontent','financeisAgreecontent'], 'string']
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
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
<<<<<<< HEAD
        	'financeisAgreecontent'=>'情况说明',
            'financeundo' => '退回',
=======
        	'financeisAgreecontent'=>'情况说明'
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
        ];
    }
    
    public static function attributesList()
    {
    	return [
    		'isqcbf' => '是否欠缴宜农林地承包费',
            'other' => '是否存在其他款项',
<<<<<<< HEAD
//    		'financeisAgree' => ''
=======
    		'financeisAgree' => ''
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
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
<<<<<<< HEAD

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
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
}
