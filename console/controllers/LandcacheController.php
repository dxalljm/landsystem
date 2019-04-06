<?php

namespace console\controllers;
use yii;
use yii\web\Application;
use console\models\elasticsearchtest;
use yii\console\Controller;
use console\models\Farms;
use console\models\User;
use console\models\Cache;
use console\models\Plantingstructurecheck;
use console\models\Collection;
use console\models\Huinonggrant;
use console\models\Huinong;
use console\models\Plantinputproduct;
use console\models\Projectapplication;
use console\models\Farmselastic;
use console\models\Yields;
use console\models\Theyear;
use console\models\Insurance;
use console\models\Loan;
use app\models\Fireprevention;
class LandcacheController extends Controller
{

	public function actionIndex()
	{
		elasticsearchtest::showdb();
		
	}
	
	public function actionUpdateall($user_id)
	{
// 		var_dump(\Yii::$app->getUser());exit;
//		if(empty($this->farmscache($user_id)))
//			echo '农场面积数量图表更新完毕!';
//		if(empty($this->collectioncache($user_id)))
//			echo '承包费收缴图表更新完毕!';
//		if(empty($this->huinongcache($user_id)))
//			echo '惠农政策图表更新完毕!';
//		$this->plantingstructurecache($user_id);
//		$this->projectapplicationcache($user_id);
//		$this->yieldscache($user_id);
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
//		$allid = $this->getAllUser();
//		$i = 0;
//		foreach ($allid as $id) {
//			if($cache = Cache::find()->where(['user_id'=>$id,'year'=>date('Y')])->one())
//				$landcache = Cache::findOne($cache->id);
//			else
//				$landcache = new Cache();
//			$landcache->user_id = $id;
//			$landcache->farmscache = Farms::getFarmarea($id);
//			$landcache->farmstitle = '面积：'.Farms::totalArea($id).' 农场户数：'.Farms::totalNum($id);
//			$landcache->farmscategories = json_encode(Farms::getUserManagementAreaname($id));
//			$landcache->year = date('Y');
//			if($landcache->save())
//			    $i++;
//		}
//		echo 'finished Farms';
// 		var_dump($i);
	}
	
	public function actionFirecache()
	{
//		$allid = $this->getAllUser();
//// 		$i = 0;
//		foreach ($allid as $id) {
//			if($cache = Cache::find()->where(['user_id'=>$id,'year'=>date('Y')])->one())
//				$landcache = Cache::findOne($cache->id);
//			else
//				$landcache = new Cache();
//			$landcache->user_id = $id;
//// 			var_dump(Fireprevention::getBfblist($id));
//			$landcache->firecache = Fireprevention::getBfblist($id);
//// 			var_dump($landcache->firecache);
//			$landcache->firetitle = '防火完成度：'.Fireprevention::getAllbfb($id);
//			$landcache->firecategories = json_encode(Farms::getUserManagementAreaname($id));
//			$landcache->year = date('Y');
//			$landcache->save();
//// 			    $i++;
//// 			var_dump($landcache->getErrors());
//		}
//		echo 'finished fire';
	}
	
	public function actionCollectioncache()
	{
//		$allid = $this->getAllUser();
//		foreach ($allid as $id) {
//			if($cache = Cache::find()->where(['user_id'=>$id,'year'=>date('Y')])->one())
//				$landcache = Cache::findOne($cache->id);
//			else
//				$landcache = new Cache();
//			$landcache->user_id = $id;
//			$landcache->collectioncache = Collection::getCollection($id);
//			$landcache->collectiontitle = '应收：'. Collection::totalAmounts($id).' 实收：'.Collection::totalReal($id);
//			$landcache->collectioncategories = json_encode(Farms::getUserManagementAreaname($id));
//			$landcache->year = date('Y');
//			$landcache->save();
//		}
	}
	public function actionPlantingstructurecache()
	{
//		$allid = $this->getAllUser();
//		foreach ($allid as $id) {
//			if($cache = Cache::find()->where(['user_id'=>$id,'year'=>date('Y')])->one())
//				$landcache = Cache::findOne($cache->id);
//			else
//				$landcache = new Cache();
//			$landcache->user_id = $id;
//			$landcache->plantingstructurecache = Plantingstructurecheck::getPlantingstructure($id);
//			$landcache->plantingstructuretitle = '作物面积：'.Plantingstructurecheck::getPlantGoodseedSum($id)['plantSum'].'亩';
//			$plant = Plantingstructurecheck::getPlantname($id);
//// 			var_dump($id);
//// 			var_dump($plant);
//			$landcache->plantingstructurecategories = json_encode($plant['plantname']);
//			$landcache->year = date('Y');
//			$landcache->save();
//		}
//		echo 'finished Plantingstructure';
	}
	
