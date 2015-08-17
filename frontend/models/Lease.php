<?php

namespace app\models;

use Yii;
use app\models\Farms;
/**
 * This is the model class for table "{{%lease}}".
 *
 * @property integer $id
 * @property string $lease_area
 * @property string $lessee
 * @property string $plant_id
 */
class Lease extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lease}}';
    }

    /**
     * @inheritdoc
     */
    
    public static function getOverArea()
    {
    	$areas = 0;
    	if(($model = self::find()->where(['farms_id'=>$_GET['farms_id']])->all()) !== null) {
    		foreach($model as $val) {
    			$areas+=$val['lease_area'];
    		}
    	}
    	$farms = Farms::find()->where(['id'=>$_GET['farms_id']])->one()['measure'];
    	return $farms-$areas;
    }
    
	public function rules() 
    { 
        return [
            [['farms_id', 'years'], 'integer'],
            [['lessee_cardid', 'enddate'], 'string'],
            [['lease_area', 'lessee', 'plant_id', 'lessee_telephone', 'begindate', 'photo'], 'string', 'max' => 500]
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'lease_area' => '租赁面积',
            'lessee' => '承租人',
            'plant_id' => '种植结构',
            'farms_id' => '农场ID',
            'years' => '年度',
            'lessee_cardid' => '身份证号',
            'lessee_telephone' => '联系电话',
            'begindate' => '开始日期',
            'enddate' => '结束日期',
            'photo' => '近期照片',
        ]; 
    }
}
