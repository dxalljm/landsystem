<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%Machinetype}}".
 *
 * @property integer $id
 * @property integer $father_id
 * @property string $typename
 * @property integer $is_delete
 * @property integer $sort
 */
class Machinetype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%machinetype}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['father_id', 'is_delete', 'sort'], 'integer'],
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
			'is_delete' => 'is_delete',
            'sort' => '排序',
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
    
    public static function getClass($type_id)
    {
    	if($type_id) {
	    	$machinetype3 = self::find()->where(['id'=>$type_id])->one();
	    	$three = $machinetype3['typename'];
	    	$machinetype2 = self::find()->where(['id'=>$machinetype3->father_id])->one();
	    	$two = $machinetype2['typename'];
	    	$machinetype1 = self::find()->where(['id'=>$machinetype2->father_id])->one();
	    	$one = $machinetype1['typename'];
//			var_dump($two);
//			var_dump($one);exit;
	    	return [$one,$two,$three];
    	} else 
    		return ['','',''];
    }

	public static function getApplyType()
	{
		$types = [];
		$applys = Machineapply::find()->where(['year'=>User::getYear(),'scanfinished'=>1,'state'=>1])->all();
		foreach ($applys as $apply) {
			$machinetype = Machinetype::findOne($apply['machinetype_id']);
			$father = Machinetype::findOne($machinetype['father_id']);
			$types[$apply['machinetype_id']] = $father['typename'];
		}
//		var_dump($types);exit;
		return array_unique($types);
	}

	public static function getBigclass()
	{
		$data = Machinetype::find()->where(['father_id'=>0])->all();
		return ArrayHelper::map($data,'id','typename');
	}

	public static function getSmallclass($bigclass)
	{
		$data = Machinetype::find()->where(['father_id'=>$bigclass])->all();
		return ArrayHelper::map($data,'id','typename');
	}

	public static function getMachinetype($smallclass)
	{
		$data = Machinetype::find()->where(['father_id'=>$smallclass])->all();
		return ArrayHelper::map($data,'id','typename');
	}
}
