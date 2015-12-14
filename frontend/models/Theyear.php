<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%theyear}}".
 *
 * @property integer $id
 * @property integer $years
 */
class Theyear extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%theyear}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['years'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'years' => '年度',
        ];
    }
    
    public static function getYeartime()
    {
    	$year = Theyear::findOne(1)['years'];
    	$thisyear = [strtotime($year.'-01-01 00:00:01'),strtotime($year.'-12-31 23:59:59')];
    	return $thisyear;
    }
    
    public static function formatDate($begindate=false,$enddate=false)
    {
    	$timeFront = '';
    	$timeBack = '';
    	if($begindate) {
    		$timeFront = strtotime($begindate);
    	} else {
    		$timeFront = self::getYeartime()[0];
    	}
    	if($enddate) {
    		$timeBack = strtotime($enddate);
    	} else {
    		$timeBack = self::getYeartime()[1];
    	}
    	$whereDate = ['begindate'=>$timeFront,'enddate'=>$timeBack];
    	return $whereDate;
    }
}
