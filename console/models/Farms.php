<?php

namespace console\models;

use Yii;
use yii\base\Application;
use yii\helpers\Json;
use console\models\ManagementArea;
use console\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
use PhpOffice\PhpWord\Writer\Word2007\Part\Theme;
use console\models\Theyear;

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
class Farms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%farms}}';
    }

    /**
     * @inheritdoc
     */
public function rules() {
		return [ 
				[ 
						[ 
								'farmname',
								'farmername' 
						],
						'required' 
				],
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
								'locked' 
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
								'notstateinfo',
								'accountnumber',
						],
						'string',
						'max' => 500 
				],
				[ 
						[ 
								'measure',
								'spyear' 
						],
						'safe' 
				] 
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
    
    
    
    public function getfarmer()
    {
    	return $this->hasOne(Farmer::className(), ['farms_id' => 'id']);
    }
    
    /**
     * 修改农场保存之后
     */
    public function afterSave($insert, $changedAttributes)
    {
        // 调用父级afterSave
        parent::afterSave($insert, $changedAttributes);

        // 删除指定缓存
        $cacheKeyList = [
            'farms-search-all'
        ];
        foreach ($cacheKeyList as $cacheName) {
            Yii::$app->cache->delete($cacheName);
        }
    }
	//获取冻结信息
    public static function getLocked ($farms_id)
    {
    	return self::findOne($farms_id)['locked'];
    }
    
    //解冻
    public static function unLocked($farms_id)
    {
    	$unlockDate = Loan::find()->where(['farms_id'=>$farms_id])->one()['enddate'];
    	if($unlockDate) {
	    	$model = self::findOne($farms_id);
	    	if(strtotime(date('Y-m-d')) > strtotime($unlockDate)) {
		    	$model->locked = 0;
		    	$model->save();
	    	} else {
	    		$model->locked = 1;
	    		$model->save();
	    	}  		
    	}
    } 
    /**
     * 搜索所有农场信息
     * @author wubaiqing <wubaiqing@vip.qq.com>
     */
    public static function searchAll()
    {
        $cacheKey = 'farms-search-all2';

        $result = Yii::$app->cache->get($cacheKey);
        if (!empty($result)) {
            return $result;
        }
        $departmentid = User::find()->where(['id'=>Yii::$app->getUser()->id])->one()['department_id'];
        $keshi = Department::find()->where(['id'=>$departmentid])->one();
        switch ($keshi['departmentname'])
        {
        	case '财务科';
        		$url = 'index.php?r=collection/collectionindex&farms_id=';
        		break;
        	default:
        		$url = 'index.php?r=farms/farmsmenu&farms_id=';
        }
        
        // 所有农场
        $data = [];
        $where = explode(',', $keshi['membership']);
        $result = Farms::find()->where(['management_area'=>$where])->all();
        foreach ($result as $farm) {
          $data[] = [
            'value' => $farm['pinyin'], // 拼音
            'data' => $farm['farmname'], // 下拉框显示的名称
            'url' => Url::to($url.$farm['id']), 
          ];
          $data[] = [
          		'value' => $farm['farmerpinyin'],
          		'data' => $farm['farmername'],
          		'url' => Url::to($url.$farm['id']),
          			
          ];
        }
        $jsonData = Json::encode($data);
        Yii::$app->cache->set($cacheKey, $jsonData, 3600);
        
        return $jsonData;
    }

    public static function getFarmArray($management_area = NULL)
    {
    	if(empty($management_area)) {
	    	$departmentid = User::find()->where(['id'=>\Yii::$app->getUser()->id])->one()['department_id'];
	    	$strdepartment = Department::find()->where(['id'=>$departmentid])->one()['membership'];
    	} else 
    		$strdepartment = $management_area;
    	$farms = self::find()->where(['management_area'=>$strdepartment])->all();
    	 
    	foreach ($farms as $key=>$value) {
    		$arrayFarmsid[] = $value['id'];
    	}
    	return $arrayFarmsid;
    }
    
    public static function showRow($farms_id)
    {
    	$arrayFarmsid = self::getFarmArray();
    	$top = $arrayFarmsid[0];
    	$last = $arrayFarmsid[count($arrayFarmsid)-1];
    	$up = 0;
    	$down = 0;
    	for($i=0;$i<count($arrayFarmsid);$i++) {

    		if($farms_id == $arrayFarmsid[$i]) {
    			$nownum = $i+1;
    			$farmsid = $arrayFarmsid[$i];
    			if($i !== 0)
    				$up = $arrayFarmsid[$i-1];
    			if($i !== count($arrayFarmsid)-1)
    				$down = $arrayFarmsid[$i+1];
    		}
    	}
		$action = Yii::$app->controller->id.'/'.yii::$app->controller->action->id;
		//echo $action;
    	echo '<table class="table table-bordered table-hover">';
    	echo '<tr>';
    	echo '<td width="10%" align="center"><a href='.Url::to('index.php?r='.Yii::$app->controller->id.'/'.yii::$app->controller->action->id.'&farms_id='.$top).'><font size="5">第一条<a></td>';
    	echo '<td width="10%" align="center"><a href='.Url::to('index.php?r='.Yii::$app->controller->id.'/'.yii::$app->controller->action->id.'&farms_id='.$up).'><font size="5">上一条</font><a></td>';
    	echo '<td width="10%" align="center"><a href='.Url::to('index.php?r='.Yii::$app->controller->id.'/'.yii::$app->controller->action->id.'&farms_id='.$down).'><font size="5">下一条</font><a></td>';
    	echo '<td width="15%" align="center"><a href='.Url::to('index.php?r='.Yii::$app->controller->id.'/'.yii::$app->controller->action->id.'&farms_id='.$last).'><font size="5">最后一条</font><a></td>';
    	echo '<td width="10%">'.html::textInput('jump',$nownum,['class'=>'form-control','id'=>'rowjump']).'</td>';
    	echo '<td>'.html::button('跳转',['class' => 'btn btn-success','onclick'=>'jumpurl("'.$action.'")']).'</td>';
    	echo '</tr>';
    	echo '</table>';
    	echo html::hiddenInput('famsid','',['id'=>'setFarmsid']);
    }
    
    public static function getContractnumber($farms_id,$state=null)
    {
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
    	$contractnumber = Contractnumber::findOne(1);
    	if($state == 'add')
    		$cn1 = str_pad($contractnumber->contractnumber+1,4,'0',STR_PAD_LEFT);
    	else
    		$cn1 = str_pad($contractnumber->contractnumber,4,'0',STR_PAD_LEFT);
    	
    	if(date('Y') <= $contractnumber->lifeyear)
    		$cn2 = substr('2010',2);
    	else 
    		$cn2 = substr($contractnumber->lifeyear, 2);
    	if($state == 'new')
    		$cn3 = 0;
    	else
    		$cn3 = $farm->contractarea;
    	
//     		$cn3 = substr($cn3,0,strlen($cn3)-1); 
    	$cn4 = $farm->management_area;
    	$contractnumber = $cn1.'-'.$cn2.'-'.$cn3.'-'.$cn4;
    	return $contractnumber;
    }
    
    public static function getNowContractnumberArea($farms_id,$state=null)
    {
//     	var_dump($farms_id);exit;
    	$farm = Farms::find()->where(['id'=>$farms_id,'state'=>1])->one();
    	return $farm['contractarea'];
    }
    
    public static function getManagementArea($str = NULL)
    {
    	$dep_id = User::findByUsername ( yii::$app->user->identity->username )['department_id'];
    	$departmentData = Department::find ()->where ( [
    			'id' => $dep_id
    	] )->one ();
    	$whereArray = explode ( ',', $departmentData ['membership'] );
    	$managementarea = ManagementArea::find()->where(['id'=>$whereArray])->all();
    	foreach ($managementarea as $value) {
    		$result['id'][] = $value['id'];
    		if($str == 'small')
    			$result['areaname'][] = str_ireplace('管理区', '', $value['areaname']);
    		else 
    			$result['areaname'][] = $value['areaname'];
    	}
    	return $result;
    }
    public static function getUserManagementArea($userid) 
    {
    	$result = [];
    	$dep_id = User::findIdentity($userid)['department_id'];
//     	var_dump($userid);
//     	var_dump($dep_id);
    	$departmentData = Department::find ()->where ( [
    			'id' => $dep_id
    	] )->one ();
    	$whereArray = explode ( ',', $departmentData ['membership'] );
    	$managementarea = ManagementArea::find()->where(['id'=>$whereArray])->all();
    	foreach ($managementarea as $value) {
    		$result[] = $value['id'];
    	}
//     	var_dump($userid)
//     	var_dump($result);
    	return $result;
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
    public static function getManagementAreaAllID($userid)
    {
    	$allid = [];
    	$management_ids = self::getUserManagementArea($userid);
    	foreach ($management_ids as $value) {
    		$farms = Farms::find()->where(['management_area'=>$value])->all();
    		foreach ($farms as $val) {
    			$allid[] = $val['id']; 
    		}
    	}
    	return $allid;
    }
    public static function getFarmrows() {
    	$cacheKey = 'farms-hcharts';
    	$result = Yii::$app->cache->get ( $cacheKey );
    	if (! empty ( $result )) {
    		return $result;
    	}
    	$rows = [];
    	$sum = 0;
    	$farmsID = [];
    	$i=0;
    	$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
    	$all = Farms::find ()->count ();
    	foreach (self::getManagementArea()['id'] as $value) {
    		$row = ( float ) Farms::find ()->where ( [
    				'management_area' => $value
    		] )->count ();
    		
    		$rows[] = ['color'=>$color[$i],'y'=>$row];
    		$sum += $row;
    		$percent[] = sprintf("%.2f", $row/$all*100);
    		$i++;
    	}

    	$result = [[
    			'type' => 'column',
    			'name' => '数量',
    			'percent' => $percent,
    			'data' => $rows,
    			'dataLabels'=> [
    				'enabled'=> true,
    				'rotation'=> 0,
    				'color'=> '#FFFFFF',
    				'align'=> 'center',
    				'x'=> 0,
    				'y'=> 0,
    				'style'=> [
    					'fontSize'=> '13px',
    					'fontFamily'=> 'Verdana, sans-serif',
    					'textShadow'=> '0 0 3px black'
    				]
    			],
				'tooltip' => [
					'shared' => true,
					'formatter' => ''
				]
    	]];
        
    	$jsonData = json_encode(['result'=>$result,'all'=>$all]);
    	Yii::$app->cache->set ( $cacheKey, $jsonData, 1 );
    
    	return $jsonData;
    }
    public static function getFarmarea($id) {
//     	$cacheKey = 'farms-hcharts2';
//     	$result = Yii::$app->cache->get ( $cacheKey );
//     	if (! empty ( $result )) {
//     		return $result;
//     	}
    	$areas = [];
//     	$sum = 0.0;
    	$farmsID = [];
    	$percent = [];
    	$rows = [];
    	$rowpercent = [];
    	$i=0;
    	$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
    	$all = Farms::find ()->sum ('contractarea');
    	foreach (self::getUserManagementArea($id) as $value) {
			if(date('Y') == User::getYear($id)) {
				$area = ( float ) Farms::find ()->where ( [
						'management_area' => $value,
// 											'state'=>1,
				] )->andFilterWhere(['between', 'state', 1,5])->sum ( 'contractarea' );
				
				$areas[] = (float)sprintf("%.2f", $area);
				$percent[] = sprintf("%.2f", $area/$all*100);
				$i++;
			} else {
			    $query = self::getLandCondition($id);
				$area = $query->andFilterWhere(['management_area' => $value])->sum ( 'contractarea' );
				
	    		$areas[] = (float)sprintf("%.2f", $area);
	    		$percent[] = sprintf("%.2f", $area/$all*100);
	    		$i++;
			}
    	}
    	if(date('Y') == User::getYear($id)) {
    		$all = Farms::find ()->where ( [
    							'state'=>[1,2,3,4,5],
    		] )->count ();
    	} else {
    		$query = self::getLandCondition($id);
    		$query->andFilterWhere(['management_area' => $value])->count();
    	}
    	foreach (self::getUserManagementArea($id) as $value) {
    		if(date('Y') == User::getYear($id)) {
    			$row = ( float ) Farms::find ()->where ( [
    					'management_area' => $value,
//     					    				'state' => 1,
    			] )->andFilterWhere(['between', 'state', 1,5])->count ();
    			
    			$rows[] = $row;
    			$rowpercent[] = sprintf("%.2f", $row/$all*100);
    		} else {
    		    $query = self::getLandCondition($id);
	    		$row = ( float ) $query->andFilterWhere( [
	    				'management_area' => $value,
	//     				'state' => 1,
	    		] )->count ();
	    	
	    		$rows[] = $row;
	    		$rowpercent[] = sprintf("%.2f", $row/$all*100);
    		}
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

//     	var_dump($result);
    	$jsonData = json_encode($result);
//     	Yii::$app->cache->set ( $cacheKey, $jsonData, 1 );
    	return $jsonData;
    }
    public static function getLandCondition($userid)
    {
        $query = Farms::find()->orderBy('farmerpinyin asc');
        if(date('Y') == User::getYear($userid)) {

            $query->andFilterWhere(['between', 'state', 1, 5]);
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
    public static function totalNum($userid)
    {
    	$whereArray = self::getUserManagementArea($userid);
    	if(date('Y') == User::getYear($userid)) {
    		return Farms::find()->where(['management_area'=>$whereArray])->andFilterWhere(['between', 'state', 1,5])->count().'户';
    	} else {
    	    $query = self::getLandCondition($userid);
            return $query->count().'户';
        }

    }
    public static function totalArea($userid)
    {
    	$whereArray = self::getUserManagementArea($userid);
//     	var_dump($whereArray);
    	if(date('Y') == User::getYear($userid)) {
    		$area = Farms::find()->where(['management_area'=>$whereArray])->andFilterWhere(['between', 'state', 1,5])->sum('contractarea');
    	} else {
            $query = self::getLandCondition($userid);
            $area = $query->sum('contractarea');
        }
//    		$farms = Farms::find()->where(['management_area'=>$whereArray])->andFilterWhere(['between','create_at',Theyear::getYeartime($userid)[0],Theyear::getYeartime($userid)[1]])->all();
//    	$measue = 0.0;
//    	$notclear = 0.0;
//    	foreach ($farms as $farm) {
//    		$measue += $farm['contractarea'];
//    		if($farm['contractarea'] < self::getNowContractnumberArea($farm['id']))
//    			$notclear += $farm['notclear'];
//    	}
//    	$area = $measue + $notclear;
    	return sprintf("%.2f",$area).'亩';
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
}
