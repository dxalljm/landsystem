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
class Farmerinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%farmerinfo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state'], 'integer'],
            [['farmerbeforename', 'gender', 'nation', 'political_outlook','zhibu', 'cultural_degree', 'domicile', 'nowlive', 'living_room', 'photo', 'cardpic','cardpicback'], 'string', 'max' => 500]
//            [['cardid','address','']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'farmerbeforename' => '曾用名',
            'gender' => '性别',
            'nation' => '民族',
            'political_outlook' => '政治面貌',
            'cultural_degree' => '文化程度',
            'domicile' => '户籍所在地',
            'nowlive' => '现住地',
            'living_room' => '生产生活用房坐标点',
            'photo' => '近期照片',
            'cardpic' => '身份证扫描件',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'cardpicback' => '身份证扫描件',
            'state' => '状态',
            'zhibu' => '所在支部',
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

    public static function setPoliticaloutlook()
    {
        return ['群众','团员','党员','民主党派','其他'];
    }

    public static function getPoliticaloutlook($id)
    {
        return self::setPoliticaloutlook()[$id];
    }


    public static function isPhoto($farms_id)
    {
        $farmerinfo = Farmerinfo::find()->where(['cardid'=>Farms::getFarmsCardID($farms_id)])->one();
        $contract = Electronicarchives::find()->where(['farms_id'=>$farms_id])->count();
        if($contract < 10)
            return false;
        if(empty($farmerinfo))
            return false;
        if($farmerinfo['photo'] !== '' and $farmerinfo['cardpic'] !== '' and $farmerinfo['cardpicback'] !== '') {
            return true;
        } else
            return false;
    }
}
