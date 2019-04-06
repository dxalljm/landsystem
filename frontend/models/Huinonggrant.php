<?php

namespace app\models;

use app\models\Huinong;
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
            [['farms_id', 'huinong_id','lease_id','subsidiestype_id','typeid', 'state','create_at','update_at','management_area','issubmit'], 'integer'],
            [['money', 'area'], 'number'],
            [['note','subsidyobject','proportion'], 'string']
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
        	'lease_id' => '承租人',
            'huinong_id' => '惠农政策ID',
        	'subsidiestype_id' => '补贴类型',
        	'typeid' => '补贴种类',
            'money' => '补贴金额',
        	'lease_id' => '租赁者ID',
            'area' => '种植面积',
            'state' => '状态',
            'note' => '备注',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'management_area' => '管理区ID',
        	'issubmit' => '是否提交',
            'proportion' => '占比',
            'subsidyobject' => '补贴对象',
        ];
    }
    
    public static function isInHuinonggrant($huinong_id)
    {
    	
    	if(Huinonggrant::find()->where(['huinong_id'=>$huinong_id])->count())
    		return true;
    	else 
    		return false;
    }

    public static function getHuinonggrantinfo()
    {

        $areaid = Farms::getManagementArea()['id'];
        $sum = [];
        $allSum = [];
        $result = NULL;
        $name = '';
        $huinong = Huinong::find()->all();
        $query = Farms::getAllCondition();
        if($huinong) {
            foreach ($areaid as $key => $value) {
                $farms = $query->where(['management_area' => $value]);
                $huinonggrantSum = 0.0;
                foreach ($huinong as $val) {
                    $class = Subsidiestype::find()->where(['id' => $val['subsidiestype_id']])->one()['typename'];
                    if ($class == 'Plant') {
                        $name = Plant::find()->where(['id' => $val['typeid']])->one()['cropname'];
                    }
                    if ($class == 'Goodseed') {
                        $goodseed = Goodseed::find()->where(['id' => $val['typeid']])->one();
                        $name = Plant::find()->where(['id' => $goodseed['plant_id']])->one()['cropname'] . '/' . $goodseed['plant_model'];
                    }
                    $allSum[$val['subsidiestype_id']]['name'] = $name;
                    $allSum[$val['subsidiestype_id']]['key'] = $val['subsidiestype_id'];
                    $allSum[$val['subsidiestype_id']]['data'][$key] = (float)sprintf("%.2f", $val['subsidiesmoney'] * $farms->sum('contractarea'));
                    $allSum[$val['subsidiestype_id']]['stack'] = $val['subsidiestype_id'];
                    foreach ($farms->all() as $v) {
                        $huinonggrant = Huinonggrant::find()->where(['farms_id' => $v['id'], 'huinong_id' => $val['id'], 'state' => 1])->one();
                        $huinonggrantSum += $huinonggrant['money'];
                    }
                    $sum[$val['subsidiestype_id']]['name'] = $name;
                    $sum[$val['subsidiestype_id']]['key'] = $val['subsidiestype_id'];
                    $sum[$val['subsidiestype_id']]['data'][$key] = (float)sprintf("%.2f", $huinonggrantSum);
                    $sum[$val['subsidiestype_id']]['stack'] = $val['subsidiestype_id'];
                }
            }
        } else {
            $sum[] = 0;
            $allSum[] = 0;
        }
        foreach ($sum as $value) {
            //     		var_dump($value['key']);exit;
            $result[] =
                [
                    // 					'color' => '#FFF',
                    'name' => '实发',
                    'type' => 'bar',
                    'data' => $value['data'],
                    'stack' => 'sum',
                    'barCategoryGap' => '50%',
                    'itemStyle' => [
                        'normal' => [
                            'color' => 'tomato',
                            'barBorderColor' => 'tomato',
                            'barBorderWidth' => 3,
                            'barBorderRadius' => 0,
                            'label' => [
                                'show' => true,
                                'position' => 'insideTop'
                            ]
                        ]
                    ],
                ];
        }
        foreach ($allSum as $value) {
            $result[] =
                [
                    // 					'color' => '#FFF',
                    'name' => '应发',
                    'type' => 'bar',
                    'data' => $value['data'],
                    'stack' => 'sum',
                    'itemStyle' => [
                        'normal' => [
                            'color' => '#fff',
                            'barBorderColor' => 'tomato',
                            'barBorderWidth' => 3,
                            'barBorderRadius' => 0,
                            'label' => [
                                'show' => false,
                                'position' => 'top',
                                // 						'formatter'=> '{c}',
                                'textStyle' => [
                                    'color' => 'tomato'
                                ]
                            ]
                        ]
                    ],
                ];
        }
//     	var_dump($result);
        if(!empty($result))
            $jsonData = json_encode ($result);
        else
            $jsonData = $result;
        return $jsonData;
    }
}
