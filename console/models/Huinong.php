<?php

namespace console\models;

use Yii;
use console\models\Theyear;
/**
 * This is the model class for table "{{%huinong}}".
 *
 * @property integer $id
 * @property string $subsidiestype_id
 * @property double $subsidiesarea
 * @property double $subsidiesmoney
 */
class Huinong extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%huinong}}';
    }

    /**
     * @inheritdoc
     */
public function rules() 
    { 
        return [
            [['subsidiesarea', 'subsidiesmoney', 'totalamount', 'realtotalamount'], 'number'],
            [['typeid', 'create_at', 'update_at'], 'integer'],
            [['subsidiestype_id', 'begindate', 'enddate'], 'string', 'max' => 500]
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'subsidiestype_id' => '补贴类型',
            'subsidiesarea' => '补贴面积',
            'subsidiesmoney' => '补贴金额',
            'typeid' => '补贴种类',
        	
            'totalamount' => '补贴总金额',
            'realtotalamount' => '实际补贴金额',
            'begindate' => '开始日期',
            'enddate' => '结束日期',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
        ]; 
    } 
    public static function getName($key)
    {
    	$huinongName = ['plant'=>'作物价格补贴','goodseed'=>'良种补贴'];
    	return $huinongName[$key];
    }
    public static function getHuinonginfo()
    {
    	$result = [];
    	$huinong = Huinong::find()->all();
//     	var_dump($huinong);
    	return $huinong;
    }
    public static function getHuinongname()
    {
    	$huinong = self::getHuinonginfo();
    	foreach($huinong as $value) {
    		$result[] = self::getName($value['subsidiestype_id']);
    	}
    	return $result;
    }

}
