<?php

namespace app\models;

use Yii;
use yii\helpers\Json;
use app\models\ManagementArea;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Farmselastic;

/**
 * This is the model class for table "{{%farms}}".
 *
 * @property integer $id
 * @property string $farmname
 * @property string $address
 * @property string $management_area
 * @property string $spyear
 * @property integer $measure
 * @property string $zongdi
 * @property string $cooperative_id
 * @property string $surveydate
 * @property string $groundsign
 * @property string $investigator
 * @property string $farmersign
 */
class Farms extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%farms}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				
				[ 
						[ 
								'measure',
								'notclear',
								'notstate',
								'contractarea',
						],
						'number' 
				],
				[ 
						[ 
								'zongdi', 
								'remarks',
						],
						'string' 
				],
				[ 
						[ 
								'management_area',
								'state',
								'oldfarms_id',
								'locked',
								'notstateinfo',
						],
						'integer' 
				],
				[ 
						[ 
								'farmname',
								'farmername',
								'cardid',
								'telephone',
								'address',
								'cooperative_id',
								'surveydate',
								'groundsign',
								'farmersign',
								'pinyin',
								'farmerpinyin',
								'contractnumber',
								'begindate',
								'enddate',
								'latitude',
								'longitude',
								'accountnumber',
								
						],
						'string',
						'max' => 500 
				],
