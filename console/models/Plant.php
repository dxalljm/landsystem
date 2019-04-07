<?php

namespace console\models;

use Yii;
use app\models\Plantingstructurecheck;
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

	public static function otherPlant()
	{

	}

	public static function zdPlant()
	{

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

	public static function getTypename()
	{
		$result = [];
		$plants = Plant::find()->where('father_id>1')->all();
		foreach ($plants as $value) {
			$result[] = $value['typename'];
		}
//		var_dump($result);
		return $result;
	}

    public static function getPlanAllname($year=null)
    {
//     	var_dump($totalData);
		if(empty($year)) {
			$year = User::getYear();
		}
    	$result = [];
    	$data = [];
    	$totalData = Plantingstructure::find()->where(['management_area'=>Farms::getManagementArea()['id'],'year'=>$year])->all();
    	foreach($totalData as $value) {
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

	public static function getAllname()
	{
//     	var_dump($totalData);
		$result = [];
		$data = [];
		$totalData = Plantingstructure::find()->where(['management_area'=>Farms::getManagementArea()['id'],'year'=>User::getYear()])->all();
		foreach($totalData as $value) {
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


	public static function getCheckAllname($year=null)
	{
//     	var_dump($totalData);
		if(empty($year)) {
			$year = User::getYear();
		}
		$result = [];
		$data = [];
		$totalData = Plantingstructurecheck::find()->where(['management_area'=>Farms::getManagementArea()['id'],'year'=>User::getYear()])->all();
//		$totalData = Plantingstructurecheck::find()->where(['management_area'=>Farms::getManagementArea()['id'],'year'=>$year])->all();
		foreach($totalData as $value) {
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

	public static function getSalesAllname($year)
	{
//     	var_dump($totalData);
		$result = [];
		$data = [];
		$totalData = Sales::find()->where(['management_area'=>Farms::getManagementArea()['id'],'year'=>$year])->all();
		foreach($totalData as $value) {
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
