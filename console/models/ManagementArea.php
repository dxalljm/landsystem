<?php

namespace console\models;

use Yii;

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
    public static function getAreaname()
    {
    	$cache = 'cache-key-areaname'.yii::$app->getUser()->id;
    	$data = Yii::$app->cache->get($cache);
    	if (!empty($data)) {
    		return $data;
    	}
    	$whereArray = Farms::getManagementArea();
    	$area = ManagementArea::find()->where(['id'=>$whereArray['id']])->all();
    
    	foreach ($area as $key => $val) {
    		$data[$val->id] = $val->areaname;
    	}
    	// 		var_dump($data);exit;
    	Yii::$app->cache->set($cache, $data, 86400);
    
    	return $data;
    }
    
    public static function getAreanameOne($id)
    {
    	//     	var_dump($id);
    	$data = self::getAreaname();
    	//     	var_dump($data);exit;
    	return  $data[$id];   //主要通过此种方式实现
    }
}
