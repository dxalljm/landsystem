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
    
    public static function getAreaname()
    {
    	$cache = 'cache-key-areaname'.\Yii::$app->getUser()->id;
    	$data = Yii::$app->cache->get($cache);
    	if (!empty($data)) {
    		return $data;
    	}
    	$whereArray = Farms::getManagementArea();
    	$area = ManagementArea::find()->where(['id'=>$whereArray['id']])->all();

        foreach ($area as $key => $val) {
            $data[$val->id] = $val->areaname;
        }
<<<<<<< HEAD
// 		var_dump($data);exit;
        Yii::$app->cache->set($cache, $data, 86400);
=======
        Yii::$app->cache->set($cache, $data, 86400);

>>>>>>> eaec1d78e94b3bce8fc1937e082afd1c832da24f
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
