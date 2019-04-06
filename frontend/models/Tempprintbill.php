<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tempprintbill}}".
 *
 * @property integer $id
 * @property string $farmername
 * @property double $standard
 * @property double $number
 * @property double $amountofmoney
 * @property string $bigamountofmoney
 * @property integer $nonumber
 */
class Tempprintbill extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tempprintbill}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	[['state','farms_id','management_area','collection_id','year','farmstate','kptime'],'integer'],
        	[['amountofmoneys','measure','amountofmoney'], 'number'],
            [['standard', 'nonumber','farmername', 'bigamountofmoney','remarks'], 'string', 'max' => 500],
        	[['farmername'],'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
        	'farms_id' => '农场',
            'management_area' => '管理区',
            'farmername' => '法人姓名',
        	'remarks' => '备注',
            'standard' => '标准',
            'measure' => '数量',
            'amountofmoney' => '金额',
            'bigamountofmoney' => '大写金额',
            'nonumber' => '发票号',
			'create_at' => '创建日期',
        	'update_at' => '更新日期',  
        	'amountofmoneys' => '金额计算', 
        	'state' => '状态',   
        	'collection_id' => '收缴情况id',
        	'year' => '年度'	,
            'farmstate' => '农场状态',
            'kptime' => '开票日期'
        ];
    }
}
