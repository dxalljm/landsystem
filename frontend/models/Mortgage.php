<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%mortgage}}".
 *
 * @property integer $id
 * @property integer $isdydk
 * @property string $isdydkcontent
 * @property integer $reviewprocess_id
 */
class Mortgage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mortgage}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isdydk', 'reviewprocess_id','mortgageisAgree'], 'integer'],
            [['isdydkcontent','mortgageisAgreecontent','mortgageundo'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'isdydk' => '是否存在抵押贷款',
            'isdydkcontent' => '情况说明',
            'reviewprocess_id' => '审核过程ID',
        	'mortgageisAgree' => '是否同意',
        	'mortgageisAgreecontent' => '情况说明',
            'islockbook' => '是否提供银行冻结通知书',
        	'islockbookcontent' => '情况说明',
            'isunlockbook' => '是否提供银行解冻通知书',
            'isunlockbookcontent' => '情况说明',
            'mortgageundo' => '退回',
        ];
    }
    
    public static function attributesList()
    {
    	return [
    			'isdydk' => '是否存在抵押贷款',
//    			'mortgageisAgree' => ''
    	];
    }

    public static function loanAttributesList()
    {
        return [
            'islockbook' => '是否提供银行冻结通知书',
            'isunlockbook' => '是否提供银行解冻通知书',
        ];
    }

    public static function loanAttributesKey()
    {
        return [
            'islockbook',
            'isunlockbook',
        ];
    }

    public static function attributesKey()
    {
    	return [
    			'isdydk',
    			'mortgageisAgree'
    	];
    }
}
