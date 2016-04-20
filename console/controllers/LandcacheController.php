<?php

namespace console\controllers;
use yii;
use yii\web\Application;
use console\models\elasticsearchtest;
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
use console\models\Farmselastic;
use console\models\Yields;
class LandcacheController extends Controller
{

	public function actionIndex()
	{
		elasticsearchtest::showdb();
		
	}
	
	public function actionUpdateall($user_id)
	{
// 		var_dump(\Yii::$app->getUser());exit;
		$this->farmscache($user_id);
		$this->collectioncache($user_id);
		$this->huinongcache($user_id);
		$this->plantingstructurecache($user_id);
		$this->projectapplicationcache($user_id);
		$this->yieldscache($user_id);
	}
	
	public function eInsert()
	{
		set_time_limit(0);
		$farms = Farms::find()->all();
		foreach ($farms as $farm) {
			$elastic = new Elasticsearch();
			$elastic->value = $farm;
			$elastic->insert();
		}
		echo 'done';
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
			$landcache->plantingstructuretitle = '作物面积：'.Plantingstructure::getPlantGoodseedSum($id)['plantSum'].'亩&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;良种面积：'.Plantingstructure::getPlantGoodseedSum($id)['goodseedSum'].'亩';
			$plant = Plantingstructure::getPlantname($id);
// 			var_dump($id);
// 			var_dump($plant);
			$landcache->plantingstructurecategories = json_encode($plant['plantname']);
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
// 		var_dump($allid);exit;
		foreach ($allid as $id) {
			if($cache = Cache::find()->where(['user_id'=>$id])->one())
				$landcache = Cache::findOne($cache->id);
			else
				$landcache = new Cache();
			$landcache->user_id = $id;
			$landcache->plantinputproductcache = Plantinputproduct::getInputproduct($id);
			$landcache->plantinputproducttitle = '投入品使用情况';
			$landcache->plantinputproductcategories = json_encode(Plantinputproduct::getTypenamelist($id)['typename']);
			$landcache->save();
		}
	}
	
	public function actionYieldscache()
	{
		$allid = $this->getAllUser();
		// 		var_dump($allid);exit;
		foreach ($allid as $id) {
			if($cache = Cache::find()->where(['user_id'=>$id])->one())
				$landcache = Cache::findOne($cache->id);
			else
				$landcache = new Cache();
			$landcache->user_id = $id;
			$landcache->plantinputproductcache = Yields::getUserYields($id);
			$landcache->plantinputproducttitle = '农产品产量情况';
			$landcache->plantinputproductcategories = json_encode(Yields::getUserTypenamelist($id)['typename']);
			$landcache->save();
		}
	}
	//
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
			$landcache->projectapplicationcategories = json_encode(Projectapplication::getTypenamelist($id)['projecttype']);
			$landcache->save();
		}
	}
	//以下为单个用户
	public function farmscache($user_id)
	{
		
		if($cache = Cache::find()->where(['user_id'=>$user_id])->one())
			$landcache = Cache::findOne($cache->id);
		else
			$landcache = new Cache();
		$landcache->user_id = $user_id;
		$landcache->farmscache = Farms::getFarmarea($user_id);
		$landcache->farmstitle = '面积：'.Farms::totalArea($user_id).' 农场户数：'.Farms::totalNum($user_id);
		$landcache->farmscategories = json_encode(Farms::getUserManagementAreaname($user_id));
		$landcache->save();
		echo 'farmscache finished|';
	}
	
	public function collectioncache($user_id)
	{
		if($cache = Cache::find()->where(['user_id'=>$user_id])->one())
			$landcache = Cache::findOne($cache->id);
		else
			$landcache = new Cache();
		$landcache->user_id = $user_id;
		$landcache->collectioncache = Collection::getCollection($user_id);
		$landcache->collectiontitle = '应收：'. Collection::totalAmounts($user_id).' 实收：'.Collection::totalReal($user_id);
		$landcache->collectioncategories = json_encode(Farms::getUserManagementAreaname($user_id));
		$landcache->save();
		echo 'collectioncache finished|';
	}
	public function plantingstructurecache($user_id)
	{
		if($cache = Cache::find()->where(['user_id'=>$user_id])->one())
			$landcache = Cache::findOne($cache->id);
		else
			$landcache = new Cache();
		$landcache->user_id = $user_id;
		$landcache->plantingstructurecache = Plantingstructure::getPlantingstructure($user_id);
		$landcache->plantingstructuretitle = '作物面积：'.Plantingstructure::getPlantGoodseedSum($user_id)['plantSum'].'亩&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;良种面积：'.Plantingstructure::getPlantGoodseedSum($user_id)['goodseedSum'].'亩';
		$plant = Plantingstructure::getPlantname($user_id);
		$landcache->plantingstructurecategories = json_encode($plant['plantname']);
		$landcache->save();
		echo 'plantingstructurecache finished|';
	}
	
	public function huinongcache($user_id)
	{
		if($cache = Cache::find()->where(['user_id'=>$user_id])->one())
			$landcache = Cache::findOne($cache->id);
		else
			$landcache = new Cache();
		$landcache->user_id = $user_id;
		$landcache->huinongcache = Huinonggrant::getHuinonggrantinfo($user_id);
		$landcache->huinongtitle = '惠农政策补贴发放情况';
		$landcache->huinongcategories = json_encode(Farms::getUserManagementAreaname($user_id));
		$landcache->save();
		echo 'huinongcache finished|';
	}
	
	public function plantinputproductcache($user_id)
	{
		if($cache = Cache::find()->where(['user_id'=>$user_id])->one())
			$landcache = Cache::findOne($cache->id);
		else
			$landcache = new Cache();
		$landcache->user_id = $user_id;
		$landcache->plantinputproductcache = Plantinputproduct::getInputproduct($user_id);
		$landcache->plantinputproducttitle = '投入品使用情况';
		$landcache->plantinputproductcategories = json_encode(Plantinputproduct::getTypenamelist($user_id)['typename']);
		$landcache->save();
		echo 'plantinputproductcache finished|';
	}
	
	public function yieldscache($user_id)
	{
		if($cache = Cache::find()->where(['user_id'=>$user_id])->one())
			$landcache = Cache::findOne($cache->id);
		else
			$landcache = new Cache();
		$landcache->user_id = $user_id;
		$landcache->plantinputproductcache = Yields::getUserYields($user_id);
		$landcache->plantinputproducttitle = '农产品产量情况';
		$landcache->plantinputproductcategories = json_encode(Yields::getUserTypenamelist($user_id)['typename']);
		$landcache->save();
		echo 'yieldscache finished|';
	}
	
	public function projectapplicationcache($user_id)
	{
		if($cache = Cache::find()->where(['user_id'=>$user_id])->one())
			$landcache = Cache::findOne($cache->id);
		else
			$landcache = new Cache();
		$landcache->user_id = $user_id;
		$landcache->projectapplicationcache = Projectapplication::getProjectapplicationcache($user_id);
		$landcache->projectapplicationtitle = '项目情况';
		$landcache->projectapplicationcategories = json_encode(Projectapplication::getTypenamelist($user_id)['projecttype']);
		$landcache->save();
		echo 'projectapplicationcache finished|';
	}
}