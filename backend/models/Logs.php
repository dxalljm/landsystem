<?php 
namespace app\models;

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
}
