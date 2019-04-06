<?php
namespace console\models;

use yii\elasticsearch\ActiveRecord;

class test extends ActiveRecord
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