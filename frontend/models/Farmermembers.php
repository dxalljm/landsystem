<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%farmermembers}}".
 *
 * @property integer $id
 * @property integer $farmer_id
 * @property string $relationship
 * @property string $membername
 * @property string $cardid
 * @property string $remarks
 */
class Farmermembers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%farmermembers}}';
    }

    /**
     * @inheritdoc
     */
public function rules() 
    { 
        return [
            [['farmer_id', 'isupdate'], 'integer'],
            [['remarks'], 'string'],
            [['relationship', 'membername', 'cardid'], 'string', 'max' => 500]
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'farmer_id' => '法人ID',
            'relationship' => '关系',
            'membername' => '姓名',
            'cardid' => '身份证号',
            'remarks' => '备注',
            'isupdate' => '是否可更新',
        ]; 
    } 
}
