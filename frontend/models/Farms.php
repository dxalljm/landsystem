<?php

namespace app\models;

use frontend\helpers\MoneyFormat;
use Yii;
use yii\helpers\Json;
use app\models\ManagementArea;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Farmselastic;
use app\models\Numberlock;

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
								'tempdata',
								'isbreed',
								'otherstate',
								'nowyearstate',
								'creditscore',
								'star',
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
			'management_area' => '管理区',
			'farmname' => '农场名称',
			'pinyin' => '农场名称拼音首字母',
			'farmername' => '承包人姓名',
			'farmerpinyin' => '法人姓名简单首字母',
			'cardid' => '身份证号',
			'telephone' => '电话号码',
			'address' => '农场位置',
			'contractarea' => '合同面积',
			'measure' => '宗地面积',
			'spyear' => '审批年度',
			'zongdi' => '宗地',
			'cooperative_id' => '合作社',
			'surveydate' => '合同领取日期',
			'groundsign' => '地产科签字',
			'farmersign' => '农场法人签字',
			'create_at' => '创建日期',
			'update_at' => '更新日期',
			'notclear' => '未明确地块面积',
			'contractnumber' => '合同号',
			'begindate' => '开始日期',
			'enddate' => '结束日期',
			'oldfarms_id' => '变更前ID',
			'longitude' => '经度',
			'latitude' => '纬度',
			'locked' => '锁定',
			'notstate' => '未明确状态面积',
			'notstateinfo' => '未明确状态信息',
			'accountnumber' => '账页号',
			'state' => '状态',
			'remarks' => '备注',
			'tempdata' => '是否为临时数据',
			'isbreed' => '是否为养殖户',
			'otherstate' => '其他状态',
			'nowyearstate' => '当年流转状态',
			'satr' => '当前信用分',
			'creditscore' => '信息总分',
		];
	}

	public static function getFarmerCount($allid)
	{
		$data = [];
		
		$farms = Farms::find()->select('cardid')->where(['id'=>$allid])->distinct()->count();
//		foreach ($farms as $farm) {
//			$data[] = ['farmername'=>$farm['farmername'],'cardid'=>$farm['cardid']];
//		}

		if($farms) {
//			$newdata = Farms::unique_arr($data);
			return $farms;
		}
		else
			return 0;
	}
	public static function getFarmsCount($allid)
	{
		$data = [];
		$newid = array_unique($allid);
		$farms = Farms::find()->where(['id'=>$newid])->count();
		//		foreach ($farms as $farm) {
		//			$data[] = ['farmername'=>$farm['farmername'],'cardid'=>$farm['cardid']];
		//		}
	
		if($farms) {
			//			$newdata = Farms::unique_arr($data);
			return $farms;
		}
		else
			return 0;
	}
	public static function getBusinessmenu()
	{
		$menu = [];
		$department = Department::findOne(Yii::$app->getUser()->getIdentity()->department_id);
		$arrayBusinessMenu = explode ( ',', $department->businessmenu );
		$menu = Mainmenu::find()->where(['id'=>$arrayBusinessMenu])->all();
		return $menu;
	}

	public static function showFarmerinfopic($farms_id)
	{
		$cardid = Farms::find()->where(['id'=>$farms_id])->one()['cardid'];
		$farmerinfo = Farmerinfo::findOne($cardid);
		$html = '';
		$html .= '<table width="600px" class="table table-bordered table-hover">';
		$html .= '<tr>';
		$html .= '<td align="center"><img width="200" src=http://192.168.1.10/'.$farmerinfo['photo'].'></td>';
		$html .= '<td align="center"><img width="500" src=http://192.168.1.10/'.$farmerinfo['cardpic'].'></td>';
		$html .= '<td width="" align="center"><img width="500" src=http://192.168.1.10/'.$farmerinfo['cardpicback'].'></td>';
		$html .= '</tr>';
		$html .= '</table>';
		return $html;
	}

	public static function showFarminfo($farms_id)
	{
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		if(!empty($farm->zongdi)) {
			$zongdi = '宗地(' . count(explode('、', $farm->zongdi)) . '宗)：';
		} else
			$zongdi = '宗地：';
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
		$html .= '<td width="26" rowspan="4" align="center">'.Html::img(Farmerinfo::find()->where(['cardid'=>$farm->cardid])->one()['photo'],['height'=>'200px']).'</td>';
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
				if($i%5 == 0) {
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
		if($farm->notstateinfo == '' or $farm->notstateinfo == 0) {
            $html .= '<td width="23" align="left"></td>';
        } else {
            $html .= '<td width="23" align="right">'.Farms::getStateInfo($farm->notstateinfo).':</td>';
        }
		if($notstate == '' or $notstate == 0) {
            $html .= '<td width="23" align="left"></td>';
        } else {
            $html .= '<td width="23" align="left">'.$notstate.'</td>';
        }
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td align="right">农场状态:</td>';
		$html .= '<td align="left">';
		if($farm['locked']) {
			$html .= '<span class="text-danger"><strong>已冻结</strong></span>';
		} else {
			$html .= '<span class="text-success">未冻结</span>';
		}
		$html .='</td>';
		$html .= '<td align="right">冻结原因:</td>';
		$html .= '<td colspan="5" align="left" class="text-danger">'.Lockedinfo::find()->where(['farms_id'=>$farms_id])->one()['lockedcontent'].'</td>';

		$bank = BankAccount::find()->where(['cardid'=>$farm['cardid']])->one();
		$html .= '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td align="right">环境治理</td>';
		$html .= '<td align="center">';
		$html .= Environment::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one()['isgovernment']?"是":"否";
		$html .= '</td>';
		$html .= '<td align="right">银行账号'.Html::a('编辑','#',['id'=>'bankupdate','class'=>'btn btn-xs btn-success']).'</td>';
		$html .= '<td align="right">'.$bank['bank'].'</td>';
		$html .= '<td align="left" colspan="3"><span id="banktext">'.$bank['accountnumber'].'</span></td>';
		$html .= '<td align="right">信用分:</td>';
		$html .= '<td align="left">';
		if($farm->star) {
			for ($i = 0; $i < $farm->star; $i++) {
				$html .= '<i class="fa fa-star text-red"></i>';
			}
		}
		$html .= '</td>';
		$html .= '</tr>';
		$html .= '</table>';
		$html .= '<div id="bankDialog" title="修改法人银行账号">';
		$html .= '<table class="table">';
		$html .= '<tr>';
		$html .= '<td align="right">银行名称:</td>';
		$html .= '<td align="left">大兴安岭农村商业银行</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td align="right">银行账号:</td>';
		$html .= '<td align="left">'.Html::textInput('accountnumber',$bank['accountnumber'],['id'=>'bank-accountnumber','class'=>'form-control']).'</td>';
		$html .= '</tr>';
		$html .= '</table>';
		$html .= '</div>';
		$html .='<script>';
		$html .= '$("#bankupdate").click(function(){';
		$html .= '$("#bankDialog").dialog("open");';
		$html .= '$("#bank-accountnumber").focus();';
		$html .= '});';

		$html .= '$( "#bankDialog" ).dialog({';
        $html .= 'autoOpen: false,';
        $html .= 'width: 600,';
        $html .= 'modal: true,';
		$html .= 'buttons: [';
		$html .= '{';
		$html .= 'text: "确定",';
		$html .= 'click: function () {';
		$html .= 'var num = $("#bank-accountnumber").val();';
		$html .= '$.getJSON("index.php?r=bankaccount/setaccountnumber", {';
		$html .= '"farms_id": 1,';
		$html .= '"accountnumber": num';
		$html .= '}, function (data) {';
		$html .= 'if (data.state) {';
		$html .= '$("#banktext").text(data.number);';
		$html .= '$("#bankDialog").dialog( "close" );';
		$html .= '}';
		$html .= '});';
		$html .= '},';
		$html .= '},';
		$html .= '{';
		$html .= 'text: "取消",';
		$html .= 'click: function() {';
		$html .= '$("#bankDialog").dialog( "close" );';
		$html .= '}';
		$html .= '}';
		$html .= ']});';
		$html .= '</script>';

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
		$html .= '<table class="table table2-bordered table-hover">';
		$html .= '<tr>';
		$html .= '<td width="79" align="right" style="vertical-align: middle;">管理区：</td>';
		$html .= '<td width="90" align="left" style="vertical-align: middle;">'.ManagementArea::find()->where(['id'=>$farm->management_area])->one()['areaname'].'</td>';
		$html .= '<td width="88" align="right" style="vertical-align: middle;">农场名称：</td>';
		$html .= '<td width="60" align="left" style="vertical-align: middle;">'.$farm->farmname.'</td>';
		$html .= '<td width="71" align="right" style="vertical-align: middle;">法人：</td>';
		$html .= '<td width="94" align="left" style="vertical-align: middle;">'.$farm->farmername.'</td>';
		$html .= '<td width="58" align="right" style="vertical-align: middle;">身份证号：</td>';
		$html .= '<td width="23" align="left" style="vertical-align: middle;">'.$farm->cardid.'</td>';
		$html .= '<td width="26" rowspan="2" align="center" style="vertical-align: middle;">'.Html::img(Farmerinfo::find()->where(['cardid'=>$farm->cardid])->one()['photo'],['height'=>'130px']).'</td>';
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

	public static function showFarminfo_sm($farms_id)
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
		$html .= '<table class="table table2-bordered table-hover">';
		$html .= '<tr>';
		$html .= '<td width="79" align="right" style="vertical-align: middle;">管理区：</td>';
		$html .= '<td width="90" align="left" style="vertical-align: middle;">'.ManagementArea::find()->where(['id'=>$farm->management_area])->one()['areaname'].'</td>';
		$html .= '<td width="88" align="right" style="vertical-align: middle;">农场名称：</td>';
		$html .= '<td width="60" align="left" style="vertical-align: middle;">'.$farm->farmname.'</td>';
		$html .= '<td width="26" rowspan="4" align="center" style="vertical-align: middle;">'.Html::img(Farmerinfo::find()->where(['cardid'=>$farm->cardid])->one()['photo'],['height'=>'130px']).'</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td width="71" align="right" style="vertical-align: middle;">法人：</td>';
		$html .= '<td width="94" align="left" style="vertical-align: middle;">'.$farm->farmername.'</td>';
		$html .= '<td width="58" align="right" style="vertical-align: middle;">身份证号：</td>';
		$html .= '<td width="23" align="left" style="vertical-align: middle;">'.$farm->cardid.'</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td align="right" style="vertical-align: middle;">农场位置：</td>';
		$html .= '<td align="left" style="vertical-align: middle;">'.$farm->address.'</td>';
		$html .= '<td align="right" style="vertical-align: middle;">合同号：</td>';
		$html .= '<td width="80" align="left" style="vertical-align: middle;">'.$farm->contractnumber.'</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td align="right" style="vertical-align: middle;">面积：</td>';
		$html .= '<td align="left" style="vertical-align: middle;">'.$farm->contractarea.'亩'.'</td>';
		$html .= '<td width="80" align="right" style="vertical-align: middle;">电话：</td>';
		$html .= '<td width="23" align="left" style="vertical-align: middle;">'.$farm->telephone.'</td>';
		$html .= '</tr>';
		$html .= '</table>';
		return $html;
	}

	public static function businessIcon($state=false)
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
//		$html .= Html::checkbox('iszx',$state,['id'=>'Iszx','class'=>'']);
		
		return $html;
	}
	
	public static function notstateInfo($id = NULL)
	{
		$array = ['销户','正常','未更换合同','临时性管理','买断合同','其它','未明确状态','测量误差','政府征用地','树苗地','其它',-1=>'陈欠收缴',99=>'09年后未变更'];
		if(!empty($id)) {
			if (is_array($id)) {
				foreach ($id as $key => $val) {
					$result[$val] = $array[$val];
				}
				return $result;
			}
			if (is_string($id) or is_int($id))
				return $array[$id];
		} else {
			return false;
		}
	}

	public static function getStateInfo($id = NULL)
	{
//		var_dump($id);
		if($id === NULL) {
			return '';
		}
		$array = ['销户','正常','未更换合同','临时性管理','买断合同','其它','未明确状态','测量误差','政府征用地','树苗地','其它',-1=>'陈欠收缴',99=>'09年后未变更'];
//		var_dump($array);
// 		var_dump($id);exit;
		if(!empty($id)) {
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
		$newcontractnumber = self::getLastContractNubmer();
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
	public static function getContractstate($farms_id) {
		$farm = Farms::find ()->where ( [
			'id' => $farms_id
		] )->one ();
		$contractnumber = $farm->contractnumber;
		$array = explode ( '-', $contractnumber );

		return $array [1];
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

		$cacheKey = 'farms-search-all27'.\Yii::$app->getUser()->id;

		$result = Yii::$app->cache->get($cacheKey);
		if (!empty($result)) {
			return $result;
		}
		$url = 'index.php?r=farms/farmsmenu&farms_id=';
		if(User::getItemname('服务大厅')) {
			if(isset($_GET['huinong_id']))
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

	public static function getZongdi($farms_id)
	{
		$farm = Farms::findOne($farms_id);
		$zongdiArray = explode('、',$farm->zongdi);
		return ['zongdi'=>$zongdiArray,'num'=>count($zongdiArray)];
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
		$arrayFarmsid = [];
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
		echo '<td width="10%" align="center"><a href="' . Url::to ( 'index.php?r=' . Yii::$app->controller->id . '/' . yii::$app->controller->action->id . '&farms_id=' . $top ) . '"><font size="5">第一条<a></td>';
		echo '<td width="10%" align="center"><a href="' . Url::to ( 'index.php?r=' . Yii::$app->controller->id . '/' . yii::$app->controller->action->id . '&farms_id=' . $up ) . '"><font size="5">上一条</font><a></td>';
		echo '<td width="10%" align="center"><a href="' . Url::to ( 'index.php?r=' . Yii::$app->controller->id . '/' . yii::$app->controller->action->id . '&farms_id=' . $down ) . '"><font size="5">下一条</font><a></td>';
		echo '<td width="15%" align="center"><a href="' . Url::to ( 'index.php?r=' . Yii::$app->controller->id . '/' . yii::$app->controller->action->id . '&farms_id=' . $last ) . '"><font size="5">最后一条</font><a></td>';
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
	public static function getContractnumber($farms_id, $state = null,$number=null) {
		$farm = Farms::find ()->where ( [ 
				'id' => $farms_id 
		] )->one ();
		if(empty($number)) {
			$number = self::getLastContractNubmer($farms_id);
		}

		$contractnumberModel = Contractnumber::findOne(1);
		if ($state == 'add') {
			$cn1 = str_pad($number, 4, '0', STR_PAD_LEFT);
		} else
			$cn1 = str_pad($number, 4, '0', STR_PAD_LEFT);

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
		Numberlock::lock($cn1,$farms_id);
		if($state == 'number')
			return $cn1;
		$contractnumber = $cn1 . '-' . $cn2 . '-' . $cn3 . '-' . $cn4;
//		var_dump($contractnumber);exit;
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
	
	public static function getManagementArea($str = NULL)
	{
		if(Yii::$app->user->isGuest) {
			return false;
		}
		$dep_id = User::findByUsername(Yii::$app->user->identity->username)['department_id'];
		$departmentData = Department::find()->where([
			'id' => $dep_id
		])->one();
		$whereArray = explode(',', $departmentData ['membership']);
		$managementarea = ManagementArea::find()->where([
			'id' => $whereArray
		])->all();
		foreach ($managementarea as $value) {
			$result ['id'] [] = $value ['id'];
			if ($str == 'small')
				$result ['areaname'] [] = str_ireplace('管理区', '', $value ['areaname']);
			else
				$result ['areaname'] [] = $value ['areaname'];
		}
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

	
	public static function getFarmarea()
	{
		$areas = [];
//     	$sum = 0.0;
		$farmsID = [];
		$percent = [];
		$rows = [];
		$rowpercent = [];
		$i=0;
		$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
		$query = self::getAllCondition();
//		var_dump($query->where);
		$all = sprintf ( "%.2f", $query->sum ( 'contractarea' ) );
		foreach (self::getManagementArea()['id'] as $value) {

			$area = self::getTotalArea($value);
			$areas[] = (float)sprintf("%.2f", $area);
			if($area>0) {
				$percent[] = sprintf("%.2f", $area / $all * 100);
			} else {

			}
			$i++;
		}
		$allcount = $query->count ();
		foreach (self::getManagementArea()['id'] as $value) {
			$row = self::getTotalNum($value);
			$rows[] = (int)$row;
			$rowpercent[] = sprintf("%.2f", $row/$allcount*100);
		}
//     	var_dump($areas);
		//$allvalue = $all - $sum;

//     	if ($allvalue !== 0) {
//     		$data[] = ['name'=>'其他管理区','y'=>$allvalue];
//     	}
		$result = [[
			'name' => '面积',
			'type' => 'bar',
			'percent' => $percent,
			'data' => $areas,
			'itemStyle'=> [
				'normal'=> [
					'label' => [
						'show'=> true,
						'position'=> 'top',
// 			    					'formatter'=> '{c}万亩',
					]
				]
			]
		],
			[
				'name' => '数量',
				'type' => 'bar',
				'percent' => $rowpercent,
				'data' => $rows,
				'itemStyle'=> [
					'normal'=> [
						'label' => [
							'show'=> false,
							'position'=> 'top',
// 			    					'formatter'=> '{c}户',
						]
					]
				]
			]];

//     	var_dump($rows);exit;
		$jsonData = json_encode($result);
//     	Yii::$app->cache->set ( $cacheKey, $jsonData, 1 );
		return $jsonData;
	}
	public static function getUserManagementAreaname($userid)
	{
		$result = [];
		$dep_id = User::findIdentity($userid)['department_id'];
		$departmentData = Department::find ()->where ( [
			'id' => $dep_id
		] )->one ();
		$whereArray = explode ( ',', $departmentData ['membership'] );
		$managementarea = ManagementArea::find()->where(['id'=>$whereArray])->all();
		foreach ($managementarea as $value) {
			$result[] = str_ireplace('管理区', '', $value['areaname']);
		}
		//     	var_dump($userid)
		//     	var_dump($result);exit;
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
//		echo $times;exit;
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
		$query = self::getNumCondition();
//		var_dump($query->where);
		return $query->count () . '户';
	}
	
	public static function totalArea() {
		$query = self::getCondition();
		return sprintf ( "%.2f", $query->sum ( 'contractarea' ) ) . '亩';
	}

	public static function getTotalNum($management_area) {
		$query = self::getAllCondition();
		$query->andFilterWhere(['management_area'=>$management_area]);
		return $query->count () . '户';
	}

	public static function getTotalArea($management_area) {
		$query = self::getAllCondition();
		$query->andFilterWhere(['management_area'=>$management_area]);
		return sprintf ( "%.2f", $query->sum ( 'contractarea' ) ) . '亩';
	}

	private static function getPlate($controller, $menuUrl) {
// 		$cacheKey = 'cache-key-plate1'.\Yii::$app->getUser()->id;
// 		$value = Yii::$app->cache->get($cacheKey);
// 		if (!empty($value)) {
// 			return $value;
// 		}
		$query = self::getCondition();
		$where = self::getManagementArea ()['id'];
		$action = explode('/',$menuUrl['menuurl']);
		switch ($action[1]) {
			case 'farmsland' :
				$value['color'] = 'blue';
				$value ['icon'] = 'fa fa-delicious';
				$value ['title'] = $menuUrl ['menuname'];
//				if(Yii::$app->user->identity->realname == '杜镇宇') {
//					$value ['url'] = Url::to ( 'index.php?r=' . 'farms/farmscareful' );
//				} else {
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
//				}
				$value ['info'] = '共' . $query->count () . '户农场';
				$value ['description'] = '农场基础信息';
				break;
			case 'plantingstructureinfo' :
				$value['color'] = 'green';
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
			case 'yieldsinfo' :
				$value['color'] = 'orange';
				$value ['icon'] = 'fa fa-line-chart';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '产品信息';
				$value ['description'] = '农产品产量信息';
				break;
			case 'huinonggrantinfo' :
				$value['color'] = 'purple';
				$value ['icon'] = 'fa fa-dollar';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '现有' . Huinong::find ()->andWhere ( 'enddate>=' . Theyear::getYeartime ()[0] )->andWhere ( 'enddate<=' . Theyear::getYeartime ()[1] )->count () . '条惠农补贴信息';
				$value ['description'] = '补贴发放情况';
				break;
			case 'collectioninfo' :
				$value['color'] = 'rose';
				$value ['icon'] = 'fa fa-cny';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '完成' . Collection::getPercentage () . '%';
				$value ['description'] = '承包费收缴情况';
				break;
			case 'firepreventioninfo' :
				$value['color'] = 'red';
				$value ['icon'] = 'fa fa-fire-extinguisher';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '有' . Fireprevention::find ()->where ( [
						'management_area' => $where
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '户签订防火合同';
				$value ['description'] = '防火完成情况';
				break;
			case 'breedinfoinfo' :
				$value['color'] = 'rs';
				$value ['icon'] = 'fa fa-github-alt';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$employeerows = Breed::find ()->where ( [
					'management_area' => $where,
					'year' => User::getYear(),
				] )->count ();
				$value ['info'] = '共有' . $employeerows . '户养殖户';
				$value ['description'] = '养殖户基本信息';
				break;
			case 'disasterinfo' :
				$value['color'] = 'blue';
				$value ['icon'] = 'fa fa-soundcloud';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '有' . Disaster::find ()->where ( [
						'management_area' => $where
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '户受灾';
				$value ['description'] = '农户受灾情况';
				break;
			case 'projectapplicationinfo' :
				$value['color'] = 'xm';
				$value ['icon'] = 'fa fa-road';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '有' . Projectapplication::find ()->where ( [
						'management_area' => $where
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '条基础设施建设';
				$value ['description'] = '项目情况';
				break;
			case 'insuranceinfo' :
				$value['color'] = 'bx';
				$value ['icon'] = 'fa fa-file-text';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '已经办理' . Insurance::find ()->where ( [
						'management_area' => $where,
						'state' => 1
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '保险业务';
				$value ['description'] = '保险业务';
				break;
			case 'loaninfo' :
				$value['color'] = 'dk';
				$value ['icon'] = 'fa fa-bank';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
				$value ['info'] = '现有' . Loan::find ()->where ( [
						'management_area' => $where,
						'lock' => 1
					] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '贷款业务';
				$value ['description'] = '贷款业务';
				break;
			case 'photograph' :
				$value['color'] = 'blue';
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
//			case 'machineoffarm' :
//				$value['color'] = 'red';
//				$value ['icon'] = 'fa fa-truck';
//				$value ['title'] = $menuUrl ['menuname'];
//				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] );
//				$machines = Machineoffarm::find()->count();
////				if($machines)
//					$value ['info'] = '共有农机'.$machines.'台';
////				else
////					$value['info'] = '法人电子信息已经全部采集完成';
//				$value ['description'] = '农机器具';
//				break;
			default :
				$value = false;
		}
// 		Yii::$app->cache->set($cacheKey, $value, 0);
//		var_dump($value);
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
		if($divInfo) {
			switch (Yii::$app->user->identity->template) {
				case 'default':
					$html = '<div class="col-md-1-5" style="text-align:center;">';
					$html .= '<a href=' . $divInfo ['url'] . '>';
					$html .= '<div class="info-box bg-blue" data-header-animation="true">';
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
					break;
				case 'template2018':
					$html = '<div class="col-md-1-5" style="text-align:center;" >';
					$html .= '<a href=' . $divInfo ['url'] . '>';
					$html .= '<div class="card card-stats" >';
					$html .= '<div class="card-header" data-background-color="' . $divInfo['color'] . '" data-header-animation="true">';
					$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
					$html .= '</div>';
					$html .= '<div class="card-content">';
					$html .= '<p class="category"><strong>' . $divInfo ['description'] . '</strong></p>';
					$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
					$html .= '</div>';
					$html .= '<div class="card-footer text-right">';
					$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
					$html .= '<a href="#pablo"><strong>' . $divInfo ['info'] . '</strong></a>';
					$html .= '</div>';
					$html .= '</div>';
					$html .= '</div>';
					$html .= '<!-- /.info-box --></a>';
					$html .= '</div>';
					break;
			}
			return $html;
		}
	}
//	private static function showEightPlant($menuUrl) {
//		$str = explode ( '/', $menuUrl ['menuurl'] );
//		$divInfo = self::getPlate ( $str [0], $menuUrl );
//		$html = '<div class="col-md-1-5" style="text-align:center;">';
//		$html .= '<a href=' . $divInfo ['url'] . '>';
//		$html .= '<div class="card card-stats">';
//        $html .= '<div class="card-header" data-background-color="blue">';
//        $html .= '<i class="' . $divInfo ['icon'] . '"></i>';
//        $html .= '</div>';
//        $html .= '<div class="card-content">';
//        $html .= '<p class="category">' . $divInfo ['description'] . '</p>';
//        $html .= '<h3 class="card-title">' . $divInfo ['title'] . '</h3>';
//        $html .= '</div>';
//		$html .= '<div class="card-footer text-right">';
//		$html .= '<div class="stats">';
////		$html .= '<i class="material-icons text-danger">warning</i>';
//		$html .= '<a href="#pablo">'.$divInfo ['info'].'</a>';
//		$html .= '</div>';
//		$html .= '</div>';
//		$html .= '</div>';
//		$html .= '<!-- /.info-box --></a>';
//		$html .= '</div>';
//		return $html;
//	}
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
	public static function getFarmerrows($params=null)
	{
		$farm = Farms::find ();
		$farm->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
		if($params['farmsSearch']['management_area'])
			$management_area = $params['farmsSearch']['management_area'];
		else
			$management_area = self::getManagementArea ()['id'];
		if (empty ( $params )) {
			$farm->andWhere(['management_area' => self::getManagementArea ()['id']]);
			$farm->andWhere(['state' => [1,2,3,4,5]]);
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

	public static function getManagementAreaFarmerrows($management_area)
	{
		$farm = Farms::find ()->where(['management_area'=>$management_area,'state'=>[1,2,3,4,5]]);

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
//		var_dump($zongdiArray);
		foreach ($zongdiArray as $zongdi => $area) {
			$sum = 0.0;
//			if(count($area) > 1) {
				foreach ($area as $val) {
					$sum = floatval(bcadd($sum,$val,2));
				}
//
//			} else {
//				$sum = $area[0];
//			}
			$result[] = $zongdi.'('.$sum.')';
		}
		var_dump($result);
		if($result)
			return implode('、',$result);
		else
			return '';
	}

	public static function zongdiBJ($zongdistr,$zongdi)
	{
		$zongdiArray = explode('、',$zongdistr);
//		var_dump($zongdi);
		foreach ($zongdiArray as $val) {
			if(Lease::getZongdi($val) == Lease::getZongdi($zongdi)) {
				return true;
			}
		}
		return false;
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

	public static function getLastContractNubmer($farms_id=null)
	{
		if(!empty($farms_id)) {
			$locknumber = Numberlock::getLockNumber($farms_id);
			if($locknumber) {
				return $locknumber;
			}
		}
		$number = [];
		$lastNumber = Contractnumber::find()->where(['id'=>1])->one()['contractnumber'];
		$lock = Numberlock::find()->all();
		$narray = [];

		for($i=1584;$i<=$lastNumber;$i++)
		{
			$narray[] = $i;
		}
		$farms = Farms::find()->where(['state'=>1])->all();
		foreach ($farms as $farm) {
			$n = explode('-',$farm['contractnumber']);
			if($n > $i)
				$number[] = $n[0];
		}
		foreach ($lock as $value) {
			$number[] = $value['number'];
		}
		$result = array_diff($narray,$number);
//		var_dump($result);exit;
		if($result) {
			$min = min($result);
			Numberlock::lock($min,$farms_id);
			return $min;
		} else {
			if(in_array($lastNumber,$number)) {
				return Contractnumber::contractnumberAdd();
			}
			return $lastNumber;
		}
	}

	//判断农场信息是否完整
	public static function isFarmsInfo($farms_id)
	{
		$model = Farms::findOne($farms_id);
	
		if($model->cardid == '' or $model->address == '' or $model->longitude == '' or $model->latitude == '')
			return 1;
		else
			return 0;
	}

	//用php从身份证中提取生日,包括15位和18位身份证
	public static function getIDCardInfo($IDCard){
		$result['error']=0;//0：未知错误，1：身份证格式错误，2：无错误
		$result['flag']='';//0标示成年，1标示未成年
		$result['tdate']='';//生日，格式如：2012-11-15
		if(!preg_match("/^[1-9]([0-9a-zA-Z]{17}|[0-9a-zA-Z]{14})$/i",$IDCard)){
			$result['error']=1;
			return $result;
		}else{
			if(strlen($IDCard)==18){
				$tyear=intval(substr($IDCard,6,4));
				$tmonth=intval(substr($IDCard,10,2));
				$tday=intval(substr($IDCard,12,2));
				if($tyear>date("Y")||$tyear<(date("Y")-100)){
					$flag=0;
				}elseif($tmonth<0||$tmonth>12){
					$flag=0;
				}elseif($tday<0||$tday>31){
					$flag=0;
				}else{
					$tdate=$tyear."-".$tmonth."-".$tday." 00:00:00";
					if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){
						$flag=0;
					}else{
						$flag=1;
					}
				}
			}elseif(strlen($IDCard)==15){
				$tyear=intval("19".substr($IDCard,6,2));
				$tmonth=intval(substr($IDCard,8,2));
				$tday=intval(substr($IDCard,10,2));
				if($tyear>date("Y")||$tyear<(date("Y")-100)){
					$flag=0;
				}elseif($tmonth<0||$tmonth>12){
					$flag=0;
				}elseif($tday<0||$tday>31){
					$flag=0;
				}else{
					$tdate=$tyear."-".$tmonth."-".$tday." 00:00:00";
					if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){
						$flag=0;
					}else{
						$flag=1;
					}
				}
			}
		}
		$result['error']=2;//0：未知错误，1：身份证格式错误，2：无错误
		$result['isAdult']=$flag;//0标示成年，1标示未成年
		$result['birthday']=$tdate;//生日日期
		return $result;
	}

	public static function getAge($cardid){
		$birthday = self::getIDCardInfo($cardid)['birthday'];
		$age = strtotime($birthday);
		if($age === false){
			return false;
		}
		list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age));
		$now = strtotime("now");
		list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now));
		$age = $y2 - $y1;
		if((int)($m2.$d2) < (int)($m1.$d1))
			$age -= 1;
		return $age;
	}

	public static function setContractNumberArea($contractnumber,$area)
	{
		$array = explode('-',$contractnumber);
		$array[2] = $area;
		$result = implode('-',$array);
		return $result;
	}

	public static function getAllCondition($action=null)
	{
		$query = Farms::find()->orderBy('farmerpinyin asc');
//		$whereArray = self::getManagementArea ()['id'];
		if(date('Y') == User::getYear()) {
			if(Yii::$app->user->identity->realname == '杜镇宇') {
				if($action == 'collection') {
					$query->andFilterWhere(['between', 'state', 1, 3]);
				} else {
					$query->andFilterWhere(['between', 'state', 0, 5]);
				}

			} else {
				if($action == 'collection') {
					$query->andFilterWhere(['between', 'state', 1, 3]);
				} else {
					$query->andFilterWhere(['between', 'state', 1, 5]);
				}
			}

			//			$this->ids = $this->getIDs($query->all());
		} else {
			//创建日期在所选年度里
			//		var_dump(date('Y-m-d',Theyear::getYeartime()[0]));

			if($action == 'collection') {
				$query->where([
					'and',
					['between', 'state', 1, 3],
					['between', 'create_at', strtotime('2015-01-01 00:00:01'), Theyear::getYeartime()[1]],
				])->orWhere([
					'and',
					['otherstate' => [6,7]],
					['nowyearstate' => -1],
					//				['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]],
					['between', 'update_at', Theyear::getYeartime()[0], time()],
				]);
			} else {

				$query->where([
					'and',
					['between', 'state', 1, 5],
					['between', 'create_at', strtotime('2015-01-01 00:00:01'), Theyear::getYeartime()[1]],
				])->orWhere([
					'and',
					['otherstate' => [6,7]],
					['nowyearstate' => -1],
					//				['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]],
					['between', 'update_at', Theyear::getYeartime()[0], time()],
				]);
			}
//            $query->where([
//                'and',
//                ['between', 'state', 1, 5],
//                ['between','create_at',strtotime('2015-01-01 00:00:01'),Theyear::getYeartime()[1]],
//            ]);
		}

		return $query;

	}

	public static function getCondition($action=null)
	{
		$whereArray = self::getManagementArea ()['id'];
		$query = Farms::find()->orderBy('farmerpinyin asc');
		if(date('Y') == User::getYear()) {
			if(Yii::$app->user->identity->realname == '杜镇宇') {
				if($action == 'collection') {
					$query->andFilterWhere(['management_area'=>$whereArray])->andFilterWhere(['between', 'state', 1, 3]);
				} else {
//					var_dump('333');exit;
					$query->andFilterWhere(['management_area'=>$whereArray])->andFilterWhere(['between', 'state', 0, 5]);
				}
			} else {
				if($action == 'collection') {
					$query->andFilterWhere(['management_area' => $whereArray])->andFilterWhere(['between', 'state', 1, 3]);
				} else {
					$query->andFilterWhere(['management_area' => $whereArray])->andFilterWhere(['between', 'state', 1, 5]);
				}
			}

			//			$this->ids = $this->getIDs($query->all());
		} else {
			//创建日期在所选年度里
			//		var_dump(date('Y-m-d',Theyear::getYeartime()[0]));
			if($action == 'collection') {
				$query->where([
					'and',
					['management_area' => $whereArray],
					['between', 'state', 1, 3],
					['between', 'create_at', strtotime('2015-01-01 00:00:01'), Theyear::getYeartime()[1]],
				])->orWhere([
					'and',
					['management_area' => $whereArray],
					['otherstate' => [6,7]],
					['nowyearstate' => 1],
					//				['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]],
					['between', 'update_at', Theyear::getYeartime()[0], time()],
				]);
			} else {
				$query->where([
					'and',
					['management_area' => $whereArray],
					['between', 'state', 1, 5],
					['between', 'create_at', strtotime('2015-01-01 00:00:01'), Theyear::getYeartime()[1]],
				]);
//					->orWhere([
//					'and',
//					['management_area' => $whereArray],
////					['state'=>0],
//					['otherstate' => [6,7]],
//					['nowyearstate' => -1],
//					//				['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]],
//					['between', 'update_at', Theyear::getYeartime()[0], time()],
//				]);
			}
//            $query->where([
//                'and',
//                ['between', 'state', 1, 5],
//                ['between','create_at',strtotime('2015-01-01 00:00:01'),Theyear::getYeartime()[1]],
//            ]);
		}

		return $query;

	}

	public static function getNumCondition($action=null)
	{
		$whereArray = self::getManagementArea ()['id'];
		$query = Farms::find()->orderBy('farmerpinyin asc');
		if(date('Y') == User::getYear()) {
			if(Yii::$app->user->identity->realname == '杜镇宇') {
				if($action == 'collection') {
					$query->andFilterWhere(['management_area'=>$whereArray])->andFilterWhere(['between', 'state', 1, 3]);
				} else {
//					var_dump('333');exit;
					$query->andFilterWhere(['management_area'=>$whereArray])->andFilterWhere(['between', 'state', 0, 5]);
				}
			} else {
				if($action == 'collection') {
					$query->andFilterWhere(['management_area' => $whereArray])->andFilterWhere(['between', 'state', 1, 3]);
				} else {
					$query->andFilterWhere(['management_area' => $whereArray])->andFilterWhere(['between', 'state', 1, 5]);
				}
			}

			//			$this->ids = $this->getIDs($query->all());
		} else {
			//创建日期在所选年度里
			//		var_dump(date('Y-m-d',Theyear::getYeartime()[0]));
			if($action == 'collection') {
				$query->where([
					'and',
					['management_area' => $whereArray],
					['between', 'state', 1, 3],
					['between', 'create_at', strtotime('2015-01-01 00:00:01'), Theyear::getYeartime()[1]],
				])->orWhere([
					'and',
					['management_area' => $whereArray],
					['otherstate' => [6,7]],
					['nowyearstate' => 1],
					//				['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]],
					['between', 'update_at', Theyear::getYeartime()[0], time()],
				]);
			} else {
				$query->where([
					'and',
					['management_area' => $whereArray],
					['between', 'state', 1, 5],
					['between', 'create_at', strtotime('2015-01-01 00:00:01'), Theyear::getYeartime()[1]],
				])->orWhere([
					'and',
					['management_area' => $whereArray],
//					['state'=>0],
					['otherstate' => [6,7]],
					['nowyearstate' => -1],
					//				['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]],
					['between', 'update_at', Theyear::getYeartime()[0], time()],
				]);
			}
//            $query->where([
//                'and',
//                ['between', 'state', 1, 5],
//                ['between','create_at',strtotime('2015-01-01 00:00:01'),Theyear::getYeartime()[1]],
//            ]);
		}

		return $query;

	}

	public static function getUserCondition($userid,$table=null)
	{
		$query = Farms::find()->orderBy('farmerpinyin asc');
		if(date('Y') == User::getYear($userid)) {
			if($table == 'collection') {
				$query->andFilterWhere(['between', 'state', 1, 3]);
			} else {
				$query->andFilterWhere(['between', 'state', 1, 5]);
			}
		} else {
			//创建日期在所选年度里

			$query->where([
				'and',
				['between', 'state', 1, 5],
				['between','create_at',strtotime('2015-01-01 00:00:01'),Theyear::getYeartime($userid)[1]],
			]);
		}
		return $query;

	}

	//查询09年之后未变更的农场,返回ID值
	public static function getOldfarms()
	{
		$data = [];
		$farms = Farms::find()->where(['state'=>[1,2,3,4,5],'management_area'=>Farms::getManagementArea()['id']])->all();
		foreach ($farms as $farm) {
//			var_dump(date('Y',$farm['create_at']));
			if(date('Y',$farm['create_at']) == '2015') {
//				$data[] = $farm['id'];
				if($farm['measure'] > 0) {
					$zongdis = explode('、',$farm['zongdi']);
					if (!empty($zongdis)) {
						$zongdis = explode('、', $farm['zongdi']);
						$zongdiRow = count($zongdis);
						$i = 0;
						foreach ($zongdis as $zongdi) {
//							var_dump(Lease::getZongdiToNumber($zongdi));
							$temporarynumber = Parcel::find()->where(['unifiedserialnumber' => Lease::getZongdiToNumber($zongdi)])->one()['temporarynumber'];
//							var_dump($temporarynumber);
							$py = explode('-', $temporarynumber);
//							var_dump($py[0]);
//							var_dump($farm['farmerpinyin']);
							if ($py[0] == $farm['farmerpinyin']) {
								$i++;
							}
						}
						if($zongdiRow == $i) {
							$data[] = $farm['id'];
						}
					}
				}
			}
		}
//		$farms2 = Farms::find()->where(['management_area'=>Farms::getManagementArea()['id']])->all();
//		foreach ($farms2 as $farm) {
//
//		}
		if(empty($data)) {
			return 0;
		}
		return $data;
	}
}
