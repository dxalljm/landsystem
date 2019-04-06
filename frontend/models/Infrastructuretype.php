<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%infrastructuretype}}".
 *
 * @property integer $id
 * @property integer $father_id
 * @property string $typename
 */
class Infrastructuretype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%infrastructuretype}}';
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
            'father_id' => '类别',
            'typename' => '类型名称',
        ];
    }

    /**
     * 获取全部的分类信息
     *
     * @return array
     */
    public static function getAllList()
    {
        $allList = [];

        $query = self::find()->select(
            ['id', 'father_id', 'typename']
        )->where(['is_delete' => 0])
         ->orderBy(['sort'=>SORT_ASC, 'id' => SORT_ASC]);


        $rows = $query->all();

        foreach ($rows as $rowkey => $rowValue) {
            $allList[$rowValue['id']] = $rowValue->attributes;
        }

        return $allList;
    }

    public static function getNameById($id)
    {
        $query = self::find()->select(
            ['id', 'father_id', 'typename']
        )->where(['id' => $id]);

        $rows = $query->one();

        $name = '';
        if (!empty($rows)) {
            return $rows->typename;
        }
        return $name;

    }
    public static function getAllname($params)
    {
//     	$cache = 'cache-key-infrastructure2';
//     	$result = Yii::$app->cache->get($cache);
//     	if (!empty($result)) {
//     		return $result;
//     	}
    	 
    	 
    	$result = [];
    	$where = Farms::getManagementArea()['id'];
    	$project = Projectapplication::find ()->where (['management_area'=>$where])->all ();
    	$data = [];
    	foreach($project as $value) {
    		$data[] = ['id'=>$value['projecttype']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		foreach($newdata as $value) {
    			$allid[] = $value['id'];
    			//     		var_dump($value);exit;
    			// 	    		$result[$value['id']] = Plant::find()->where(['id'=>$value['id']])->one()['typename'];
    		}
    		$type = Infrastructuretype::find()->where(['id'=>$allid])->all();
    		foreach ($type as $value) {
    			$result[$value->id] = $value->typename;
    		}
    	}
//     	Yii::$app->cache->set($cache, $result, 86400);
    	return $result;
    }
    
    public static function getNameOne($params,$id)
    {
    	$data = self::getAllname($params);
    	return $data[$id];
    }
}
