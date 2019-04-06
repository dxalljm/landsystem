<?php

namespace app\models;

use frontend\helpers\whereHandle;
use frontend\models\electronicarchivesSearch;
use Yii;
use app\models\Farms;
use frontend\helpers\MoneyFormat;
use app\models\Theyear;
use app\models\PlantPrice;
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
								'iscq',
								'farmstate'
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
								'contractarea',
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

	public static function getYpay($year ,$farms_id) // 是否已经交费
	{
		$result = Collection::find()->where(['farms_id' => $farms_id,'payyear'=>$year,'state'=>0])->one();
		if($result) {
			return true;
		} else {
			return false;
		}
	}

	public static function getlsOwe($farms_id, $year) // 剩余欠缴金额
	{
		$result = 0.0;
		$colleciton = Collection::find()->where(['farms_id' => $farms_id])->andWhere('payyear<=' . $year)->orderBy('id DESC')->all();
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

	public static function getOweFarmsid($farms_id)
	{
		$result = [];
		$collections = Collection::find()->where(['farms_id' => $farms_id])->all();
		foreach ($collections as $collection) {
			
		}
	}
	
	public static function getOwe($farms_id, $year) // 剩余欠缴金额
	{
		$result = 0.0;
		$collections = Collection::find()->where(['farms_id' => $farms_id,'state'=>0,'dckpay'=>0])->all();
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
		$i = 0;
		$amounts_receivable = [];
		$real_income_amount = [];
//		var_dump(Farms::getManagementArea ()['id']);
		foreach ( Farms::getManagementArea ()['id'] as $value ) {
//			$farmQuery = Farms::getAllCondition('collection');
			$allmoney = Collection::find()->andFilterWhere(['management_area'=>$value,'payyear'=>User::getYear()])->groupBy('farms_id')->sum ( 'amounts_receivable' );
			$realmoney = (float)sprintf("%.2f",Collection::find ()->where ( [
				'management_area' => $value,
				'payyear' => User::getYear(),

			] )->andWhere('state>0')->sum ( 'real_income_amount' ));
//			$amounts_receivable [] = (float)sprintf("%.2f",$allmeasure * PlantPrice::find ()->where ( [
//							'years' => User::getYear()
//					] )->one ()['price']);
			$amounts_receivable [] = (float)bcsub($allmoney,$realmoney,2);
			$real_income_amount [] = $realmoney;
			$i ++;
		}
		$result = [ 
				'all'=> $amounts_receivable,
				'real'=> $real_income_amount,
		];
// 		var_dump($result);
		$jsonData = json_encode ($result);
		return $jsonData;
	}


	public static function getCollectionEcharts4($totalData) {
		$i = 0;
		$amounts_receivable = [];
		$real_income_amount = [];
		$area = whereHandle::getManagementArea($totalData);
//		var_dump($area);exit;
//		var_dump(Farms::getManagementArea ()['id']);
		foreach ( $area as $value ) {
//			$farmQuery = Farms::getAllCondition('collection');
			$allmoney = Collection::find()->andFilterWhere(['management_area'=>$value,'payyear'=>User::getYear()])->groupBy('farms_id')->sum ( 'amounts_receivable' );
			$realmoney = (float)sprintf("%.2f",Collection::find ()->where ( [
				'management_area' => $value,
				'payyear' => User::getYear(),

			] )->andWhere('state>0')->sum ( 'real_income_amount' ));
//			$amounts_receivable [] = (float)sprintf("%.2f",$allmeasure * PlantPrice::find ()->where ( [
//							'years' => User::getYear()
//					] )->one ()['price']);
			$amounts_receivable [] = (float)bcsub($allmoney,$realmoney,2);
			$real_income_amount [] = $realmoney;
			$i ++;
		}
		$result = [
			'all'=> $amounts_receivable,
			'real'=> $real_income_amount,
		];
		return $result;
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
				'iscq' => '是否为陈欠',
				'farmstate' => '合同状态',
				'cotractarea' => '合同面积',
		];
	}
	public function getfarms() {
		return $this->hasOne ( Farms::className (), [ 
				'id' => 'farms_id' 
		] );
	}
	
	public static function totalReal($begindate=null,$enddate=null)
	{
		$whereArray = Farms::getManagementArea()['id'];
		if(empty($begindate)) {
			$result = sprintf("%.2f", Collection::find()->where(['management_area' => $whereArray, 'payyear' => User::getYear()])->andWhere('state>0')->sum('real_income_amount'));
		} else {
			$result = sprintf("%.2f", Collection::find()->where(['management_area' => $whereArray, 'payyear' => User::getYear()])->andWhere('state>0')->andFilterWhere(['between','update_at',$begindate,$enddate])->sum('real_income_amount'));
		}
		return $result;
	}
	public static function totalAmounts()
	{
		$query = Farms::getCondition('collection');
		$measureSum = $query->sum('contractarea');
//		var_dump($measureSum);exit;
		$result = (float)sprintf("%.2f",$measureSum * PlantPrice::find ()->where ( [
							'years' => User::getYear()
					] )->one ()['price']);
		return $result;
	}

	public static function totalBfb($begindate=null,$enddate=null)
	{
		$bfb = 0;
		$real = self::totalReal($begindate,$enddate);
		$all = self::totalAmounts();
		if($real >0) {
			$bfb = sprintf("%.2f",$real/$all*100);
		}
		return $bfb.'%';
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
            $data[] = ['year'=>$val['payyear']];
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
		if($result) {
			return '<small class="label pull-right bg-red">'.$result.'</small>';
//			return '<span class="notification">'.$result.'</span>';
		}
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
		if($coll['dckpay'] == 1 and $coll['state'] == 1)
			return true;
		else
			return false;
	}

	/**
	 * @return int
	 */
	public function copy($farms_id,$state=false)
	{
		if($state) {
			$model = new Collection();
			$model->payyear = $this->payyear;
			$model->farms_id = $farms_id;
			$model->billingtime = $this->billingtime;
			$model->amounts_receivable = 0.0;
			$model->real_income_amount = 0.0;
			$model->ypayyear = date('Y');
			$model->ypayarea = 0.0;
			$model->ypaymoney = 0.0;
			$model->measure = 0.0;
			$model->owe = 0.0;
			$model->isupdate = $this->isupdate;
			$model->create_at = time();
			$model->update_at = $this->update_at;
			$model->dckpay = 1;
			$model->management_area = $this->management_area;
			$model->nonumber = $this->nonumber;
			$model->year = $this->year;
			$model->state = 1;
			$model->save();
		} else {
			$model = new Collection();
			$model->payyear = $this->payyear;
			$model->farms_id = $farms_id;
			$model->billingtime = $this->billingtime;
			$model->amounts_receivable = $this->amounts_receivable;
			$model->real_income_amount = $this->real_income_amount;
			$model->ypayyear = $this->ypayyear;
			$model->ypayarea = $this->ypayarea;
			$model->ypaymoney = $this->ypaymoney;
			$model->measure = $this->measure;
			$model->owe = $this->owe;
			$model->isupdate = $this->isupdate;
			$model->create_at = $this->create_at;
			$model->update_at = $this->update_at;
			$model->dckpay = $this->dckpay;
			$model->management_area = $this->management_area;
			$model->nonumber = $this->nonumber;
			$model->year = $this->year;
			$model->state = $this->state;
			$model->save();
		}
	}

	public static function newCollection($farms_id,$state = false)
	{
		$farms = Farms::findOne($farms_id);
		if($state) {
			$model = new Collection();
			$model->payyear = date('Y');
			$model->farms_id = $farms_id;
			$model->amounts_receivable = 0.0;
			$model->real_income_amount = 0.0;
			$model->ypayyear = date('Y');
			$model->ypayarea = 0.0;
			$model->ypaymoney = 0.0;
			$model->measure = 0.0;
			$model->owe = 0.0;
			$model->create_at = time();
			$model->update_at = $model->create_at;
			$model->dckpay = 1;
			$model->management_area = $farms['management_area'];
			$model->state = 1;
			$model->save();
		} else {
			$collectionModel = new Collection();
			$collectionModel->create_at = time();
			$collectionModel->update_at = $collectionModel->create_at;
			$collectionModel->payyear = date('Y');
			$collectionModel->farms_id = $farms_id;
			$collectionModel->amounts_receivable = $collectionModel->getAR(date('Y'), $farms_id);;
			$collectionModel->real_income_amount = 0.0;
			$collectionModel->ypayarea = $farms['contractarea'];
			$collectionModel->ypaymoney = bcmul($farms['contractarea'], $prices = PlantPrice::find()->where(['years' => date('Y')])->one()['price'], 2);
			$collectionModel->owe = 0.0;
			$collectionModel->measure = 0.0;
			$collectionModel->dckpay = 0;
			$collectionModel->state = 0;
			$collectionModel->management_area = $farms['management_area'];
			$collectionModel->save();
		}
	}

	public static function newCollection2($farms_id,$old,$new)
	{
		$farms = Farms::findOne($farms_id);
		$collectionModel = new Collection();
		$collectionModel->create_at = time();
		$collectionModel->update_at = $collectionModel->create_at;
		$collectionModel->payyear = date('Y');
		$collectionModel->farms_id = $farms_id;
		if($old and $new) {
			$collectionModel->amounts_receivable = bcadd($old['amounts_receivable'], $new['amounts_receivable'], 2);
			$collectionModel->real_income_amount = bcadd($old['real_income_amount'], $new['real_income_amount'], 2);
			$collectionModel->ypayarea = bcadd($old['ypayarea'], $new['ypayarea'], 2);
			$collectionModel->ypaymoney = bcadd($old['ypaymoney'], $new['ypaymoney'], 2);
			$state = $old['state'] + $new['state'];
			$measure = bcadd($old['measure'],$new['measure'],2);
		} else {
			if($old and !$new) {
				$collectionModel->amounts_receivable = $old['amounts_receivable'];
				$collectionModel->real_income_amount = $old['real_income_amount'];
				$collectionModel->ypayarea = $old['ypayarea'];
				$collectionModel->ypaymoney = $old['ypaymoney'];
				$state = $old['state'];
				$measure = $old['measure'];
			}
			if(!$old and $new) {
				$collectionModel->amounts_receivable = $new['amounts_receivable'];
				$collectionModel->real_income_amount = $new['real_income_amount'];
				$collectionModel->ypayarea = $new['ypayarea'];
				$collectionModel->ypaymoney = $new['ypaymoney'];
				$state = $new['state'];
				$measure = $new['measure'];
			}
		}
		$collectionModel->management_area = $farms['management_area'];

		switch ($state) {
//			case 0:
//				$collectionModel->dckpay = 0;
//				$collectionModel->state = 0;
//				$collectionModel->owe = 0.0;
//				$collectionModel->measure = 0.0;
//				break;
			case 1:
				$collectionModel->dckpay = 1;
				$collectionModel->state = 2;
				$collectionModel->owe = bcsub($collectionModel->amounts_receivable,$collectionModel->real_income_amount,2);
				$collectionModel->measure = $measure;
				break;
			case 2:
				$collectionModel->dckpay = 1;
				$collectionModel->state = 1;
				$collectionModel->owe = 0.0;
				$collectionModel->measure = $measure;
				break;
		}
		$collectionModel->save();
	}

	public static function newCollectionTtpoarea($farms_id,$old,$new,$area)
	{
		$money = bcmul($area, PlantPrice::find()->where(['years' => date('Y')])->one()['price'], 2);
		if($old['state']) {
			$ypaymoney = 0.0;
			$ypayarea = 0.0;
			$realmoney = $money;
			$measure = $area;
		} else {
			$ypaymoney = $money;
			$ypayarea = $area;
			$realmoney = 0.0;
			$measure = 0.0;
		}
		$farms = Farms::findOne($farms_id);
		$collectionModel = new Collection();
		$collectionModel->create_at = time();
		$collectionModel->update_at = $collectionModel->create_at;
		$collectionModel->payyear = date('Y');
		$collectionModel->farms_id = $farms_id;
		$collectionModel->amounts_receivable = bcadd($new['amounts_receivable'],$money,2);

		$collectionModel->real_income_amount = bcadd($new['real_income_amount'],$realmoney,2);
		$collectionModel->ypayarea = bcadd($new['ypayarea'],$ypayarea,2);
		$collectionModel->ypaymoney = bcadd($new['ypaymoney'],$ypaymoney,2);

		$collectionModel->owe = bcsub($collectionModel->amounts_receivable,$collectionModel->real_income_amount,2);
		$collectionModel->measure = bcadd($new['measure'],$measure,2);
		$collectionModel->management_area = $farms['management_area'];
		$state = $old['state'] + $new['state'];
		switch ($state) {
			case 0:
				$collectionModel->dckpay = 0;
				$collectionModel->state = 0;
				break;
			case 1:
				$collectionModel->dckpay = 1;
				$collectionModel->state = 2;
				break;
			case 2:
				$collectionModel->dckpay = 1;
				$collectionModel->state = 1;
				$collectionModel->amounts_receivable = 0.0;
				$collectionModel->real_income_amount = 0.0;
				break;
		}
//		var_dump($collectionModel);exit;
		$collectionModel->save();
	}

	public static function getUserCollection($userid) {

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
		$jsonData = json_encode ($result);
		return $jsonData;
	}

	public static function iscq($farms_id,$year) {
		$collection = Collection::find()->where(['farms_id'=>$farms_id,'payyear'=>$year])->all();
		foreach ($collection as $c) {
			if($c->state == 1) {
				return false;
			}
		}
		return true;
	}
}
