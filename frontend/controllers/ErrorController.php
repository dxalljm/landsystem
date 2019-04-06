<?php

namespace frontend\controllers;

use console\models\Farms;
use Yii;
use yii\web\Controller;
class ErrorController extends Controller
{
	
	public function actionError($msg)
	{

		return $this->render('error',['msg'=>$msg]);
	}
	public function beforeAction($action)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		} else {
			return true;
		}
	}
	public function actionNofarmsinfo($farms_id)
	{
		$farm = Farms::findOne($farms_id);
		return $this->render('nofarmsinfo',['farm'=>$farm]);
	}
}