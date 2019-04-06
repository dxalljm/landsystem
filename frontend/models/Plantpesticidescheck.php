<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%plantpesticides}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $lessee_id
 * @property integer $pesticides_id
 * @property double $pconsumption
 */
class Plantpesticidescheck extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plantpesticidescheck}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'lessee_id', 'pesticides_id','plant_id','planting_id','management_area'], 'integer'],
            [['pconsumption'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
        	'planting_id' => '种植结构ID',
            'farms_id' => '农场ID',
            'lessee_id' => '承租人ID',
        	'plant_id' => '作物ID',
            'pesticides_id' => '农药使用情况',
            'pconsumption' => '农药用量',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'management_area' => '管理区',
        ];
    }
}
