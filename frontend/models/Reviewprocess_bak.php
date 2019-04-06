<?php

namespace app\models;

use app\models\User;
use Composer\Package\Loader\ValidatingArrayLoader;
use frontend\models\employeeSearch;
use Yii;
use app\models\Auditprocess;
use yii\helpers\Html;
use app\models\Logicalpoint;
use app\models\Tempauditing;

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
            [['newfarms_id','ttpozongdi_id', 'operation_id', 'create_at', 'update_at', 'estate', 'finance', 'filereview', 'publicsecurity', 'leader', 'mortgage', 'steeringgroup', 'estatetime', 'financetime', 'filereviewtime', 'publicsecuritytime', 'leadertime', 'mortgagetime', 'steeringgrouptime', 'regulations', 'regulationstime', 'oldfarms_id', 'management_area', 'state', 'project', 'projecttime','samefarms_id'], 'integer'],
            [['projectcontent'], 'string'],
            [['estatecontent','financecontent', 'filereviewcontent', 'publicsecuritycontent', 'leadercontent', 'mortgagecontent', 'steeringgroupcontent', 'regulationscontent', 'actionname','undo','fromundo'], 'string', 'max' => 500],
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
			'undo' => '退回',
			'fromundo' => '从哪退回',
			'samefarms_id' => '分户指向原农场ID'
        ];
    }

	public static function getProcessRole($Identification)
	{
//     	var_dump($Identification);
		$processname = Processname::find()->where(['Identification'=>$Identification])->one();
//     	var_dump($processname);exit;
		$result['department_id'] = $processname['department_id'];
		$result['level_id'] = $processname['level_id'];
//		var_dump($result);exit;
//    	$result['isFinished'] = Reviewprocess::find()->where([$Identification=>2])->count();
//		var_dump($result);
		return $result;
	}
    
    public static function getProcessIdentification($actionname)
    {
		$result = [];
//     	$processname = Processname::find()->orWhere(['rolename'=>User::getItemname()])->orWhere(['sparerole'=>User::getItemname()])->one();
		$au = Auditprocess::find()->where(['actionname'=>$actionname])->one()['process'];
		$auArr = explode('>',$au);
    	$processname = [];
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
    	if($temp) {
    		$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
			$processname[] = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
    	}
    	$processname[] = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
//    	    	var_dump($processname);exit;a
    	foreach ($processname as $value) {
    		foreach ($value as $val)
    			$result[] = $val;
    	}
//		var_dump(array_intersect($result,$auArr));exit;
    	return array_intersect($result,$auArr);
    }

	public static function getUndo($model)
	{
		$auditprocess = Auditprocess::find()->where(['actionname'=>'farmstransfer'])->one();
		$processname = explode('>',$auditprocess['process']);
		foreach ($processname as $item) {
			if($model->$item == 0)
				return $item;
		}
		return false;
	}

	public static function isNextLoanProcess($id,$field,$post)
	{
		$model = self::findOne($id);
		$processs = self::getProcess($model->operation_id);
		$tempProcess = $processs;
		$rows = count($processs);
		$i = 0;
		switch ($field) {
			case 'estate':
				$model->estate = 1;
				$model->mortgage = 2;
				$model->state = 4;
				break;
			case 'mortgage':
				$model->mortgage = $post['mortgageisAgree'];

//				var_dump($post['mortgageisAgree']);exit;
				if(self::isUndo($field,$model)) {

					//fromundo为退回用户
					$fromundoArray = explode(',',$model->fromundo);
					if(count($fromundoArray) > 1)
						$model->$fromundoArray[1] = 2;
					else
						$model->$fromundoArray[0] = 2;
					$model->leader = 3;
				} else {
					unset($tempProcess[0]);
					unset($tempProcess[1]);
					unset($tempProcess[$rows - 1]);
//					var_dump($tempProcess);
					$model->mortgage = $post['mortgageisAgree'];

					foreach ($tempProcess as $process) {
						$model->$process = 2;
					}
//					var_dump($model->mortgage);
				}

				if($model->mortgage == 0) {
					$model->state = 9;
					if(!empty($model->undo)) {
						$model->state = 8;
						$model->$post['mortgageundo'] = 8;
						$undoArray = explode(',', $model->undo);
						$undoArray[] = $post['mortgageundo'];
						$model->undo = implode(',', $undoArray);
					} else
						$model->undo = $post['mortgageundo'];
					if(!empty($model->fromundo)) {
						$fromundoArray = explode(',', $model->fromundo);
						$fromundoArray[] = 'mortgage';
						$model->fromundo = implode(',', $fromundoArray);
					} else
						$model->fromundo = 'mortgage';
				} else {
					$undo = self::getUndo($model);
					if($undo)
						$model->$undo = 2;
					$model->state = 4;
				}

				if(isset($post['mortgageisAgreecontent']))
					$model->mortgagecontent = $post['mortgageisAgreecontent'];
				$model->mortgagetime = time();
				$model->save();
//				exit;
				break;
			case 'leader':
				$i = 0;
				$model->leader = $post['leaderisAgree'];
				$model->leadertime = time();
//				foreach ($processs as $process) {
//					if($model->$process == 1) {
//						$i++;
//					}
//				}
				if($model->leader == 0) {
					if($post['leaderundo'] == 'undo') {
						$model->state = 9;

					}

					if($post['leaderundo'] !== '' and $post['leaderundo'] !== 'undo') {
						$undo = $post['leaderundo'];
						$model->state = 8;
						$model->$undo = 8;
					}
					if(!empty($model->undo)) {
						$undoArray = explode(',', $model->undo);
						$undoArray[] = $post['leaderundo'];
						$model->undo = implode(',', $undoArray);
					} else
						$model->undo = $post['leaderundo'];
					if(!empty($model->fromundo)) {
						$fromundoArray = explode(',', $model->fromundo);
						$fromundoArray[] = 'leader';
						$model->fromundo = implode(',', $fromundoArray);
					} else
						$model->fromundo = 'leader';
					if(isset($post['leaderisAgreecontent']))
						$model->leadercontent = $post['leaderisAgreecontent'];
				} else {
					if($model->actionname == 'loancreate') {
						$model->state = 7;
					} else {
						$model->state = 6;
					}
				}
				break;

			default:
//				var_dump($model->$field);
				switch ($model->$field) {
					case 0:
						if($post[$field.'undo'] == 'undo')
							$model->state = 9;
						if($post[$field.'undo'] !== '' and $post[$field.'undo'] !== 'undo') {
							$model->state = 8;
						}
						if(!empty($model->undo)) {
							$undoArray = explode(',', $model->undo);
							$undoArray[] = $post[$field.'undo'];
							$model->undo = implode(',', $undoArray);
						} else
							$model->undo = $post[$field.'undo'];
						if(!empty($model->fromundo)) {
							$fromundoArray = explode(',', $model->fromundo);
							$fromundoArray[] = $field;
							$model->fromundo = implode(',', $fromundoArray);
						} else
							$model->fromundo = $field;
						$contentStr = $field.'isAgreecontent';
						$modelContentStr = $field.'content';
						if(isset($post[$contentStr]))
							$model->$modelContentStr = $post[$contentStr];
						break;
					case 2:
						$undofield = $post[$field.'undo'];
						$model->$field = $post[$field.'isAgree'];
						$contentStr = $field.'isAgreecontent';
						$modelContentStr = $field.'content';
						if(isset($post[$contentStr]))
							$model->$modelContentStr = $post[$contentStr];
//						var_dump($field);
//						var_dump($model->$undofield);exit;
						if($model->$field == 0) {
//							var_dump($undofield);exit;
							if(!empty($model->undo)) {
								$undoArray = explode(',', $model->undo);
								$undoArray[] = $post[$field.'undo'];
								$model->undo = implode(',', $undoArray);
							} else
								$model->undo = $post[$field.'undo'];
//							var_dump($post[$field.'undo']);
							if($post[$field.'undo'] == 'undo') {
								$model->state = 9;
							}
							if($post[$field.'undo'] !== 'undo' and $post[$field.'undo'] !== '') {
								$model->state = 8;
								$model->$undofield = 8;
							}

							if(!empty($model->fromundo)) {
								$fromundoArray = explode(',', $model->fromundo);
								$fromundoArray[] = $field;
								$model->fromundo = implode(',', $fromundoArray);
							} else
								$model->fromundo = $field;
						} else {
//							var_dump($model);exit;
							$model->$field = 1;
							$model->state = 4;
							if(!empty($model->undo)) {
								$undoArray = self::getUndoArray($model);
								$undolast = end($undoArray['undo']);
								$fromlast = end($undoArray['fromundo']);
//								var_dump($model->$last);
								if($undolast !== 'undo' and $model->$undolast == 8) {
									$model->state = 8;
								}
								if($undolast == 'undo' and $model->$fromlast == 0) {
									$model->state = 9;
								}
							}
//							var_dump($model->state);exit;
						}

						break;
					case 3:
						$model->state = 4;
						$contentStr = $field.'isAgreecontent';
						$modelContentStr = $field.'content';
						if(isset($post[$contentStr]))
							$model->$modelContentStr = $post[$contentStr];
						break;
					case 8:
						//退回后,再退回出现问题
//						$model->undo = '';

						$model->$field = $post[$field.'isAgree'];
						$contentStr = $field.'isAgreecontent';
						$modelContentStr = $field.'content';
						if(isset($post[$contentStr]))
							$model->$modelContentStr = $post[$contentStr];
						if($model->$field == 0) {
							if($post[$field.'undo'] == 'undo') {
								$model->state = 9;
							} else {
								$undoArray = explode(',', $model->undo);
								$undoArray[] = $post[$field . 'undo'];
								$model->undo = implode(',', $undoArray);
								$fromundoArray = explode(',', $model->fromundo);
								$fromundoArray[] = $field;
								$model->fromundo = implode(',', $fromundoArray);
								$model->leader = 3;
								$undo = $post[$field.'undo'];
								$model->$undo = 8;
								$model->state = 8;
							}
						} else {
							$model->state = 4;
						}
						break;
				}
				$i = 0;
				if(isset($post[$field.'isAgree']))
					$model->$field = $post[$field.'isAgree'];
				foreach ($processs as $process) {
					if($model->$process == 1) {
						$i++;
					}
				}
				$state = true;
				unset($tempProcess[$rows - 1]);
				foreach ($tempProcess as $process) {
					if($model->$process == 0 or $model->$process == 2) {
						$state = false;
					}
				}
				if($state) {
					$model->leader = 2;
				}

		}
		$timeStr = $field.'time';
		$model->$timeStr = time();
		$State = $model->state;
		$model->update_at = time();
//		var_dump($model);exit;
		$model->save();
		return $State;
	}

	public static function isNextProcess($id,$field,$post)
	{
//		var_dump($id);var_dump($field);var_dump($post);exit;
		$model = self::findOne($id);
		$processs = self::getProcess($model->operation_id);
		$tempProcess = $processs;
		$rows = count($processs);
    	$i = 0;
		switch ($field) {
			case 'estate':
				//如果退回字段值与当前用户流程标识相同
				$model->estate = $post['estateisAgree'];
				if(self::isUndo($field,$model)) {

					//fromundo为退回用户
					$fromundoArray = explode(',',$model->fromundo);
					if(count($fromundoArray) > 1)
						$model->$fromundoArray[1] = 2;
					else
						$model->$fromundoArray[0] = 2;
					$model->leader = 3;
				} else {
					unset($tempProcess[0]);
					unset($tempProcess[$rows - 1]);
					$model->estate = $post['estateisAgree'];
					foreach ($tempProcess as $process) {
						$model->$process = 2;
					}
				}
				if($model->estate == 0) {
					$model->state = 9;
					if(!empty($model->undo)) {
						$undoArray = explode(',', $model->undo);
						$undoArray[] = $post['estateundo'];
						$model->undo = implode(',', $undoArray);
					} else
						$model->undo = $post['estateundo'];
					if(!empty($model->fromundo)) {
						$fromundoArray = explode(',', $model->fromundo);
						$fromundoArray[] = 'estate';
						$model->fromundo = implode(',', $fromundoArray);
					} else
						$model->fromundo = 'estate';
				} else {
					$undo = self::getUndo($model);
					if($undo)
						$model->$undo = 2;
					$model->state = 4;
				}

				if(isset($post['estateisAgreecontent']))
					$model->estatecontent = $post['estateisAgreecontent'];
				$model->estatetime = time();
				break;
			case 'leader':
				$i = 0;
				$model->leader = $post['leaderisAgree'];
				$model->leadertime = time();
//				foreach ($processs as $process) {
//					if($model->$process == 1) {
//						$i++;
//					}
//				}
				if($model->leader == 0) {
					if($post['leaderundo'] == 'undo') {
						$model->state = 9;

					}

					if($post['leaderundo'] !== '' and $post['leaderundo'] !== 'undo') {
						$undo = $post['leaderundo'];
						$model->state = 8;
						$model->$undo = 8;
					}
					if(!empty($model->undo)) {
						$undoArray = explode(',', $model->undo);
						$undoArray[] = $post['leaderundo'];
						$model->undo = implode(',', $undoArray);
					} else
						$model->undo = $post['leaderundo'];
					if(!empty($model->fromundo)) {
						$fromundoArray = explode(',', $model->fromundo);
						$fromundoArray[] = 'leader';
						$model->fromundo = implode(',', $fromundoArray);
					} else
						$model->fromundo = 'leader';
					if(isset($post['leaderisAgreecontent']))
						$model->leadercontent = $post['leaderisAgreecontent'];
				} else {
					if($model->actionname == 'loancreate') {
						$model->state = 7;
					} else {
						$model->state = 6;
					}
				}
				break;
			case 'project':
				$i = 0;
				$model->project = $post['projectisAgree'];
				$model->projecttime = time();
//				foreach ($processs as $process) {
//					if($model->$process == 1) {
//						$i++;
//					}
//				}
				if($model->project == 0) {
					if($post['projectundo'] == 'undo') {
						$model->state = 9;

					}

					if($post['projectundo'] !== '' and $post['projectundo'] !== 'undo') {
						$undo = $post['projectundo'];
						$model->state = 8;
						$model->$undo = 8;
					}
					if(!empty($model->undo)) {
						$undoArray = explode(',', $model->undo);
						$undoArray[] = $post['projectundo'];
						$model->undo = implode(',', $undoArray);
					} else
						$model->undo = $post['projectundo'];
					if(!empty($model->fromundo)) {
						$fromundoArray = explode(',', $model->fromundo);
						$fromundoArray[] = 'project';
						$model->fromundo = implode(',', $fromundoArray);
					} else
						$model->fromundo = 'project';
					if(isset($post['projectisAgreecontent']))
						$model->projectcontent = $post['projectisAgreecontent'];
				} else {
					$model->state = 7;
				}
				break;
			default:
//				var_dump($model->$field);
				switch ($model->$field) {
					case 0:
						if($post[$field.'undo'] == 'undo')
							$model->state = 9;
						if($post[$field.'undo'] !== '' and $post[$field.'undo'] !== 'undo') {
							$model->state = 8;
						}
						if(!empty($model->undo)) {
							$undoArray = explode(',', $model->undo);
							$undoArray[] = $post[$field.'undo'];
							$model->undo = implode(',', $undoArray);
						} else
							$model->undo = $post[$field.'undo'];
						if(!empty($model->fromundo)) {
							$fromundoArray = explode(',', $model->fromundo);
							$fromundoArray[] = $field;
							$model->fromundo = implode(',', $fromundoArray);
						} else
							$model->fromundo = $field;
						$contentStr = $field.'isAgreecontent';
						$modelContentStr = $field.'content';
						if(isset($post[$contentStr]))
							$model->$modelContentStr = $post[$contentStr];
						break;
					case 2:
						$undofield = $post[$field.'undo'];
						$model->$field = $post[$field.'isAgree'];
						$contentStr = $field.'isAgreecontent';
						$modelContentStr = $field.'content';
						if(isset($post[$contentStr]))
							$model->$modelContentStr = $post[$contentStr];
//						var_dump($field);
//						var_dump($model->$undofield);exit;
						if($model->$field == 0) {
//							var_dump($undofield);exit;
							if(!empty($model->undo)) {
								$undoArray = explode(',', $model->undo);
								$undoArray[] = $post[$field.'undo'];
								$model->undo = implode(',', $undoArray);
							} else
								$model->undo = $post[$field.'undo'];
//							var_dump($post[$field.'undo']);
							if($post[$field.'undo'] == 'undo') {
								$model->state = 9;
							}
							if($post[$field.'undo'] !== 'undo' and $post[$field.'undo'] !== '') {
								$model->state = 8;
								$model->$undofield = 8;
							}

							if(!empty($model->fromundo)) {
								$fromundoArray = explode(',', $model->fromundo);
								$fromundoArray[] = $field;
								$model->fromundo = implode(',', $fromundoArray);
							} else
								$model->fromundo = $field;
						} else {
//							var_dump($model);exit;
							$model->$field = 1;
							$model->state = 4;
							if(!empty($model->undo)) {
								$undoArray = self::getUndoArray($model);
								$undolast = end($undoArray['undo']);
								$fromlast = end($undoArray['fromundo']);
//								var_dump($model->$last);
								if($undolast !== 'undo' and $model->$undolast == 8) {
									$model->state = 8;
								}
								if($undolast == 'undo' and $model->$fromlast == 0) {
									$model->state = 9;
								}
							}
//							var_dump($model->state);exit;
						}

						break;
					case 3:
						$model->state = 4;
						$contentStr = $field.'isAgreecontent';
						$modelContentStr = $field.'content';
						if(isset($post[$contentStr]))
							$model->$modelContentStr = $post[$contentStr];
						break;
					case 8:
						//退回后,再退回出现问题
//						$model->undo = '';

						$model->$field = $post[$field.'isAgree'];
						$contentStr = $field.'isAgreecontent';
						$modelContentStr = $field.'content';
						if(isset($post[$contentStr]))
							$model->$modelContentStr = $post[$contentStr];
						if($model->$field == 0) {
							if($post[$field.'undo'] == 'undo') {
								$model->state = 9;
							} else {
								$undoArray = explode(',', $model->undo);
								$undoArray[] = $post[$field . 'undo'];
								$model->undo = implode(',', $undoArray);
								$fromundoArray = explode(',', $model->fromundo);
								$fromundoArray[] = $field;
								$model->fromundo = implode(',', $fromundoArray);
								$model->leader = 3;
								$undo = $post[$field.'undo'];
								$model->$undo = 8;
								$model->state = 8;
							}
						} else {
							$model->state = 4;
						}
						break;
				}
				$i = 0;
				if(isset($post[$field.'isAgree']))
					$model->$field = $post[$field.'isAgree'];
				foreach ($processs as $process) {
					if($model->$process == 1) {
						$i++;
					}
				}
				$state = true;
				unset($tempProcess[$rows - 1]);
				foreach ($tempProcess as $process) {
					if($model->$process == 0 or $model->$process == 2) {
						$state = false;
					}
				}
				if($state) {
					$model->leader = 2;
				}

		}
		$timeStr = $field.'time';
		$model->$timeStr = time();
		$State = $model->state;
		$model->update_at = time();
//		var_dump($model);exit;
		$model->save();
		return $State;
	}

	public static function getUndoArray($model)
	{
		$undoArray = explode(',',$model->undo);
		$fromundoArray = explode(',',$model->fromundo);
		$result['undo'] = $undoArray;
		$result['fromundo'] = $fromundoArray;
		return $result;
	}

	public static function isUndo($field,$model)
	{
		$v = false;
		$result = self::getUndoArray($model);
		foreach ($result['undo'] as $value) {
			if($field == $value) {
				$v = $field;
			}
		}
		return $v;
	}

	public static function getDepartment($field,$model)
	{
		$v = false;
		$result = self::getUndoArray($model);
		foreach ($result['fromundo'] as $key => $value) {
			if($field == $value) {
				$v = $result['undo'][$key];
			}
		}
		return $v;
	}
