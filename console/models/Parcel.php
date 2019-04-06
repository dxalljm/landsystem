<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%parcel}}".
 *
 * @property integer $id
 * @property integer $serialnumber
 * @property integer $temporarynumber
 * @property string $unifiedserialnumber
 * @property string $powei
 * @property string $poxiang
 * @property string $podu
 * @property string $agrotype
 * @property string $stonecontent
 * @property double $grossarea
 * @property double $piecemealarea
 * @property double $netarea
 * @property string $figurenumber
 */
class Parcel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%parcel}}';
    }

    
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           
            [['unifiedserialnumber', 'temporarynumber', 'powei', 'poxiang', 'podu', 'agrotype',  'figurenumber'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'serialnumber' => '编号',
            'temporarynumber' => '地块暂编号',
            'unifiedserialnumber' => '地块统编号(地块号)',
            'powei' => '坡位',
            'poxiang' => '坡向',
            'podu' => '坡度',
            'agrotype' => '土壤类型',
            'stonecontent' => '含石量',
            'grossarea' => '毛面积',
            'piecemealarea' => '零星地类面积',
            'netarea' => '净面积',
            'figurenumber' => '图幅号',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        ];
    }
    
    public static function getAllGrossarea()
    {
    	$all = 0;
    	$parcels = Parcel::find()->all();
    	foreach($parcels as $value) {
    		$all += $value['grossarea'];
    	}
    	return $all;
    }
}
