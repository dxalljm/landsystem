<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%machine}}".
 *
 * @property integer $id
 * @property string $productname
 * @property string $implementmodel
 * @property string $filename
 * @property string $province
 * @property string $enterprisename
 * @property string $parameter
 */
class Machine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%machine}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	[['id','machinetype_id','year','state'],'integer'],
            [['parameter','content',], 'string'],
            [['productname', 'implementmodel', 'filename', 'province', 'enterprisename','machinetype'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
        	'machinetype_id' => '类型ID',
            'productname' => '产品名称',
            'implementmodel' => '机具型号',
            'filename' => '分档名称',
            'province' => '企业所属省份',
            'enterprisename' => '企业名称',
            'parameter' => '基本配置及参数',
        	'content' => '备注',
            'year' => '年度',
            'state' => '状态',
            'machinetype' => '机具类型'
        ];
    }

}