//    public static function isNextProcess($id,$field)
//    {
//    	$model = self::findOne($id);
//    	$processs = self::getProcess($model->operation_id);
//    	$processname = Processname::find()->where(['Identification'=>'leader'])->one();
//    	$rows = count($processs);
//    	$i = 0;
//    	$no = true;
//    	foreach ($processs as $value) {
//    		if($model->$value == 0)
//    			$no = false;
//    	}
////     	var_dump($no);exit;
//
//    	foreach ($processs as $value) {
//    		if($no and $model->$value == 3) {
//    			$result = $model->$value-1;
//    			$model->$value = $result;
//    		}
//    		if($model->$value == 1)
//    			$i++;
//    	}
//    	$l = $rows - 1;
//    	if($i < $l) {
//    		$model->leader = 3;
//    	}
//		else
//    		$model->leader = 2;
//    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
//		if(User::getItemname() == $processname['rolename'] or $temp) {
//			$model->leader = 1;
//			$i++;
//		}
//    	if($i >= $rows) {
//    		$model->state = 6;
//    		$state = true;
//    	}
//		else {
//			$model->state = 4;
//			$state = false;
//		}
//// 		var_dump(self::isShowProess($model->operation_id));exit;
//		$model->save();
//    	return $state;
//    }
    
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
	//审核角色或临时授权角色与当前用户角色是否相同，返回布尔型
	public static function isRole($field)
	{
		//获取当前流程的角色信息
		$newField = explode(',',$field);
		if(count($newField) > 1) {
			$role = self::getProcessRole($newField[1]);
		} else
			$role = self::getProcessRole($newField[0]);
// 			  	var_dump($role);exit;
		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		$tempitem = '';

		if($temp) {
			$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
			if(in_array($userinfo['department_id'],explode(',',$role['department_id'])) and $role['level_id'] == $userinfo['level'])
				return true;
			else
				return false;
		}
		if(in_array(Yii::$app->getUser()->getIdentity()->department_id,explode(',',$role['department_id'])) and $role['level_id'] == Yii::$app->getUser()->getIdentity()->level)
			return true;
	}



	public static function showReviewprocess($model,$field,$process,$key=null)
	{
		$html = '';
		$fromundo = '';
		$classname = 'app\\models\\' . ucfirst($field);
		$classdata = $classname::find()->where(['reviewprocess_id'=>$model->id])->one();
		if($model->actionname == 'loancreate') {
			$lists = $classname::loanAttributesList();
		} else
			$lists = $classname::attributesList();
//		var_dump($field);exit;
//		var_dump($lists);exit;
//		if(!empty($key)) {
//			foreach ($key as $val) {
//				$newlist[$val] = $lists[$val];
//			}
//			$lists = $newlist;
//		}
		switch ($model->$field)
		{
			case 0:
				if ($lists) {
					if ($classdata) {
						$html .= '<td colspan="7" align="left">';
						$html .= '<font color="#00CC66">';
						$html .= '<table>';
						foreach ($lists as $key => $list) {
							if (!strstr($key, 'isAgree')) {
								$html .= '<tr>';
								$html .= '<td>';
								if ($classdata[$key]) {
									$html .= '<strong>否<i class="fa fa-square-o"></i></strong>&nbsp;&nbsp;';
									$html .= '<strong>是<i class="fa fa-check-square-o"></i></strong>';
								} else {
									$html .= '<strong>否<i class="fa fa-check-square-o"></i></strong>&nbsp;&nbsp;';
									$html .= '<strong>是<i class="fa fa-square-o"></i></string>';
								}
								$html .= '</td>';
								$html .= '<td>';
								$html .= '&nbsp;&nbsp;' . $list;
								$html .= '</td>';
								$html .= '<td>';
								if ($classdata[$key . 'content'])
									$html .= '&nbsp;&nbsp;<font color="red"><strong>情况说明：' . $classdata[$key . 'content'] . '</strong></font>';
								$html .= "</td>";
								$html .= '</tr>';
							}
						}
						$html .= '</table>';

						$html .= '</font>';
						$html .= '</td>';
					}
				}
				$html .= '<tr>';
				$html .= '<td colspan="7" align="left" >';
				$html .= '<font color="#0033FF"><strong>';
				$html .= self::state($model->$field);
				$html .= '</strong></font>';
				$html .= "<br>";
				$content = $field.'content';
				if($model->$content and ($model->state == 8 or $model->state == 9)) {
					$html .= '<font color="#FF0000"><strong>';
					$modelStr = $field.'content';
					$department = Processname::getDepartment($field,$model);;
					$html .= $department."<br>原由：".$model->$modelStr;
					$html .= '</strong></font>';
				}
				$html .= '</td>';
				$html .= '</tr>';
//				}
				break;
			case 1:
//				if(self::isRole($field)) {
					if ($lists) {
						if ($classdata) {
							$html .= '<td colspan="7" align="left">';
							$html .= '<font color="#00CC66">';
							$html .= '<table>';
							foreach ($lists as $key => $list) {
								if (!strstr($key, 'isAgree')) {
									$html .= '<tr>';
									$html .= '<td>';
									if ($classdata[$key]) {
										$html .= '<strong>否<i class="fa fa-square-o"></i></strong>&nbsp;&nbsp;';
										$html .= '<strong>是<i class="fa fa-check-square-o"></i></strong>';
									} else {
										$html .= '<strong>否<i class="fa fa-check-square-o"></i></strong>&nbsp;&nbsp;';
										$html .= '<strong>是<i class="fa fa-square-o"></i></string>';
									}
									$html .= '</td>';
									$html .= '<td>';
									$html .= '&nbsp;&nbsp;' . $list;
									$html .= '</td>';
									$html .= '<td>';
									if ($classdata[$key . 'content'])
										$html .= '&nbsp;&nbsp;<font color="red"><strong>情况说明：' . $classdata[$key . 'content'] . '</strong></font>';
									$html .= "</td>";
									$html .= '</tr>';
								}
							}
							$html .= '</table>';

							$html .= '</font>';
							$html .= '</td>';
						}
					}
				$html .= '<tr>';
				$html .= '<td colspan="7" align="left" >';
					$html .= '<font color="#0033FF"><strong>';
					$timefield = $field.'time';
					$html .= self::state($model->$field).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="#ed0404">审核时间：'.date('Y-m-d H:i:s',$model->$timefield).'</font>';
					$html .= '</strong></font>';
				$html .= "<br>";
				$content = $field.'content';
				if($model->$content and ($model->state == 8 or $model->state == 9)) {
					$html .= '<font color="#FF0000"><strong>';

					$modelStr = $field.'content';
					$department = Processname::getDepartment($field,$model);;
					$html .= $department."<br>原由：".$model->$content;

					$html .= '</strong></font>';
				}
				$html .= '</td>';
				$html .= '</tr>';
//				}
				break;
			case 2:
				if(self::isRole($field) and Yii::$app->controller->action->id !== 'reviewprocessview') {
					if ($field == 'leader' or $field == 'project') {
						echo '<td colspan="7" align="left">';
						echo '<table class="table">';
						echo '<tr>';
						echo '<td width="150">';
						echo Html::radioList($field . 'isAgree', '', [1 => '同意', 0 => '不同意'], ['onChange' => 'showContent("' . $field . 'isAgree' . '","' . $field . '","'.$model[$field.'content'].'")', 'class' => 'radiolist' . $field]);
						echo '</td>';
						echo '<td align="center" width="10%" id="' . $field . 'isAgree' . '-text">';
						echo '</td>';
						echo '<td>';
						echo Html::dropDownList($field.'undo', '', Reviewprocess::showProccessList($process), ['class' => 'form-control', 'id' => $field.'Undo']);
						echo '</td>';
						echo '<td id="' . $field . 'isAgree' . '-add">';
						echo "</td>";
						echo '</tr>';
						echo '</table>';
						echo '</td>';
					} else {
						if($lists) {
							$html .= '<td colspan="7" align="left">';
							$html .= '<table class="table">';
							foreach ($lists as $key => $list) {
								$html .= '<tr>';
								$html .= '<td width="100">';

								$html .= Html::radioList($key, $classdata[$key], ['否', '是'], ['onChange' => 'showContent("' . $key . '","' . $field . '","' . $classdata[$key . 'content'] . '")', 'class' => 'radiolist']);
								$html .= '</td>';
								$html .= '<td colspan="2" align="left" width="40%">';
								$html .= '&nbsp;&nbsp;' . $list;
								$html .= '</td>';
								$html .= '<td id="' . $key . '-add" colspan="2">';
								if (self::yesOrNo($classdata[$key], $key)) {
									$html .= Html::textarea($key . 'content', $classdata[$key . 'content'], ['id' => $key . 'content', 'rows' => 1, 'cols' => 50, 'class' => "isText form-control"]);
								}
								$html .= "</td>";
								$html .= '</tr>';
							}
							$html .= '<tr>';
							$html .= '<td width="150">';
							$html .= Html::radioList($field . 'isAgree', '', [1 => '同意', 0 => '不同意'], ['onChange' => 'showContent("' . $field . 'isAgree' . '","' . $field . '","' . $model[$field . 'content'] . '")', 'class' => 'radiolist' . $field]);
							$html .= '</td>';
							$html .= '<td align="center" width="10%" id="' . $field . 'isAgree' . '-text">';
							$html .= '</td>';
							$html .= '<td>';
							$html .= Html::dropDownList($field .'undo', '', Reviewprocess::showProccessList($process), ['class' => 'form-control', 'id' => $field .'Undo']);
							$html .= '</td>';
							$html .= '<td id="' . $field . 'isAgree' . '-add">';
							$html .= "</td>";
							$html .= '</tr>';
							$html .= '</table>';
							$html .= '</td>';
						}
					}
				} else {
					if($lists) {
						if ($classdata) {
							$html .= '<td colspan="7" align="left">';
							$html .= '<font color="#00CC66">';
							$html .= '<table>';
							foreach ($lists as $key => $list) {
								if (!strstr($key, 'isAgree')) {
									$html .= '<tr>';
									$html .= '<td>';
									if ($classdata[$key]) {
										$html .= '<strong>是<i class="fa fa-check-square-o"></i></strong>&nbsp;&nbsp;';
										$html .= '<strong>否<i class="fa fa-square-o"></i></strong>';
									} else {
										$html .= '<strong>是<i class="fa fa-square-o"></i></string>&nbsp;&nbsp;';
										$html .= '<strong>否<i class="fa fa-check-square-o"></i></strong>';
									}
									$html .= '</td>';
									$html .= '<td>';
									$html .= '&nbsp;&nbsp;' . $list;
									$html .= '</td>';
									$html .= '<td>';
									if ($classdata[$key . 'content'])
										$html .= '&nbsp;&nbsp;<font color="red"><strong>情况说明：' . $classdata[$key . 'content'] . '</strong></font>';
									$html .= "</td>";
									$html .= '</tr>';
								}
							}
							$html .= '</table>';

							$html .= '</font>';
							$html .= '</td>';
						}
					}
				}
				$html .= '<tr>';
				$html .= '<td colspan="7" align="left" >';
				$html .= '<font color="#0033FF"><strong>';
				$html .= self::state($model->$field);
				$html .= '</strong></font>';
				$html .= "<br>";
				$content = $field.'content';
				if($model->$content and ($model->state == 8 or $model->state == 9)) {
					$html .= '<font color="#FF0000"><strong>';
					$modelStr = $field.'content';
					$department = Processname::getDepartment($field,$model);;
					$html .= $department."<br>原由：".$model->$content;
					$html .= '</strong></font>';
				}
				$html .= '</td>';
				$html .= '</tr>';
				break;
			case 3:
				$html .= '<tr>';
				$html .= '<td colspan="7" align="left" >';
				$html .= '<font color="#0033FF"><strong>';
				$html .= self::state($model->$field);
				$html .= '</strong></font>';
				$html .= "<br>";
				$content = $field.'content';
				if($model->$content and ($model->state == 8 or $model->state == 9)) {
					$html .= '<font color="#FF0000"><strong>';
					$department = Processname::getDepartment($field,$model);;
					$html .= $department."<br>原由：".$model->$content;

					$html .= '</strong></font>';
				}
				$html .= '</td>';
				$html .= '</tr>';
				break;
			case 8:
				$undo = self::isUndo($field,$model);
//				var_dump(self::isRole($model->undo));
				if (self::isRole($field) and Yii::$app->controller->action->id !== 'reviewprocessview') {
					$html .= '<td colspan="7" align="left">';
					$html .= '<table class="table">';
					foreach ($lists as $key => $list) {
						$html .= '<tr>';
						$html .= '<td width="100">';
						$html .= Html::radioList($key, $classdata[$key], ['否', '是'], ['onChange' => 'showContent("' . $key . '","' . $undo . '","'.$classdata[$key.'content'].'")', 'class' => 'radiolist']);
						$html .= '</td>';
						$html .= '<td colspan="2" align="left" width="40%">';
						$html .= '&nbsp;&nbsp;' . $list;
						$html .= '</td>';
						$html .= '<td id="' . $key . '-add" colspan="2">';
						if(self::yesOrNo($classdata[$key],$key)) {
							$html .= Html::textarea($key.'content',$classdata[$key.'content'],['id'=>$key.'content','rows'=>1, 'cols'=>50, 'class'=>"isText form-control"]);
						}
						$html .= "</td>";
						$html .= '</tr>';
					}
					$html .= '<tr>';
					$html .= '<td width="150">';
					$html .= Html::radioList($undo . 'isAgree', '', [1 => '同意', 0 => '不同意'], ['onChange' => 'showContent("' . $undo . 'isAgree' . '","' . $undo . '","'.$model[$undo.'content'].'")', 'class' => 'radiolist' . $undo]);
					$html .= '</td>';
					$html .= '<td align="center" width="10%" id="' . $undo . 'isAgree' . '-text">';
					$html .= '</td>';
					$html .= '<td>';
					$html .= Html::dropDownList($field.'undo', $classdata[$field.'undo'], Reviewprocess::showProccessList($process), ['class' => 'form-control', 'id' => $field.'Undo']);
					$html .= '</td>';
					$html .= '<td id="' . $undo . 'isAgree' . '-add">';
					$html .= "</td>";
					$html .= '</tr>';
					$html .= '</table>';
					$html .= '</td>';
				} else {
					if ($lists) {
						if ($classdata) {
							$html .= '<td colspan="7" align="left">';
							$html .= '<font color="#00CC66">';
							$html .= '<table>';
							foreach ($lists as $key => $list) {
								if (!strstr($key, 'isAgree')) {
									$html .= '<tr>';
									$html .= '<td>';
									if ($classdata[$key]) {
										$html .= '<strong>是<i class="fa fa-check-square-o"></i></strong>&nbsp;&nbsp;';
										$html .= '<strong>否<i class="fa fa-square-o"></i></strong>';
									} else {
										$html .= '<strong>是<i class="fa fa-square-o"></i></string>&nbsp;&nbsp;';
										$html .= '<strong>否<i class="fa fa-check-square-o"></i></strong>';
									}
									$html .= '</td>';
									$html .= '<td>';
									$html .= '&nbsp;&nbsp;' . $list;
									$html .= '</td>';
									$html .= '<td>';
									if ($classdata[$key . 'content'])
										$html .= '&nbsp;&nbsp;<font color="red"><strong>情况说明：' . $classdata[$key . 'content'] . '</strong></font>';
									$html .= "</td>";
									$html .= '</tr>';
								}
							}
							$html .= '</table>';

							$html .= '</font>';
							$html .= '</td>';
						}
					}
				}
				$html .= '<tr>';
				$html .= '<td colspan="7" align="left" >';
				$html .= '<font color="#0033FF"><strong>';
				$html .= self::state($model->$field);
				$html .= '</strong></font>';
				$html .= "<br>";
				$content = $field.'content';
				if($model->$content and ($model->state == 8 or $model->state == 9)) {
					$html .= '<font color="#FF0000"><strong>';
					$department = Processname::getDepartment($field,$model);
					if($department)
						$html .= $department."<br>原由：".$model->$content;
					$html .= '</strong></font>';
				}
				$html .= '</td>';
				$html .= '</tr>';
				break;

		}
		$html .= '</td>';
		return $html;
	}

	public static function yesOrNo($value,$key)
	{
		if ($key == 'isdydk' or $key == 'sfdj' or $key == 'isqcbf' or $key == 'other' or $key == 'sfyzy') {
			if($value)
				return true;
			else
				return false;
		} else {
			if($value === 0) {
				return true;
			}
			else {
				return false;
			}
		}
	}
	
    public static function state($num,$reviewprocess=null,$process=null)
    {
		$html = '';
		$fromundolast = '';
		$undolast = '';
		if($reviewprocess) {
			if ($reviewprocess['fromundo']) {
				$fromundoArray = explode(',', $reviewprocess['fromundo']);
				$fromundolast = end($fromundoArray);
			}
			if ($reviewprocess['undo']) {
				$undoArray = explode(',', $reviewprocess['undo']);
				$undolast = end($undoArray);
			}
		}
//    	if($reviewprocess['sate'] === null)
//			return null;
    	$stateArray = [3=>'排队等待',2=>'待审核',1=>'同意',0=>'不同意',-1=>'无',4=>'审核中',7=>'通过',5=>'审核未通过',6=>'合同未领取',8=>'退回',9=>'撤消',21=>'重新审核',10=>'被退回'];
		switch ($num) {
			case 3:
				$html = '<font color="blue">'.$stateArray[$num].'</font>';
//				if($fromundolast == $process) {
//					$html = '<font color="red">'.$stateArray[8].'</font>';
//				}
//				if($undolast == $process) {
//					$html = '<font color="red">'.$stateArray[10].'</font>';
//				}
				break;
			case 2:
				$html = '<font color="blue">'.$stateArray[$num].'</font>';
//				if($fromundolast == $process) {
//					$html = '<font color="red">'.$stateArray[8].'</font>';
//				}
//				if($undolast == $process) {
//					$html = '<font color="red">'.$stateArray[10].'</font>';
//				}
				break;
			case 1:
				$html = '<font color="green">'.$stateArray[$num].'</font>';
//				if($fromundolast == $process) {
//					$html = '<font color="red">'.$stateArray[8].'</font>';
//				}
//				if($undolast == $process) {
//					$html = '<font color="red">'.$stateArray[10].'</font>';
//				}
				break;
			case 0:
				$html = '<font color="red">'.$stateArray[$num].'</font>';
				if($reviewprocess['state'] == 9) {
					$html = '<font color="red">'.$stateArray[9].'</font>';
				}
				break;
			case 4:
				$html = '<font color="#8a2be2">'.$stateArray[$num].'</font>';
//				if($fromundolast == $process) {
//					$html = '<font color="red">'.$stateArray[8].'</font>';
//				}
//				if($undolast == $process) {
//					$html = '<font color="red">'.$stateArray[10].'</font>';
//				}
				break;
			case 7:
				$html = '<font color="green">'.$stateArray[$num].'</font>';
//				if($fromundolast == $process) {
//					$html = '<font color="red">'.$stateArray[8].'</font>';
//				}
//				if($undolast == $process) {
//					$html = '<font color="red">'.$stateArray[10].'</font>';
//				}
				break;
			case 5:
				$html = '<font color="red">'.$stateArray[$num].'</font>';
				if($fromundolast == $process) {
					$html = '<font color="red">'.$stateArray[8].'</font>';
				}
				if($undolast == $process) {
					$html = '<font color="red">'.$stateArray[10].'</font>';
				}
				break;
			case 6:
				$html = '<font color="black">'.$stateArray[$num].'</font>';
//				if($fromundolast == $process) {
//				$html = '<font color="red">'.$stateArray[8].'</font>';
//			}
//				if($undolast == $process) {
//					$html = '<font color="red">'.$stateArray[10].'</font>';
//				}
				break;
			case 8:
				$html = '<font color="red">'.$stateArray[$num].'</font>';
				if($fromundolast == $process) {
					$html = '<font color="red">'.$stateArray[8].'</font>';
				}
				if($undolast == $process) {
					$html = '<font color="red">'.$stateArray[10].'</font>';
				}
				break;
			case 9:
				$html = '<font color="red">'.$stateArray[$num].'</font>';
//				if($fromundolast == $process) {
//					$html = '<font color="red">'.$stateArray[9].'</font>';
//				}
				if($reviewprocess[$process] == 0) {
					$html = '<font color="red">'.$stateArray[9].'</font>';
				}
//				if($undolast == $process) {
//					$html = '<font color="red">'.$stateArray[10].'</font>';
//				}
				break;
		}
    	return $html;
    }
	//得到当前流程状态
	public static function getState($reviewprocess_id)
	{
		if(empty($reviewprocess_id))
			return '待办理';
		$model = Reviewprocess::findOne($reviewprocess_id);
		return self::state($model->state);
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
//		if(User::getItemname()) {
//			return true;
//		}
//		var_dump($auditprocess_id);exit;
    	$process = Reviewprocess::getProcess($auditprocess_id);
//		var_dump($process);exit;
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
//     	var_dump($process);exit;
    	foreach ($process as $p) {
//     		if(self::getProcessRole($p)['rolename'] == User::getItemname() or self::getProcessRole($p)['sparerole'] == User::getItemname())
			if($temp) {
				$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
				if(in_array($userinfo['department_id'],explode(',',self::getProcessRole($p)['department_id'])) and self::getProcessRole($p)['level_id'] == $userinfo['level'])
					return true;
			} else {
//				var_dump(self::getProcessRole($p));
				if(in_array(Yii::$app->getUser()->getIdentity()->department_id,explode(',',self::getProcessRole($p)['department_id'])) and self::getProcessRole($p)['level_id'] == Yii::$app->getUser()->getIdentity()->level)
	    			return true;
			}
    	}
//		exit;
    	return false;
    }
   
    
    //保存流程
    public static function processRun($auditprocess_id,$oldfarms_id=NULL,$newfarms_id=null,$ttpozongdi_id = NULL,$samefarms_id=null)
    { 	
    	$auditprocess = Auditprocess::find()->where(['id'=>$auditprocess_id])->one();
//     	var_dump($auditprocess);exit;
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
		$reviewprocessModel->samefarms_id = $samefarms_id;
		$reviewprocessModel->undo = '';
//		$reviewprocessModel->estate = 1;
//     	var_dump($reviewprocessModel->attributes);exit;
    	for($i=0;$i<count($processs);$i++) {
			$str = $processs[$i];
    		if($str == 'estate')
    			$reviewprocessModel->$str = 2;
    		else
    			$reviewprocessModel->$str = 3;
    		if($str == 'leader') {
				if ($i == 0)
					$reviewprocessModel->$str = 2;
				else
					$reviewprocessModel->$str = 3;
			}
			if($str == 'project') {
				$reviewprocessModel->$str = 2;
			}
			if(Yii::$app->controller->action->id == 'loancreate') {
				$reviewprocessModel->estate = 1;
				$reviewprocessModel->mortgage = 2;
			}
    	}
		
    	$reviewprocessModel->state = 4;
		if($auditprocess->actionname == 'loancreate') {
			$reviewprocessModel->estatetime = time();
		}
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
    public static function getUserProcessCountHtml($state)
    {
		$processRows = 0;
		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$whereArray = Farms::getUserManagementArea($temp['user_id']);
		} else {
			$whereArray = Farms::getManagementArea();
		}
		$result = false;
		switch ($state) {
			//待办任务
			case 0:
				$processRows = self::getUserProcessCount(0);
				if($processRows)
					$result = $processRows;
				break;
			//审核任务
			case 2:
				$processRows = self::getUserProcessCount(2);
				if($processRows)
					$result = $processRows;
				break;
			//正在办理
			case 4:
				$processRows = self::getUserProcessCount(4);
				if($processRows)
					$result = $processRows;
				break;
			//已完成
			case 6:
				$processRows = self::getUserProcessCount(6);
				if($processRows)
					$result = $processRows;
				break;
			case 7:
				$processRows = self::getUserProcessCount(7);
				if($processRows)
					$result = $processRows;
				break;
			//退回
			case 8:
				$processRows = self::getUserProcessCount(8);
				if($processRows)
					$result = $processRows;
				break;
			case 9:
				$processRows = self::getUserProcessCount(9);
				if($processRows)
					$result = $processRows;
				break;
		}

    	return $result;
    }
	public static function getUserProcessCount($state)
	{
		$processRows = 0;
		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$whereArray = Farms::getUserManagementArea($temp['user_id']);
		} else {
			$whereArray = Farms::getManagementArea();
		}
		$result = false;
		$where = [];
		$orWhere = [];
		switch ($state) {
			//待办任务
			case 0:
				if(User::getItemname('主任') or User::getItemname('副主任') or User::getItemname('地产科'))
					$processRows += Ttpozongdi::find()->where(['state'=>0,'management_area'=>$whereArray['id']])->count();
				break;
			//审核任务
			case 2:
				if(Auditprocess::isAuditing('承包经营权转让')) {
					$reviewprocess = Reviewprocess::find();
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$reviewprocess->orWhere(['management_area' => $whereArray['id'], 'actionname'=>'farmstransfer',$proces => $state, 'state' => 4]);
							}
							$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
// 					var_dump(User::getItemname());

					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
					if ($processnames) {
						$where = [];
						foreach ($processnames as $proces) {
							$reviewprocess->orWhere(['management_area' => $whereArray['id'],'actionname'=>'farmstransfer', $proces => $state, 'state' => 4]);
						}
						$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
				}

				if(Auditprocess::isAuditing('项目审核')) {
					$reviewprocess = Reviewprocess::find();
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$reviewprocess->orWhere(['management_area' => $whereArray['id'], 'actionname'=>'projectapplication',$proces => $state, 'state' => 4]);
							}
							$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
// 					var_dump(User::getItemname());

					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
					if ($processnames) {
						$where = [];
						foreach ($processnames as $proces) {
							$reviewprocess->orWhere(['management_area' => $whereArray['id'],'actionname'=>'projectapplication', $proces => $state, 'state' => 4]);
						}
						$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
//					var_dump($reviewprocess->where);
				}
				if(Auditprocess::isAuditing('贷款冻结审批')) {
					$reviewprocess = Reviewprocess::find();
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$reviewprocess->orWhere(['management_area' => $whereArray['id'], 'actionname'=>'loancreate',$proces => $state, 'state' => 4]);
							}
							$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
// 					var_dump(User::getItemname());

					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
					if ($processnames) {
						$where = [];
						foreach ($processnames as $proces) {
							$reviewprocess->orWhere(['management_area' => $whereArray['id'],'actionname'=>'loancreate', $proces => $state, 'state' => 4]);
						}
						$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
				}
				break;
			//正在办理
			case 4:
				if(Auditprocess::isAuditing('承包经营权转让')) {
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
//					$processnames = Processname::find()->where(['rolename'=>User::getItemname()])->all();
//						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
//						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
//						if ($processnames) {
//							foreach ($processnames as $proces) {
//								$orWhere[] = ['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer','state' => 4];
//								$orWhere[] = ['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer',$proces => 0, 'state' => 8];
//								$orWhere[] = ['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer',$proces => 8, 'state' => 8];
////								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'farmstransfer', $proces => 1, 'state' => 4];
////								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'farmstransfer', $proces => 8, 'state' => 8];
//							}
							$processModel = Reviewprocess::find();
//							foreach ($orWhere as $or) {
//								$processModel->orWhere($or);
//							}
//
//						}
						$processRows += $processModel->where(['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer','state' => [4,8]])->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
//					if ($processnames) {
//						$orWhere = [];
//						foreach ($processnames as $proces) {
//							if (User::getItemname('地产科') or User::getItemname('管委会领导')) {
//								$orWhere[] = ['management_area' => $whereArray['id'], 'actionname'=>'farmstransfer','state' => 4];
//								$orWhere[] = ['management_area' => $whereArray['id'], 'actionname'=>'farmstransfer','state' => 8];
//							} else {
//								$orWhere[] = ['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer','state' => 4];
//								$orWhere[] = ['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer',$proces => 0, 'state' => 8];
//								$orWhere[] = ['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer',$proces => 8, 'state' => 8];
//							}
//						}
						$processModel = Reviewprocess::find();
//						foreach ($orWhere as $or) {
//							$processModel->orWhere($or);
//						}
						$processRows += $processModel->where(['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer','state' => [4,8]])->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
//					}
				}
				if(Auditprocess::isAuditing('项目审核')) {
//					var_dump($processRows);
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
//					$processnames = Processname::find()->where(['rolename'=>User::getItemname()])->all();
//						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
//						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
//						if ($processnames) {
//							foreach ($processnames as $proces) {
//								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'projectapplication', $proces => 1, 'state' => 4];
//								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'projectapplication', $proces => 8, 'state' => 8];
//							}
							$processModel = Reviewprocess::find();
//							foreach ($orWhere as $or) {
//								$processModel->orWhere($or);
//							}
//							$processRows += $processModel->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
//						}
						$processRows += $processModel->where(['management_area'=>$whereArray['id'],'actionname'=>'projectapplication','state' => [4,8]])->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
//					var_dump($processRows);
//					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
//					if ($processnames) {
//						$orWhere = [];
//						foreach ($processnames as $proces) {
//							if (User::getItemname()) {
//								$orWhere[] = ['management_area' => $whereArray['id'], 'state' => 4];
//								$orWhere[] = ['management_area' => $whereArray['id'], 'state' => 8];
//							} else {
//								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'projectapplication','state' => 4];
//								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'projectapplication','state' => 8];
//							}
//						}
//
//						foreach ($orWhere as $or) {
							$processModel = Reviewprocess::find();
//							$processModel->orWhere($or);
////							var_dump($processModel->where);
////							var_dump($processModel->count());
//
////							var_dump($processRows);
//						}
//						$processRows += $processModel->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
//					}

//					foreach ($processModel->all() as $value) {
//						var_dump($value['id']);
//					}
					$processRows += $processModel->where(['management_area'=>$whereArray['id'],'actionname'=>'projectapplication','state' => [4,8]])->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
				}
				if(Auditprocess::isAuditing('贷款冻结审批')) {
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
//					$processnames = Processname::find()->where(['rolename'=>User::getItemname()])->all();
//						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
//						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
//						if ($processnames) {
//							foreach ($processnames as $proces) {
//								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'loancreate', $proces => 1, 'state' => 4];
//								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'loancreate', $proces => 8, 'state' => 8];
//							}
							$processModel = Reviewprocess::find();
//							foreach ($orWhere as $or) {
//								$processModel->orWhere($or);
//							}
//							$processRows += $processModel->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
//						}
						$processRows += $processModel->where(['management_area'=>$whereArray['id'],'actionname'=>'loancreate','state' => [4,8]])->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
//					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
//					if ($processnames) {
//						$orWhere = [];
//						foreach ($processnames as $proces) {
//							if (User::getItemname()) {
//								$orWhere[] = ['management_area' => $whereArray['id'], 'state' => 4];
//								$orWhere[] = ['management_area' => $whereArray['id'], 'state' => 8];
//							} else {
//								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'loancreate', $proces => 1, 'state' => 4];
//								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'loancreate', $proces => 8, 'state' => 8];
//							}
//						}
						$processModel = Reviewprocess::find();
//						foreach ($orWhere as $or) {
//							$processModel->orWhere($or);
//						}
//						$processRows += $processModel->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
//					}
					$processRows += $processModel->where(['management_area'=>$whereArray['id'],'actionname'=>'loancreate','state' => [4,8]])->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
				}
				break;
			//已经完成
			case 6:
//				var_dump(User::getItemname());
				if(Auditprocess::isAuditing('承包经营权转让')) {
					$orWhere = [];
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
//					$processnames = Processname::find()->where(['rolename'=>User::getItemname()])->all();
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'farmstransfer', $proces => 1, 'state' => 6];
								$orWhere[] = ['management_area' => $whereArray['id'], 'actionname'=>'farmstransfer',$proces => 1, 'state' => 7];
							}
							$processModel = Reviewprocess::find();
							foreach ($orWhere as $or) {
								$processModel->orWhere($or);
							}
							$processRows += $processModel->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
					$orWhere = [];
					if (User::getItemname()) {
//						$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'farmstransfer',  'state' => 6];
//						$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'farmstransfer',  'state' => 7];
						$processModel = Reviewprocess::find()->andFilterWhere(['management_area' => $whereArray['id'],'actionname'=>'farmstransfer',  'state' => [6,7]]);
					} else {
						$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
						if ($processnames) {
							foreach ($processnames as $proces) {
//								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'farmstransfer', $proces => 1, 'state' => 6];
//								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'farmstransfer', $proces => 1, 'state' => 7];
								$processModel = Reviewprocess::find()->andFilterWhere(['management_area' => $whereArray['id'],$proces => 1,'actionname'=>'farmstransfer',  'state' => [6,7]]);
							}
						}
					}
					$processRows += $processModel->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
				}
				if(Auditprocess::isAuditing('项目审核')) {
					$orWhere = [];
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'projectapplication', $proces => 1, 'state' => 7];
							}
							$processModel = Reviewprocess::find();
							foreach ($orWhere as $or) {
								$processModel->orWhere($or);
							}
							$processRows += $processModel->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
					$orWhere = [];
					if (User::getItemname()) {
						$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'projectapplication',  'state' => 7];
					} else {
						$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'projectapplication', $proces => 1, 'state' => 7];
							}
						}
					}
					$processModel = Reviewprocess::find();
					foreach ($orWhere as $or) {
						$processModel->orFilterWhere($or);
						$processRows += $processModel->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}

