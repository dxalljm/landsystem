<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%management_area}}".
 *
 * @property integer $id
 * @property string $areaname
 */
class ManagementArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%management_area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['areaname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'areaname' => '区域名称',
        ];
    }
    
    public static function getAreaname($id = NULL)
    {
//     	$cache = 'cache-key-areaname'.\Yii::$app->getUser()->id;
//     	$data = Yii::$app->cache->get($cache);
//     	if (!empty($data)) {
//     		return $data;
//     	}
<<<<<<< HEAD
        $data = '';
		if(empty($id)) {
	    	$whereArray = Farms::getManagementArea();
	    	$area = ManagementArea::find()->where(['id'=>$whereArray['id']])->all();

	        foreach ($area as $key => $val) {
	            $data[$val->id] = $val->areaname;
	        }
//            return '';
=======
		if(empty($id)) {
	    	$whereArray = Farms::getManagementArea();
	    	$area = ManagementArea::find()->where(['id'=>$whereArray['id']])->all();
	
	        foreach ($area as $key => $val) {
	            $data[$val->id] = $val->areaname;
	        }
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
		} else {
			$data = ManagementArea::find()->where(['id'=>$id])->one()['areaname'];
		}
//         if(count($data) > 1)
//         	array_splice($data,0,0,[0=>'全部']);
		
//         Yii::$app->cache->set($cache, $data, 86400);
    	return $data;
    }
    
    public static function getAreanameOne($id=null)
    {
//     	var_dump($id);exit;
        if(empty($id)) {
            return '';
        }
    	$data = self::getAreaname();
//     	var_dump($data);exit;
    	return  $data[$id];   //主要通过此种方式实现
    }

    public static function getManagementareaName($farms_id)
    {
        $farm = Farms::findOne($farms_id);
        return self::getAreanameOne($farm->management_area);
    }
    
    public static function getNowManagementareaName()
    {
    	$m = Farms::getManagementArea()['id'];
    	if(count($m) == 1)
    		return ManagementArea::find()->where(['id'=>$m])->one()['areaname'];
    }
}
