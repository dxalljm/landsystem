<?php

namespace console\models;
use Yii;
use console\models\Farms;
use frontend\helpers\MoneyFormat;
use console\models\Theyear;

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
								'payyear' 
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
								'billingtime' 
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
	public static function getCollection($userid) {

		$i = 0;
		$amounts_receivable = [];
		$real_income_amount = [];
		$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
		$amountsColor = ['#fedfdf','#feeedf','#fefddf','#e1fedf','#dffcfe','#dfe3fe','#fedffe'];
		foreach ( Farms::getUserManagementArea($userid) as $value ) {
			$allmeasure = Farms::find ()->where ( [
					'management_area' => $value,
// 					'state' => 1,
			] )->andFilterWhere(['between', 'state', 1,3])->andFilterWhere(['between','create_at',Theyear::getYeartime($userid)[0],Theyear::getYeartime($userid)[1]])->sum ( 'contractarea' );
			
			$amountsSum = ( float ) sprintf ( "%.2f", $allmeasure * PlantPrice::find ()->where ( [
					'years' => User::getYear($userid)
			] )->one ()['price'] );
			
			
			$collectionSUm = 0.0;
			
			$collectionSUm = Collection::find ()->where ( [
					'management_area' => $value,
// 					'state' => 1,
					'payyear' => User::getYear($userid),
			] )->andWhere('farms_id<>0')->sum ( 'real_income_amount' );
// 			$amounts_receivable  = ( float )bcsub($amountsSum , $collectionSUm ,2);
			$amounts_receivable = $amountsSum;
			$real_income_amount  = ( float ) sprintf ( "%.2f", $collectionSUm );
			$result['all'][] = $amounts_receivable;
			$result['real'][] = $real_income_amount;
		}
// 		$cha = [];
// 		var_dump($real_income_amount);
// 		for($i=0;$i<count($amounts_receivable);$i++) {
// 			if($real_income_amount[$i])
// 				$cha[$i] = $amounts_receivable[$i] - $real_income_amount[$i];
// 			else
// 				$cha[$i] = 0;
// 		}
// 		exit;
		$result = [[
			'name'=>'实收金额',
			'type'=>'bar',
			'stack'=>'sum',
			'barCategoryGap'=>'50%',
			'itemStyle'=>[
				'normal'=> [
					'color'=> 'tomato',
					'barBorderColor'=> 'tomato',
					'barBorderWidth'=> 3,
					'barBorderRadius'=>0,
					'label'=>[
						'show'=> true, 
						'position'=> 'insideTop'
					]
				]
			],
			'data'=>$result['real'],
		],
		[
			'name'=>'应收金额',
			'type'=>'bar',
			'stack'=>'sum',
			'itemStyle'=> [
				'normal'=> [
					'color'=>'#fff',
					'barBorderColor'=> 'tomato',
					'barBorderWidth'=> 3,
					'barBorderRadius'=>0,
					'label' => [
						'show'=> false,
						'position'=> 'top',
// 						'formatter'=> '{c}/10000.toFixed(2)',
						'textStyle'=>[
							'color'=> 'tomato'
						]
					]
				]
			],
			'data'=>$result['all'],
		]];
// 		$result = [ 
// 				[
// // 					'color' => '#FFF',					
// 						'name' => '应收金额',
// 						'data' => $amounts_receivable,
// 						'dataLabels' => [
// 								'enabled' => false,
// 								'rotation' => 0,
// 								'color' => '#FFFFFF',
// 								'align' => 'center',
// 								'x' => 0,
// 								'y' => 0,
// 								'style' => [
// 										'fontSize' => '13px',
// 										'fontFamily' => 'Verdana, sans-serif',
// 										'textShadow' => '0 0 3px black'
// 								]
// 						]
// 				],
// 				[
// 						'name' => '实收金额',
// 						'data' => $real_income_amount,
// 						'dataLabels' => [
// 								'enabled' => true,
// 								'rotation' => 0,
// 								'color' => '#FFFFFF',
// 								'align' => 'center',
// 								'x' => 0,
// 								'y' => 0,
// 								'style' => [
// 										'fontSize' => '13px',
// 										'fontFamily' => 'Verdana, sans-serif',
// 										'textShadow' => '0 0 3px black'
// 								]
// 						]
// 				]
// 		];
// 		var_dump($result);
		$jsonData = json_encode ($result);
		return $jsonData;
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
				'dckpay' => '地产科提交缴费' 
		];
	}
	public function getfarms() {
		return $this->hasOne ( Farms::className (), [ 
				'id' => 'farms_id' 
		] );
	}
	
	public static function totalReal($userid)
	{
		$whereArray = Farms::getUserManagementArea($userid);
		//$farm = Farms::find()->where(['management_area'=>$whereArray])->all();
		//$sum = 0.0;
		//foreach ($farm as $value) {
			$sum = (float)Collection::find()->where(['management_area'=>$whereArray,'state'=>1,'payyear'=>User::getYear($userid)])->andWhere('farms_id<>0')->sum('real_income_amount');
// 			var_dump($s);
			
		//}
		
		return sprintf("%.2f",$sum).'元';
	}
	public static function totalAmounts($userid)
	{
		$whereArray = Farms::getUserManagementArea($userid);
		$farms = Farms::find()->where(['management_area'=>$whereArray])->all();
		$sum = 0.0;
		foreach ($farms as $value) {
			$sum += Farms::getNowContractnumberArea($value['id']);
		}
		return (float)sprintf("%.2f",$sum * PlantPrice::find ()->where ( [ 
							'years' => User::getYear($userid)
					] )->one ()['price']).'元';
	}
}