// 				[
// 					[
// 							'cardid',
// 					],
// 					'string',
// 					'max' => 18,
// 					'min' => 18,
// 				],
// 				[
// 				[
// 						'telephone',
// 				],
// 					'string',
// 					'max' => 11,
// 					'min' => 11,
// 				],
				[ 
						[ 
								'measure',
								'spyear' 
						],
						'safe' 
				],
				[
				[
						'farmname',
						'farmername',
						'cardid',
						'address',
						'longitude',
						'latitude'
					
				],
				'required'
								],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => 'ID',
				'farmname' => '农场名称',
				'farmername' => '承包人姓名',
				'cardid' => '身份证号',
				'telephone' => '电话号码',
				'address' => '农场位置',
				'management_area' => '管理区',
				'spyear' => '审批年度',
				'measure' => '面积',
				'zongdi' => '宗地',
				'cooperative_id' => '合作社',
				'surveydate' => '合同领取日期',
				'groundsign' => '地产科签字',
				'farmersign' => '农场法人签字',
				'create_at' => '创建日期',
				'update_at' => '更新日期',
				'pinyin' => '农场名称拼音首字母',
				'farmerpinyin' => '法人姓名简单首字母',
				'state' => '状态',
				'notclear' => '未明确地块面积',
				'contractnumber' => '合同号',
				'begindate' => '开始日期',
				'enddate' => '结束日期',
				'oldfarms_id' => '变更前ID',
				'latitude' => '纬度',
				'longitude' => '经度',
				'locked' => '锁定',
				'notstate' => '未明确状态面积',
				'notstateinfo' => '未明确状态信息',
				'accountnumber' => '账页号',
				'contractarea' => '合同面积',
				'remarks' => '备注',

		];
	}

	public static function getBusinessmenu()
	{
		$department = Department::findOne(Yii::$app->getUser()->getIdentity()->department_id);
		$arrayBusinessMenu = explode ( ',', $department->businessmenu );
		return $arrayBusinessMenu;
	}

	public static function showFarminfo($farms_id)
	{
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		if(!empty($farm->zongdi)) {
			$zongdi = '宗地(' . count(explode('、', $farm->zongdi)) . '宗)：';
		} else
			$zongdi = '';
		if($farm->notstate)
			$notstate =  $farm->notstate.'亩';
		else
			$notstate = '';
		$html = '';
		$html .= '<table width="600px" class="table table-bordered table-hover">';
		$html .= '<tr>';
		$html .= '<td width="79" align="right">管理区：</td>';
		$html .= '<td width="90" align="left">'.ManagementArea::find()->where(['id'=>$farm->management_area])->one()['areaname'].'</td>';
		$html .= '<td width="88" align="right">农场名称：</td>';
		$html .= '<td width="60" align="left">'.$farm->farmname.'</td>';
		$html .= '<td width="71" align="right">法人：</td>';
		$html .= '<td width="94" align="left">'.$farm->farmername.'</td>';
		$html .= '<td width="58" align="right">电话：</td>';
		$html .= '<td width="23" align="left">'.$farm->telephone.'</td>';
		$html .= '<td width="26" rowspan="6" align="left">'.Html::img(Farmerinfo::find()->where(['cardid'=>$farm->cardid])->one()['photo'],['height'=>'130px']).'</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td align="right">农场位置：</td>';
		$html .= '<td align="left">'.$farm->address.'</td>';
		$html .= '<td align="right">合同号：</td>';
		$html .= '<td width="80" align="left">'.$farm->contractnumber.'</td>';
		$html .= '<td align="right">面积：</td>';
		$html .= '<td align="left">'.$farm->contractarea.'亩'.'</td>';
		$html .= '<td width="100" align="right">未明确地块：</td>';
		$html .= '<td width="23" align="left">'.$farm->notclear.'亩'.'</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td align="right">'.$zongdi.'</td>';
		$html .= '<td colspan="5" align="left">';
		$zongdiArray = explode('、',$farm->zongdi);
		if($zongdiArray) {
			$html .= '<table width="100%" border="0" align="right">';
			for($i = 0;$i<count($zongdiArray);$i++) {
				if($i%10 == 0) {
					$html .= '<tr height="10">';
					$html .=  '<td align="left">';
					$html .=  $zongdiArray[$i];
					$html .=  '</td>';
				} else {
					$html .= '<td align="left">';
					$html .= $zongdiArray[$i];
					$html .= '</td>';
				}
//				if($i%10 == 0)
//					$html .= '</tr>';
			}
			$html .= '</table>';
		}
		$html .= '</td>';
		$html .= '<td width="23" align="left">'.$farm->notstateinfo.'</td>';
		$html .= '<td width="23" align="left">'.$notstate.'</td>';
		$html .= '</tr>';
		$html .= '</table>';
		return $html;
	}

	public static function showFarminfo2($farms_id)
	{
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		if(!empty($farm->zongdi)) {
			$zongdi = '宗地(' . count(explode('、', $farm->zongdi)) . '宗)：';
		} else
			$zongdi = '';
		if($farm->notstate)
			$notstate =  $farm->notstate.'亩';
		else
			$notstate = '';
		$html = '';
		$html .= '<table class="table table-bordered table-hover">';
		$html .= '<tr>';
		$html .= '<td width="79" align="right" style="vertical-align: middle;">管理区：</td>';
		$html .= '<td width="90" align="left" style="vertical-align: middle;">'.ManagementArea::find()->where(['id'=>$farm->management_area])->one()['areaname'].'</td>';
		$html .= '<td width="88" align="right" style="vertical-align: middle;">农场名称：</td>';
		$html .= '<td width="60" align="left" style="vertical-align: middle;">'.$farm->farmname.'</td>';
		$html .= '<td width="71" align="right" style="vertical-align: middle;">法人：</td>';
		$html .= '<td width="94" align="left" style="vertical-align: middle;">'.$farm->farmername.'</td>';
		$html .= '<td width="58" align="right" style="vertical-align: middle;">身份证号：</td>';
		$html .= '<td width="23" align="left" style="vertical-align: middle;">'.$farm->cardid.'</td>';
		$html .= '<td width="26" rowspan="6" align="left" style="vertical-align: middle;">'.Html::img(Farmerinfo::find()->where(['cardid'=>$farm->cardid])->one()['photo'],['height'=>'130px']).'</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td align="right" style="vertical-align: middle;">农场位置：</td>';
		$html .= '<td align="left" style="vertical-align: middle;">'.$farm->address.'</td>';
		$html .= '<td align="right" style="vertical-align: middle;">合同号：</td>';
		$html .= '<td width="80" align="left" style="vertical-align: middle;">'.$farm->contractnumber.'</td>';
		$html .= '<td align="right" style="vertical-align: middle;">面积：</td>';
		$html .= '<td align="left" style="vertical-align: middle;">'.$farm->contractarea.'亩'.'</td>';
		$html .= '<td width="80" align="right" style="vertical-align: middle;">电话：</td>';
		$html .= '<td width="23" align="left" style="vertical-align: middle;">'.$farm->telephone.'</td>';
		$html .= '</tr>';
		$html .= '</table>';
		return $html;
	}

	public static function businessIcon()
	{
		$html = '';
		$alloption = '<i class="fa fa-reply-all text-red"></i>';
		$url = Url::to(['farms/farmsbusiness']);
		$alltitle = '全部';
		$html .= Html::a($alloption,$url, [
				'id' => 'farmermenu',
				'title' => $alltitle,
		]);
		$html .= '&nbsp;';
		$zongdioption = '<i class="fa fa-map text-red"></i>';
		$url = Url::to(['farms/farmsbusiness','zongdi'=>'icon']);
		$html .= Html::a($zongdioption,$url, [
				'title' => '宗地',
		
		]);
		$html .= '&nbsp;';
		$notclearoption = '<i class="fa fa-exclamation-circle text-red"></i>';
		$url = Url::to(['farms/farmsbusiness','notclear'=>'icon']);
		$html .= Html::a($notclearoption,$url, [
				'title' => '未明确地块面积',
		
		]);
		$html .= '&nbsp;';
		$notstateoption = '<i class="fa fa-tag text-red"></i>';
		$url = Url::to(['farms/farmsbusiness','notstate'=>'icon']);
		$html .= Html::a($notstateoption,$url, [
				'title' => '未明确状态面积',
		
		]);
		$html .= '&nbsp;';
		$disputeoption = '<i class="fa fa-commenting text-red"></i>';
		$url = Url::to(['farms/farmsbusiness','dispute'=>'icon']);
		$html .= Html::a($disputeoption,$url, [
				'title' => '纠纷',
		]);
		$html .= '&nbsp;';
		$machineoption = '<i class="fa fa-truck text-red"></i>';
		$url = Url::to(['farms/farmsbusiness','machine'=>'icon']);
		$html .= Html::a($machineoption,$url, [
				'title' => '农机器具',
		]);
		$html .= '&nbsp;';
		$projectoption = '<i class="fa fa-sticky-note-o text-red"></i>';
		$url = Url::to(['farms/farmsbusiness','project'=>'icon']);
		$html .= Html::a($projectoption,$url, [
				'title' => '项目',
		]);
		$html .= '&nbsp;';
		$collecitonoption = '<i class="fa fa-cny text-red"></i>';
		$collectiontitle = '已交承包费';
		$url = Url::to(['farms/farmsbusiness','collection'=>'icon']);
		$html .= Html::a($collecitonoption,$url, [
			'title' => $collectiontitle,
		]);
		$html .= '&nbsp;';
		$collecitondckoption = '<i class="fa fa-bullhorn text-red"></i>';
		$collectiondcktitle = '承包费已提交到财务科';
		$url = Url::to(['farms/farmsbusiness','collectiondck'=>'icon']);
		$html .= Html::a($collecitondckoption,$url, [
			'title' => $collectiondcktitle,
		]);
		$html .= '&nbsp;';
		$lockoption = '<i class="fa fa-lock text-red"></i>';
		$url = Url::to(['farms/farmsbusiness','locked'=>1]);
		$locktitle = '冻结';
		$html .= Html::a($lockoption,$url, [
				'id' => 'farmermenu',
				'title' => $locktitle,
		]);
		$html .= '&nbsp;';
		
		return $html;
	}
	
	public static function notstateInfo($id = NULL)
	{
		$array = ['销户','正常','未更换合同','临时性管理','买断合同','其它','未明确状态','测量误差','政府征用地','树苗地','其它'];
// 		var_dump($id);exit;
		if(empty($id)) {
			return $array[$id];
		}
		if(is_array($id)) {
			foreach ($id as $key => $val) {
				$result[$val] = $array[$val];
			}
//			var_dump($result);
			return $result;
		}
//		var_dump($array[$id]);exit;
		if(is_string($id) or is_int($id))
			return $array[$id];
		else 
			return $array;
	}

	public static function getStateInfo($id = NULL)
	{
		$array = ['销户','正常','未更换合同','临时性管理','买断合同','其它','未明确状态','测量误差','政府征用地','树苗地','其它'];
// 		var_dump($id);exit;
		if(empty($id)) {
			return $array[$id];
		}
		if(is_array($id)) {
			foreach ($id as $key => $val) {
				$result[$val] = $array[$val];
			}
//			var_dump($result);
			return $result;
		}
//		var_dump($array[$id]);exit;
		if(is_string($id) or is_int($id))
			return $array[$id];
		else
			return $array;
	}

	public static function getFarmsAreaID($farms_id) {
		return Farms::find ()->where ( [ 
				'id' => $farms_id 
		] )->one ()['management_area'];
	}
	public function getfarmer() {
		return $this->hasOne ( Farmer::className (), [ 
				'farms_id' => 'id' 
		] );
	}
	
	/**
	 * 修改农场保存之后
	 */
	public function afterSave($insert, $changedAttributes) {
		// 调用父级afterSave
		parent::afterSave ( $insert, $changedAttributes );
		
		// 删除指定缓存
		$cacheKeyList = [ 
				'farms-search-all' 
		];
		foreach ( $cacheKeyList as $cacheName ) {
			Yii::$app->cache->delete ( $cacheName );
		}
	}
	// 获取冻结信息
	public static function getLocked($farms_id) {
		return self::findOne ( $farms_id )['locked'];
	}
	
	public static function getReviewprocessState($farms_id)
	{
		$unlockFarm = Reviewprocess::find()->where(['oldfarms_id'=>$farms_id,'state'=>7])->count();
		if($unlockFarm)
			return true;
		else 
			return false;
	}
	public static function getNewContractserialnumber()
	{
		$newcontractnumber = self::getNewContractnumber();
		$array = explode('-',$newcontractnumber);
		return $array[0];
	}
	
	public static function getContractserialnumber($farms_id) {
		$farm = Farms::find ()->where ( [
				'id' => $farms_id
		] )->one ();
		$contractnumber = $farm->contractnumber;
		$array = explode ( '-', $contractnumber );
	
		return $array [0];
	}
	public static function getLoanState($farms_id)
	{
		$unlockLoan = Loan::find ()->where ( [
				'farms_id' => $farms_id
		] )->one ()['enddate'];
		if ($unlockLoan) {
			if (strtotime ( date ( 'Y-m-d' ) ) > strtotime ( $unlockLoan )) 
				return true;
			else
				return false;
		}
	}
	
	// 解冻
	public static function unLocked($farms_id) {
		$model = Farms::findOne($farms_id);
		if(self::getReviewprocessState($farms_id) and self::getLoanState($farms_id)) {
			$lockid = Lockedinfo::find()->where(['farms_id'=>$farms_id])->one()['id'];
			$lockedModel = Lockedinfo::findOne($lockid);
			if($lockedModel)
				$lockedModel->delete();
			$model->locked = 0;
		}
		$model->save();
	}
	/**
	 * 搜索所有农场信息
	 * 
	 * @author wubaiqing <wubaiqing@vip.qq.com>
	 */
	public static function searchAll() {

		$cacheKey = 'farms-search-all22'.\Yii::$app->getUser()->id;

		$result = Yii::$app->cache->get($cacheKey);
		if (!empty($result)) {
			return $result;
		}
		$url = 'index.php?r=farms/farmsmenu&farms_id=';
		if(User::getItemname('服务大厅')) {
			if(isset($_GET['id']))
				$url = 'index.php?r=huinong/huinongprovideone&huinong_id='.$_GET['huinong_id'].'&farms_id=';
			else
				$url = 'index.php?r=huinong/huinongprovideone&farms_id=';
		}
		if(User::getItemname('财务科')) {
			$url = 'index.php?r=collection/collectionindex&farms_id=';
		}

		// 所有农场
		$data = [];
		$where = self::getManagementArea()['id'];
		$result = Farms::find()->where(['management_area'=>$where,'state'=>[1,2,3,4,5]])->all();
		foreach ($result as $farm) {
			$data[] = [
				'value' => $farm['pinyin'], // 拼音
				'data' => $farm['farmname'].'('.$farm['contractarea'].')', // 下拉框显示的名称
				'url' => Url::to($url.$farm['id']),
			];
			$data[] = [
				'value' => $farm['farmerpinyin'],
				'data' => $farm['farmername'].'('.$farm['contractarea'].')',
				'url' => Url::to($url.$farm['id']),

			];
		}
//		var_dump($data);
		$jsonData = Json::encode($data);
		Yii::$app->cache->set($cacheKey, $jsonData, 3600);
		
		return $jsonData;
// 		$departmentid = User::find ()->where ( [ 
// 				'id' => Yii::$app->getUser ()->id 
// 		] )->one ()['department_id'];
// 		$keshi = Department::find ()->where ( [ 
// 				'id' => $departmentid 
// 		] )->one ();
// 		switch ($keshi ['departmentname']) {
// 			case '财务科' :
// 				$url = 'index.php?r=collection/collectionindex&farms_id=';
// 				break;
// 			default :
// 				$url = 'index.php?r=farms/farmsmenu&farms_id=';
// 		}
// 		// 所有农场
// 		$data = [ ];
// 		$where = self::getManagementArea ()['id'];
// 		$result = Farmselastic::find ()->where ( [ 
// 				'management_area' => $where 
// 		] )->all ();
// // 		var_dump($result);exit;
// 		foreach ( $result as $farm ) {
// 			$data [] = [ 
// 					'value' => $farm ['pinyin'], // 拼音
// 					'data' => $farm ['farmname'], // 下拉框显示的名称
// 					'url' => Url::to ( $url . $farm ['id'] ) 
// 			];
// 			$data [] = [ 
// 					'value' => $farm ['farmerpinyin'],
// 					'data' => $farm ['farmername'],
// 					'url' => Url::to ( $url . $farm ['id'] ) 
// 			]
// 			;
// 		}
// 		$jsonData = Json::encode ( $data );
// 		return $jsonData;

		// $cacheKey = 'farms-search-all2';
		
		// $result = Yii::$app->cache->get($cacheKey);
		// if (!empty($result)) {
		// return $result;
		// }
		// $departmentid = User::find()->where(['id'=>Yii::$app->getUser()->id])->one()['department_id'];
		// $keshi = Department::find()->where(['id'=>$departmentid])->one();
		// switch ($keshi['departmentname'])
		// {
		// case '财务科';
		// $url = 'index.php?r=collection/collectionindex&farms_id=';
		// break;
		// default:
		// $url = 'index.php?r=farms/farmsmenu&farms_id=';
		// }
		
		// // 所有农场
		// $data = [];
		// $where = explode(',', $keshi['membership']);
		// $result = Farms::find()->where(['management_area'=>$where])->all();
		// foreach ($result as $farm) {
		// $data[] = [
		// 'value' => $farm['pinyin'], // 拼音
		// 'data' => $farm['farmname'], // 下拉框显示的名称
		// 'url' => Url::to($url.$farm['id']),
		// ];
		// $data[] = [
		// 'value' => $farm['farmerpinyin'],
		// 'data' => $farm['farmername'],
		// 'url' => Url::to($url.$farm['id']),
		
		// ];
		// }
		// $jsonData = Json::encode($data);
		// Yii::$app->cache->set($cacheKey, $jsonData, 3600);
		
		// return $jsonData;

	}

	public static function diff_zongdi($zongdi,$ttpozongdi)
	{
		$zongdiArray = explode('、',$zongdi);
		$ttpozongdiArray = explode('、',$ttpozongdi);
		if(empty($zongdi)) {
			return $ttpozongdiArray;
		}

		$zongdiNumber = [];
		foreach ($zongdiArray as $value) {
			$zongdiNumber[] = Lease::getZongdi($value);
		}
		$ttpozongdiNumber = [];
		foreach ($ttpozongdiArray as $value) {
			$ttpozongdiNumber[] = Lease::getZongdi($value);
		}

		$diff = array_diff($zongdiNumber,$ttpozongdiNumber);
		$result = [];
		foreach ($ttpozongdiArray as $value) {
			foreach ($diff as $dv) {
				if(Lease::getZongdi($value) == $dv) {
					$result[] = $value;
				}
			}
		}
//		var_dump($result);exit;
		return $result;
	}

	public static function iden_zongdi($zongdi,$ttpozongdi)
	{
		if(empty($zongdi)) {
			return '';
		}
		$zongdiArray = explode('、',$zongdi);
		$ttpozongdiArray = explode('、',$ttpozongdi);
		$result= [];
		foreach ($zongdiArray as $item) {
			foreach ($ttpozongdiArray as $value) {
				if(Lease::getZongdi($item) == Lease::getZongdi($value)) {
					$result[] = $value;
				}
			}
		}
		return $result;
	}

	public static function getFarmArray($management_area = NULL) {
		$management_area = self::getManagementArea()['id'];
		$farms = self::find ()->where ( [ 
				'management_area' => $management_area,
				'state' => 1,
		] )->all ();
		
		foreach ( $farms as $key => $value ) {
			$arrayFarmsid [] = $value ['id'];
		}
		return $arrayFarmsid;
	}
	public static function showRow($farms_id) {
		$arrayFarmsid = self::getFarmArray ();
		$top = $arrayFarmsid [0];
		$last = $arrayFarmsid [count ( $arrayFarmsid ) - 1];
		$up = 0;
		$down = 0;
		$nownum = 0;
		
		for($i = 0; $i < count ( $arrayFarmsid ); $i ++) {
// 			echo $arrayFarmsid[$i].'<br>';
			if ($farms_id == $arrayFarmsid [$i]) {
// 				echo $arrayFarmsid [$i];
				$nownum = $i + 1;
				$farmsid = $arrayFarmsid [$i];
				if ($i !== 0)
					$up = $arrayFarmsid [$i - 1];
				if ($i !== count ( $arrayFarmsid ) - 1)
					$down = $arrayFarmsid [$i + 1];
			}
		}
		$action = Yii::$app->controller->id . '/' . yii::$app->controller->action->id;
		// echo $action;
		echo '<table class="table table-bordered table-hover">';
		echo '<tr>';
		echo '<td width="10%" align="center"><a href=' . Url::to ( 'index.php?r=' . Yii::$app->controller->id . '/' . yii::$app->controller->action->id . '&farms_id=' . $top ) . '><font size="5">第一条<a></td>';
		echo '<td width="10%" align="center"><a href=' . Url::to ( 'index.php?r=' . Yii::$app->controller->id . '/' . yii::$app->controller->action->id . '&farms_id=' . $up ) . '><font size="5">上一条</font><a></td>';
		echo '<td width="10%" align="center"><a href=' . Url::to ( 'index.php?r=' . Yii::$app->controller->id . '/' . yii::$app->controller->action->id . '&farms_id=' . $down ) . '><font size="5">下一条</font><a></td>';
		echo '<td width="15%" align="center"><a href=' . Url::to ( 'index.php?r=' . Yii::$app->controller->id . '/' . yii::$app->controller->action->id . '&farms_id=' . $last ) . '><font size="5">最后一条</font><a></td>';
		echo '<td width="10%">' . html::textInput ( 'jump', $nownum, [ 
				'class' => 'form-control',
				'id' => 'rowjump' 
		] ) . '</td>';
		echo '<td>' . html::button ( '跳转', [ 
				'class' => 'btn btn-success',
				'onclick' => 'jumpurl("' . $action . '")' 
		] ) . '</td>';
		echo '</tr>';
		echo '</table>';
		echo html::hiddenInput ( 'famsid', '', [ 
				'id' => 'setFarmsid' 
		] );
	}
	public static function getContractnumber($farms_id, $state = null) {
		$farm = Farms::find ()->where ( [ 
				'id' => $farms_id 
		] )->one ();
		$number = self::getLastContractNubmer();
		$contractnumberModel = Contractnumber::findOne(1);
		if ($state == 'add')
			$cn1 = str_pad ( $number + 1, 4, '0', STR_PAD_LEFT );
		else
			$cn1 = str_pad ( $number, 4, '0', STR_PAD_LEFT );
		
		if (date ( 'Y' ) <= $contractnumberModel->lifeyear)
			$cn2 = substr ( '2010', 2 );
		else
			$cn2 = substr ( $contractnumberModel->lifeyear, 2 );
		if ($state == 'new')
			$cn3 = 0;
		else
			$cn3 = $farm->contractarea;
			
			// $cn3 = substr($cn3,0,strlen($cn3)-1);
		$cn4 = $farm->management_area;
		if($state == 'number')
			return $cn1;
		$contractnumber = $cn1 . '-' . $cn2 . '-' . $cn3 . '-' . $cn4;
		return $contractnumber;
	}
	
	public static function getNewContractnumber() {
		$contractnumber = Contractnumber::findOne ( 1 );
		$cn1 = str_pad ( $contractnumber->contractnumber, 4, '0', STR_PAD_LEFT );
	
		if (date ( 'Y' ) <= $contractnumber->lifeyear)
			$cn2 = substr ( '2010', 2 );
		else
			$cn2 = substr ( $contractnumber->lifeyear, 2 );
		$cn3 = 0;
			
		// $cn3 = substr($cn3,0,strlen($cn3)-1);
		$cn4 = 1;
		$contractnumber = $cn1 . '-' . $cn2 . '-' . $cn3 . '-' . $cn4;
		return $contractnumber;
	}
	
	public static function getNowContractnumberArea($farms_id, $state = null) {
		$farm = Farms::find ()->where ( [ 
				'id' => $farms_id 
		] )->one ();
		$contractnumber = $farm->contractnumber;
		$array = explode ( '-', $contractnumber );

		return $array [2];
	}
	
	public static function getContractnumberArea($contractnumber)
	{
 		if(empty($contractnumber))
			return 0;
		$array = explode ( '-', $contractnumber );
//		var_dump($array);exit;
		return $array [2];
	}
	
	public static function getManagementArea($str = NULL) {
// 		$result = [];
// var_dump(yii::$app->user->identity->username);exit;
		$dep_id = User::findByUsername ( yii::$app->user->identity->username )['department_id'];
		$departmentData = Department::find ()->where ( [ 
				'id' => $dep_id 
		] )->one ();
		$whereArray = explode ( ',', $departmentData ['membership'] );
// 		var_dump($whereArray);exit;
		$managementarea = ManagementArea::find ()->where ( [ 
				'id' => $whereArray 
		] )->all ();
		foreach ( $managementarea as $value ) {
			$result ['id'] [] = $value ['id'];
			if ($str == 'small')
				$result ['areaname'] [] = str_ireplace ( '管理区', '', $value ['areaname'] );
			else
				$result ['areaname'] [] = $value ['areaname'];
		}
// 		var_dump($result);
		return $result;
	}

	public static function managementAreaDropDownList($str = NULL) {
 		$result['id'] = [0=>null];
		$result['areaname'] = [0=>null];
// var_dump(yii::$app->user->identity->username);exit;
		$dep_id = User::findByUsername ( yii::$app->user->identity->username )['department_id'];
		$departmentData = Department::find ()->where ( [
			'id' => $dep_id
		] )->one ();
		$whereArray = explode ( ',', $departmentData ['membership'] );
		$managementarea = ManagementArea::find ()->where ( [
			'id' => $whereArray
		] )->all ();
		foreach ( $managementarea as $value ) {
			$result ['id'] [] = $value ['id'];
			if ($str == 'small')
				$result ['areaname'] [] = str_ireplace ( '管理区', '', $value ['areaname'] );
			else
				$result ['areaname'] [] = $value ['areaname'];
		}
		return $result;
	}

	public static function getUserManagementArea($user_id,$str = NULL) {
		$result = [];
// 		var_dump(>username);exit;
		$dep_id = User::findIdentity( $user_id )['department_id'];
		$departmentData = Department::find ()->where ( [
				'id' => $dep_id
		] )->one ();
// 		var_dump($departmentData);exit;
		$whereArray = explode ( ',', $departmentData ['membership'] );
		$managementarea = ManagementArea::find ()->where ( [
				'id' => $whereArray
		] )->all ();
		foreach ( $managementarea as $value ) {
			$result ['id'] [] = $value ['id'];
			if ($str == 'small')
				$result ['areaname'] [] = str_ireplace ( '管理区', '', $value ['areaname'] );
			else
				$result ['areaname'] [] = $value ['areaname'];
		}
// 		var_dump($result);exit;
		return $result;
	}
	
	public static function getManagementAreaAllID() {
		$allid = [ ];
		$management_ids = self::getManagementArea ()['id'];
		foreach ( $management_ids as $value ) {
			$farms = Farms::find ()->where ( [ 
					'management_area' => $value,
					'state' => 1 
			] )->all ();
			foreach ( $farms as $val ) {
				$allid [] = $val ['id'];
			}
		}
		return $allid;
	}
	//比较宗地号是否在另一个参数中，参数为字符串，返回比较后的数组
	public static function is_zongdi($zongdistr,$zongdistrs)
	{
		foreach ($zongdiarray as $zongdi) {
			if(strstr(Lease::getZongdi($zongdistr),$zongdistrs)) {
				
			}
		}
	}
	public static function farmSearch($str)
	{

		if (preg_match ("/^[A-Za-z]/", $str)) {
			$tj = ['like','pinyin',$str];
		} else {
			$tj = ['like','farmname',$str];
		}
		 
		return $tj;
	}
	
	public static function farmerSearch($str)
	{

		if (preg_match ("/^[A-Za-z]/", $str)) {
			$tj = ['like','farmerpinyin',$str];
		} else {
			$tj = ['like','farmername',$str];
		}
		//     	var_dump($tj);exit;
		return $tj;
	}
	public static function getRows($params = NULL) {
// 		var_dump($params);
		$farm = Farms::find ();
		$farm->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
		if($params['farmsSearch']['management_area'])
			$management_area = $params['farmsSearch']['management_area'];
		else 
			$management_area = self::getManagementArea ()['id'];
		if (empty ( $params )) {
			$farm->andWhere(['management_area' => self::getManagementArea ()['id']]);
			$farm->andWhere(['state' => 1]);
		} else {
			foreach ($params['farmsSearch'] as $key => $value) {
				switch ($key) {
					case 'farmname':
						$farm->andFilterWhere(self::farmSearch($value));
						break;
					case 'farmername':
						$farm->andFilterWhere(self::farmerSearch($value));
						break;
					case 'management_area':
						$farm->andWhere([$key=>$management_area]);
						break;
					case 'state':
						$farm->andWhere([$key=>$value]);
						break;
					case 'contractarea':
						$farm->andWhere($key.$value);
						break;
					default:
						$farm->andFilterWhere(['like',$key,$value]);
				}
			}
		}
// 		var_dump($farm->where);
		$row = $farm->count ();
		return $row;
	}

	
	public static function getFarmarea($params) 
	{
		$farm = Farms::find ();
		$farm->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
		if($params['farmsSearch']['management_area'])
			$management_area = $params['farmsSearch']['management_area'];
		else
			$management_area = self::getManagementArea ()['id'];
		if (empty ( $params )) {
			$farm->andWhere(['management_area' => self::getManagementArea ()['id']]);
			$farm->andWhere(['state' => 1]);
		} else {
			foreach ($params['farmsSearch'] as $key => $value) {
				switch ($key) {
					case 'farmname':
						$farm->andFilterWhere(self::farmSearch($value));
						break;
					case 'farmername':
						$farm->andFilterWhere(self::farmerSearch($value));
						break;
					case 'management_area':
						$farm->andWhere([$key=>$management_area]);
						break;
					case 'state':
						$farm->andWhere([$key=>$value]);
						break;	
					case 'contractarea':
						$farm->andWhere($key.$value);
						break;
					default:
						$farm->andFilterWhere(['like',$key,$value]);
				}
			}
		}
		$sum = 0.0;
		foreach ( $farm->all() as $value ) {
			$area = self::getNowContractnumberArea ( $value ['id'] );
			$sum += $area;
		}
		$result = ( float ) sprintf ( "%.2f", $sum / 10000 );
		
		return $result;
	}
	
	public static function getFarmsarea() {
		
		$areas = [];
		//     	$sum = 0.0;
		$farmsID = [];
		$percent = [];
		$rows = [];
		$rowpercent = [];
		$i=0;
		$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
		$start = explode(' ', microtime());
		$all = Farmselastic::sum('contractarea');
		$endtime = explode(' ', microtime());
		$times =  $endtime[0]+$endtime[1]-($start[0]+$start[1]);
		echo $times;exit;
		foreach (self::getManagementArea()['id'] as $value) {
	
			$area = ( float ) Farmselastic::sum ('contractarea',['management_area' => $value]);
				
			$areas[] = (float)sprintf("%.2f", $area/10000);
			$percent[] = sprintf("%.2f", $area/$all*100);
			$i++;
		}
		$end = microtime(true);
		$all = Farmselastic::find ()->count ();
		foreach (self::getManagementArea()['id'] as $value) {
			$row = ( float ) Farmselastic::find ()->limit(9999)->where ( [
					'management_area' => $value
			] )->count ();
			 
			$rows[] = $row;
			$rowpercent[] = sprintf("%.2f", $row/$all*100);
		}
		$result = [
	    	        [
	    	            'name'=>'面积',
	    	            'type'=>'bar',
	    	            'data'=>$areas,
// 	    	            'markPoint' => [
// 	    	                'data' =>  [
// 	    	                    ['type' =>  'max', 'name' =>  '最大值'],
// 	    	                    ['type' =>  'min', 'name' =>  '最小值']
// 	    	                ]
// 	    	            ],
// 	    	            'markLine' =>  [
// 	    	                'data' =>  [
// 	    	                    ['type' =>  'average', 'name' =>  '平均值']
// 	    	                ]
// 	    	            ]
	    	        ],
	    	        [
	    	            'name' => '数量',
	    	            'type' => 'bar',
	    	            'data' => $rows,
// 	    	            'markPoint' =>  [
// 	    	                'data ' =>  [
// 	    	                    ['name' =>  '年最高', 'value' =>  182.2, 'xAxis' =>  7, 'yAxis' =>  183, 'symbolSize' => 18],
// 	    	                    ['name' =>  '年最低', 'value' =>  2.3, 'xAxis' =>  11, 'yAxis' =>  3]
// 	    	                ]
// 	    	            ],
// 	    	            'markLine' =>  [
// 	    	                'data' =>  [
// 	    	                    ['type' =>  'average', 'name' =>  '平均值']
// 	    	                ]
// 	    	            ]
	    	        ]
	    	    ];
	
// 		    	var_dump($result);exit;
		$jsonData = json_encode(['result'=>$result]);
		//     	Yii::$app->cache->set ( $cacheKey, $jsonData, 1 );
		return $jsonData;
	}
	
	public static function totalNum() {
		$whereArray = self::getManagementArea ();
		return Farms::find ()->where ( [ 
				'management_area' => $whereArray ['id'] 
		] )->count () . '户';
	}
	
	public static function totalArea() {
		$whereArray = self::getManagementArea ();
		return sprintf ( "%.2f", Farms::find ()->where ( [ 
				'management_area' => $whereArray ['id'] 
		] )->sum ( 'contractarea' ) / 10000 ) . '万亩';
	}
	private static function getPlate($controller, $menuUrl) {
// 		$cacheKey = 'cache-key-plate1'.\Yii::$app->getUser()->id;
// 		$value = Yii::$app->cache->get($cacheKey);
// 		if (!empty($value)) {
// 			return $value;
// 		}
		$where = self::getManagementArea ()['id'];
		switch ($controller) {
			case 'farms' :
				$value ['icon'] = 'fa fa-delicious';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '共' . Farms::find ()->where ( [ 
						'management_area' => Farms::getManagementArea ()['id'],
						'state' => [1,2,3,4,5] 
				] )->count () . '户农场';
				$value ['description'] = '农场基础信息';
				break;
			case 'plantingstructure' :
				$value ['icon'] = 'fa fa-pagelines';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '种植了' . Plantingstructure::getPlantRowsMenu ( [ 
						'begindate'=>Theyear::getYeartime()[0],
						'enddate' =>Theyear::getYeartime()[1],
						'management_area' => self::getManagementArea ()['id'], 
				] ) . '种作物';
				$value ['description'] = '种植作物信息';
				break;
			case 'yields' :
				$value ['icon'] = 'fa fa-line-chart';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '产品信息';
				$value ['description'] = '农产品产量信息';
				break;
			case 'huinonggrant' :
				$value ['icon'] = 'fa fa-dollar';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '现有' . Huinong::find ()->andWhere ( 'enddate>=' . Theyear::getYeartime ()[0] )->andWhere ( 'enddate<=' . Theyear::getYeartime ()[1] )->count () . '条惠农补贴信息';
				$value ['description'] = '补贴发放情况';
				break;
			case 'collection' :
				$value ['icon'] = 'fa fa-cny';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '完成' . Collection::getPercentage () . '%';
				$value ['description'] = '承包费收缴情况';
				break;
			case 'fireprevention' :
				$value ['icon'] = 'fa fa-fire-extinguisher';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '有' . Fireprevention::find ()->where ( [ 
						'management_area' => $where 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '户签订防火合同';
				$value ['description'] = '防火完成情况';
				break;
			case 'breedinfo' :
				$value ['icon'] = 'fa fa-github-alt';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$employeerows = Breed::find ()->where ( [ 
						'management_area' => $where 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count ();
				$value ['info'] = '共有' . $employeerows . '户养殖户';
				$value ['description'] = '养殖户基本信息';
				break;
			case 'disaster' :
				$value ['icon'] = 'fa fa-soundcloud';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '有' . Disaster::find ()->where ( [ 
						'management_area' => $where 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '户受灾';
				$value ['description'] = '农户受灾情况';
				break;
			case 'projectapplication' :
				$value ['icon'] = 'fa fa-road';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '有' . Projectapplication::find ()->where ( [ 
						'management_area' => $where 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '条基础设施建设';
				$value ['description'] = '项目情况';
				break;
			case 'insurance' :
				$value ['icon'] = 'fa fa-file-text';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '已经办理' . Insurance::find ()->where ( [
						'management_area' => $where,
						'state' => 1
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '保险业务';
				$value ['description'] = '保险业务';
				break;
			case 'loan' :
				$value ['icon'] = 'fa fa-bank';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '已经办理' . Reviewprocess::find ()->where ( [
						'management_area' => $where,
						'state' => 7
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '贷款业务';
				$value ['description'] = ' 贷款业务';
				break;
			case 'photograph' :
				$value ['icon'] = 'fa fa-camera';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$farmerinfo = Farmerinfo::find()->where(['cardid'=>Farms::getFarmsCardID($_GET['farms_id'])])->one();
				if($farmerinfo['cardpic'] == '' or $farmerinfo['cardpicback'] == '' or $farmerinfo['photo'] == '')
					$value ['info'] = '法人电子信息不完整';
				else
					$value['info'] = '法人电子信息已经全部采集完成';
				$value ['description'] = ' 电子信息采集';
				break;
			default :
				$value = false;
		}
// 		Yii::$app->cache->set($cacheKey, $value, 0);
		return $value;
	}
	public static function showEightPlantmenu() {
		
//		$cache = 'cache-key-plantmenu22'.\Yii::$app->getUser()->id;
//    	$html = Yii::$app->cache->get($cache);
//    	if (!empty($html)) {
//    		return $html;
//    	}
		
//		$businessmenu = Yii::$app->getUser()->getIdentity()->plate;
		$arrayBusinessMenu = User::getPlate()['id'];
		$sort = [];
		foreach ($arrayBusinessMenu as $menu) {
			$menuUrl = Mainmenu::find ()->where ( [
					'id' => $menu
			] )->one ();
			$sort[$menuUrl['sort']] = $menuUrl;
		}
		sort($sort);
//     	var_dump($sort);exit;
//     	$result = array_flip($sort);
		$html = '<div class="row" >';
		
		for($i = 0; $i < count ( $sort ); $i ++) {			
			$html .= self::showEightPlant ( $sort[$i] );
		}
		$html .= '</div>';
//		Yii::$app->cache->set($cache, $html, 3600);
		return $html;
	}
	private static function showEightPlant($menuUrl) {
		$str = explode ( '/', $menuUrl ['menuurl'] );
		$divInfo = self::getPlate ( $str [0], $menuUrl );
		$html = '<div class="col-md-1-5" style="text-align:center;">';
		$html .= '<a href=' . $divInfo ['url'] . '>';
		$html .= '<div class="info-box bg-blue">';
		$html .= '<span class="info-box-icon"><i class="' . $divInfo ['icon'] . '"></i></span>';
		$html .= '<div class="info-box-content">';
		$html .= '<span class="info-box-number">' . $divInfo ['title'] . ' </span>';
		$html .= '<span class="info-box-text">' . $divInfo ['info'] . '</span>';
		$html .= '<!-- The progress section is optional -->';
		$html .= '<div class="progress">';
		$html .= '<div class="progress-bar" style="width: 100%"></div>';
		$html .= '</div>';
		$html .= '<span class="progress-description">';
		$html .= $divInfo ['description'];
		$html .= '</span>';
		$html .= '</div><!-- /.info-box-content -->';
		$html .= '</div><!-- /.info-box --></a>';
		$html .= '</div>';
		return $html;
	}
	public static function unique_arr($array2D, $stkeep = false, $ndformat = true) {
		// 判断是否保留一级数组键 (一级数组键可以为非数字)
		if ($stkeep)
			$stArr = array_keys ( $array2D );
			
			// var_dump($array2D);exit;
			// 判断是否保留二级数组键 (所有二级数组键必须相同)
		if ($ndformat)
			$ndArr = array_keys ( end ( $array2D ) );
			
			// 降维,也可以用implode,将一维数组转换为用逗号连接的字符串
		foreach ( $array2D as $v ) {
			$v = join ( ",", $v );
			$temp [] = $v;
		}
		
		// 去掉重复的字符串,也就是重复的一维数组
		$temp = array_unique ( $temp );
		
		// 再将拆开的数组重新组装
		foreach ( $temp as $k => $v ) {
			if ($stkeep)
				$k = $stArr [$k];
			if ($ndformat) {
				$tempArr = explode ( ",", $v );
				foreach ( $tempArr as $ndkey => $ndval )
					$output [$k] [$ndArr [$ndkey]] = $ndval;
			} else
				$output [$k] = explode ( ",", $v );
		}
		
		return $output;
	}
	// 获取管理区法人个数
	public static function getFarmerrows($params) 
	{
		$farm = Farms::find ();
		$farm->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
		if($params['farmsSearch']['management_area'])
			$management_area = $params['farmsSearch']['management_area'];
		else
			$management_area = self::getManagementArea ()['id'];
		if (empty ( $params )) {
			$farm->andWhere(['management_area' => self::getManagementArea ()['id']]);
			$farm->andWhere(['state' => 1]);
		} else {
			foreach ($params['farmsSearch'] as $key => $value) {
				switch ($key) {
					case 'farmname':
						$farm->andFilterWhere(self::farmSearch($value));
						break;
					case 'farmername':
						$farm->andFilterWhere(self::farmerSearch($value));
						break;
					case 'management_area':
						$farm->andWhere([$key=>$management_area]);
						break;
					case 'state':
						$farm->andWhere([$key=>$value]);
						break;					
					case 'contractarea':
						$farm->andWhere($key.$value);
						break;
					default:
						$farm->andFilterWhere(['like',$key,$value]);
				}
			}
		}
		$data = [ ];
		foreach ( $farm->all() as $value ) {
			$data [] = [ 
					'farmername' => $value ['farmername'],
					'cardid' => $value ['cardid'] 
			];
		}
		if ($data) {
			$newdata = Farms::unique_arr ( $data );
			return count ( $newdata );
		} else
			return 0;
	}
	
	public static function getContractarea($farmsid) {
		$model = self::findOne($farmsid);
		return $model->contractarea;
	}
	public static function getFarmsState($array=NULL)
	{
		if($array) {
			return self::notstateInfo($array);
		}
		return self::notstateInfo();
	}
	public static function getFarmsStateOne($id)
	{
		if($id === 0)
			return '销户';
		if($id)
			return self::notstateInfo($id);
		else
			return Null;
	}

	//获取法人身份证号
	public static function getFarmsCardID($farms_id)
	{
		$model = Farms::findOne($farms_id);
		return $model->cardid;
	}
	//相同宗地号,面积进行合并
	public static function zongdiHB($newzongdi)
	{
		$result = [];
		$zongdiArray = [];
		if(empty($newzongdi))
			return '';
		$newarray = explode('、',$newzongdi);
		foreach ($newarray as $new) {
			$zongdiArray[Lease::getZongdi($new)][] = Lease::getArea($new);
		}
		foreach ($zongdiArray as $zongdi => $area) {
			$sum = 0.0;
			if(count($area) > 1) {
				foreach ($area as $val) {
					$sum = bcadd($sum,$val,2);
				}

			} else {
				$sum = $area[0];
			}
			$result[] = $zongdi.'('.$sum.')';
		}
		if($result)
			return implode('、',$result);
		else
			return '';
	}

	public static function getFarmsStateInfo($cond,$datestr,$year,$valuestr)
	{
		$result = '';
		$farm = Farms::find();

		$farm->andFilterWhere($cond)
			->andFilterWhere(['between',$datestr,strtotime($year.'-01-01 00:00:01'),strtotime($year.'-12-31 23:59:59')]);
		var_dump($farm->where);
		switch ($valuestr)
		{
			case 'count':
				$result = $farm->count();
				break;
			case 'all':
				$result = $farm->all();
				break;
			case 'all-id':
				foreach ($farm->all() as $val)
				{
					$result[] = $val['id'];
				}
//				var_dump('3333');
				break;
		}
		var_dump($result);
	}

	public static function getLastContractNubmer()
	{
		$number = [];
		$lastNumber = Contractnumber::find()->where(['id'=>1])->one()['contractnumber'];
		$narray = [];
		for($i=1575;$i<=$lastNumber;$i++)
		{
			$narray[] = $i;
		}
		$farms = Farms::find()->all();
		foreach ($farms as $farm) {
			$n = explode('-',$farm['contractnumber']);
			if($n > $i)
				$number[] = $n[0];
		}

		$result = array_diff($narray,$number);
		if($result)
			return min($result);
		else
			return $lastNumber;
	}

	//判断农场信息是否完整
	public static function isFarmsInfo($farms_id)
	{
		$model = Farms::findOne($farms_id);
		if($model->cardid == '' or $model->address == '' or $model->longitude == '' or $model->latitude == '')
			return true;
		else
			return false;
	}
}
