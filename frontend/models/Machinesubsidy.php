<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%machinesubsidy}}".
 *
 * @property integer $id
 * @property integer $machine_id
 * @property string $filename
 * @property string $parameter
 * @property string $subsidymoney
 */
class Machinesubsidy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%machinesubsidy}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['machinetype_id','year'], 'integer'],
            [['filename', 'parameter', 'subsidymoney','machinetype'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'machinetype_id' => '农机ID',
            'filename' => '分档名称',
            'parameter' => '基本配置和参数',
            'subsidymoney' => '中央财政补贴额',
            'machinetype' => '机具类型',
            'year' => '年度'
        ];
    }
}
