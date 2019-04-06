<?php
namespace console\models;

class elasticsearchtest extends \yii\elasticsearch\ActiveRecord
{
	public static function tableName()
    {
        return '{{%farms}}';
    }
	
    public static function showdb()
    {
    	var_dump(self::getDb());
    }
}