	public function actionHuinongcache()
	{
//		$allid = $this->getAllUser();
//		foreach ($allid as $id) {
//			if($cache = Cache::find()->where(['user_id'=>$id,'year'=>date('Y')])->one())
//				$landcache = Cache::findOne($cache->id);
//			else
//				$landcache = new Cache();
//			$landcache->user_id = $id;
//			$landcache->huinongcache = Huinonggrant::getHuinonggrantinfo($id);
//			$landcache->huinongtitle = '惠农政策补贴发放情况';
//			$landcache->huinongcategories = json_encode(Farms::getUserManagementAreaname($id));
//			$landcache->year = date('Y');
//			$landcache->save();
//		}
//		echo 'finished Huinong';
	}
	
	public function actionPlantinputproductcache()
	{
//		$allid = $this->getAllUser();
//// 		var_dump($allid);exit;
//		foreach ($allid as $id) {
//			if($cache = Cache::find()->where(['user_id'=>$id,'year'=>date('Y')])->one())
//				$landcache = Cache::findOne($cache->id);
//			else
//				$landcache = new Cache();
//			$landcache->user_id = $id;
//			$landcache->plantinputproductcache = Plantinputproduct::getInputproduct($id);
//			$landcache->plantinputproducttitle = '投入品使用情况';
//			$landcache->plantinputproductcategories = json_encode(Plantinputproduct::getTypenamelist($id)['typename']);
//			$landcache->year = date('Y');
//			$landcache->save();
//		}
//		echo 'finished Plantinputproduct';
	}
	
	public function actionYieldscache()
	{
//		$allid = $this->getAllUser();
//		// 		var_dump($allid);exit;
//		foreach ($allid as $id) {
//			if($cache = Cache::find()->where(['user_id'=>$id,'year'=>date('Y')])->one())
//				$landcache = Cache::findOne($cache->id);
//			else
//				$landcache = new Cache();
//			$landcache->user_id = $id;
//			$landcache->plantinputproductcache = Yields::getUserYields($id);
//			$landcache->plantinputproducttitle = '农产品产量情况';
//			$landcache->plantinputproductcategories = json_encode(Yields::getUserTypenamelist($id)['typename']);
//			$landcache->year = date('Y');
//			$landcache->save();
//		}
//		echo 'finished Yield';
	}
	//
	public function actionProjectapplicationcache()
	{
//		$allid = $this->getAllUser();
//		foreach ($allid as $id) {
//			if($cache = Cache::find()->where(['user_id'=>$id,'year'=>date('Y')])->one())
//				$landcache = Cache::findOne($cache->id);
//			else
//				$landcache = new Cache();
//			$landcache->user_id = $id;
//			$landcache->projectapplicationcache = Projectapplication::getProjectapplicationcache($id);
//			$landcache->projectapplicationtitle = '项目情况';
//			$landcache->projectapplicationcategories = json_encode(Projectapplication::getTypenamelist($id)['projecttype']);
//			$landcache->year = date('Y');
//			$landcache->save();
//		}
//		echo 'finished Projectapplication';
	}
	public function actionInsurancecache()
	{
//		$allid = $this->getAllUser();
//		foreach ($allid as $user_id) {
//			if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>date('Y')])->one())
//				$landcache = Cache::findOne($cache->id);
//			else
//				$landcache = new Cache();
//			$landcache->user_id = $user_id;
//			$landcache->insurancecache = Insurance::getInsurancecache($user_id);
//			$landcache->insurancetitle = '保险业务';
//			$landcache->insurancecategories = json_encode(Farms::getUserManagementAreaname($user_id));
//			$result = $landcache->save();
//		}
//		echo 'finished Insurance';
	}
	
