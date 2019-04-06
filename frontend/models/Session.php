<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%session}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%session}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'key' => '键值',
            'value' => '数值',
        ];
    }
    
    public static function getValue($farms_id,$key)
    {
    	return self::find()->where(['key'=>$key,'id'=>$farms_id])->one()['value'];
    }
    
    public static function sessionSave($farms_id,$key,$value)
    {
   		$session = self::findOne($farms_id);
    	if(!$session)
    		$session = new Session();
    	$session->id = $farms_id;
    	$session->key = $key;
    	$session->value = $value;
    	$session->save();
    }
    
}
