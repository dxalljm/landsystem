<?php

namespace app\models;

use Yii;
use app\models\ManagementArea;
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
            [['farmname'], 'required'],
            [['measure'], 'number'],
            [['farmname', 'address', 'zongdi', 'cooperative_id', 'groundsign', 'investigator', 'farmersign'], 'string', 'max' => 500]
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
        ];
    }
    
    public function getmanagementarea()
    {
    	return $this->hasOne(ManagementArea::className(), ['id' => 'management_area']);
    }
    
    public function getfarms()
    {
    	return $this->hasOne(Farms::className(), ['id' => 'farms_id']);
    }
}
