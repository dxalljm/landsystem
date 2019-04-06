<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%dispute}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property string $content
 * @property string $create_at
 * @property string $update_at
 * @property integer $disputetype_id
 */
class Dispute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dispute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'disputetype_id','state'], 'integer'],
            [['content'], 'string'],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'farms_id' => '农场ID',
            'content' => '备注',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'disputetype_id' => '纠纷类型',
        	'state' => '状态',
        ];
    }
}