	public function actionLoancache()
	{
//		$allid = $this->getAllUser();
//		foreach ($allid as $user_id) {
//			if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>date('Y')])->one())
//				$landcache = Cache::findOne($cache->id);
//			else
//				$landcache = new Cache();
//			$landcache->user_id = $user_id;
//			$landcache->loancache = Loan::getLoancache($user_id);
//			$landcache->loantitle = '贷款金额：'.(int)Loan::getLoanMoney($user_id).'万元';
//			$landcache->loancategories = json_encode(Loan::getBankName());
//			$result = $landcache->save();
//		}
//		echo 'finished Loan';
	}
	//以下为单个用户
	public function actionFarmscacheone($user_id)
	{
		
//		if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>date('Y')])->one())
//			$landcache = Cache::findOne($cache->id);
//		else
//			$landcache = new Cache();
//		$landcache->user_id = $user_id;
//		$landcache->farmscache = Farms::getFarmarea($user_id);
//		$landcache->farmstitle = '面积：'.Farms::totalArea($user_id).' 农场户数：'.Farms::totalNum($user_id);
//		$landcache->farmscategories = json_encode(Farms::getUserManagementAreaname($user_id));
//		$landcache->year = date('Y');
//		$result = $landcache->save();
//		echo '宜林农地首页图表更新完成！';
//		return $result;
	}
	
	public function actionCollectioncacheone($user_id)
	{
//		if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>date('Y')])->one())
//			$landcache = Cache::findOne($cache->id);
//		else
//			$landcache = new Cache();
//		$landcache->user_id = $user_id;
//		$landcache->collectioncache = Collection::getCollection($user_id);
//		$landcache->collectiontitle = '应收：'. Collection::totalAmounts($user_id).' 实收：'.Collection::totalReal($user_id);
//		$landcache->collectioncategories = json_encode(Farms::getUserManagementAreaname($user_id));
//		$landcache->year = date('Y');
//		$result = $landcache->save();
//		echo 'collectioncache finished|';
//		return $result;
	}
	public function actionPlantingstructurecacheone($user_id)
	{
//		if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>date('Y')])->one())
//			$landcache = Cache::findOne($cache->id);
//		else
//			$landcache = new Cache();
//		$landcache->user_id = $user_id;
//		$landcache->plantingstructurecache = Plantingstructure::getPlantingstructure($user_id);
//		$landcache->plantingstructuretitle = '作物面积：'.Plantingstructure::getPlantGoodseedSum($user_id)['plantSum'].'亩&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;良种面积：'.Plantingstructure::getPlantGoodseedSum($user_id)['goodseedSum'].'亩';
//		$plant = Plantingstructure::getPlantname($user_id);
//		$landcache->plantingstructurecategories = json_encode($plant['plantname']);
//		$landcache->year = date('Y');
//		$result = $landcache->save();
//		echo '种植结构首页图表更新完成！';
//		return $result;
	}
	
	public function actionHuinongcacheone($user_id)
	{
//		if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>date('Y')])->one())
//			$landcache = Cache::findOne($cache->id);
//		else
//			$landcache = new Cache();
//		$landcache->user_id = $user_id;
//		$landcache->huinongcache = Huinonggrant::getHuinonggrantinfo($user_id);
//		$landcache->huinongtitle = '惠农政策补贴发放情况';
//		$landcache->huinongcategories = json_encode(Farms::getUserManagementAreaname($user_id));
//		$landcache->year = date('Y');
//		$result = $landcache->save();
//		echo '惠农政策补贴发放首页图表更新完成！';
//		return $result;
	}
	
