<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%management_area}}".
 *
 * @property integer $id
 * @property string $areaname
 */
class ManagementArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%management_area}}';
    }

    public static function getAreaname($strId) 
    {
    	$arrayId = explode(',', $strId);
    	foreach ($arrayId as $value) {
    		$arrayAreaName[] = self::find()->where(['id'=>$value])->one()['areaname'];
    	}
    	$strAreaName = implode(',', $arrayAreaName);
    	return $strAreaName;
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['areaname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'areaname' => '区域名称',
        ];
    }
}
