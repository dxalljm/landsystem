<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%subsidyratio}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $typeid
 * @property double $farmer
 * @property double $lessee
 */
class Subsidyratio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subsidyratio}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'typeid','lease_id','create_at','year'], 'integer'],
            [['farmer', 'lessee'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'farms_id' => '农场',
            'lease_id' => '承租人',
            'typeid' => '补贴类型',
            'farmer' => '法人占比',
            'lessee' => '承租人占比',
            'create_at' => '创建日期',
            'year' => '年度'
        ];
    }

    //获取农场补贴比率分配情况(第二个参数为指定补贴类型)
    public static function getSubsidyratio($plant_id,$farms_id,$lease_id)
    {
        $typeid = Subsidytypetofarm::find()->where(['plant_id'=>$plant_id])->one()['id'];
        $data = Subsidyratio::find()->where(['farms_id'=>$farms_id,'typeid'=>$typeid,'lease_id'=>$lease_id])->one();
        return $data;
    }

//    public static function getBtinfo($plant_id,$farms_id)
//    {
//        $result = [];
//        $data = self::getSubsidyratio($plant_id,$farms_id);
//        foreach ($data as $value) {
//            $result[$value['plant_id']] = ['farmer'=>$value['farmer'],'lessee'=>$value['lessee']];
//        }
//        return $result;
//    }

    public static function getPlanter($plant_id,$farms_id,$lease_id)
    {
        $result = [];
        $farm = Farms::findOne($farms_id);
        $data = self::getSubsidyratio($plant_id,$farms_id,$lease_id);
        if($lease_id == 0) {
            $result[0] = ['id'=>0,'name'=>$farm->farmername,'cardid'=>$farm->cardid];
        } else {
            $farmer = (float)$data['farmer'] / 100;
            $lessee = (float)$data['lessee'] / 100;
            if(bccomp($farmer,1) == 0) {
                $result[0] = ['id'=>0,'name'=>$farm->farmername,'cardid'=>$farm->cardid];
            }
            if(bccomp($lessee,1) == 0) {
                $lease = Lease::findOne($lease_id);
                $result[$lease_id] = ['id'=>$lease['id'],'name' => $lease['lessee'],'cardid'=>$lease['lessee_cardid']];
            }
            if ($farmer < 1 and $farmer > 0) {
                if ($lessee < 1) {
                    $result[0] = ['id'=>0,'name'=>$farm->farmername,'cardid'=>$farm->cardid];
                    $lease = Lease::findOne($lease_id);
                    $result[$lease_id] = ['id'=>$lease['id'],'name' => $lease['lessee'],'cardid'=>$lease['lessee_cardid']];
                } else {
                    $lease = Lease::findOne($lease_id);
                    $result[$lease_id] = ['id'=>$lease['id'],'name' => $lease['lessee'],'cardid'=>$lease['lessee_cardid']];
                }
            }
        }
        return $result;
    }
}
