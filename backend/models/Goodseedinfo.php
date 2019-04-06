<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goodseedinfo}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $management_area
 * @property integer $lease_id
 * @property integer $planting_id
 * @property integer $plant_id
 * @property integer $goodseed_id
 * @property string $zongdi
 * @property double $area
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $year
 */
class Goodseedinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goodseedinfo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'management_area', 'lease_id', 'planting_id', 'plant_id', 'goodseed_id', 'create_at', 'update_at', 'year'], 'integer'],
            [['area'], 'number'],
            [['zongdi'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'farms_id' => 'Farms ID',
            'management_area' => 'Management Area',
            'lease_id' => 'Lease ID',
            'planting_id' => 'Planting ID',
            'plant_id' => 'Plant ID',
            'goodseed_id' => 'Goodseed ID',
            'zongdi' => 'Zongdi',
            'area' => 'Area',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'year' => 'Year',
        ];
    }
}
