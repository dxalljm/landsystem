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
            [['newfarms_id','ttpozongdi_id', 'operation_id', 'create_at', 'update_at', 'estate', 'finance', 'filereview', 'publicsecurity', 'leader', 'mortgage', 'steeringgroup', 'estatetime', 'financetime', 'filereviewtime', 'publicsecuritytime', 'leadertime', 'mortgagetime', 'steeringgrouptime', 'regulations', 'regulationstime', 'oldfarms_id', 'management_area', 'state', 'project', 'projecttime'], 'integer'],
            [['projectcontent'], 'string'],
            [['estatecontent', 'financecontent', 'filereviewcontent', 'publicsecuritycontent', 'leadercontent', 'mortgagecontent', 'steeringgroupcontent', 'regulationscontent', 'actionname'], 'string', 'max' => 500]
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'newfarms_id' => '现农场ID',
        	'operation_id' => '操作ID',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'estate' => '地产科状态',
            'finance' => '财务报表',
            'filereview' => '档案审查状态',
            'publicsecurity' => '公安部门状态',
            'leader' => '分管领导',
            'mortgage' => '抵押贷款审查状态',
            'steeringgroup' => '领导小组状态',
            'estatecontent' => '地产科意见',
            'financecontent' => '财务科意见',
            'filereviewcontent' => '档案审查情况',
            'publicsecuritycontent' => '公安部门意见',
            'leadercontent' => '分管领导意见',
            'mortgagecontent' => '抵押贷款审查',
            'steeringgroupcontent' => '领导小组意见',
            'estatetime' => '地产科审查时间',
            'financetime' => '财务科审核时间',
            'filereviewtime' => '档案审查时间',
            'publicsecuritytime' => '公安部门审核时间',
            'leadertime' => '分管领导审核时间',
            'mortgagetime' => '抵押贷款审核时间',
            'steeringgrouptime' => '领导小组审核时间',
            'regulations' => '法规科状态',
            'regulationscontent' => '法规科意见',
            'regulationstime' => '法规科审核时间',
            'actionname' => '方法名称',
            'oldfarms_id' => '原农场ID',
            'management_area' => '管理区',
            'state' => '状态',
            'project' => '项目科状态',
            'projectcontent' => '项目科内容',
            'projecttime' => '项目科审核时间',
        	'ttpozongdi_id' => '转让信息ID',
        ]; 
    } 
    
    public static function getProcessRole($Identification)
    {
//     	var_dump($Identification);
    	$processname = Processname::find()->where(['Identification'=>$Identification])->one();
//     	var_dump($processname);exit;
   		$result['rolename'] = $processname['rolename'];
   		$result['sparerole'] = $processname['sparerole'];
    	$result['isFinished'] = Reviewprocess::find()->where([$Identification=>2])->count();
    	return $result;
    }
    
    public static function getProcessIdentification()
    {
//     	$processname = Processname::find()->orWhere(['rolename'=>User::getItemname()])->orWhere(['sparerole'=>User::getItemname()])->one();
    	$processname = [];
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
    	if($temp) {
    		$processname[] = Processname::find()->where(['rolename'=>User::getUserItemname($temp['user_id'])])->all();
    	}
    	$processname[] = Processname::find()->where(['rolename'=>User::getItemname()])->all();
    	    	
    	foreach ($processname as $value) {
    		foreach ($value as $val)
    			$result[] = $val['Identification'];
    	}
    	    	 
    	return $result;
    }
    
    public static function isNextProcess($id)
    {
    	$model = self::findOne($id);
    	$processs = self::getProcess($model->operation_id);
    	$processname = Processname::find()->where(['Identification'=>'leader'])->one();
    	$rows = count($processs);
    	$i = 0;
    	$no = true;
//     	var_dump($processs);
    	foreach ($processs as $value) {
    		if($model->$value == 0 or $model->$value == 2)
    			$no = false;
    	}
//     	var_dump($no);exit;
    	
    	foreach ($processs as $value) {
    		if(($model->$value == 3) and $no) {
    			$result = $model->$value-1;
    			$model->$value = $result;
    		}
    		if($model->$value == 1)
    			$i++;
    	}
    	$l = $rows - 1;
    	if($i < $l) {
    		$model->leader = 3;
    	} else
    		$model->leader = 2;
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if(User::getItemname() == $processname['rolename'] or $temp) {
			$model->leader = 1;
			$i++;
		}
    	if($i >= $rows) {
    		$model->state = 7;
    		$state = true;
    	}
		else {
			$model->state = 4;
			$state = false;
		}	
// 		var_dump(self::isShowProess($model->operation_id));exit;
		$model->save();
    	return $state;
    }
    
