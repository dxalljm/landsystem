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
    	$model->zongdinumber = $zongdi['number'];
    	$model->measure = $zongdi['measure'];
    	$model->save();
    }
    
    public static function getZongdiArray($zongdi)
    {
//		var_dump($zongdi);exit;
		if(is_string($zongdi))
    		$array = explode('、', $zongdi);
		else
			$array = $zongdi;
    	$result = false;
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
//    	var_dump($zongdi);exit;
    		$zongdioffarm = Zongdioffarm::find()->where(['farms_id'=>$farms_id])->all();
    		foreach ($zongdioffarm as $value) {
    			$zongdioffarmModel = Zongdioffarm::findOne($value['id']);
    			$zongdioffarmModel->delete();
    		}
    		if($zongdi) {
	    		$newzongdi = self::getZongdiArray($zongdi);
	    		
	    		foreach ($newzongdi as $value) {
		    		$model = new Zongdioffarm();
					$model->id = '';
		    		$model->farms_id = $newfarms_id;
		    		$model->zongdinumber = $value['number'];
		    		$model->measure = $value['measure'];
		    		$model->save();
//					var_dump($model);exit;
	    		}
    		}
    	
// else {
// 	    	if($zongdi) {
// 		    	$newzongdi = self::getZongdiArray($zongdi);
		    	
// 		    	foreach ($newzongdi as $value) {
		    		
// 		    		$zongdioffarm = Zongdioffarm::find()->where(['zongdinumber'=>$value['number'],'farms_id'=>$farms_id])->one();
// 	// 	    		var_dump($ID);exit;
// 		    		if($zongdioffarm) {
// 		    			$model = self::findOne($zongdioffarm['id']);
// 		    			if($zongdioffarm['measure'] == $value['measure']) {
// 		    				$model->delete();
// 		    			} else {
// 		    				$model->measure = bcsub($model->measure,$value['measure'],2);
// 		    				$model->save();
// 		    			}
// 		    		}
// 					$newzongdioffarm = Zongdioffarm::find()->where(['zongdinumber'=>$value['number'],'farms_id'=>$newfarms_id])->one();		
// 					if($newzongdioffarm) {
// 						$model = Zongdioffarm::findOne($newzongdioffarm['id']);
// 						$model->measure = bcadd($model->measure , $value['measure'],2);
// 					} else {
// 						$model = new Zongdioffarm();
// 						$model->farms_id = $newfarms_id;
// 						$model->zongdinumber = $value['number'];
// 						$model->measure = $value['measure'];
// 					}	    		
// 		    		$model->save();
// 		    	}
// 	    	}
//     	}
    }
    
    public static function zongdiDelete($farms_id,$zongdi)
    {
//     	var_dump($zongdi);exit;
// 		var_dump(self::find()->where(['farms_id'=>$farms_id])->one());exit;
    	$newzongdi = self::getZongdiArray($zongdi);
		if($newzongdi) {
	    	foreach ($newzongdi as $value) {
	//     		var_dump($value);
	    		$zdof = self::find()->andFilterWhere(['farms_id'=>$farms_id,'zongdinumber'=>$value['number']])->andFilterWhere(['like','measure',$value['measure']])->one();
	    		if($zdof) {
		    		$model = Zongdioffarm::findOne($zdof['id']);
		    		$model->delete();
	    		}
	    	}
		}
//     	exit;
    }
}
