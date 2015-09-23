<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%farms}}".
 *
 * @property integer $id
 * @property string $farmname
 * @property string $address
 * @property string $management_area
 * @property string $spyear
 * @property integer $measure
 * @property string $zongdi
 * @property string $cooperative_id
 * @property string $surveydate
 * @property string $groundsign
 * @property string $investigator
 * @property string $farmersign
 */
class Farms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%farms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farmname', 'farmername'], 'required'],
            [['measure'], 'number'],
            [['zongdi'], 'string'],
            [['management_area','state'], 'integer'],
            [['farmname', 'farmername', 'cardid', 'telephone', 'address', 'cooperative_id', 'surveydate', 'groundsign', 'investigator', 'farmersign', 'pinyin','farmerpinyin'], 'string', 'max' => 500]
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [ 
            'id' => 'ID',
            'farmname' => '农场名称',
            'farmername' => '承包人姓名',
            'cardid' => '身份证号',
            'telephone' => '电话号码',
            'address' => '农场位置',
            'management_area' => '管理区',
            'spyear' => '审批年度',
            'measure' => '面积',
            'zongdi' => '宗地',
            'cooperative_id' => '合作社',
            'surveydate' => '调查日期',
            'groundsign' => '地产科签字',
            'investigator' => '地星调查员',
            'farmersign' => '农场法人签字',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'pinyin' => '农场名称拼音首字母',
        	'farmerpinyin' => '法人姓名简单首字母',
        	'state' => '状态',
        ]; 
    }
    
    public function getfarmer()
    {
    	return $this->hasOne(Farmer::className(), ['farms_id' => 'id']);
    }


}
