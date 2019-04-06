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
            [['farms_id', 'management_area', 'lease_id', 'planting_id', 'plant_id', 'goodseed_id', 'create_at', 'update_at', 'year','farmstate'], 'integer'],
            [['area','total_area','contractarea'], 'number'],
            [['zongdi','contractnumber'], 'string', 'max' => 500],
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
            'management_area' => '管理区',
            'lease_id' => '租赁ID',
            'planting_id' => '种植结构ID',
            'plant_id' => '农作物ID',
            'goodseed_id' => '良种ID',
            'zongdi' => '宗地',
            'area' => '亩数',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'year' => '年度',
            'total_area' => '总面积',
            'contractarea' => '合同面积',
            'contractnumber' => '合同号',
            'farmstate' => '农场状态',
        ];
    }

    public static function isGoodseed($planting_id)
    {
        $info = Goodseedinfo::find()->where(['planting_id'=>$planting_id,'year'=>User::getYear()])->count();
        if($info) {
            return $info;
        } else {
            return 0;
        }
    }
}
