<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
class UpdatecacheController extends Controller
{
	
	public function actionUpdateall()
	{
		$msg[] = ['正在更新....'];
// 		exec('d:\wamp\www\landsystem\yii landcache/updateall '.\Yii::$app->getUser()->id,$array,$result);
		exec('d:\wamp\www\landsystem\yii landcache/farmscacheone '.\Yii::$app->getUser()->id,$array,$result);
		$msgarr[] = $array;
		$msg = $array;
		$this->render('updateall',['msg'=>$msg,'result'=>$result]);
		exec('d:\wamp\www\landsystem\yii landcache/huinongcacheone '.\Yii::$app->getUser()->id,$array,$result);
		$msg[] = $array;
		$this->render('updateall',['msg'=>$msg,'result'=>$result]);
		exec('d:\wamp\www\landsystem\yii landcache/plantingstructurecacheone '.\Yii::$app->getUser()->id,$array,$result);
		$msg[] = $array;
		$this->render('updateall',['msg'=>$msg,'result'=>$result]);
		exec('d:\wamp\www\landsystem\yii landcache/projectapplicationcacheone '.\Yii::$app->getUser()->id,$array,$result);
		$msg[] = $array;
		$this->render('updateall',['msg'=>$msg,'result'=>$result]);
		exec('d:\wamp\www\landsystem\yii landcache/yieldscacheone '.\Yii::$app->getUser()->id,$array,$result);
		$msg[] = $array;
		$this->render('updateall',['msg'=>$msg,'result'=>$result]);
		exec('d:\wamp\www\landsystem\yii landcache/projectapplicationcacheone '.\Yii::$app->getUser()->id,$array,$result);
		$msg[] = $array;
		return $this->render('updateall',['msg'=>$msg,'result'=>$result]);

	}

}