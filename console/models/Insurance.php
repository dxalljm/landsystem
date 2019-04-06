<?php

namespace console\models;

use Yii;
use frontend\helpers\arraySearch;
/**
 * This is the model class for table "{{%insurance}}".
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
 * @property string $create_at
 * @property string $update_at
 * @property string $policyholdertime
 * @property string $managemanttime
 * @property string $halltime
 */
class Insurance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%insurance}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['management_area','nameissame', 'farms_id','state','fwdtstate','issame','isselfselect','company_id','isbxsame'], 'integer'],
            [['wheat', 'soybean', 'insuredarea', 'insuredwheat', 'insuredsoybean','other','insuredother','contractarea'], 'number'],
            [['year','farmername', 'policyholder', 'cardid', 'telephone',  'create_at', 'update_at', 'policyholdertime', 'managemanttime', 'halltime','farmerpinyin','policyholderpinyin'], 'string', 'max' => 500],
            [['statecontent'],'string'],
        	[['company_id','insuredarea','contractarea'],'required']
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
            'farms_id' => '农场ID',
            'farmername'=>'农场法人',
            'policyholder' => '被保险人',
            'cardid' => '法人身份证',
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
        	'namesisame' => '法人与被保险人是否一致',
            'farmerpinyin' => '法人拼音',
            'policyholderpinyin' => '被投保人拼音',
        	'isbxsame' => '是否与保险公司一致'
        ];
    }

    public static function getUserfwdtCount()
    {
        $mamangmentarea = Farms::getManagementArea();
        $processRows = 0;

        $processRows = Insurance::find()->where(['management_area'=>$mamangmentarea['id'],'state'=>0,'fwdtstate'=>0])->count();

        if($processRows)
            return '<small class="label pull-right bg-red">'.$processRows.'</small>';
        else
            return false;
    }
    public static function getUserdckCount()
    {
        $mamangmentarea = Farms::getManagementArea();
        $processRows = 0;

        $processRows = Insurance::find()->where(['management_area'=>$mamangmentarea['id'],'state'=>0,'fwdtstate'=>1])->count();

        if($processRows)
            return '<small class="label pull-right bg-red">'.$processRows.'</small>';
        else
            return false;
    }
    public static function getOverArea($farms_id)
    {
    	$insurance = Insurance::find()->where(['farms_id' => $farms_id])->andWhere('state<>-1')->all();
    	$areaSum = 0.0;
    	if($insurance) {
    		foreach ($insurance as $value) {
    			$areaSum += $value['insuredarea'];
    		}
    	}
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

    public static function getInsurancecache($userid)
    {
        $i = 0;
        $allarea = [];
        $insurancearea = [];
        $color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
        $amountsColor = ['#fedfdf','#feeedf','#fefddf','#e1fedf','#dffcfe','#dfe3fe','#fedffe'];
        if(count(Farms::getUserManagementArea($userid)) > 1) {
        	foreach ( Farms::getUserManagementArea($userid) as $value ) {
        	
        		
        	
        		if(Insurance::find ()->where ( [
        				'management_area' => $value,
        				'state' => 1,
        				'year' => User::getYear($userid),
        		] )->count()) {
        			$area =  (float)Insurance::find ()->where ( [
        					'management_area' => $value,
        					'state' => 1,
        					'year' => User::getYear($userid),
        			] )->sum ( 'insuredarea' );
        		} else {
        			$area = 0;
        		}   
        		$insurancearea[] = (float)$area;
                $query = Farms::getLandCondition($userid);
        		$allarea[] = (float)bcsub($query->andFilterWhere ( [
        				'management_area' => $value
        		] )->sum ( 'contractarea' ),$area,2);
        	}
        	$result['all'] = $allarea;
        	$result['real'] = $insurancearea;
        } else {

        	if(Insurance::find ()->where ( [
        			'management_area' => Farms::getUserManagementArea($userid),
        			'state' => 1,
        			'year' => User::getYear($userid),
        	] )->count()) {
        		$insurancearea[] =  Insurance::find ()->where ( [
        				'management_area' => Farms::getUserManagementArea($userid),
        				'state' => 1,
        				'year' => User::getYear($userid),
        		] )->sum ( 'insuredarea' );
        	} else {
        		$insurancearea[] = 0;
        	}
            if(date('Y') == User::getYear($userid)) {
                $allarea[] = Farms::find ()->where ( [
                    'state'=>[1,2,3,4,5],
                    'management_area' => Farms::getUserManagementArea($userid),
                ] )->sum ( 'contractarea' );
            } else {
        	    $query = Farms::getLandCondition($userid);
        	    $allarea[] = $query->sum('contractarea');
            }

        	$result['all'] = [(float)$allarea];
        	$result['real'] = [(float)$insurancearea];
        }
        
//         var_dump($result);
//         $result = [[
//             'name'=>'保险面积',
//             'type'=>'bar',
//             'stack'=>'sum',
//             'barCategoryGap'=>'50%',
//             'itemStyle'=>[
//                 'normal'=> [
//                     'color'=> 'tomato',
//                     'barBorderColor'=> 'tomato',
//                     'barBorderWidth'=> 3,
//                     'barBorderRadius'=>0,
//                     'label'=>[
//                         'show'=> true,
//                         'position'=> 'insideTop'
//                     ]
//                 ]
//             ],
//             'data'=>$insurancearea,
//         ],
//             [
//                 'name'=>'合同面积',
//                 'type'=>'bar',
//                 'stack'=>'sum',
//                 'itemStyle'=> [
//                     'normal'=> [
//                         'color'=>'#fff',
//                         'barBorderColor'=> 'tomato',
//                         'barBorderWidth'=> 3,
//                         'barBorderRadius'=>0,
//                         'label' => [
//                             'show'=> false,
//                             'position'=> 'top',
// // 						'formatter'=> '{c}/10000.toFixed(2)',
//                             'textStyle'=>[
//                                 'color'=> 'tomato'
//                             ]
//                         ]
//                     ]
//                 ],
//                 'data'=>$allarea,
//             ]];

        $jsonData = json_encode ($result);
        return $jsonData;
    }
}
