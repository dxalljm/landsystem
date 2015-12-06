<?php

namespace app\models;

use Yii;
use yii\helpers\Json;
use app\models\ManagementArea;
use yii\helpers\Url;
use yii\helpers\Html;
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
    public function rules()
    {
        return [
            [['farmname', 'farmername'], 'required'],
            [['measure','notclear'], 'number'],
            [['zongdi'], 'string'],
            [['management_area','state','oldfarms_id','locked'], 'integer'],
            [['farmname', 'farmername', 'cardid', 'telephone', 'address', 'cooperative_id', 'surveydate', 'groundsign', 'farmersign', 'pinyin','farmerpinyin','contractnumber', 'begindate', 'enddate','latitude','longitude'], 'string', 'max' => 500],
        	[['measure','spyear'],'safe'],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
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
            'surveydate' => '合同更换日期',
            'groundsign' => '地产科签字',
            'farmersign' => '农场法人签字',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'pinyin' => '农场名称拼音首字母',
        	'farmerpinyin' => '法人姓名简单首字母',
        	'state' => '状态',
        	'notclear' => '未明确地块',
        	'contractnumber' => '合同号',
        	'begindate' => '开始日期',
        	'enddate' => '结束日期',
        	'oldfarms_id' => '变更前ID',
        	'latitude' => '纬度',
        	'longitude' => '经度',
        	'locked' => '锁定',
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
    	$model = self::findOne($farms_id);
    	if(strtotime(date('Y-m-d')) > strtotime($unlockDate)) {
	    	$model->locked = 0;
	    	$model->save();
    	} else {
    		$model->locked = 1;
    		$model->save();
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

    public static function getFarmArray()
    {
    	$departmentid = User::find()->where(['id'=>\Yii::$app->getUser()->id])->one()['department_id'];
    	$strdepartment = Department::find()->where(['id'=>$departmentid])->one()['membership'];
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
    		$cn3 = $farm->measure;
    	$cn4 = $farm->management_area;
    	$contractnumber = $cn1.'-'.$cn2.'-'.$cn3.'-'.$cn4;
    	return $contractnumber;
    }
    public static function getManagementArea()
    {
    	$dep_id = User::findByUsername ( yii::$app->user->identity->username )['department_id'];
    	$departmentData = Department::find ()->where ( [
    			'id' => $dep_id
    	] )->one ();
    	$whereArray = explode ( ',', $departmentData ['membership'] );
    	$managementarea = ManagementArea::find()->where(['id'=>$whereArray])->all();
    	foreach ($managementarea as $value) {
    		$result['id'][] = $value['id'];
    		$result['areaname'][] = $value['areaname'];
    	}
    	return $result;
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
    	
    	$all = Farms::find ()->count ();
    	foreach (self::getManagementArea()['id'] as $value) {
    		$row = ( float ) Farms::find ()->where ( [
    				'management_area' => $value
    		] )->count ();
    		$rows[] = $row;
    		$sum += $row;
    		$percent[] = bcdiv($row,$all)*100;
    	}

    	$result = [[
    			'type' => 'column',
    			'name' => '数量',
    			'percent' => $all,
    			'data' => $rows,
    			'dataLabels'=> [
    				'enabled'=> true,
    				'rotation'=> -90,
    				'color'=> '#FFFFFF',
    				'align'=> 'right',
    				'x'=> 0,
    				'y'=> 0,
    				'style'=> [
    					'fontSize'=> '13px',
    					'fontFamily'=> 'Verdana, sans-serif',
    					'textShadow'=> '0 0 3px black'
    				]
    			]
    	]];
        
    	$jsonData = json_encode(['result'=>$result,'all'=>$all]);
    	Yii::$app->cache->set ( $cacheKey, $jsonData, 1 );
    
    	return $jsonData;
    }
    public static function getFarmarea() {
    	$cacheKey = 'farms-hcharts2';
    	$result = Yii::$app->cache->get ( $cacheKey );
    	if (! empty ( $result )) {
    		return $result;
    	}
    	$rows = [];
    	$sum = 0;
    	$farmsID = [];
    	 
    	$all = Farms::find ()->sum ('measure');
    	foreach (self::getManagementArea()['id'] as $value) {

			$area = ( float ) Farms::find ()->where ( [
    		  		'management_area' => $value
    		 ] )->sum ( 'measure' );
    		 $areas[] = $area;
    		 $sum += $area;
    	}
    	
    	$allvalue = $all - $sum;
    
    	if ($allvalue !== 0) {
    		$data[] = ['name'=>'其他管理区','y'=>$allvalue];
    	}
    	$result = [[
    			'type' => 'column',
    			'name' => '面积',
    			'data' => $areas,
    			'dataLabels'=> [
    					'enabled'=> true,
    					'rotation'=> -90,
    					'color'=> '#FFFFFF',
    					'align'=> 'right',
    					'x'=> 0,
    					'y'=> -20,
    					'style'=> [
    							'fontSize'=> '13px',
    							'fontFamily'=> 'Verdana, sans-serif',
    							'textShadow'=> '0 0 3px black'
    					]
    			]
    	]];
    
    	$jsonData = json_encode(['result'=>$result]);
    	Yii::$app->cache->set ( $cacheKey, $jsonData, 1 );
    
    	return $jsonData;
    }
}
