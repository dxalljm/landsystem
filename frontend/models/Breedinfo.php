<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%breedinfo}}".
 *
 * @property integer $id
 * @property integer $breed_id
 * @property integer $number
 * @property double $basicinvestment
 * @property double $housingarea
 * @property integer $breedtype_id
 */
class Breedinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%breedinfo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['breed_id', 'number', 'breedtype_id','create_at','update_at','management_area'], 'integer'],
            [['basicinvestment', 'housingarea'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'breed_id' => '养殖户ID',
            'number' => '数量',
            'basicinvestment' => '基础投资',
            'housingarea' => '圈舍面积',
            'breedtype_id' => '养殖种类',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'management_area' => '管理区',
        ];
    }
    
    public static function getFarmRows($params)
    {
    	$where = [];
    	foreach ($params['breedinfoSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$row = Breedinfo::find ()->where ($where)->count ();
    	return $row;
    }
    
    public static function getFarmerrows($params)
    {
    	$where = [];
    	foreach ($params['breedinfoSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$result = Breedinfo::find ()->where ($where)->all ();
    	//     	var_dump($farms);exit;
    	$data = [];
    	foreach($result as $value) {
    		$breed = Breed::find()->where(['id'=>$value['breed_id']])->one();
    		$farm = Farms::find()->where(['id'=>$breed['farms_id']])->one();
    		$data[] = ['farmername'=>$farm['farmername'],'cardid'=>$farm['cardid']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
    	}
    	else
    		return 0;;
    }
    
    public static function getTypeRows($params)
    {
    	$where = [];
    	foreach ($params['breedinfoSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$result = Breedinfo::find ()->where ($where)->all ();
    	//     	var_dump($farms);exit;
    	$data = [];
    	foreach($result as $value) {
			$type = Breedtype::find()->where(['id'=>$value['breedtype_id']])->one();
    		$data[] = ['id'=>$type['id'],'typename'=>$type['typename']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
    	}
    	else
    		return 0;;
    }
}
