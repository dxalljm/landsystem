<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%plant}}".
 *
 * @property integer $id
 * @property string $cropname
 * @property integer $father_id
 */
class Plant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plant}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['father_id'], 'integer'],
            [['cropname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'cropname' => '作物名称',
            'father_id' => '类别',
        ];
    }
    
    public static function getAllname()
    {
    	$cache = 'cache-key-plants';
    	$result = Yii::$app->cache->get($cache);
    	if (!empty($result)) {
    		return $result;
    	}
    	
    	
     	$result = [];
    	$where = Farms::getManagementArea()['id'];
    	$Plantingstructure = Plantingstructure::find ()->where (['management_area'=>$where])->all ();
    	$data = [];
    	foreach($Plantingstructure as $value) {
    		$data[] = ['id'=>$value['plant_id']];
    	}
    	if($data) {
    	$newdata = Farms::unique_arr($data);
	    	foreach($newdata as $value) {
	    		$allid[] = $value['id'];
	//     		var_dump($value);exit;
// 	    		$result[$value['id']] = Plant::find()->where(['id'=>$value['id']])->one()['cropname'];
	    	}
	    	$plants = Plant::find()->where(['id'=>$allid])->all();
	    	foreach ($plants as $value) {
	    		$result[$value->id] = $value->cropname;
	    	}
    	}
    	Yii::$app->cache->set($cache, $result, 86400);
    	return $result;
    }
    
    public static function getNameOne($id)
    {
    	$data = self::getAllname();
    	return $data[$id];
    }
}
