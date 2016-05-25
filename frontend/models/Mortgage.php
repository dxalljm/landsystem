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
            [['isdydk', 'reviewprocess_id'], 'integer'],
            [['isdydkcontent'], 'string']
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
        ];
    }
    
    public static function attributesList()
    {
    	return [
    			'isdydk' => '是否存在抵押贷款',
    	];
    }
    
    public static function attributesKey()
    {
    	return [
    			'isdydk',
    	];
    }
}
