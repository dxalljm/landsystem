<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%insuranceplan}}".
 *
 * @property integer $id
 * @property integer $insurance_id
 * @property integer $management_area
 * @property string $year
 * @property integer $farms_id
 * @property string $policyholder
 * @property string $cardid
 * @property string $telephone
 * @property double $insuredarea
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $policyholdertime
 * @property string $farmername
 * @property integer $state
 * @property double $contractarea
 * @property string $farmerpinyin
 * @property string $policyholderpinyin
 * @property integer $farmstate
 * @property integer $lease_id
 * @property string $insured
 */
class Insuranceplan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%insuranceplan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['insurance_id', 'management_area', 'farms_id', 'create_at', 'update_at', 'policyholdertime', 'state', 'farmstate', 'lease_id'], 'integer'],
            [['insuredarea', 'contractarea'], 'number'],
            [['year', 'policyholder', 'cardid', 'telephone', 'farmername', 'farmerpinyin', 'policyholderpinyin'], 'string', 'max' => 500],
            [['insured'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'insurance_id' => '保险id',
            'management_area' => '管理区',
            'year' => '年度',
            'farms_id' => '农场ID',
            'policyholder' => '投保人',
            'cardid' => '法人身份证',
            'telephone' => '联系电话',
			'insuredarea' => 'Insuredarea',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
			'policyholdertime' => 'Policyholdertime',
            'farmername' => '法人姓名',
            'state' => '状态',
            'contractarea' => '合同面积',
            'farmerpinyin' => '法人拼音首字母',
			'policyholderpinyin' => 'Policyholderpinyin',
            'farmstate' => '农场状态',
            'lease_id' => '承租人ID',
            'insured' => '被保险人',
        ];
    }
}
