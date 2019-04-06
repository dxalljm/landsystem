<?php

namespace app\models;

use frontend\helpers\whereHandle;
use Yii;
use app\models\User;
/**
 * This is the model class for table "{{%fireprevention}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property string $firecontract
 * @property string $safecontract
 * @property string $environmental_agreement
 * @property string $firetools
 * @property string $mechanical_fire_cover
 * @property string $chimney_fire_cover
 * @property string $isolation_belt
 * @property string $propagandist
 * @property string $fire_administrator
 * @property string $cooker
 * @property string $fieldpermit
 * @property string $propaganda_firecontract
 * @property string $leaflets
 * @property string $employee_firecontract
 * @property string $rectification_record
 * @property string $equipmentpic
 * @property string $peoplepic
 * @property string $facilitiespic
 */
class Fireprevention extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fireprevention}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id','management_area','finished'], 'integer'],
            [['firecontract', 'safecontract', 'environmental_agreement', 'firetools', 'mechanical_fire_cover', 'chimney_fire_cover', 'isolation_belt', 'propagandist', 'fire_administrator', 'cooker', 'fieldpermit', 'propaganda_firecontract', 'leaflets', 'employee_firecontract', 'rectification_record', 'equipmentpic', 'peoplepic', 'facilitiespic','year'], 'string', 'max' => 500],
			[['percent'],'number']
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
        	'management_area' => '管理区',
            'firecontract' => '防火合同',
            'safecontract' => '安全生产合同',
            'environmental_agreement' => '环境保护协议',
            'firetools' => '扑火工具',
            'mechanical_fire_cover' => '机械设备防火罩',
            'chimney_fire_cover' => '烟囱防火罩',
            'isolation_belt' => '房屋防火隔离带',
            'propagandist' => '防火义务宣管员',
            'fire_administrator' => '一盒火管理员',
            'cooker' => '液化气灶具',
            'fieldpermit' => '野外作业许可证',
            'propaganda_firecontract' => '消防宣传合同',
            'leaflets' => '防火宣传单',
            'employee_firecontract' => '雇工防火合同',
            'rectification_record' => '防火检查整改记录',
            'equipmentpic' => '设备照片',
            'peoplepic' => '人员照片',
            'facilitiespic' => '设施照片',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'year' => '年度',
			'percent' => '完成百分比',
			'finished' => '是否完成',
        ];
    }

	public static function sixLabels()
	{
		return [
			'firecontract' => ['label'=>'防火合同','type'=>'radioList'],
			'safecontract' => ['label'=>'安全生产合同','type'=>'radioList'],
			'environmental_agreement' => ['label'=>'环境保护协议','type'=>'radioList'],
			'fieldpermit' => ['label'=>'野外作业许可证','type'=>'dialog'],
			'leaflets' => ['label'=>'防火宣传单','type'=>'dialog'],
			'rectification_record' => ['label'=>'防火检查整改记录','type'=>'dialog'],
		];
	}

	public static function sixindexLabels()
	{
		return [
			'firecontract',
			'safecontract',
			'environmental_agreement',
		];
	}

	public static function getSixbfb($farms_id)
	{
		$result = 0;
		$fire = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
		foreach (self::sixindexLabels() as $field) {
			$result += $fire[$field];
		}
		return $result;
	}

    public static function setPercent()
    {
    	return [
	    	'firecontract' => 20,
	    	'safecontract' => 20,
	    	'environmental_agreement' => 20,
	    	'firetools' => 3,
	    	'mechanical_fire_cover' => 3,
	    	'chimney_fire_cover' => 3,
	    	'isolation_belt' => 3,
	    	'propagandist' => 3,
	    	'fire_administrator' => 5,
	    	'cooker' => 3,
	    	'fieldpermit' => 15,
	    	'propaganda_firecontract' => 5,
	    	'leaflets' => 15,
	    	'employee_firecontract' => 3,
	    	'rectification_record' => 10,
	    	'equipmentpic' => 3,
	    	'peoplepic' => 3,
	    	'facilitiespic' => 3,
    	];
    }
    public static function getPercent($model) {
    	$percent = 0;
    	foreach (self::setPercent() as $key => $value) {
    		if($model->$key) {
    			$percent += $value; 
    		}
    	}
    	return $percent;
    }
    public static function getFinir($id)
    {
    	$yes = 0;
    	$no = 0;
    	$array = ['firecontract', 'safecontract', 'environmental_agreement', 'firetools', 'mechanical_fire_cover', 'chimney_fire_cover', 'isolation_belt', 'propagandist', 'fire_administrator', 'cooker', 'fieldpermit', 'propaganda_firecontract', 'leaflets', 'employee_firecontract', 'rectification_record', 'equipmentpic', 'peoplepic', 'facilitiespic'];
    	foreach ($array as $value) {
    		$fire = Fireprevention::find()->where(['id'=>$id])->one();
    		if($fire[$value])
    			$yes++;
    	}
    	return (float)sprintf("%.2f", $yes/count($array))*100;
    }

	public static function getFinishedField()
	{
		return [
			'firecontract' => 20,
			'safecontract' => 20,
			'environmental_agreement' => 20,
			'fieldpermit' => 15,
			'leaflets' => 15,
			'rectification_record' => 10,
		];
	}

	public static function getAllbfb($user_id = null)
	{
		$i = 0;
		$finished = [];
		$allfire = 0;
		$part = 0;
// 		$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
// 		$amountsColor = ['#fedfdf','#feeedf','#fefddf','#e1fedf','#dffcfe','#dfe3fe','#fedffe'];
		if(!empty($user_id)) {

			$managementarea = Farms::getUserManagementArea($user_id)['id'];
		} else {
			$managementarea = Farms::getManagementArea()['id'];
		}
//		if($managementarea) {
//			$query = Fireprevention::find();
//			foreach ($managementarea['id'] as $value) {
//		var_dump($managementarea);
		$allfire = Fireprevention::find()->where(['management_area' => $managementarea, 'year' => User::getYear()])->count();
		$part = Fireprevention::find()->where(['management_area' => $managementarea, 'year' => User::getYear(), 'finished' => [1,2]])->count();
//			}
//		var_dump($allfire);var_dump($part);
		if ($allfire) {
			return (float)sprintf("%.2f", $part / $allfire)*100;
		} else {
			return 0;
		}
//		}

	}
	
	public static function getData($total,$user_id = null)
	{
		$i = 0;
		$all = [];
		$part = [];
		$finished = [];
//     	$percent = 0;
//     	$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
//     	$amountsColor = ['#fedfdf','#feeedf','#fefddf','#e1fedf','#dffcfe','#dfe3fe','#fedffe'];
//     	var_dump(Farms::getUserManagementArea($userid));
		$where = whereHandle::toFireWhere($total);
		if(!empty($user_id)) {
			$managementarea = Farms::getUserManagementArea($user_id)['id'];
		} else {
			$managementarea = Farms::getManagementArea();
		}
		if($managementarea) {
			foreach ($managementarea as $value) {
				$all[] = Fireprevention::find()->where($where)->andFilterWhere(['management_area' => $value])->count();
				$part[] = Fireprevention::find()->where($where)->andFilterWhere(['management_area' => $value, 'finished' => 2])->count();
				$finished[] = Fireprevention::find()->where($where)->andFilterWhere(['management_area' => $value, 'finished' => 1])->count();
			}
//		var_dump($all);
//		var_dump($finished);
			return ['all' => $all, 'part' => $part, 'real' => $finished];
		}
	}
	public static function getBfblist($user_id = null)
	{
		$i = 0;
		$all = [];
		$part = [];
		$finished = [];
//     	$percent = 0;
//     	$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
//     	$amountsColor = ['#fedfdf','#feeedf','#fefddf','#e1fedf','#dffcfe','#dfe3fe','#fedffe'];
//     	var_dump(Farms::getUserManagementArea($userid));

		if(!empty($user_id)) {
			$managementarea = Farms::getUserManagementArea($user_id);
		} else {
			$managementarea = Farms::getManagementArea();
		}
		if($managementarea) {
			foreach ($managementarea['id'] as $value) {
				$all[] = Fireprevention::find()->where(['management_area' => $value, 'year' => User::getYear()])->count();
				$part[] = Fireprevention::find()->where(['management_area' => $value, 'year' => User::getYear(), 'finished' => 2])->count();
				$finished[] = Fireprevention::find()->where(['management_area' => $value, 'year' => User::getYear(), 'finished' => 1])->count();
			}
//		var_dump($all);
//		var_dump($finished);
			return json_encode(['all' => $all, 'part' => $part, 'real' => $finished]);
		}
	}
	public static function newFire($farms_id)
	{
		$farm = Farms::findOne($farms_id);
		$model = new Fireprevention();
		$model->year = User::getYear();
		$model->farms_id = $farms_id;
		$model->farmstate = $farm['state'];
		$model->management_area = $farm['management_area'];
		$model->create_at = time();
		$model->update_at = $model->create_at;
		$model->finished = 0;
		if($model->save()) {
			return $model->id;
		}
		return 0;
	}

	public static function copy($id,$farms_id=null)
	{
		$old = Fireprevention::findOne($id);
		if(empty($farms_id)) {
			$farm = Farms::findOne($old->farms_id);
			$model = new Fireprevention();
			$model->year = User::getYear();
			$model->farms_id = $old->farms_id;
			$model->farmstate = $farm['state'];
			$model->management_area = $farm['management_area'];
			$model->create_at = time();
			$model->update_at = $model->create_at;
			$model->firecontract = $old->firecontract;
			$model->safecontract = $old->safecontract;
			$model->environmental_agreement = $old->environmental_agreement;
			$model->firetools = $old->firetools;
			$model->mechanical_fire_cover = $old->mechanical_fire_cover;
			$model->chimney_fire_cover = $old->chimney_fire_cover;
			$model->isolation_belt = $old->isolation_belt;
			$model->propagandist = $old->propagandist;
			$model->fire_administrator = $old->fire_administrator;
			$model->cooker = $old->cooker;
			$model->fieldpermit = $old->fieldpermit;
			$model->propaganda_firecontract = $old->propaganda_firecontract;
			$model->leaflets = $old->leaflets;
			$model->employee_firecontract = $old->employee_firecontract;
			$model->rectification_record = $old->rectification_record;
			$model->equipmentpic = $old->equipmentpic;
			$model->peoplepic = $old->peoplepic;
			$model->facilitiespic = $old->facilitiespic;
			$model->percent = $old->percent;
			$model->finished = $old->finished;
			if ($model->save()) {
				return $model->id;
			}
			return 0;
		} else {
			$farm = Farms::findOne($farms_id);
			$model = new Fireprevention();
			$model->year = User::getYear();
			$model->farms_id = $farms_id;
			$model->farmstate = $farm['state'];
			$model->management_area = $farm['management_area'];
			$model->create_at = time();
			$model->update_at = $model->create_at;
			$model->firecontract = $old->firecontract;
			$model->safecontract = $old->safecontract;
			$model->environmental_agreement = $old->environmental_agreement;
			$model->firetools = $old->firetools;
			$model->mechanical_fire_cover = $old->mechanical_fire_cover;
			$model->chimney_fire_cover = $old->chimney_fire_cover;
			$model->isolation_belt = $old->isolation_belt;
			$model->propagandist = $old->propagandist;
			$model->fire_administrator = $old->fire_administrator;
			$model->cooker = $old->cooker;
			$model->fieldpermit = $old->fieldpermit;
			$model->propaganda_firecontract = $old->propaganda_firecontract;
			$model->leaflets = $old->leaflets;
			$model->employee_firecontract = $old->employee_firecontract;
			$model->rectification_record = $old->rectification_record;
			$model->equipmentpic = $old->equipmentpic;
			$model->peoplepic = $old->peoplepic;
			$model->facilitiespic = $old->facilitiespic;
			$model->percent = $old->percent;
			$model->finished = $old->finished;
			if ($model->save()) {
				return $model->id;
			}
			return 0;
		}
	}
}
