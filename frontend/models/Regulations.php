<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%regulations}}".
 *
 * @property integer $id
 * @property integer $reviewprocess_id
 * @property integer $sfdj
 * @property string $sfdjcontent
 */
class Regulations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%regulations}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reviewprocess_id', 'sfdj','regulationsisAgree'], 'integer'],
            [['sfdjcontent','regulationsisAgreecontent','regulationsundo'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'reviewprocess_id' => '审核过程ID',
            'sfdj' => '是否有司法机关、行政机关查封、冻结行为',
            'sfdjcontent' => '情况说明',
        	'regulationsisAgree' => '是否同意',
        	'regulationsisAgreecontent' => '情况说明',
            'regulationsundo' => '退回',
        ];
    }
    
    public static function attributesList()
    {
    	return [
    			'sfdj' => '是否有司法机关、行政机关查封、冻结行为',
//    			'regulationsisAgree' => '',
    	];
    }
    
    public static function attributesKey()
    {
    	return [
    			'sfdj',
    			'regulationsisAgree'
    	];
    }

    public static function loanAttributesList()
    {
        return [
            'sfdj' => '是否有司法机关、行政机关查封、冻结行为',
//    			'regulationsisAgree' => '',
        ];
    }

    public static function loanAttributesKey()
    {
        return [
            'sfdj',
            'regulationsisAgree'
        ];
    }
}
