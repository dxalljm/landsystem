<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%goodseedinfocheck}}".
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
 * @property double $total_area
 */
class Goodseedinfocheck extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goodseedinfocheck}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'management_area', 'lease_id', 'planting_id', 'plant_id', 'goodseed_id', 'create_at', 'update_at', 'year','farmstate'], 'integer'],
            [['area', 'total_area','contractarea'], 'number'],
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
            'farms_id' => 'Farms ID',
            'management_area' => 'Management Area',
            'lease_id' => 'Lease ID',
            'planting_id' => 'Planting ID',
            'plant_id' => 'Plant ID',
            'goodseed_id' => 'Goodseed ID',
            'zongdi' => 'Zongdi',
            'area' => '种植面积',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'year' => 'Year',
            'total_area' => 'Total Area',
            'contractarea' => '合同面积',
            'contractnumber' => '合同号',
            'farmstate' => '农场状态',
        ];
    }

    public static function isGoodseed($planting_id)
    {
        $info = Goodseedinfocheck::find()->where(['planting_id'=>$planting_id,'year'=>User::getYear()])->count();
        if($info) {
            return $info;
        } else {
            return 0;
        }
    }

    public static function getAllname($state = null)
    {
        $result = [];
        $result2 = [];
        $data = self::find()->where(['year'=>User::getYear()])->all();
        foreach ($data as $value) {
            $result[$value['goodseed_id']] = Goodseed::findOne($value['goodseed_id'])->typename;
        }
        if(empty($state))
            return $result;
        else {
            foreach ($result as $value) {
                $result2[] = $value;
            }
            return $result2;
        }
    }

    public static function getPlantNumber()
    {
        $result = [];
        $data = self::find()->where(['year'=>User::getYear()])->all();

        foreach ($data as $value) {
            $result[] = $value['plant_id'];
        }
        return array_unique($result);
    }
}
