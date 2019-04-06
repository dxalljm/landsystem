<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%bank_account}}".
 *
 * @property integer $id
 * @property string $accountnumber
 * @property integer $farmer_id
 * @property string $bank
 */
class BankAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bank_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id','state','modfiytime','farmstate','management_area','lease_id'],'integer'],
            [['accountnumber', 'bank','cardid','lessee','modfiyname','farmerpinyin','lesseepinyin','farmername','contractnumber','farmpinyin','farmname'], 'string', 'max' => 500],
            [['contractarea'],'number'],
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
            'lease_id' => '租赁ID',
            'accountnumber' => '账号',
            'cardid' => '法人身份证',
            'bank' => '所属银行',
            'lessee' => '种植者姓名',
            'state' => '审核状态',
            'modfiyname' => '修正人',
            'modfiytime' => '修正时间',
            'management_area' => '管理区',
            'farmstate' => '农场状态',
            'farmername' => '法人姓名',
            'farmerpinyin' => '法人姓名拼音',
            'lesseepinyin' => '种植者拼音',
            'contractarea' => '合同面积',
            'contractnumber' => '合同号',
            'farmpinyin' => '农场名称拼音',
            'farmname' => '农场名称',
        ];
    }

    public static function scanCard($cardid)
    {
        $bank = BankAccount::find()->where(['cardid'=>$cardid])->all();
        foreach ($bank as $value) {
            if($value['state'] == 1) {
                $model = BankAccount::findOne($value['id']);
                return $model->attributes;
            }
        }
        return false;
    }

    public static function isState($cardid)
    {
        $bank = BankAccount::find()->where(['cardid'=>$cardid])->all();
        foreach ($bank as $value) {
            if($value['state'] == 1) {
                return true;
            }
        }
        return false;
    }

    public static function isBank($cardid,$farms_id)
    {
        return BankAccount::find()->where(['cardid'=>$cardid,'farms_id'=>$farms_id])->count();

    }
}
