<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%huinonggrant}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $huinong_id
 * @property double $money
 * @property double $area
 * @property integer $state
 * @property string $note
 */
class Huinonggrant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%huinonggrant}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'huinong_id', 'state','create_at','update_at'], 'integer'],
            [['money', 'area'], 'number'],
            [['note'], 'string']
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
            'huinong_id' => '惠农政策ID',
            'money' => '补贴金额',
            'area' => '种植面积',
            'state' => '状态',
            'note' => '备注',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        ];
    }
}
