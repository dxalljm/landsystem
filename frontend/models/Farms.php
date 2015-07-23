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
 * @property integer $iscontract
 * @property string $contractlife
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
            [['iscontract', 'measure'], 'integer'],
            [['farmname', 'address', 'management_area', 'spyear', 'contractlife', 'zongdi'], 'string', 'max' => 500]
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
            'iscontract' => '是否承包',
            'contractlife' => '承包年限',
            'measure' => '面积',
            'zongdi' => '宗地',
        ]; 
    } 
    
    public function getManagementarea()
    {
    	return $this->hasOne(ManagementArea::className(), ['id' => 'management_area']);
    }
}