	public function actionPlantinputproductcacheone($user_id)
	{
//		if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>date('Y')])->one())
//			$landcache = Cache::findOne($cache->id);
//		else
//			$landcache = new Cache();
//		$landcache->user_id = $user_id;
//		$landcache->plantinputproductcache = Plantinputproduct::getInputproduct($user_id);
//		$landcache->plantinputproducttitle = '投入品使用情况';
//		$landcache->plantinputproductcategories = json_encode(Plantinputproduct::getTypenamelist($user_id)['typename']);
//		$landcache->year = date('Y');
//		$result = $landcache->save();
//		echo '投入品使用情况首页图表更新完成！';
//		return $result;
	}
	
	public function actionYieldscacheone($user_id)
	{
//		if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>date('Y')])->one())
//			$landcache = Cache::findOne($cache->id);
//		else
//			$landcache = new Cache();
//		$landcache->user_id = $user_id;
//		$landcache->plantinputproductcache = Yields::getUserYields($user_id);
//		$landcache->plantinputproducttitle = '农产品产量情况';
//		$landcache->plantinputproductcategories = json_encode(Yields::getUserTypenamelist($user_id)['typename']);
//		$landcache->year = date('Y');
//		$result = $landcache->save();
//		echo '农产品产量情况首页图表更新完成！';
//		return $result;
	}
	
	public function actionProjectapplicationcacheone($user_id)
	{
//		if($cache = Cache::find()->where(['user_id'=>$user_id,'year'=>date('Y')])->one())
//			$landcache = Cache::findOne($cache->id);
//		else
//			$landcache = new Cache();
//		$landcache->user_id = $user_id;
//		$landcache->projectapplicationcache = Projectapplication::getProjectapplicationcache($user_id);
//		$landcache->projectapplicationtitle = '项目情况';
//		$landcache->projectapplicationcategories = json_encode(Projectapplication::getTypenamelist($user_id)['projecttype']);
//		$landcache->year = date('Y');
//		$result = $landcache->save();
//		echo '项目情况首页图表更新完成！';
//		return $result;
	}
	public function actionInsurancecacheone($user_id)
	{
//		if($cache = Cache::find()->where(['user_id'=>$user_id])->one())
//			$landcache = Cache::findOne($cache->id);
//		else
//			$landcache = new Cache();
//		$landcache->user_id = $user_id;
//		$landcache->insurancecache = Insurance::getInsurancecache($user_id);
//		$landcache->insurancetitle = '保险业务';
//		$landcache->insurancecategories = json_encode(Farms::getUserManagementAreaname($user_id));
//		$result = $landcache->save();
//		echo 'insurahcecache finished|';
//		return $result;
	}
	
	public function actionLoancacheone($user_id)
	{
//		if($cache = Cache::find()->where(['user_id'=>$user_id])->one())
//			$landcache = Cache::findOne($cache->id);
//		else
//			$landcache = new Cache();
//		$landcache->user_id = $user_id;
//		$landcache->loancache = Loan::getLoancache($user_id);
//		$landcache->loantitle = '贷款金额：'.Loan::getLoanMoney($user_id).'亩';
//		$landcache->loancategories = json_encode(Loan::getBankName());
//		$result = $landcache->save();
//		echo 'loancache finished|';
//		return $result;
	}
	public function actionNewyear()
	{
		$oldyear = date('Y') - 1;
		if(Theyear::getYear() !== date('Y')) {
			Theyear::setYear(date('Y'));
			User::setAllUserYear(date('Y'));
			$collection = Collection::find()->all();
			foreach ($collection as $coll) {
				$model = Collection::findOne($coll['id']);
				if ($model->state == 0 and $model->payyear == $oldyear) {
					$model->owe = $model->ypaymoney;
// 					$model->update_at = time();
					$model->ypayyear = $oldyear;
					$model->iscq = 1;
				}
				
				$model->save();
			}
		}
	}
}