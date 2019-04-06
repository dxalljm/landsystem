<?php

namespace console\models;

use Yii;
use yii\base\Theme;


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
    
    public static function getYeartime($user_id)
    {
    	$year = User::getYear($user_id);
    	$thisyear = [strtotime($year.'-01-01 00:00:01'),strtotime($year.'-12-31 23:59:59')];
    	return $thisyear;
    }
    
    public static function getYear()
    {
    	return Theyear::findOne(1)['years'];
    }
    
    public static function setYear($year)
    {
    	$model = Theyear::findOne(1);
    	$model->years = $year;
    	$model->save();
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
    
    public static function extendDate($date,$location,$num) 
    {
    	$strDate = strtotime(date('Y-m-d',$date).' +'.$num.' '.$location);
    	return $strDate;
    }
}
