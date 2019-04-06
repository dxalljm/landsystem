<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%projectplan}}".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $begindate
 * @property integer $enddate
 * @property string $content
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $state
 */
class Projectplan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%projectplan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'create_at', 'update_at', 'state'], 'integer'],
        	[['money'], 'number'],
            [['content','contract'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'project_id' => '项目ID',
            'begindate' => '开始日期',
            'enddate' => '结束日期',
            'content' => '备注',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'state' => '状态',
        	'money' => '补贴金额',
        	'contract' => '项目施工合同',
        ];
    }
}
