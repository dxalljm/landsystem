<?php

namespace console\controllers;
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
class LandcacheController extends Controller
{

	public function actionIndex()
	{
		elasticsearchtest::showdb();
		
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
// 	public function actionFarmscache()
// 	{
// // 		var_dump(Farmselastic::getDb());exit;
// 		set_time_limit ( 0 );
// 		$farms = Farms::find ()->all ();
// 		$attributes = Farmselastic::getAtt();
// 		foreach ( $farms as $farm ) {
// 			$elastic = new Farmselastic();
// 			foreach ( $attributes as $value ) {
// 				if ($value !== 'index' and $value !== 'type')
//     				$elastic->$value = $farm[$value];
//     		}
//     		$elastic->insert();
//     	}
// // 		var_dump(Farmselastic::index());
//     	echo 'insert done';
// 	}
	
	public function actionFarmsecharts()
	{
		
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
	
}