<?php

namespace app\models;

use Yii;
use app\models\Plant;
use app\models\Goodseed;
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
            [['subsidiesarea', 'subsidiesmoney', 'totalamount', 'realtotalamount'], 'number'],
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
        	
            'totalamount' => '补贴总金额',
            'realtotalamount' => '实际补贴金额',
            'begindate' => '开始日期',
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
}
