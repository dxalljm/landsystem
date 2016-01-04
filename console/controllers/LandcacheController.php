<?php

namespace console\controllers;

use yii\console\Controller;
use console\models\Farms;
use console\models\User;
use console\models\Cache;
use console\models\Plantingstructure;
use console\models\Collection;
use console\models\Huinonggrant;
use console\models\Huinong;
use console\models\Plantinputproduct;
use console\models\Projectapplication;
// use console\models\Projectapplication;
class LandcacheController extends Controller
{


	  
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
			$landcache->farmstitle = '面积：'.Farms::totalArea($id).' 农场户数：'.Farms::totalNum($id);
			$landcache->farmscategories = json_encode(Farms::getUserManagementAreaname($id));
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
			$landcache->collectiontitle = '应收：'. Collection::totalAmounts($id).' 实收：'.Collection::totalReal($id);
			$landcache->collectioncategories = json_encode(Farms::getUserManagementAreaname($id));
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
			$landcache->plantingstructuretitle = '作物面积：'.Plantingstructure::getPlantGoodseedSum($id)['plantSum'].'万亩&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;良种面积：'.Plantingstructure::getPlantGoodseedSum($id)['goodseedSum'].'万亩';
			$landcache->plantingstructurecategories = json_encode(Plantingstructure::getPlantname($id));
			$landcache->save();
		}
	}
	
	public function actionHuinongcache()
	{
		$allid = $this->getAllUser();
		foreach ($allid as $id) {
			if($cache = Cache::find()->where(['user_id'=>$id])->one())
				$landcache = Cache::findOne($cache->id);
			else
				$landcache = new Cache();
			$landcache->user_id = $id;
			$landcache->huinongcache = Huinonggrant::getHuinonggrantinfo($id);
			$landcache->huinongtitle = '惠农政策补贴发放情况';
			$landcache->huinongcategories = json_encode(Farms::getUserManagementAreaname($id));
			$landcache->save();
		}
	}
	
	public function actionPlantinputproductcache()
	{
		$allid = $this->getAllUser();
		foreach ($allid as $id) {
			if($cache = Cache::find()->where(['user_id'=>$id])->one())
				$landcache = Cache::findOne($cache->id);
			else
				$landcache = new Cache();
			$landcache->user_id = $id;
			$landcache->plantinputproductcache = Plantinputproduct::getInputproduct($id);
			$landcache->plantinputproducttitle = '投入品使用情况';
			$landcache->plantinputproductcategories = json_encode(Plantinputproduct::getTypenamelist($id));
			$landcache->save();
		}
	}
	
	public function actionProjectapplicationcache()
	{
		$allid = $this->getAllUser();
		foreach ($allid as $id) {
			if($cache = Cache::find()->where(['user_id'=>$id])->one())
				$landcache = Cache::findOne($cache->id);
			else
				$landcache = new Cache();
			$landcache->user_id = $id;
			$landcache->projectapplicationcache = Projectapplication::getProjectapplicationcache($id);
			$landcache->projectapplicationtitle = '项目情况';
			$landcache->projectapplicationcategories = json_encode(Projectapplication::getTypenamelist($id));
			$landcache->save();
		}
	}
	
}