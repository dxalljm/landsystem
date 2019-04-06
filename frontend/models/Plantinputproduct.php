<?php

namespace app\models;

use Yii;

//use frontend\helpers\eActionColumn;

/**
 * This is the model class for table "{{%plantinputproduct}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $lessee_id
 * @property integer $father_id
 * @property integer $son_id
 * @property integer $inputproduct_id
 * @property double $pconsumption
 * @property string $zongdi
 * @property integer $plant_id
 */


// $e = new eActionColumn();
// var_dump($e);
// exit;

class Plantinputproduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plantinputproduct}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'lessee_id', 'father_id', 'son_id', 'inputproduct_id', 'plant_id','planting_id','management_area'], 'integer'],
            [['pconsumption'], 'number'],
            [['zongdi'], 'string', 'max' => 500]
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
            'lessee_id' => '承租人ID',
        	'planting_id' => '种植结构ID',
            'father_id' => '类别',
            'son_id' => '子类ID',
            'inputproduct_id' => '化肥使用情况',
            'pconsumption' => '农药用量',
            'zongdi' => '宗地',
            'plant_id' => '种植结构',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'management_area' => '管理区',
        ];
    }

    public static function getInputproduct($userid)
    {
//     	var_dump($userid);
        $where = Farms::getUserManagementArea($userid);
        $typenamelist = self::getTypenamelist($userid);
        $plantlist = Plantingstructure::getPlantname($userid);
//     	var_dump($typenamelist);exit;
        $data = [];
        $result = [];
        $lastresult = [];
        $name = '';
        foreach ($plantlist['id'] as $plantkey => $plant) {
            $name = Plant::find()->where(['id'=>$plant])->one()['typename'];
            foreach ($typenamelist['id'] as $typenamekey => $val) {
                $input = Plantinputproduct::find()->where(['management_area'=>$where,'inputproduct_id'=>$val['id'],'plant_id'=>$plant['id']])->andFilterWhere(['between','create_at',Theyear::getYeartime($userid)[0],Theyear::getYeartime($userid)[1]])->all();
                $sum = 0.0;
                foreach ($input as $value) {
                    $sum += (float)Lease::getArea($value->attributes['zongdi'])*$value->attributes['pconsumption'];
                }
                $data[$name][$typenamekey] = (float)sprintf("%.2f", $sum);
            }
        }
        foreach ($data as $key => $value) {
            $result[] = [
                'name' => $key,
                'type' => 'bar',
                'data' => $value,
            ];
        }

//     	sort($result);
//     	var_dump($result);

        $jsonData = json_encode ($result);

        return $jsonData;
    }

    public static function getTypenamelist($userid)
    {

        $input = Plantinputproduct::find()->all();
//     	var_dump($input);exit;
        $data = [];
        $result = [];
        foreach ($input as $value) {
            $data[] = ['id'=>$value['inputproduct_id']];
        }
        if($data) {
            $newdata = Farms::unique_arr($data);
            foreach ($newdata as $value) {
                $result['id'][] = $value;
                $result['typename'][] = Inputproduct::find()->where(['id' => $value])->one()['fertilizer'];
            }
        }

//     	var_dump($result);exit;
        return $result;
    }
}
