<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%plantingstructureyearfarmsid}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $state
 * @property double $contractarea
 * @property string $contractnumber
 * @property integer $isfinished
 * @property string $year
 */
class Plantingstructureyearfarmsidplan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plantingstructureyearfarmsidplan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'state', 'isfinished','create_at','management_area'], 'integer'],
            [['contractarea'], 'number'],
            [['contractnumber', 'year','farmname','farmername','pinyin','farmerpinyin','cardid','telephone'], 'string', 'max' => 500],
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
            'cardid' => '身份证号',
            'telephone' => '联系电话',
            'state' => 'State',
            'contractarea' => '合同面积',
            'contractnumber' => '合同号',
            'isfinished' => 'Isfinished',
            'year' => 'Year',
            'create_at' => '创建日期',
            'farmname' => '农场名称',
            'farmername' => '法人姓名',
            'management_area' => '管理区',
            'pinyin' => 'pinyin',
            'farmerpinyin' => 'farmerpinyin',
        ];
    }

    public static function newPlan($farms_id,$is=0,$year=null)
    {
        if(empty($year)) {
            $year = User::getYear();
        }
        $farm = Farms::findOne($farms_id);
        $plant = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>$year])->one();
        $plan = Plantingstructureyearfarmsidplan::find()->where(['farms_id'=>$farms_id,'year'=>$year])->one();
        if(empty($plan)) {
            $model = new Plantingstructureyearfarmsidplan();
            $model->farms_id = $farms_id;
            $model->cardid = $farm['cardid'];
            $model->telephone = $farm['telephone'];
            $model->state = $farm['state'];
            $model->contractarea = $farm['contractarea'];
            $model->contractnumber = $farm['contractnumber'];
            $model->isfinished = $is;
            $model->year = $year;
            $model->create_at = $plant['create_at'];
            $model->farmname = $farm['farmname'];
            $model->farmername = $farm['farmername'];
            $model->management_area = $farm['management_area'];
            $model->pinyin = $farm['pinyin'];
            $model->farmerpinyin = $farm['farmerpinyin'];
            $state = $model->save();
        } else {
            $model = Plantingstructureyearfarmsidplan::findOne($plan['id']);
            $model->isfinished = $is;
            $state = $model->save();
        }
        return $state;
    }



    public static function copy($id,$farms_id)
    {
        $plan = Plantingstructureyearfarmsidplan::findOne($id);
        $farm = Farms::findOne($farms_id);
        $model = new Plantingstructureyearfarmsidplan();
        $model->farms_id = $farm['id'];
        $model->cardid = $farm['cardid'];
        $model->telephone = $farm['telephone'];
        $model->state = $farm['state'];
        $model->contractarea = $farm['contractarea'];
        $model->contractnumber = $farm['contractnumber'];
        $model->isfinished = 0;
        $model->year = User::getYear();
        $model->create_at = $plan['create_at'];
        $model->farmname = $farm['farmname'];
        $model->farmername = $farm['farmername'];
        $model->management_area = $farm['management_area'];
        $model->pinyin = $farm['pinyin'];
        $model->farmerpinyin = $farm['farmerpinyin'];
        $model->save();
    }
}