//					var_dump($processModel->where);
				}
				if(Auditprocess::isAuditing('贷款冻结审批')) {
					$orWhere = [];
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'loancreate', $proces => 1, 'state' => 7];
							}
							$processModel = Reviewprocess::find();
							foreach ($orWhere as $or) {
								$processModel->orWhere($or);
							}
							$processRows += $processModel->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
					$orWhere = [];
					if (User::getItemname()) {
						$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'loancreate',  'state' => 7];
					} else {
						$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
//						var_dump($processnames);exit;
						if ($processnames) {
							foreach ($processnames as $proces) {
								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'loancreate', $proces => 1, 'state' => 7];
							}
						}
					}
					$processModel = Reviewprocess::find();
					foreach ($orWhere as $or) {
						$processModel->orWhere($or);
					}
//					var_dump($processModel->where);
					$processRows += $processModel->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
				}
				break;
			//已完成
			case 7:
				if(Auditprocess::isAuditing('承包经营权转让')) {
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					$where['management_area'] = $whereArray['id'];
					if ($temp2) {
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$where[$proces] = 1;
							}
							$where['actionname'] = 'farmstransfer';
							$where['state'] = $state;
							$processRows += Reviewprocess::find()->where($where)->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
					if ($processnames) {
						$where['management_area'] = $whereArray['id'];
						foreach ($processnames as $proces) {
							$where[$proces] = 1;
						}
						$where['actionname'] = 'farmstransfer';
						$where['state'] = $state;
						$processRows += Reviewprocess::find()->where($where)->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
				}


				if(Auditprocess::isAuditing('项目审核')) {
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					$where['management_area'] = $whereArray['id'];
					if ($temp2) {
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$where[$proces] = 1;
							}
							$where['actionname'] = 'projectapplication';
							$where['state'] = $state;
							$processRows += Reviewprocess::find()->where($where)->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
					if ($processnames) {
						$where['management_area'] = $whereArray['id'];
						foreach ($processnames as $proces) {
							$where[$proces] = 1;
						}
						$where['actionname'] = 'projectapplication';
						$where['state'] = $state;
						$processRows += Reviewprocess::find()->where($where)->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
				}
				if(Auditprocess::isAuditing('贷款冻结审批')) {
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					$where['management_area'] = $whereArray['id'];
					if ($temp2) {
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$where[$proces] = 1;
							}
							$where['actionname'] = 'loancreate';
							$where['state'] = $state;
							$processRows += Reviewprocess::find()->where($where)->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
					if ($processnames) {
						$where['management_area'] = $whereArray['id'];
						foreach ($processnames as $proces) {
							$where[$proces] = 1;
						}
						$where['actionname'] = 'loancreate';
						$where['state'] = $state;
						$processRows += Reviewprocess::find()->where($where)->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
				}
				break;
			//退回
			case 8:
				if(Auditprocess::isAuditing('承包经营权转让')) {
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'farmstransfer', $proces => $state, 'state' => $state];
							}
							$reviewprocess = Reviewprocess::find();
							foreach ($orWhere as $or) {
								$reviewprocess->orWhere($or);
							}
							$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
					if ($processnames) {
						$orWhere = [];
						foreach ($processnames as $proces) {
							$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'farmstransfer', $proces => $state, 'state' => $state];
						}
						$reviewprocess = Reviewprocess::find();
						foreach ($orWhere as $or) {
							$reviewprocess->orWhere($or);
						}
						$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
				}
				if(Auditprocess::isAuditing('项目审核')) {
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'projectapplication', $proces => $state, 'state' => $state];
							}
							$reviewprocess = Reviewprocess::find();
							foreach ($orWhere as $or) {
								$reviewprocess->orWhere($or);
							}
							$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
					if ($processnames) {
						$orWhere = [];
						foreach ($processnames as $proces) {
							$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'projectapplication', $proces => $state, 'state' => $state];
						}
						$reviewprocess = Reviewprocess::find();
						foreach ($orWhere as $or) {
							$reviewprocess->orWhere($or);
						}
						$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
				}
				if(Auditprocess::isAuditing('贷款冻结审批')) {
					$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
					if ($temp2) {
						$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
						$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
						if ($processnames) {
							foreach ($processnames as $proces) {
								$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'loancreate', $proces => $state, 'state' => $state];
							}
							$reviewprocess = Reviewprocess::find();
							foreach ($orWhere as $or) {
								$reviewprocess->orWhere($or);
							}
							$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
						}
					}
					$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
					if ($processnames) {
						$orWhere = [];
						foreach ($processnames as $proces) {
							$orWhere[] = ['management_area' => $whereArray['id'],'actionname'=>'loancreate', $proces => $state, 'state' => $state];
						}
						$reviewprocess = Reviewprocess::find();
						foreach ($orWhere as $or) {
							$reviewprocess->orWhere($or);
						}
						$processRows += $reviewprocess->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
					}
				}
				break;
			case 9:
				if(Auditprocess::isAuditing('承包经营权转让')) {
						$processRows += Reviewprocess::find()->where(['management_area' => $whereArray['id'],'actionname'=>'farmstransfer', 'state' => $state])->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
				}
				if(Auditprocess::isAuditing('项目审核')) {

						$processRows += Reviewprocess::find()->where(['management_area' => $whereArray['id'],'actionname'=>'projectapplication', 'state' => $state])->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();

				}
				if(Auditprocess::isAuditing('贷款冻结审批')) {

						$processRows += Reviewprocess::find()->where(['management_area' => $whereArray['id'],'actionname'=>'loancreate', 'state' => $state])->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->count();
				}
				break;
		}

		return $processRows;
	}
	public static function getUserProcessAllCount()
	{
		$result = self::getUserProcessCount(2) + self::getUserProcessCount(0) + self::getUserProcessCount(8);
		if($result)
			return $result;
		else
			return false;
	}

	public static function showProccessList($process,$model=null)
	{
		$result = [];
		$list = [];
		$processRolename = Processname::getProcessnameobj(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
		foreach ($process as $proces) {
			$list[$proces] = Processname::find()->where(['Identification'=>$proces])->one()['processdepartment'];
		}
//		var_dump($processRolename);exit;
		$result['estate'] = '地产科';
		foreach ($processRolename as $item) {
			switch ($item['processdepartment'])
			{
				case '财务科':
//					$result['estate'] = '地产科';
					break;
				case '档案审查':
//					$result['estate'] = '地产科';
					break;
				case '法规科':
//					$result['estate'] = '地产科';
					break;
				case '公安部门':
//					$result['estate'] = '地产科';
					break;
				case '抵押贷款审查':
//					$result['estate'] = '地产科';
					break;
				case '项目科':
//					$result['estate'] = '地产科';
					break;
				case '分管领导':
					if(empty($model)) {
						$result = $list;
					}
					else {
						if($model->actionname == 'projectapplication') {
							$result['estate'] = '地产科';
						}

					}
					if(!in_array('地产科',$result)) {
						$result = array_merge($result, ['estate' => '地产科']);
					}
					array_pop($result);
					break;
			}
		}

		$result['undo'] = '撤消申请';
		return $result;
	}

	//判断部分过户中,如果合同面积为0,则销户
	public static function isContractNumberZero($farms_id)
	{
		$model = Farms::findOne($farms_id);
		if($model->contractarea == 0) {
			$model->state = 0;
			$model->save();
			return true;
		}
		return false;
	}

	//贷款是否已经开始审核
	public static function loanISexamine($id)
	{
		$loan = Loan::findOne($id);
		$reviewprocess = Reviewprocess::find()->where(['id'=>$loan->reviewprocess_id])->one();
		if($reviewprocess['mortgage'] == 1)
			return true;
		else
			return false;

	}

	public static function getBtn($reviewprocess_id)
	{
		$html = '';
		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
			$processname = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
		} else {
			$processname = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
		}
		$model = Reviewprocess::findOne($reviewprocess_id);
		switch ($model->state) {
			case 4:
				$html = html::a('查看', ['reviewprocess/reviewprocessview', 'id' => $model->id, 'class' => $model->actionname], ['class' => 'btn btn-success']);
				foreach ($processname as $process) {
					if($model->$process == 2) {
						$html = html::a('审核', ['reviewprocess/reviewprocessinspections', 'id' => $model->id, 'class' => $model->actionname], ['class' => 'btn btn-danger']);
					}
				}
				break;
			case 8:
				$html = html::a('查看', ['reviewprocess/reviewprocessview', 'id' => $model->id, 'class' => $model->actionname], ['class' => 'btn btn-success']);
				foreach ($processname as $process) {
					if($model->$process == 8) {
						$html = html::a('审核', ['reviewprocess/reviewprocessinspections', 'id' => $model->id, 'class' => $model->actionname], ['class' => 'btn btn-danger']);
					}
				}
				break;
			default:
				$html = html::a('查看', ['reviewprocess/reviewprocessview', 'id' => $model->id, 'class' => $model->actionname], ['class' => 'btn btn-success']);
		}
		return $html;
	}

	public static function getRows($actionname,$begindate=null,$enddate=null)
	{
		switch (Yii::$app->controller->action->id) {
			case 'reviewprocessindex':
				$state = 4;
				$iden = 2;
				$class = 'label bg-red';
				break;
			case 'reviewprocessing':
				$state = 4;
				$iden = null;
				$class = 'label pull-right bg-green';
				break;
			case 'reviewprocesswait':
				$state = 0;
				$iden = null;
				$class = 'label pull-right bg-green';
				break;
			case 'reviewprocessfinished':
				$state = [6,7];
				$iden = null;
				$class = 'label pull-right bg-green';
				break;
			case 'reviewprocesscacle':
				$state = 9;
				$iden = null;
				$class = 'label pull-right bg-green';
				break;
			case 'reviewprocessreturn':
				$state = 8;
				$iden = 8;
				$class = 'label pull-right bg-red';
				break;
		}
		$processRows = 0;
		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$whereArray = Farms::getUserManagementArea($temp['user_id']);
		} else {
			$whereArray = Farms::getManagementArea();
		}
		$temp2 = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
		if ($temp2) {
			$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
			$processnames = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
			if ($processnames) {
				$reviewprocess = Reviewprocess::find();
				if($iden) {
					foreach ($processnames as $proces) {
						$orWhere[] = ['management_area' => $whereArray['id'], 'actionname' => $actionname, $proces => $iden, 'state' => $state];
					}
					foreach ($orWhere as $or) {
						$reviewprocess->orWhere($or);
					}
				} else {
					$reviewprocess->where(['management_area' => $whereArray['id'], 'actionname' => $actionname, 'state' => $state]);
				}
				if(empty($begindate)) {
					$processRows += $reviewprocess->andFilterWhere(['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]])->count();
				} else {
					$processRows += $reviewprocess->andFilterWhere(['between', 'create_at', $begindate, $enddate])->count();
				}
			}
		}
		$processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
		if ($processnames) {
			$reviewprocess = Reviewprocess::find();
			if($iden) {
				foreach ($processnames as $proces) {
					$orWhere[] = ['management_area' => $whereArray['id'], 'actionname' => $actionname, $proces => $iden, 'state' => $state];
				}
				foreach ($orWhere as $or) {
					$reviewprocess->orWhere($or);
				}
			} else {
				$reviewprocess->where(['management_area' => $whereArray['id'], 'actionname' => $actionname, 'state' => $state]);
			}
			if(empty($begindate)) {
				$processRows += $reviewprocess->andFilterWhere(['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]])->count();
			} else {
				$processRows += $reviewprocess->andFilterWhere(['between', 'create_at', $begindate, $enddate])->count();
			}
		}

		return '<small class="'.$class.'">'.$processRows.'</small>';
	}
}
