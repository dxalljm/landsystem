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
class Leader extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%leader}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        		[['reviewprocess_id', 'leaderisAgree'], 'integer'],
        		[['leaderisAgreecontent'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        	'leaderisAgree' => '是否同意',
        	'leaderisAgreecontent' => '情况说明'
        ];
    }
    
    public static function attributesList()
    {
    	return ['leaderisAgree' => ''];
    }
    
    public static function attributesKey()
    {
    	return ['leaderisAgree'];
    }
}
