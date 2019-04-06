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
<<<<<<< HEAD
    	$model->zongdinumber = $zongdi['number'];
    	$model->measure = $zongdi['measure'];
=======
    	$model->zongdinumber = $zongdi;
    	$model->measure = $measure;
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    	$model->save();
    }
    
    public static function getZongdiArray($zongdi)
    {
<<<<<<< HEAD
//		var_dump($zongdi);exit;
		if(is_string($zongdi))
    		$array = explode('、', $zongdi);
		else
			$array = $zongdi;
    	$result = false;
=======
    	$array = explode('、', $zongdi);
    	$result = [];
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    	foreach ($array as $key => $value) {
    		$result[$key]['number'] = Lease::getZongdi($value);
    		$result[$key]['measure'] = Lease::getArea($value);
    	}
<<<<<<< HEAD
    
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
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
<<<<<<< HEAD
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
=======
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
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    }
    
    public static function zongdiDelete($farms_id,$zongdi)
    {
//     	var_dump($zongdi);exit;
<<<<<<< HEAD
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
=======
    	$newzongdi = self::getZongdiArray($zongdi);
    	foreach ($newzongdi as $value) {
    		$zdof = self::find()->where(['farms_id'=>$farms_id,'zongdinumber'=>$value['number'],'measure'=>$value['measure']])->one();
//     		var_dump($farms_id);
    		$model = Zongdioffarm::findOne($zdof['id']);
    		$model->delete();
    	}
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
//     	exit;
    }
}
