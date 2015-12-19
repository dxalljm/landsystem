<?php

namespace console\controllers;

use yii\console\Controller;
use console\models\Farms;
use console\models\User;
use console\models\Cache;
use console\models\Plantingstructure;
use console\models\Collection;
class LandcacheController extends Controller
{

	public function actionIndex($str = NULL)
	{
	}
	  
	public function getAllUser()
	{
		$allUser = User::find()->all();
		foreach($allUser as $user) {
			if($user['id'] !== 1)
				$allID[] = $user['id'];
		}
		return $allID;
	}
	
	public function actionFarmscache()
	{
		$allid = $this->getAllUser();
		foreach ($allid as $id) {
			if($cache = Cache::find()->where(['user_id'=>$id])->one())
				$landcache = Cache::findOne($cache->id);
			else 
				$landcache = new Cache();
			$landcache->user_id = $id;
			$landcache->farmscache = Farms::getFarmarea($id);
			$landcache->save();
		}
	}
	public function actionCollectioncache()
	{
		$allid = $this->getAllUser();
		foreach ($allid as $id) {
			if($cache = Cache::find()->where(['user_id'=>$id])->one())
				$landcache = Cache::findOne($cache->id);
			else
				$landcache = new Cache();
			$landcache->user_id = $id;
			$landcache->collectioncache = Collection::getCollection($id);
			$landcache->save();
		}
	}
	public function actionPlantingstructurecache()
	{
		$allid = $this->getAllUser();
		foreach ($allid as $id) {
			if($cache = Cache::find()->where(['user_id'=>$id])->one())
				$landcache = Cache::findOne($cache->id);
			else
				$landcache = new Cache();
			$landcache->user_id = $id;
			$landcache->plantingstructurecache = Plantingstructure::getPlantingstructure($id);
			$landcache->save();
		}
	}
}