<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%insuranceplan}}".
 *
 * @property integer $id
 * @property integer $management_area
 * @property string $year
 * @property integer $farms_id
 * @property string $policyholder
 * @property string $cardid
 * @property string $telephone
 * @property double $wheat
 * @property double $soybean
 * @property double $insuredarea
 * @property double $insuredwheat
 * @property double $insuredsoybean
 * @property string $company_id
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $policyholdertime
 * @property integer $managemanttime
 * @property integer $halltime
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
 * @property integer $iscontractarea
 * @property integer $farmstate
 * @property integer $lease_id
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
            [['insurance_id','management_area', 'farms_id', 'create_at', 'update_at', 'policyholdertime', 'managemanttime', 'halltime', 'state', 'fwdtstate', 'issame', 'isselfselect', 'nameissame', 'isbxsame', 'iscontractarea', 'farmstate', 'lease_id'], 'integer'],
            [['wheat', 'soybean', 'insuredarea', 'insuredwheat', 'insuredsoybean', 'other', 'insuredother', 'contractarea'], 'number'],
            [['statecontent'], 'string'],
//            [['iscontractarea', 'farmstate'], 'required'],
            [['year', 'policyholder', 'cardid', 'telephone', 'company_id', 'farmername', 'farmerpinyin', 'policyholderpinyin'], 'string', 'max' => 500],
			[['insured'],'string','max'=>1000],
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'insurance_id' => '保险ID',
			'management_area' => '管理区',
			'year' => '年度',
			'farms_id' => '农场ID',
			'lease_id' => '承租人ID',
			'farmername'=>'农场法人',
			'policyholder' => '被保险人',
			'cardid' => '被保险人身份证',
			'telephone' => '联系电话',
			'wheat' => '小麦',
			'soybean' => '大豆',
			'insuredarea' => '投保面积',
			'contractarea' => '合同面积',
			'insuredwheat' => '投保小麦面积',
			'insuredsoybean' => '投保大豆面积',
			'company_id' => '保险公司',
			'create_at' => '创建日期',
			'update_at' => '更新日期',
			'policyholdertime' => '投保人签字日期',
			'managemanttime' => '管理区提交日期',
			'halltime' => '保险负责人提交日期',
			'other' => '其他',
			'insuredother' => '投保其它面积',
			'state' => '保险单状态',
			'fwdtstate' => '地产科',
			'statecontent' => '情况说明',
			'issame' => '申报面积与保险面积是否一致',
			'isselfselect' => '是否本要选择承保公司',
			'nameissame' => '法人与被保险人是否一致',
			'farmerpinyin' => '法人拼音',
			'policyholderpinyin' => '被投保人拼音',
			'isbxsame' => '是否与保险公司一致',
			'iscontractarea' => '是否与合同面积一致',
			'farmstate' => '农场状态',
			'insured' => '保险情况',
		];
	}


	public static function getNoPrint($state = 'right')
	{
		$noprontRows = Insuranceplan::find()->where(['management_area'=>Farms::getManagementArea()['id'],'fwdtstate'=>1,'state'=>0,'year'=>User::getYear()])->count();

		if($noprontRows) {
			if($state == 'right')
				return '<small class="label pull-right bg-red">' . $noprontRows . '</small>';
			else
				return '<small class="label">' . $noprontRows . '</small>';
		} else {
			if($state == 'right')
				return false;
			else
				return 0;
		}
	}

	public static function getUserfwdtCount()
	{
		$mamangmentarea = Farms::getManagementArea();
		$processRows = 0;

		$processRows = Insuranceplan::find()->where(['management_area'=>$mamangmentarea['id'],'state'=>0,'fwdtstate'=>2])->count();

		if($processRows)
			return '<small class="label pull-right bg-red">'.$processRows.'</small>';
		else
			return false;
	}
	public static function getUserdckCount()
	{
		$mamangmentarea = Farms::getManagementArea();
		$processRows = 0;

		$processRows = Insuranceplan::find()->where(['management_area'=>$mamangmentarea['id'],'state'=>0,'fwdtstate'=>1])->count();

		if($processRows)
			return '<small class="label pull-right bg-red">'.$processRows.'</small>';
		else
			return false;
	}
	public static function getOverArea($farms_id)
	{
		$areaSum = Insuranceplan::find()->where(['farms_id' => $farms_id])->andWhere('state<>-1')->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->sum('insuredarea');

		return $areaSum;
	}
	public static function isShowAdd($farms_id)
	{
		$contractarea = Farms::find()->where(['id'=>$farms_id])->one()['contractarea'];
		$areaSum = self::getOverArea($farms_id);
		if($areaSum == 0.0)
			return true;
		if($areaSum < $contractarea)
			return true;
		else
			return false;
	}

	public static function getInsuranceplancache()
	{
		$i = 0;
		$allarea = [];
		$insurancearea = [];
		$color = ['#f30703', '#f07304', '#f1f100', '#02f202', '#01f0f0', '#0201f2', '#f101f1'];
		$amountsColor = ['#fedfdf', '#feeedf', '#fefddf', '#e1fedf', '#dffcfe', '#dfe3fe', '#fedffe'];

		$result = [];
		$management_area = Farms::getManagementArea()['id'];

		foreach ($management_area as $value) {
			if (Insuranceplan::find()->where([
				'management_area' => $value,
				'state' => 1,
				'year' => User::getYear(),
			])->count()
			) {
				$area = (float)Insuranceplan::find()->where([
					'management_area' => $value,
					'state' => 1,
					'year' => User::getYear(),
				])->sum('insuredarea');
			} else {
				$area = 0;
			}
			$insurancearea[] = (float)$area;
			$query = Farms::getAllCondition();
			$allarea[] = (float)bcsub($query->andFilterWhere([
				'management_area' => $value
			])->sum('contractarea'), $area, 2);
		}
		$result['all'] = $allarea;
		$result['real'] = $insurancearea;
		$jsonData = json_encode($result);
		return $jsonData;
	}

	public static function getArea($plant_id,$str)
	{
		$array = explode(',',$str);
		foreach ($array as $value) {
			$arr = explode('-',$value);
			if($arr[0] == $plant_id) {
//				return $arr;
				return $arr[1];
			} else {
				return 0;
			}
		}
	}
}
