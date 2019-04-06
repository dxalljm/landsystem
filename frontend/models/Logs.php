<?php 
namespace app\models;

use frontend\helpers\MacAddress;
use Yii;
class Logs extends Log {
	
	public static function writeLog($description='',$objecId='',$oldAttributes='',$newAttributes='')
	{
		$log = new Log();
		$log->user_id = \Yii::$app->getUser()->id;
		$log->user_ip = \Yii::$app->getRequest()->getUserIP();
		$log->action = \Yii::$app->controller->id;
		$log->action_type = \Yii::$app->controller->action->id;;
		$log->object_name = Tables::find()->where(['tablename'=>\Yii::$app->controller->id])->one()['Ctablename'];
		$log->object_id = $objecId;
		$log->operate_time = time();
		$log->operate_desc = $description;
		$log->object_old_attr = serialize($oldAttributes);
		$log->object_new_attr = serialize($newAttributes);
		if($log->save())
			return true;
		else {
			print_r($log->getErrors());
			//\Yii::getLogger()->log(print_r($log->getErrors(),true), 'error','swp,Log');
			return false;
		}
	}

	public static function writeLogs($description,$model = NULL,$action=null)
	{
//		$mac = new MacAddress();
//		var_dump($description);
//		var_dump($model);exit;
		if(empty($model)) {
			$log = new Log();
			$log->user_id = \Yii::$app->getUser()->id;
			$log->user_ip = \Yii::$app->getRequest()->getUserIP();
//			$log->macadress = $mac->getMac();
			if($action) {
				$log->action = $action;
			} else {
				$log->action = \Yii::$app->controller->id;
			}
			$log->action_type = \Yii::$app->controller->action->id;;
			$log->object_name = Tables::find()->where(['tablename' => \Yii::$app->controller->id])->one()['Ctablename'];
//			$log->object_id = $model->id;
			$log->operate_time = time();
			$log->operate_desc = $description;
			$log->class = '';
//			$log->object_old_attr = serialize($model->oldattributes);
//			$log->object_new_attr = serialize($model->attributes);
			if ($log->save())
				return true;
			else {
				return false;
			}
		} else {
//			if(isset($model->id)) {
//				$objid = $model->id;
//			} else {
//				$objid = $model->
//			}
//			var_dump($model->getOldAttributes());exit;
//			var_dump($model);exit;
			$log = new Log();
			$log->user_id = \Yii::$app->getUser()->id;
			$log->user_ip = \Yii::$app->getRequest()->getUserIP();
//			$log->macadress = $mac->getMac();
			if($action) {
				$log->action = $action;
			} else {
				$log->action = \Yii::$app->controller->id;
			}
			$log->action_type = \Yii::$app->controller->action->id;;
			$log->object_name = Tables::find()->where(['tablename' => \Yii::$app->controller->id])->one()['Ctablename'];
			if($model->className() == 'app\models\Farmerinfo') {
				$log->object_id = $model->cardid;
			} else {
				$log->object_id = $model->id;
			}
			$log->operate_time = time();
			$log->operate_desc = $description;
			$log->class = $model->className();
			$log->object_old_attr = serialize($model->olddata);
			$log->object_new_attr = serialize($model->newdata);
			if ($log->save())
				return true;
			else {
				return false;
			}
		}
	}
}
