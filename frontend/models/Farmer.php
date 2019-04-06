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
            'years' => '年度',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
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
