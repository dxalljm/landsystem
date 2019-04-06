<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%insurancehistory}}".
 *
 * @property integer $id
 * @property integer $management_area
 * @property string $year
 * @property string $farmname
 * @property string $conractnumber
 * @property string $policyholder
 * @property string $cardid
 * @property string $telephone
 * @property double $wheat
 * @property double $soybean
 * @property double $insuredarea
 * @property double $insuredwheat
 * @property double $insuredsoybean
 * @property string $company_id
 * @property string $create_at
 * @property string $update_at
 * @property string $policyholdertime
 * @property string $managemanttime
 * @property string $halltime
 * @property double $other
 * @property string $farmername
 * @property double $insuredother
 * @property integer $state
 * @property integer $fwdtstate
 * @property string $statecontent
 * @property integer $issame
 * @property integer $isselfselect
 * @property integer $nameissame
 * @property double $contractarea
 * @property string $farmerpinyin
 * @property string $policyholderpinyin
 * @property integer $isbxsame
 */
class Insurancehistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%insurancehistory}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['management_area', 'state', 'fwdtstate', 'issame', 'isselfselect', 'nameissame', 'isbxsame'], 'integer'],
            [['wheat', 'soybean', 'insuredarea', 'insuredwheat', 'insuredsoybean', 'other', 'insuredother', 'contractarea'], 'number'],
            [['statecontent'], 'string'],
            [['year', 'farmname', 'conractnumber', 'policyholder', 'cardid', 'telephone', 'company_id', 'create_at', 'update_at', 'policyholdertime', 'managemanttime', 'halltime', 'farmername', 'farmerpinyin', 'policyholderpinyin'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'management_area' => '管理区',
            'year' => '年度',
            'farmname' => '农场名称',
			'conractnumber' => 'Conractnumber',
            'policyholder' => '投保人',
            'cardid' => '法人身份证',
            'telephone' => '联系电话',
			'wheat' => 'Wheat',
			'soybean' => 'Soybean',
			'insuredarea' => 'Insuredarea',
			'insuredwheat' => 'Insuredwheat',
			'insuredsoybean' => 'Insuredsoybean',
			'company_id' => 'Company ID',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
			'policyholdertime' => 'Policyholdertime',
			'managemanttime' => 'Managemanttime',
			'halltime' => 'Halltime',
            'other' => '是否存在其他款项',
            'farmername' => '法人姓名',
			'insuredother' => 'Insuredother',
            'state' => '状态',
			'fwdtstate' => 'Fwdtstate',
			'statecontent' => 'Statecontent',
			'issame' => 'Issame',
			'isselfselect' => 'Isselfselect',
			'nameissame' => 'Nameissame',
            'contractarea' => '合同面积',
            'farmerpinyin' => '法人拼音首字母',
			'policyholderpinyin' => 'Policyholderpinyin',
			'isbxsame' => 'Isbxsame',
        ];
    }
}
