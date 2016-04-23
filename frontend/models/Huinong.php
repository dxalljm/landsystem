<?php

namespace app\models;

use Yii;
use app\models\Plant;
use app\models\Goodseed;
use yii\helpers\Url;
use yii\helpers\Json;
/**
 * This is the model class for table "{{%huinong}}".
 *
 * @property integer $id
 * @property string $subsidiestype_id
 * @property double $subsidiesarea
 * @property double $subsidiesmoney
 */
class Huinong extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%huinong}}';
    }

    /**
     * @inheritdoc
     */
public function rules() 
    { 
        return [
            [['subsidiesarea', 'subsidiesmoney', 'totalamount', 'realtotalamount','totalsubsidiesarea'], 'number'],
            [['typeid', 'create_at', 'update_at'], 'integer'],
            [['subsidiestype_id', 'begindate', 'enddate'], 'string', 'max' => 500]
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'subsidiestype_id' => '补贴类型',
            'subsidiesarea' => '补贴面积',
            'subsidiesmoney' => '补贴金额',
            'typeid' => '补贴种类',
        	'totalsubsidiesarea' => '补贴总面积',
            'totalamount' => '补贴总金额',
            'realtotalamount' => '实际补贴金额',
            'begindate' => '补贴年度',
            'enddate' => '结束日期',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
        ]; 
    } 
    
    public static function getTypename()
    {
    	$result = [];
    	$huinongs = Huinong::find()->all();
    	if($huinongs) {
	    	foreach ($huinongs as $value) {
// 	    		$result['id'][] = $value['id'];
// 				var_dump($value);
		    	$sub = Subsidiestype::find()->where(['id'=>$value['subsidiestype_id']])->one();
// 		    	$modelname = 'app\\model\\'.$sub['urladdress'];
		    	if($sub['urladdress'] == 'Plant')
		    		$result[$value['id']] = Plant::find()->where(['id'=>$value['typeid']])->one()['cropname'].$sub['typename'];
		    	if($sub['urladdress'] == 'Goodseed')
		    		$result[$value['id']] = Goodseed::find()->where(['id'=>$value['typeid']])->one()['plant_model'].$sub['typename'];
	    	}
    	} 
//     	var_dump($result);
    	return $result;	
    }
    
    public static function getHuinongCount()
    {
    	$rows = Huinong::find()->count();
    	if($rows)
    		return '<small class="label pull-right bg-red">'.$rows.'</small>';
    	else
    		return false;
    }
    
    public static function getFarminfo($huinong_id)
    {
    	$cacheKey = 'huinongsearch-5'.$huinong_id;
    	
    	$result = Yii::$app->cache->get($cacheKey);
    	if (!empty($result)) {
    		return $result;
    	}
    	$huinongs = Huinonggrant::find()->where(['huinong_id'=>$huinong_id])->all();
    	$farmids = [];
    	foreach ($huinongs as $huinong) {
    		$farmids[] = $huinong['farms_id'];
    	}
    	$farms = Farms::find()->where(['id'=>$farmids])->all();
    	foreach ($farms as $farm) {
    		$data[] = [
    				'value' => $farm['pinyin'], // 拼音
    				'data' => $farm['farmname'], // 下拉框显示的名称
    				'url' => Url::to(['huinong/huinongprovideone','huinong_id'=>$huinong_id,'farms_id'=>$farm['id']]),
    		];
    		$data[] = [
    				'value' => $farm['farmerpinyin'],
    				'data' => $farm['farmername'],
    				'url' => Url::to(['huinong/huinongprovideone','huinong_id'=>$huinong_id,'farms_id'=>$farm['id']]),
    		
    		];
    	}
//     	var_dump($data);
    	$jsonData = Json::encode($data);
    	Yii::$app->cache->set($cacheKey, $jsonData, 3600);
    	
    	return $jsonData;
    }
}
