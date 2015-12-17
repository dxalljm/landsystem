<?php

namespace app\models;

use Yii;
use app\models\Auditprocess;
use app\models\Session;
use app\models\Logicalpoint;
/**
 * This is the model class for table "{{%reviewprocess}}".
 *
 * @property integer $id
 * @property string $farms_id
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $estate
 * @property integer $finance
 * @property integer $filereview
 * @property integer $publicsecurity
 * @property integer $leader
 * @property integer $mortgage
 * @property integer $steeringgroup
 * @property string $estatecontent
 * @property string $financecontent
 * @property string $filereviewcontent
 * @property string $publicsecuritycontent
 * @property string $leadercontent
 * @property string $mortgagecontent
 * @property string $steeringgroupcontent
 * @property integer $estatetime
 * @property integer $financetime
 * @property integer $filereviewtime
 * @property integer $publicsecuritytime
 * @property integer $leadertime
 * @property integer $mortgagetime
 * @property integer $steeringgrouptime
 */
class Reviewprocess extends \yii\db\ActiveRecord
{
	public static $session;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reviewprocess}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldfarms_id','newfarms_id', 'management_area','create_at', 'update_at', 'estate', 'finance', 'filereview', 'publicsecurity', 'leader', 'mortgage', 'steeringgroup', 'estatetime', 'financetime', 'filereviewtime', 'publicsecuritytime', 'leadertime', 'mortgagetime', 'steeringgrouptime','regulations','regulationstime'], 'integer'],
            [['estatecontent', 'financecontent', 'filereviewcontent', 'publicsecuritycontent', 'leadercontent', 'mortgagecontent', 'steeringgroupcontent','actionname','regulationscontent'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'oldfarms_id' => '原农场ID',
        	'newfarms_id' => '新农场ID',
        	'management_area' => '管理区ID',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'estate' => '地产科状态',
        	'regulations' => '法规科状态',
            'finance' => '财务报表',
            'filereview' => '档案审查状态',
            'publicsecurity' => '公安部门状态',
            'leader' => '分管领导状态',
            'mortgage' => '抵押贷款审查状态',
            'steeringgroup' => '领导小组状态',
            'estatecontent' => '地产科意见',
        	'regulationscontent' => '法规科意见',
            'financecontent' => '财务科意见',
            'filereviewcontent' => '档案审查情况',
            'publicsecuritycontent' => '公安部门意见',
            'leadercontent' => '分管领导意见',
            'mortgagecontent' => '抵押贷款审查',
            'steeringgroupcontent' => '领导小组意见',
            'estatetime' => '地产科审查时间',
            'financetime' => '财务科审核时间',
        	'regulationstime' => '法规科审核时间',
            'filereviewtime' => '档案审查时间',
            'publicsecuritytime' => '公安部门审核时间',
            'leadertime' => '分管领导审核时间',
            'mortgagetime' => '抵押贷款审核时间',
            'steeringgrouptime' => '领导小组审核时间',
        	'actionname' => '方法名称',
        ];
    }
    
    public static function getProcessRole($Identification)
    {
    	$processname = Processname::find()->where(['Identification'=>$Identification])->one();
   		$result['rolename'] = $processname['rolename'];
   		$result['sparerole'] = $processname['sparerole'];
    	
    	return $result;
    }
    
    public static function getProcessIdentification()
    {
//     	$processname = Processname::find()->orWhere(['rolename'=>User::getItemname()])->orWhere(['sparerole'=>User::getItemname()])->one();
    	$processname = Processname::find()->where(['rolename'=>User::getItemname()])->one();
    	$result = $processname['Identification'];    	 
    	return $result;
    }
    
    public static function isNextProcess($id)
    {
    	$model = self::findOne($id);
    	$processs = Reviewprocess::getProcess($model->actionname);
    	foreach ($processs as $value) {
    		if($model->leader == 1) {
    			if($model->steeringgroup == 1) {
    				$model->state = 7;
    				$model->save();
    				$oldFarm = Farms::findOne($model->oldfarms_id);
    				$oldFarm->state = 0;
    				$oldFarm->locked = 0;
    				$oldFarm->update_at = time();
    				$oldFarm->save();
    				$newFarm = Farms::findOne($model->newfarms_id);
    				$newFarm->state = 1;
    				$newFarm->locked = 0;
    				$newFarm->save();
    				return false;
    			} else {
    				return false;
    			}
    		} else {
    			if($value !== 'leader' and $value !== 'steeringgroup') {
	    			if($model->$value == 1)
	    				$state = true;
	    			else
	    				$state = false;
    			}
    		}
    		
    	}
    	return $state;
    }
    
    public static function state($num)
    {
    	$stateArray = [3=>'待审核',2=>'排除等待',1=>'同意',0=>'不同意',-1=>'无',4=>'审核中',5=>'分管领导审核中',6=>'领导小组审核中',7=>'完成'];
    	return $stateArray[$num];
    }
    //返回指定的审核流程
    public static function getAuditprocess($actionname = NULL)
    {
    	if(empty($actionname)) {
    		$processID = Logicalpoint::find()->where(['actionname'=>yii::$app->controller->action->id])->one()['processname'];
   			return Auditprocess::find()->where(['id'=>$processID])->one();
    	}
    	else 
    		return Auditprocess::find()->where(['actionname'=>$actionname])->one();
    }
    //返回访问地址
    public static function getReturnAction() 
    {
    	
    	return 'reviewprocess/reviewprocess'.yii::$app->controller->action->id;
    }
    //返回定位方法名称
    public static function getAction($key = NULL)
    {
    	if(empty($key))
    		return self::getAuditprocess()['actionname'];
    	else 
    		return self::getReviewprocessOne($key)['actionname'];
    }
    //返回审核流程
    public static function getProcess($actionname = NULL)
    {
    	return explode('>', self::getAuditprocess($actionname)['process']);
    }
    //判断当前角色是否审核流程
    public static function isShowProess($actionname) {
    	$process = Reviewprocess::getProcess($actionname);
    	foreach ($process as $p) {
    		if(self::getProcessRole($p)['rolename'] == User::getItemname() or self::getProcessRole($p)['sparerole'] == User::getItemname())
    			return true;
    	}
    	return false;
    }
   
    
    //保存流程
    public static function processRun($oldfarms_id,$newfarms_id)
    { 	
    	$processs = self::getProcess();
//     	var_dump($processs);exit;
    	$reviewprocessModel = new Reviewprocess();
    	$reviewprocessModel->oldfarms_id = $oldfarms_id;
    	$reviewprocessModel->newfarms_id = $newfarms_id;
    	$reviewprocessModel->management_area = Farms::find()->where(['id'=>$oldfarms_id])->one()['management_area'];
    	$reviewprocessModel->actionname = self::getAction();
    	$reviewprocessModel->create_at = time();
    	$reviewprocessModel->update_at = $reviewprocessModel->create_at;
    	for($i=0;$i<count($processs);$i++) {
    		$reviewprocessModel->$processs[$i] = 3;
    		if($processs[$i] == 'leader' or $processs[$i] == 'steeringgroup')
    			$reviewprocessModel->$processs[$i] = 2;
    	}
    	$reviewprocessModel->state = 4;
//     	var_dump($reviewprocessModel);exit;
    	if($reviewprocessModel->save()) {
//     		Session::sessionSave($oldfarms_id,'reviewprocess_id',$reviewprocessModel->id);
    		return $reviewprocessModel->id;    	
    	}
    	else 
    		return false;
//     	var_dump($reviewprocessModel->getErrors());exit;
    }
    
    public static function getReviewprocessOne($key)
    {
    	$array = explode(',', Session::find()->where(['key'=>$key])->one()['value']);
    	foreach ($array as $value) {
    		$model[] = Reviewprocess::findOne($value);
    	}
    	return $model;
    }
    
    //获取当前用户的审核任务数量
    public static function getUserProcessCount()
    {
    	$mamangmentarea = Farms::getManagementArea();
    	
    	$process = Processname::find()->where(['rolename'=>User::getItemname()])->one()['Identification'];
//     	var_dump(User::getItemname());exit;
    	$processRows = Reviewprocess::find()->where(['management_area'=>$mamangmentarea['id'],$process=>3])->count();
//     	var_dump($processRows);exit;
    	if($processRows)
    		return '<small class="label pull-right bg-red">'.$processRows.'</small>';
    	else 
    		return false;
    }
}
