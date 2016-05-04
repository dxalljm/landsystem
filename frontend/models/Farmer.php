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
 * @property integer $isupdate
 * @property string $farmerbeforename
 * @property string $nickname
 * @property string $gender
 * @property string $nation
 * @property string $political_outlook
 * @property string $cultural_degree
 * @property string $domicile
 * @property string $nowlive
 * @property string $telephone
 * @property string $living_room
 * @property string $photo
 * @property string $cardpic
 * @property integer $years
 * @property string $create_at
 * @property string $update_at
 * @property string $contractnumber
 * @property string $begindate
 * @property string $enddate
 * @property integer $state
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
            [['farms_id', 'isupdate', 'years', 'state'], 'integer'],
            [['farmerbeforename', 'nickname', 'gender', 'nation', 'political_outlook', 'cultural_degree', 'domicile', 'nowlive', 'living_room', 'photo', 'cardpic','cardpicback'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
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
            'living_room' => '生产生活用房坐标点',
            'photo' => '近期照片',
            'cardpic' => '身份证扫描件',
            'years' => '年度',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'cardpicback' => '身份证扫描件',
            'state' => '状态',
        ];
    }
    public function getfarms()
    {
    	return $this->hasOne(Farms::className(), ['id' => 'farms_id']);
    }
    public static function getRelationship($id)
    {
    	$array = ['妻子','丈夫','儿子','女儿','父亲','母亲','岳父','岳母','公公','婆婆','弟兄','姐妹'];
    	return $array[$id];
    }
    
}
