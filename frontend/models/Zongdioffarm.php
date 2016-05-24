<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%zongdioffarm}}".
 *
 * @property integer $id
 * @property string $zongdinumber
 * @property integer $farms_id
 * @property double $measure
 */
class Zongdioffarm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zongdioffarm}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id'], 'integer'],
            [['measure'], 'number'],
            [['zongdinumber'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'zongdinumber' => '宗地号',
            'farms_id' => '农场ID',
            'measure' => '面积',
        ];
    }
    
    public static function add($farms_id,$zongdi)
    {
    	$model = new Zongdioffarm();
    	$model->farms_id = $farms_id;
    	$model->zongdinumber = $zongdi;
    	$model->measure = $measure;
    	$model->save();
    }
    
    public static function getZongdiArray($zongdi)
    {
    	$array = explode('、', $zongdi);
    	$result = [];
    	foreach ($array as $key => $value) {
    		$result[$key]['number'] = Lease::getZongdi($value);
    		$result[$key]['measure'] = Lease::getArea($value);
    	}
    	return $result;
    }
    
    public static function getZongdiOfFarmID($farms_id)
    {
    	$result = [];
    	$zdof = Zongdioffarm::find()->where(['farms_id'=>$farms_id])->all();
    	foreach ($zdof as $key => $zd) {
    		$result[] = $zd['id'];
    	}
    	return $result;
    }
    
    
    public static function zongdiUpdate($farms_id,$newfarms_id,$zongdi)
    {
    	if($farms_id) {
	    	$arrayID = self::getZongdiOfFarmID($farms_id);
	    	if($arrayID) {
		    	foreach ($arrayID as $id) {
		    		$model = self::findOne($id);
		    		$model->delete();
		    	}
	    	}
    	}
    	if($zongdi) {
	    	$newzongdi = self::getZongdiArray($zongdi);
	    	foreach ($newzongdi as $zongdi) {
	    		$model = new Zongdioffarm();
	    		$model->farms_id = $newfarms_id;
	    		$model->zongdinumber = $zongdi['number'];
	    		$model->measure = $zongdi['measure'];
	    		$model->save();
	    	}
    	}
    }
    
    public static function zongdiDelete($farms_id,$zongdi)
    {
//     	var_dump($zongdi);exit;
    	$newzongdi = self::getZongdiArray($zongdi);
    	foreach ($newzongdi as $value) {
    		$zdof = self::find()->where(['farms_id'=>$farms_id,'zongdinumber'=>$value['number'],'measure'=>$value['measure']])->one();
//     		var_dump($farms_id);
    		$model = Zongdioffarm::findOne($zdof['id']);
    		$model->delete();
    	}
//     	exit;
    }
}