//     public static function isNextProcess($id)
//     {
//     	$model = self::findOne($id);
//     	$processs = Reviewprocess::getProcess($model->actionname);
//     	foreach ($processs as $value) {
//     		if($model->leader == 1) {
//     			if($model->steeringgroup == 1) {
//     				$model->state = 7;
//     				$model->save();
//     				$oldFarm = Farms::findOne($model->oldfarms_id);
//     				$oldFarm->state = 0;
//     				$oldFarm->locked = 0;
//     				$oldFarm->update_at = time();
//     				$oldFarm->save();
//     				$newFarm = Farms::findOne($model->newfarms_id);
//     				$newFarm->state = 1;
//     				$newFarm->locked = 0;
//     				$newFarm->save();
//     				return false;
//     			} else {
//     				return false;
//     			}
//     		} else {
//     			if($value !== 'leader' and $value !== 'steeringgroup') {
//     				if($model->$value == 1)
//     					$state = true;
//     				else
//     					$state = false;
//     			}
//     		}
    
//     	}
//     	return $state;
//     }
    
    public static function state($num)
    {
    	
    	$stateArray = [3=>'排队等待',2=>'待审核',1=>'同意',0=>'不同意',-1=>'无',4=>'审核中',7=>'通过',5=>'审核未通过'];
    	
    	return $stateArray[$num];
    }
    //返回指定的审核流程
    public static function getAuditprocess($actionname = NULL)
    {
    	if(empty($actionname)) {
    		$processID = Logicalpoint::find()->where(['actionname'=>yii::$app->controller->action->id])->one()['processname'];
//     		var_dump(Auditprocess::find()->where(['id'=>$processID])->one());exit;
   			return Auditprocess::find()->where(['id'=>$processID])->one();
    	}
    	else 
    		return Auditprocess::find()->where(['actionname'=>$actionname])->one();
    }
    //返回访问地址
    public static function getReturnAction($id) 
    {
//     	var_dump($id);
    	return 'reviewprocess/reviewprocess'.Auditprocess::findOne($id)['actionname'];
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
    public static function getProcess($id)
    {
    	
    	return explode('>', Auditprocess::findOne($id)['process']);
    }
    //判断当前角色是否审核流程
    public static function isShowProess($auditprocess_id) {
//     	var_dump($actionname);exit;
    	$process = Reviewprocess::getProcess($auditprocess_id);
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
//     	var_dump($process);exit;
    	foreach ($process as $p) {
//     		if(self::getProcessRole($p)['rolename'] == User::getItemname() or self::getProcessRole($p)['sparerole'] == User::getItemname())
			if($temp) {
				if(self::getProcessRole($p)['rolename'] == User::getUserItemname($temp['user_id']))
					return true;
			} else {
				if(self::getProcessRole($p)['rolename'] == User::getItemname())
	    			return true;
			}
    	}
    	return false;
    }
   
    
    //保存流程
    public static function processRun($auditprocess_id,$oldfarms_id=NULL,$newfarms_id=null,$ttpozongdi_id = NULL)
    { 	
    	$auditprocess = Auditprocess::find()->where(['id'=>$auditprocess_id])->one();
//     	var_dump($auditprocess_id);exit;
    	$processs = explode('>',$auditprocess->process);
//     	var_dump();exit;
    	$reviewprocessModel = new Reviewprocess();
//		var_dump($reviewprocessModel->attributes);exit;
    	$reviewprocessModel->oldfarms_id = $oldfarms_id;
    	$reviewprocessModel->newfarms_id = $newfarms_id;
    	$reviewprocessModel->ttpozongdi_id = $ttpozongdi_id;
    	$reviewprocessModel->management_area = Farms::find()->where(['id'=>$oldfarms_id])->one()['management_area'];
    	$reviewprocessModel->actionname = $auditprocess->actionname;
    	$reviewprocessModel->create_at = time();
    	$reviewprocessModel->update_at = $reviewprocessModel->create_at;
    	$reviewprocessModel->operation_id = $auditprocess_id;
//		$reviewprocessModel->estate = 1;
//     	var_dump($reviewprocessModel->attributes);exit;
    	for($i=0;$i<count($processs);$i++) {
			$str = $processs[$i];
    		if($str == 'estate')
    			$reviewprocessModel->$str = 2;
    		else
    			$reviewprocessModel->$str = 3;
    		if($str == 'leader')
    			if($i == 0)
    				$reviewprocessModel->$str = 2;
    			else
    				$reviewprocessModel->$str = 3;
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
    	$processRows = 0;
    	//判断是否有临时授权人
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
    	if($temp) {
    		$processself = Processname::find()->where(['rolename'=>User::getItemname()])->one()['Identification'];
    		$processRows += Reviewprocess::find()->where(['management_area'=>$mamangmentarea['id'],$processself=>2])->count();
    		$process = Processname::find()->where(['rolename'=>User::getUserItemname($temp['user_id'])])->one()['Identification'];
    		$processRows += Reviewprocess::find()->where(['management_area'=>$mamangmentarea['id'],$process=>2])->count();
    	} else {	    	
	    	
	    	$process = Processname::find()->where(['rolename'=>User::getItemname()])->one()['Identification'];
			
	    	$processRows += Reviewprocess::find()->where(['management_area'=>$mamangmentarea['id'],$process=>2])->count();
    	}
    	if($processRows)
    		return '<small class="label pull-right bg-red">'.$processRows.'</small>';
    	else 
    		return false;
    }
}
