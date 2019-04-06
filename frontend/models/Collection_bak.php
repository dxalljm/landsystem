<?php

namespace app\models;

use frontend\models\electronicarchivesSearch;
use Yii;
use app\models\Farms;
use frontend\helpers\MoneyFormat;
use app\models\Theyear;
/**
 * This is the model class for table "{{%collection}}".
 *
 * @property integer $id
 * @property string $payyear
 * @property integer $farms_id
 * @property string $billingtime
 * @property integer $amounts_receivable
 * @property integer $real_income_amount
 * @property integer $ypayyear
 * @property integer $ypayarea
 * @property integer $ypaymoney
 * @property integer $owe
 */
class Collection extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%collection}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'farms_id',
								'ypayyear',
								'isupdate',
								'dckpay',
								'payyear',
								'management_area', 
								'state',
						],
						'integer' 
				],
				[ 
						[ 
								'ypayarea',
								'amounts_receivable',
								'real_income_amount',
								'ypaymoney',
								'owe', 
								'measure',
						],
						'number' 
				],
				[ 
						[ 
								'billingtime','nonumber', 'year'
						],
						'string',
						'max' => 500 
				] 
		];
	}
	public static function getAR($year,$farms_id) // 当年应收金额
	{
//		var_dump($year);var_dump($farms_id);exit;
		$farm = Farms::find ()->where ( [
					'id' => $farms_id
			] )->one ();
		$plantprice = PlantPrice::find ()->where ( [ 
				'years' => $year 
		] )->one ();
		$result = $farm ['contractarea'] * $plantprice ['price'];
//		var_dump($result);exit;
		return ( float ) $result;
	}
	public static function getYpayarea($year,$farms_id) // 应追缴面积
	{
		$result = Collection::find()->where(['farms_id' => $farms_id,'dckpay'=>0])->andWhere('payyear<=' . $year)->orderBy('id DESC')->one();
		if($result['ypayarea'] === null) {
			return Farms::find()->where(['id'=>$farms_id])->one()['contractarea'];
		} else {
			return $result['ypayarea'];
		}
	}
	public static function getYpaymoney($year ,$farms_id) // 应追缴金额
	{
		$result = Collection::find()->where(['farms_id' => $farms_id,'dckpay'=>0])->andWhere('payyear<=' . $year)->orderBy('id DESC')->one();
		if($result['ypaymoney'] === null) {
			return self::getAR($year,$farms_id);
		} else {
			return $result['ypaymoney'];
		}
	}
	public static function getlsOwe($farms_id, $year) // 剩余欠缴金额
	{
		$result = 0.0;
		$colleciton = Collection::find()->where(['farms_id' => $farms_id])->andWhere('ypayyear<=' . $year)->orderBy('id DESC')->all();
		foreach ($colleciton as $coll) {
			$result += $coll['owe'];
		}
//		var_dump($result);
		return $result;
//		if($result) {
//			return $result;
//		} else {
//			return self::getAR($year,$farms_id);
//		}

	}

	
	public static function getOwe($farms_id, $year) // 剩余欠缴金额
	{
		$result = 0.0;
		$collections = Collection::find()->where(['farms_id' => $farms_id])->all();
//		var_dump($result);exit;
		foreach ($collections as $collection) {
			$result += $collection['owe'];
		}
		return $result;

	}
	public static function getOweArea($farms_id, $year) // 剩余欠缴面积
	{
		$result = 0;
		$result = Collection::find ()->where ( [
			'farms_id' => $farms_id,
				'payyear'=>$year,
		] )->one();

		if($result['ypayarea'] === null) {
			return Farms::find()->where(['id'=>$farms_id])->one()['contractarea'];
		} else {
			return $result['ypayarea'];
		}
	}

	public static function getlsOweArea($farms_id, $year) // 剩余欠缴面积
	{
		$result = 0;
		$result = Collection::find ()->where ( [
			'farms_id' => $farms_id,
//				'state'=>1,
		] )->andWhere ( 'payyear<=' . $year )->orderBy('id DESC')->one();

		if($result['ypayarea'] === null) {
			return Farms::find()->where(['id'=>$farms_id])->one()['contractarea'];
		} else {
			return $result['ypayarea'];
		}
	}

	public static function getCollecitonInfo($farms_id) {
		$coll = Collection::find ()->where ( [ 
				'farms_id' => $farms_id 
		] )->count ();
		if ($coll)
			return '已缴纳';
		else
			return '未缴纳';
	}
	public static function getCollection() {
// 		$cacheKey = 'collection-hcharts';
// 		$result = Yii::$app->cache->get ( $cacheKey );
// 		$result =false;
// 		if (! empty ( $result )) {
// 			return $result;
// 		}
		$i = 0;
		$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
		$amountsColor = ['#fedfdf','#feeedf','#fefddf','#e1fedf','#dffcfe','#dfe3fe','#fedffe'];
		foreach ( Farms::getManagementArea ()['id'] as $value ) {
			
			$allmeasure = Farms::find ()->where ( [ 
					'management_area' => $value 
			] )->sum ( 'contractarea' );
			$amounts_receivable [] = [ 
					'color' => $amountsColor[$i],
					'borderColor' => $color [$i],
					'y' => (float)sprintf("%.2f",$allmeasure * PlantPrice::find ()->where ( [
							'years' => date ( 'Y' ) 
					] )->one ()['price']/10000)
			];
			$real_income_amount [] = [
					'color' => $color[$i],
					'y' => ( float ) sprintf("%.2f",Collection::find ()->where ( [ 
					'farms_id' => $value 
			] )->sum ( 'real_income_amount' )/10000)
			];
			$i ++;
		}
		$result = [ 
				[
// 					'color' => '#FFF',					
						'name' => '应收金额',
						'data' => $amounts_receivable,
						'dataLabels' => [
								'enabled' => false,
								'rotation' => 0,
								'color' => '#FFFFFF',
								'align' => 'center',
								'x' => 0,
								'y' => 0,
								'style' => [
										'fontSize' => '13px',
										'fontFamily' => 'Verdana, sans-serif',
										'textShadow' => '0 0 3px black'
								]
						]
				],
				[
						'name' => '实收金额',
						'data' => $real_income_amount,
						'dataLabels' => [
								'enabled' => true,
								'rotation' => 0,
								'color' => '#FFFFFF',
								'align' => 'center',
								'x' => 0,
								'y' => 0,
								'style' => [
										'fontSize' => '13px',
										'fontFamily' => 'Verdana, sans-serif',
										'textShadow' => '0 0 3px black'
								]
						]
				]
		];
// 		var_dump($result[0]);
		$jsonData = json_encode ( [ 
				'result' => $result 
		] );
// 		$landcache = new Cache();
//     	$landcache->actionname = 'collection';
//     	$landcache->content = $jsonData;
//     	$landcache->save();;
	}
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => 'ID',
				'payyear' => '缴费年度',
				'farms_id' => '农场ID',
				'billingtime' => '开票时间',
				'amounts_receivable' => '应收金额',
				'real_income_amount' => '实收金额',
				'ypayyear' => '应缴费年度',
				'ypayarea' => '应追缴费面积',
				'ypaymoney' => '应追缴费金额',
				'measure' => '缴费面积',
				'owe' => '剩余欠缴金额',
				'isupdate' => '是否可更新',
				'create_at' => '创建日期',
				'update_at' => '更新日期',
				'dckpay' => '地产科提交缴费', 
				'management_area' => '管理区',
				'nonumber' => '发票号',
				'year' => '年度',
				'state' => '状态',
		];
	}
	public function getfarms() {
		return $this->hasOne ( Farms::className (), [ 
				'id' => 'farms_id' 
		] );
	}
	
	public static function totalReal()
	{
		$whereArray = Farms::getManagementAreaAllID();
		return sprintf("%.2f",Collection::find()->where(['farms_id'=>$whereArray,'payyear'=>User::getYear()])->sum('real_income_amount'));
	}
	public static function totalAmounts()
	{
		$whereArray = Farms::getManagementArea();
		$allmeasure = Farms::find ()->where ( [
				'management_area' => $whereArray['id']
		] )->sum ( 'contractarea' );
		return (float)sprintf("%.2f",$allmeasure * PlantPrice::find ()->where ( [ 
							'years' => Theyear::findOne(1)['years'] 
					] )->one ()['price']);
	}
	
	public static function getPercentage()
	{
		$real = self::totalReal();
		$amounts = self::totalAmounts();
		if($real !== 0.0 and $amounts !== 0.0)
			$percentage = sprintf("%.2f",$real/$amounts)*100;
		else 
			$percentage = 0;
		return $percentage;
	} 
	
	public static function getFarmRows($params)
	{
		$where = [];
		foreach ($params['collectionSearch'] as $key => $value) {
			if($value !== '')
				$where[$key] = $value;
		}
		$row = Collection::find ()->where ($where)->count ();
		return $row;
	}
	
	public static function getFarmerrows($params)
	{
		$where = [];
		foreach ($params['collectionSearch'] as $key => $value) {
			if($value !== '')
				$where[$key] = $value;
		}
		$result = Collection::find ()->where ($where)->all ();
		//     	var_dump($farms);exit;
		$data = [];
		foreach($result as $value) {
			$farm = Farms::find()->where(['id'=>$value['farms_id']])->one();
			$data[] = ['farmername'=>$farm['farmername'],'cardid'=>$farm['cardid']];
		}
		if($data) {
			$newdata = Farms::unique_arr($data);
			return count($newdata);
		}
		else
			return 0;
	}
	
	public static function getAmounts($params)
	{
		$where = [];
		foreach ($params['collectionSearch'] as $key => $value) {
			if($value !== '')
				$where[$key] = $value;
		}
		$result = Collection::find ()->where ($where)->all ();
		$sum = 0.0;
		foreach($result as $value) {
			$sum += $value['amounts_receivable'];
		}
		return (float)sprintf("%.2f", $sum/10000);
	}
	
	public static function getReal($params)
	{
		$where = [];
		foreach ($params['collectionSearch'] as $key => $value) {
			if($value !== '')
				$where[$key] = $value;
		}
		$result = Collection::find ()->where ($where)->all ();
		$sum = 0.0;
		foreach($result as $value) {
			$sum += $value['real_income_amount'];
		}
		return (float)sprintf("%.2f", $sum/10000);
	}
	
	public static function getAllOwe($params)
	{
		$where = [];
		foreach ($params['collectionSearch'] as $key => $value) {
			if($value !== '')
				$where[$key] = $value;
		}
		$result = Collection::find ()->where ($where)->all ();
		$sum = 0.0;
		foreach($result as $value) {
			$sum += $value['owe'];
		}
		return (float)sprintf("%.2f", $sum/10000);
	}
	
	public static function getAllYpayarea($params)
	{
		$where = [];
		foreach ($params['collectionSearch'] as $key => $value) {
			if($value !== '')
				$where[$key] = $value;
		}
		$result = Collection::find ()->where ($where)->all ();
		$sum = 0.0;
		foreach($result as $value) {
			$sum += $value['ypayarea'];
		}
		return (float)sprintf("%.2f", $sum/10000);
	}
	
	public static function getAllYpaymoney($params)
	{
		$where = [];
		foreach ($params['collectionSearch'] as $key => $value) {
			if($value !== '')
				$where[$key] = $value;
		}
		$result = Collection::find ()->where ($where)->all ();
		$sum = 0.0;
		foreach($result as $value) {
			$sum += $value['ypaymoney'];
		}
		return (float)sprintf("%.2f", $sum/10000);
	}
	
  	public static function getYear()
    {
		$result = Collection::find ()->all ();
		$data = [];
        foreach ($result as $val) {
            $data[] = ['year'=>$val['ypayyear']];
        }
        if($data) {
        	$newdata = Farms::unique_arr($data);
        	foreach ($newdata as $value) {
        		$year[$value['year']] = $value['year'];
        	}
//         	var_dump($year);exit;
        	return $year;
        }
        else
        	return [];
    }
    
    public static function getYearOne($id)
    {
//     	var_dump($id);
    	$data = self::getYear();
//     	var_dump($data);exit;
    	return  $data[$id];   //主要通过此种方式实现
    }

	public static function getCollectionCount()
	{
		$result = Collection::find()->where(['state'=>0,'dckpay'=>1])->count();
		if($result)
			return '<small class="label pull-right bg-red">'.$result.'</small>';
		else
			return false;
	}

    //地产科提交的收缴信息，当前有效
    public static function dckpayReset()
    {
    	$colleciton = Collection::find()->where(['dckpay'=>1,'state'=>0])->all();
    	foreach ($colleciton as $coll) {
    		$year = date('Y',$coll['update_at']);
    		$month = date('m',$coll['update_at']);
    		$day = date('d',$coll['update_at']);
    		$nowyear = date('Y',time());
    		$nowmonth = date('m',time());
    		$nowday = date('d',time());
    		if($year == $nowyear and $month == $nowmonth and $day == $nowday) {
    			$h = (int)date('H',time());
    			if($h >= 18) {
    				$model = Collection::findOne($coll['id']);
    				$model->dckpay = 0;
					$model->real_income_amount = null;
					$model->ypayyear = null;
					$model->measure = null;
					$model->owe = 0.0;
					$model->save();
    			}
    		} else {
    			$model = Collection::findOne($coll['id']);
    			$model->dckpay = 0;
				$model->real_income_amount = null;
				$model->ypayyear = null;
				$model->measure = null;
				$model->owe = 0.0;
				$model->save();
    		}
    	}
    }

	public static function isShow($farms_id,$year)
	{
		$coll = Collection::find()->where(['farms_id'=>$farms_id,'payyear'=>$year])->one();
		if($coll['ypaymoney'] > 0)
			return true;
		else
			return false;
	}

	//缴费年度列表
	public static function yearsList()
	{
		$years = [];
		for($i=2016;$i<=date('Y');$i++) {
			$years[$i] = $i;
		}
		return $years;
	}
}
