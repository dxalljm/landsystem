<?php

namespace app\models;

use backend\controllers\MenutouserController;
use Yii;

/**
 * This is the model class for table "{{%auditprocess}}".
 *
 * @property integer $id
 * @property string $projectname
 * @property string $process
 * @property string $actionname
 */
class Auditprocess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auditprocess}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projectname', 'process', 'actionname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'projectname' => '项目名称',
            'process' => '流程',
            'actionname' => '方法名称',
        ];
    }
    
     public static function isShowProcess($projectname)
    {
    	$processname = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
// 		var_dump($processname);exit;
    	$rolenames = [];
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();

		foreach ($processname as $process) {
    		$rolenames[] = $process;
    	}
    	$auditprocess = self::find()->where(['projectname'=>$projectname])->one();
//		var_dump($rolenames);var_dump($auditprocess);exit;
//     	foreach($auditprocess as $value) {
    		foreach ($rolenames as $rolename) {
    			if(in_array($rolename, explode('>',$auditprocess['process'])))
    				return true;
    		}
    		
//     	}

    	if($temp)
    		return true;
    	else
    		return false;
    }

	public static function getAuditing()
	{
		$result = [];
		$m = MenuToUser::find()->where(['role_id'=>User::getItemname()])->one();
// 		var_dump($m);
		if($m['auditinguser']) {
			$mArr = explode(',', $m['auditinguser']);
			$audit = MenuToUser::getAuditingList();
			$result = [];
			foreach ($mArr as $value) {
				$result[] = $audit[$value];
			}
		}
		return $result;
	}

	public static function isAuditing($str)
	{
		$audit_id = Auditprocess::find()->where(['projectname'=>$str])->one()['id'];

		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
			if(in_array($audit_id,explode(',',$userinfo['auditinguser']))) {
				return true;
			} else {
				return false;
			}
		}
//		if($str == '贷款冻结审批' and yii::$app->controller->action->id == 'reviewprocessing') {
//			if(User::getItemname('地产科')) {
//				return true;
//			} else {
//				return false;
//			}
//		}
		if(in_array($audit_id,explode(',',Yii::$app->getUser()->getIdentity()->auditinguser))) {
			return true;
		} else {
			return false;
		}
	}
}
