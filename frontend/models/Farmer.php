<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%farmer}}".
 *
 * @property integer $id
 * @property string $farmername
 * @property string $cardid
 * @property integer $farms_id
 */
class Farmer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%farmer}}';
    }

    /**
     * @inheritdoc
     */
	public function rules() 
    { 
        return [
            [['farms_id', 'isupdate', 'years'], 'integer'],
            [['farmername', 'cardid', 'farmerbeforename', 'nickname', 'gender', 'nation', 'political_outlook', 'cultural_degree', 'domicile', 'nowlive', 'telephone', 'living_room', 'photo', 'cardpic'], 'string', 'max' => 500]
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'farmername' => '承包人姓名',
            'cardid' => '身份证号',
            'farms_id' => '农场ID',
            'isupdate' => '是否可更新',
            'farmerbeforename' => '曾用名',
            'nickname' => '绰号',
            'gender' => '性别',
            'nation' => '民族',
            'political_outlook' => '政治面貌',
            'cultural_degree' => '文化程度',
            'domicile' => '户籍所在地',
            'nowlive' => '现住地',
            'telephone' => '电话号码',
            'living_room' => '生产生活用房坐标点',
            'photo' => '近期照片',
            'cardpic' => '身份证扫描件',
            'years' => '年度',
        ]; 
    }
    public function getFarms()
    {
    	return $this->hasOne(Farms::className(), ['id' => 'farms_id']);
    }
}
