<?php

namespace app\models;

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
						],
						'integer' 
				],
				[ 
						[ 
								'ypayarea',
								'amounts_receivable',
								'real_income_amount',
								'ypaymoney',
								'owe' 
						],
						'number' 
				],
				[ 
						[ 
								'billingtime','nonumber', 
						],
						'string',
						'max' => 500 
				] 
		];
	}
	public function getAR($year) // 当年应收金额
{
		$farm = Farms::find ()->where ( [ 
				'id' => $_GET ['farms_id'] 
		] )->one ();
		$plantprice = PlantPrice::find ()->where ( [ 
				'years' => $year 
		] )->one ();
		$result = $farm ['measure'] * $plantprice ['price'];
		return ( float ) $result;
	}
	public function getYpayarea($year, $real_income_amount) // 应追缴面积
{
		$plantprice = PlantPrice::find ()->where ( [ 
				'years' => $year 
		] )->one ();
		$result = sprintf ( "%.2f", ($this->getAR ( $year ) - $real_income_amount) / $plantprice ['price'] );
		return $result;
	}
	public function getYpaymoney($year, $real_income_amount) // 应追缴金额
{
		// var_dump($this->getAR($year));
		// var_dump($real_income_amount);
		// var_dump(bcsub($this->getAR($year),(float)$real_income_amount,2));
		return bcsub ( $this->getAR ( $year ), $real_income_amount, 2 );
	}
	public function getOwe($farms_id, $year) // 剩余欠缴金额
{
		$result = 0;
		$collections = Collection::find ()->where ( [ 
				'farms_id' => $farms_id 
		] )->andWhere ( 'ypayyear<' . $year )->all ();
		// print_r($collections);
		foreach ( $collections as $val ) {
			$result += $val ['ypaymoney'];
		}
		return $result;
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
			] )->sum ( 'measure' );
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
				'owe' => '剩余欠缴金额',
				'isupdate' => '是否可更新',
				'create_at' => '创建日期',
				'update_at' => '更新日期',
				'dckpay' => '地产科提交缴费', 
				'management_area' => '管理区',
				'nonumber' => '发票号',
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
		return sprintf("%.2f",Collection::find()->where(['farms_id'=>$whereArray])->sum('real_income_amount')/10000);
	}
	public static function totalAmounts()
	{
		$whereArray = Farms::getManagementArea();
		$allmeasure = Farms::find ()->where ( [
				'management_area' => $whereArray['id']
		] )->sum ( 'measure' );
		return (float)sprintf("%.2f",$allmeasure * PlantPrice::find ()->where ( [ 
							'years' => Theyear::findOne(1)['years'] 
					] )->one ()['price']/10000);
	}
	
	public static function getPercentage()
	{
		$real = self::totalReal();
		$amounts = self::totalAmounts();
// 		var_dump($amounts);exit;
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
}
