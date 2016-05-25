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
        return '{{%mortgage}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
    }
    
    public static function attributesList()
    {
    	return [];
    }
    
    public static function attributesKey()
    {
    	return [];
    }
}
