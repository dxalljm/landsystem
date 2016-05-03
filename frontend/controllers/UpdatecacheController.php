<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
class UpdatecacheController extends Controller
{
	
	public function actionUpdateall()
	{
// 		echo \Yii::$app->getUser()->id;exit;
		exec('d:\wamp\www\landsystem\yii landcache/updateall '.\Yii::$app->getUser()->id,$array,$result);
		return $this->render('updateall',['array'=>$array,'result'=>$result]);
	}

}