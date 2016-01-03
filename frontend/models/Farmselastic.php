<?php

namespace app\models;

use Yii;
use yii\helpers\Json;
use app\models\ManagementArea;
use yii\helpers\Url;
use yii\helpers\Html;

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
class Farms extends \yii\elasticsearch\ActiveRecord
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
            [['measure','notclear','notstate'], 'number'],
            [['zongdi'], 'string'],
            [['management_area','state','oldfarms_id','locked'], 'integer'],
            [['farmname', 'farmername', 'cardid', 'telephone', 'address', 'cooperative_id', 'surveydate', 'groundsign', 'farmersign', 'pinyin','farmerpinyin','contractnumber', 'begindate', 'enddate','latitude','longitude','notstateinfo'], 'string', 'max' => 500],
        	[['measure','spyear'],'safe'],
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
            'surveydate' => '合同更换日期',
            'groundsign' => '地产科签字',
            'farmersign' => '农场法人签字',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'pinyin' => '农场名称拼音首字母',
        	'farmerpinyin' => '法人姓名简单首字母',
        	'state' => '状态',
        	'notclear' => '未明确地块',
        	'contractnumber' => '合同号',
        	'begindate' => '开始日期',
        	'enddate' => '结束日期',
        	'oldfarms_id' => '变更前ID',
        	'latitude' => '纬度',
        	'longitude' => '经度',
        	'locked' => '锁定',
        	'notstate' => '未明确状态面积',
        	'notstateinfo' => '未明确状态信息',
        ]; 
    }
    
    public function 

}
