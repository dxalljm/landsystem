<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%plant}}".
 *
 * @property integer $id
 * @property string $typename
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
            [['typename'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'typename' => '作物名称',
            'father_id' => '类别',
        ];
    }
    public static function areaWhere($str = NULL)
    {
    	 
    	if(!empty($str)) {
    		preg_match_all('/(.*)([0-9]+?)/iU', $str, $where);
    		//print_r($where);
    
    		// 		string(2) ">="
    		// 		string(3) "300"
    		if($where[1][0] == '>' or $where[1][0] == '>=')
    			$tj = ['between', 'area', (float)$where[2][0],(float)99999.0];
    		if($where[1][0] == '<' or $where[1][0] == '<=')
    			$tj = ['between', 'area', (float)0.0,(float)$where[2][0]];
    		if($where[1][0] == '')
    			$tj = ['like', 'area', $str];
    	} else
    		$tj = ['like', 'area', $str];
    	//var_dump($tj);
    	return $tj;
    }
    public static function farmSearch($str)
    {
    
    	if (preg_match ("/^[A-Za-z]/", $str)) {
    		$tj = ['like','pinyin',$str];
    	} else {
    		$tj = ['like','farmname',$str];
    	}
    
    	return $tj;
    }
    
    public static function farmerSearch($str)
    {
    
    	if (preg_match ("/^[A-Za-z]/", $str)) {
    		$tj = ['like','farmerpinyin',$str];
    	} else {
    		$tj = ['like','farmername',$str];
    	}
    	//     	var_dump($tj);exit;
    	return $tj;
    }
    public static function getAllname($totalData)
    {
//     	var_dump($totalData);exit;
    	$result = [];
    	
    	foreach($totalData->getModels() as $value) {
//     		var_dump($value->attributes);
    		$data[] = ['id'=>$value->attributes['plant_id']];
    	}
    	if($data) {
    	$newdata = Farms::unique_arr($data);
	    	foreach($newdata as $value) {
	    		$allid[] = $value['id'];
	//     		var_dump($value);exit;
// 	    		$result[$value['id']] = Plant::find()->where(['id'=>$value['id']])->one()['typename'];
	    	}
	    	$plants = Plant::find()->where(['id'=>$allid])->all();
	    	foreach ($plants as $value) {
	    		$result[$value->id] = $value->typename;
	    	}
    	}
//     	Yii::$app->cache->set($cache, $result, 86400);
    	return $result;
    }
    
    public static function getNameOne($totalData,$id)
    {
//     	var_dump($totalData);exit;
    	$data = self::getAllname($totalData);
    	return $data[$id];
    }
}
