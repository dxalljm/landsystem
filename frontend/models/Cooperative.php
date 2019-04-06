<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cooperative}}".
 *
 * @property integer $id
 * @property string $cooperativename
 * @property string $cooperativetype
 * @property string $directorname
 * @property integer $peoples
 * @property string $finance
 * @property double $registered_capital
 * @property string $dividendmode
 */
class Cooperative extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cooperative}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['peoples','create_at','update_at'], 'integer'],
            [['registered_capital'], 'number'],
            [['cooperativename', 'cooperativetype', 'directorname', 'finance', 'dividendmode'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'cooperativename' => '合作社名称',
            'cooperativetype' => '合作社类型',
            'directorname' => '理事长姓名',
            'peoples' => '入社人数',
            'finance' => '财务报表',
            'registered_capital' => '注册资金',
            'dividendmode' => '分红模式',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        ];
    }
}
