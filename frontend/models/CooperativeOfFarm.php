<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cooperative_of_farm}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $cia
 * @property string $proportion
 * @property double $bonus
 * @property integer $cooperative_id
 */
class CooperativeOfFarm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cooperative_of_farm}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'cia', 'cooperative_id'], 'integer'],
            [['bonus'], 'number'],
            [['proportion'], 'string', 'max' => 500]
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
            'cia' => '注资金额',
            'proportion' => '占比',
            'bonus' => '分红',
            'cooperative_id' => '合作社',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        ];
    }
}
