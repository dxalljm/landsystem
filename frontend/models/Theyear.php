<?php

namespace app\models;

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
    
    public static function getYeartime()
    {
    	$year = User::getYear();
    	$thisyear = [strtotime($year.'-01-01 00:00:01'),strtotime($year.'-12-31 23:59:59')];
    	return $thisyear;
    }
    
    public static function getYear()
    {
    	return Theyear::find()->where(['id'=>1])->one()['years'];
    }

    public static function setYear($year)
    {
        $model = Theyear::findOne(1);
        $model->years = $year;
        $model->save();
//        return $model->years;
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
//时间结点,在此时间之前或之后的数据,用于查询年度农场数据
    public static function getTimenode()
    {
        return User::getYear().'-03-01 00:00:01';
    }

    //跨年数据处理
    public static function newYear()
    {
        $oldyear = '2016';
        if(Theyear::getYear() !== date('Y')) {
//            Theyear::setYear(date('Y'));
//            User::setAllUserYear(date('Y'));
            $collection = Collection::find()->all();
            foreach ($collection as $coll) {
                $model = Collection::findOne($coll['id']);
                if ($model->state == 0 and $model->payyear == $oldyear) {
                    $model->owe = $model->ypaymoney;
                    $model->ypayyear = $oldyear;
                }
                $model->save();
            }
        }
    }

    public static function dateToStr($date)
    {
        $array = explode('-',$date);
        return $array[0].'年'.$array[1].'月'.$array[2].'日';
    }

    public static function getMonths($year = null)
    {
        $result = [];
        if(empty($year)) {
            $year = User::getYear();
        }
        for ($i=1;$i<=12;$i++) {
            $mb = date("Y-".$i."-01", strtotime($year.'-'.$i.'-'.'01'));
            $me = date('Y-'.$i.'-t', strtotime($mb));
            $result[] = [$mb,$me];
        }
        return $result;
    }

    public static function getYears($begindate,$enddate)
    {
        $result = [];
        $year1 = date('Y',$begindate);
        $year2 = date('Y',$enddate);
        for($i=$year1;$i<=$year2;$i++) {
            $result[] = $i;
        }
        return $result;
    }
    
}
