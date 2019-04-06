<?php

namespace frontend\controllers;

use app\models\Plant;
use app\models\User;
use Yii;
use app\models\Huinong;
use frontend\models\HuinongSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;
use app\models\Plantingstructurecheck;
use app\models\Huinonggrant;
use app\models\Farms;
use frontend\models\HuinonggrantSearch;
use frontend\models\plantingstructurecheckSearch;
use app\models\Lease;
use app\models\Subsidiestype;
use app\models\Goodseed;
use app\models\Tempprogress;
use app\models\Farmer;
use app\models\Theyear;

/**
 * HuinongController implements the CRUD actions for Huinong model.
 */
class HuinongController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
	public function beforeAction($action)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		} else {
			return true;
		}
	}
    /**
     * Lists all Huinong models.
     * @return mixed
     */
    public function actionHuinongindex()
    {
        $searchModel = new HuinongSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('惠农政策发布列表');
        return $this->render('huinongindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	//惠农政策当年有效列表
    public function actionHuinonglist()
    {
    	$huinongs = Huinong::find();
		$post = Yii::$app->request->post('setyear');

		if($post) {
			$huinongs->andFilterWhere(['year'=>$post]);
		} else
			$huinongs->andFilterWhere(['year'=>User::getLastYear()]);
    	$data = $huinongs->all();
		Logs::writeLogs(User::getLastYear().'年度惠农政策列表');
    	return $this->render('huinonglist', [
    			'huinongs' => $data,
    			'date' => $post,
    	]);
    }
//	public function actionHuinonglist()
//    {
//    	$huinongs = Huinong::find()->where(['year'=>User::getYear()])->all();
//		$plant = [];
//		foreach ($huinongs as $value) {
//			$plant[] = $value['typeid'];
//		}
//		$searchModel = new plantingstructurecheckSearch();
//		$params = Yii::$app->request->queryParams;
//		$params['plantingstructurecheckSearch']['plant_id'] = $plant;
//		$params ['plantingstructurecheckSearch'] ['year'] = User::getYear();
//		$dataProvider = $searchModel->searchIndex ( $params );
//		Logs::writeLogs(User::getLastYear().'年度惠农政策列表');
//    	return $this->render('huinonglist', [
//			'searchModel' => $searchModel,
//			'dataProvider' => $dataProvider,
//    	]);
//    }
    public function actionHuinonginfo()
    {
    	$whereArray = Farms::getManagementArea();
    	$huinongs = Huinong::find()->where(['year'=>User::getYear()])->all();
		Logs::writeLogs(User::getLastYear().'年度惠农政策首页统计');
    	return $this->render('huinonginfo', [
    			'huinongs' => $huinongs,
    	]);
    }
    
    public function actionHuinongprovidelist()
    {

		$searchModel = new plantingstructurecheckSearch();
		$params = Yii::$app->request->queryParams;
//		$params['plantingstructurecheckSearch']['plant_id'] = $plant;
		$params ['plantingstructurecheckSearch'] ['year'] = User::getYear();
		$params ['plantingstructurecheckSearch'] ['isbank'] = 1;
		$dataProvider = $searchModel->searchHuinong( $params );
		Logs::writeLogs(User::getLastYear().'年度惠农政策列表');
		return $this->render('huinongprovidelist', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
//		$huinongs = Huinong::find()->where(['year'=>User::getYear()])->all();
//    	return $this->render('huinongprovidelist', [
//    			'huinongs' => $huinongs,
//    	]);
    }
   //惠农政策获取相关数据列表
   public function actionHuinongdata($id)
   {
   		$model = $this->findModel($id);

   		$management_area = Farms::getManagementArea()['id'];
   		$issubmitsearch = Yii::$app->request->post('issubmitSearch');
   		$allData = Huinonggrant::find()->where(['huinong_id'=>$id,'management_area'=>$management_area])->all();
   		if($issubmitsearch) 
   			$huinonggrantData = Huinonggrant::find()->where(['huinong_id'=>$id,'management_area'=>$management_area,'issubmit'=>$issubmitsearch])->all();
   		else {
   			$huinonggrantData = Huinonggrant::find()->where(['huinong_id'=>$id,'management_area'=>$management_area,'issubmit'=>0])->all();
   			$issubmitsearch = 0;
   		}
   		
		$typename = Subsidiestype::find()->where(['id'=>$model->subsidiestype_id])->one()['urladdress'];
   		
   		$isSubmit = Yii::$app->request->post('isSubmit');
//    		var_dump($isSubmit);
   		if($isSubmit) {
//    			var_dump($isSubmit);exit;
   			
   			foreach ($isSubmit as $value) {
   				$plantInfo = explode('/', $value);
   				$id = $plantInfo[0];
   				$huinonggrantModel = Huinonggrant::findOne($id);
   				
   				$huinonggrantModel->issubmit = 1;
   				$huinonggrantModel->update_at = time();
   				$huinonggrantModel->save();
   				Logs::writeLogs('地产科提交符合惠农政策条件的农场用户',$huinonggrantModel);
   			}
   			return $this->redirect(['huinongsend']);
   		}
//    		var_dump($data);exit;
        return $this->render('huinongdata', [
        		'allData' => $allData,
        		'data' => $huinonggrantData,
        		'classname' => $typename,
        		'issubmitSearch' => $issubmitsearch,
        		'model' => $model,
        ]);
   }
   //补贴发放明细
   public function actionHuinongdatainfo($id)
   {
		$model = $this->findModel($id);
		$farmsallid = Farms::getManagementAreaAllID();
		switch ($model->subsidiestype_id) {
	   		case 'plant':
	   			$classname = 'plantingstructure';
	   			$data = Plantingstructurecheck::find()->where(['plant_id'=>$model->typeid,'farms_id'=>$farmsallid])->all();
	   			break;
	   		case 'goodseed':
	   			$classname = 'plantingstructure';
	   			$data = Plantingstructurecheck::find()->where(['goodseed_id'=>$model->typeid,'farms_id'=>$farmsallid])->all();
	   			break;
	   	}
	   	$whereArray = Farms::getManagementArea()['id'];
		$data = Huinonggrant::find()->where(['huinong_id'=>$id,'management_area'=>$whereArray])->all();
   		Logs::writeLog('惠农补贴发放明细');
	   	return $this->render('huinongdatainfo', [
	   			'data' => $data,
	   			'classname' => $classname,
	   			'model' => $model,
	   	]);
   }
   
   public function actionHuinonginfodata($id)
   {
   	$model = $this->findModel($id);
   	$farmsallid = Farms::getManagementAreaAllID();
   	switch ($model->subsidiestype_id) {
   		case 'plant':
   			$classname = 'plantingstructure';
   			$data = Plantingstructurecheck::find()->where(['plant_id'=>$model->typeid,'farms_id'=>$farmsallid])->all();
   			break;
   		case 'goodseed':
   			$classname = 'plantingstructure';
   			$data = Plantingstructurecheck::find()->where(['goodseed_id'=>$model->typeid,'farms_id'=>$farmsallid])->all();
   			break;
   	}
   	$whereArray = Farms::getManagementArea()['id'];
   	$data = Huinonggrant::find()->where(['huinong_id'=>$id,'management_area'=>$whereArray])->all();
   	 
   	return $this->render('huinonginfodata', [
   			'data' => $data,
   			'classname' => $classname,
   			'model' => $model,
   	]);
   }
   //惠农政策发放
   public function actionHuinongprovide($id)
   {
   		$model = $this->findModel($id);
   		$management_area = Farms::getManagementArea()['id'];
   		$allData = Huinonggrant::find()->where(['huinong_id'=>$id,'management_area'=>$management_area])->all();
	   	$post = Yii::$app->request->post();
	   	$typename = Subsidiestype::find()->where(['id'=>$model->subsidiestype_id])->one()['urladdress'];
	   	switch ($typename) {
	   		case 'Plant':
	   			$classname = 'plantingstructurecheck';
	   			break;
	   		case 'Goodseed':
	   			$classname = 'plantingstructurecheck';
	   			break;
	   	}
// 	   	var_dump($post);exit;
	   	if($post) {
// 	   		var_dump($_POST);exit;
	   		$query = Huinonggrant::find();
	   		$query->andFilterWhere(['huinong_id'=>$id]);
	   		if($post['farmname']) {
	   			$farmid = [];
	   			$farm = Farms::find()->orFilterWhere(['like','farmname',$post['farmname']])->orFilterWhere(['like','pinyin',$post['farmname']])->all();
// 	   			var_dump($farm);exit;
	   			foreach ($farm as $value) {
	   				$farmid[] = $value['id'];
	   			}
	   			$query->andFilterWhere(['farms_id'=>$farmid]);
	   		}
// 	   		var_dump($post);
	   		if($post['management_area'] !== '0') {
	   			$query->andFilterWhere(['management_area'=>$post['management_area']]);
	   		}
	   		if($post['farmername']) {
	   			$farm = Farms::find()->orFilterWhere(['like','farmername',$post['farmername']])->orFilterWhere(['like','farmerpinyin',$post['farmername']])->all();
	   			$farmid = [];
	   			foreach ($farm as $value) {
	   				$farmid[] = $value['id'];
	   			}
	   			$query->andFilterWhere(['farms_id'=>$farmid]);
	   		}
	   		if($post['lesseename']) {
// 	   			exit;
	   			$lease = Lease::find()->orFilterWhere(['like','lessee',$post['lesseename']])->all();
// 	   			var_dump($lease);exit;
	   			foreach ($lease as $value) {
	   				$leaseid[] = $value['id'];
	   			}
	   			$query->andFilterWhere(['lease_id'=>$leaseid]);
	   		}
	   		if(isset($post['is_provide'])) {
	   			$query->andFilterWhere(['state'=>$post['is_provide']]);
	   		} else {
	   			$post['is_provide'] = '';
	   		}
	   		if(isset($post['isSubmit'])) {
	   			$huinonggrantModel = Huinonggrant::findOne($post['isSubmit'][0]);
	   			$huinonggrantModel->state = 1;
	   			$huinonggrantModel->save();
	   		}
	   	} else {
	   		$post = ['farmname'=>'','farmername'=>'','lesseename'=>'','is_provide'=>''];
	   		$query = Huinonggrant::find();
		   	$data =$query->where(['huinong_id'=>$id]);
	   	} 	
// 	   	var_dump($query);exit;
	   	$data = $query->all();
// 	   	var_dump($data);exit;
		   	return $this->render('huinongprovide', [
		   			'allData' => $allData,
		   			'id' => $id,
		   			'data' => $data,
		   			'classname' => $classname,
		   			'model' => $model,
		   			'post' => $post,
		   	]);
	   
   }
   
   public function actionHuinongprovideone($farms_id,$huinong_id=NULL)
   {
   		if($huinong_id)
   			$model = $this->findModel($huinong_id);
   		else 
   			$model = null;
	   	$management_area = Farms::getManagementArea()['id'];
	   	$farm = Farms::find()->where(['id'=>$farms_id])->one();
	   	$farmer = Farmer::find()->where(['farms_id'=>$farms_id])->one();
	   	if(empty($huinong_id))
	   		$allData = Huinonggrant::find()->where(['management_area'=>$management_area])->all();
	   	else
	   		$allData = Huinonggrant::find()->where(['huinong_id'=>$huinong_id,'management_area'=>$management_area])->all();
	   	
	   	
		if($model)
	   		$typename = Subsidiestype::find()->where(['id'=>$model->subsidiestype_id])->one()['urladdress'];
		else 
			$typename = null;
	   	switch ($typename) {
	   		case 'Plant':
	   			$classname = 'plantingstructure';
	   			break;
	   		case 'Goodseed':
	   			$classname = 'plantingstructure';
	   			break;
	   		default:
	   			$classname = null;
   		}
   		$issubmit = Yii::$app->request->post('isSubmit');
//    		echo '<br><br><br><br><br><br>br><br><br><br>jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj=';print_r($issubmit);
   		if(empty($huinong_id)) {
   			//    			echo '33333';exit;
   			$huinonggrant = Huinonggrant::find()->where(['farms_id'=>$farms_id])->all();
   		}
   		else {
   			$huinonggrant = Huinonggrant::find()->where(['huinong_id'=>$huinong_id,'farms_id'=>$farms_id])->one();
   		}
   		if($issubmit) {
//    			exit;
//    			if(isset($post['isSubmit'])) {
   				$huinonggrantModel = Huinonggrant::findOne($issubmit[0]);
   				$huinonggrantModel->state = 1;
   				$huinonggrantModel->save();
   				return $this->redirect(['huinongprovideone','farms_id'=>$farms_id,'huinong_id'=>$huinong_id]);
//    			}
   		} else 
	   		return $this->render('huinongprovideone', [
	   				'huinonggrant' => $huinonggrant,
	   				'allData' => $allData,
	   				'classname' => $classname,
	   				'model' => $model,
	   				'farm' => $farm,
	   				'farmer' => $farmer,
	   		]);
   }
   
   public function actionHuinongsend()
   {
  	 	return $this->render('collectionsend');
   }
   
   public function actionHuinongsearch()
   {
   		$model = new HuinonggrantSearch();
   		return $this->render('huinongsearch',['model' => $model]);
   }
   
    /**
     * Displays a single Huinong model.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinongview($id)
    {
        return $this->render('huinongview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Huinong model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionHuinongcreate()
    {
        $model = new Huinong();

        if ($model->load(Yii::$app->request->post())) {
			if(Yii::$app->request->post('goodseed'))
				$model->typeid = Yii::$app->request->post('goodseed');
			if(Yii::$app->request->post('plant'))
				$model->typeid = Yii::$app->request->post('plant');
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
//			$model->year = User::getYear();
//        	$model->begindate = (string)$model->begindate;
        	if(Yii::$app->request->post('goodseed'))
        		$model->totalsubsidiesarea = sprintf("%.2f", Plantingstructurecheck::find()->where(['goodseed_id'=>$model->typeid,'year'=>$model->year])->sum('area'));
        	if(Yii::$app->request->post('plant'))
        		$model->totalsubsidiesarea = sprintf("%.2f", Plantingstructurecheck::find()->where(['plant_id'=>$model->typeid,'year'=>$model->year])->sum('area'));
        	if(Yii::$app->request->post('goodseed'))
        		$model->totalamount = sprintf("%.2f", Plantingstructurecheck::find()->where(['goodseed_id'=>$model->typeid,'year'=>$model->year])->sum('area')*$model->subsidiesmoney);
        	if(Yii::$app->request->post('plant'))
        		$model->totalamount = sprintf("%.2f", Plantingstructurecheck::find()->where(['plant_id'=>$model->typeid,'year'=>$model->year])->sum('area')*$model->subsidiesmoney);
			$model->year = User::getYear();
			$model->save();
//			var_dump($model->getErrors());exit;
//        	if($model->save()) {

//				var_dump($model);exit;
//        		if(Yii::$app->request->post('goodseed'))
//        			$plantingsructure = Plantingstructurecheck::find()->andFilterWhere(['goodseed_id'=>$model->typeid,'year'=>$model->year])->all();
//        		if(Yii::$app->request->post('plant'))
//        			$plantingsructure = Plantingstructurecheck::find()->andFilterWhere(['plant_id'=>$model->typeid,'year'=>$model->year])->all();
//
//        		foreach ($plantingsructure as $value) {
//					$huinongascription = Lease::getHuinonginfo($value['lease_id']);
//					if($huinongascription) {
//						$plant = Plant::findOne($model->typeid);
//						$bfb = ['farmer' => 0, 'lessee' => 0];
//						switch ($plant->typename) {
//							case '大豆':
//								$bfb['farmer'] = Lease::getBFBnumber($huinongascription['farmer']['ddcj']);
//								$bfb['lessee'] = Lease::getBFBnumber($huinongascription['lessee']['ddcj']);
//								break;
//							case '玉米':
//								$bfb['farmer'] = Lease::getBFBnumber($huinongascription['farmer']['ymcj']);
//								$bfb['lessee'] = Lease::getBFBnumber($huinongascription['lessee']['ymcj']);
//								break;
//						}
//						if (bccomp($bfb['farmer'], 0) == 1) {
//							$huinonggrantModel = Huinonggrant::find()->where(['farms_id'=>$value['farms_id'],'lease_id'=>0,'huinong_id'=>$model->id,'typeid'=>$model->typeid])->one();
//							if(empty($huinonggrantModel)) {
//								$huinonggrantModel = new Huinonggrant();
//								$huinonggrantModel->create_at = time();
//								$huinonggrantModel->update_at = $huinonggrantModel->create_at;
//							} else {
//								$huinonggrantModel->update_at = time();
//							}
//
//							$huinonggrantModel->farms_id = $value['farms_id'];
//							$huinonggrantModel->management_area = $value['management_area'];
//							$huinonggrantModel->huinong_id = $model->id;
//							$huinonggrantModel->subsidiestype_id = $model->subsidiestype_id;
//							$huinonggrantModel->typeid = $model->typeid;
//							$huinonggrantModel->lease_id = 0;
//							$huinonggrantModel->money = $model->subsidiesarea * 0.01 * $value['area'] * $model->subsidiesmoney * ($bfb['farmer']/100);
//							$huinonggrantModel->area = $value['area'];
//							$huinonggrantModel->state = 0;
//							$huinonggrantModel->issubmit = 0;
//							$huinonggrantModel->subsidyobject = Farms::find()->where(['id'=>$value['farms_id']])->one()['farmername'];
//							$huinonggrantModel->proportion = $bfb['farmer'].'%';
//							$huinonggrantModel->year = $model->year;
//							$huinonggrantModel->save();
//							Logs::writeLogs('建立所有符合条件用户数据', $huinonggrantModel);
//						}
//						if (bccomp($bfb['lessee'], 0) == 1) {
//							$huinonggrantModel = Huinonggrant::find()->where(['farms_id'=>$value['farms_id'],'lease_id'=>$value['lease_id'],'huinong_id'=>$model->id,'typeid'=>$model->typeid])->one();
//							if(empty($huinonggrantModel)) {
//								$huinonggrantModel = new Huinonggrant();
//								$huinonggrantModel->create_at = time();
//								$huinonggrantModel->update_at = $huinonggrantModel->create_at;
//							} else {
//								$huinonggrantModel->update_at = time();
//							}
//
//							$huinonggrantModel->farms_id = $value['farms_id'];
//							$huinonggrantModel->management_area = $value['management_area'];
//							$huinonggrantModel->huinong_id = $model->id;
//							$huinonggrantModel->subsidiestype_id = $model->subsidiestype_id;
//							$huinonggrantModel->typeid = $model->typeid;
//							$huinonggrantModel->lease_id = $value['lease_id'];
//							$huinonggrantModel->money = $model->subsidiesarea * 0.01 * $value['area'] * $model->subsidiesmoney * ($bfb['lessee']/100);
//							$huinonggrantModel->area = $value['area'];
//							$huinonggrantModel->state = 0;
//							$huinonggrantModel->issubmit = 0;
//							$huinonggrantModel->subsidyobject = Lease::find()->where(['id'=>$value['lease_id']])->one()['lessee'];
//							$huinonggrantModel->proportion = $bfb['lessee'].'%';
//							$huinonggrantModel->year = $model->year;
//							$huinonggrantModel->save();
//							Logs::writeLogs('建立所有符合条件用户数据', $huinonggrantModel);
//						}
//					}
//					} else {
//						$huinonggrantModel = new Huinonggrant();
//
//						$huinonggrantModel->farms_id = $value['farms_id'];
//						$huinonggrantModel->management_area = $value['management_area'];
//						$huinonggrantModel->huinong_id = $model->id;
//						$huinonggrantModel->subsidiestype_id = $model->subsidiestype_id;
//						$huinonggrantModel->typeid = $model->typeid;
//						$huinonggrantModel->lease_id = $value['lease_id'];
//						$huinonggrantModel->money = $model->subsidiesarea * 0.01 * $value['area'] * $model->subsidiesmoney;
//						$huinonggrantModel->area = $value['area'];
//						$huinonggrantModel->state = 0;
//						$huinonggrantModel->issubmit = 0;
//						$huinonggrantModel->subsidyobject = Huinong::showSubsidyName($huinongascription);
//						$huinonggrantModel->proportion = '100%';
//						$huinonggrantModel->create_at = time();
//						$huinonggrantModel->update_at = $huinonggrantModel->create_at;
//						$huinonggrantModel->save();
//						Logs::writeLog('建立所有符合条件用户数据', $huinonggrantModel->id, '', $huinonggrantModel->attributes);
//					}
// 	        		$tempModel = Tempprogress::findOne($value['id']);
// 	        		$tempModel->delete();
//        		}
//        	}
        	Logs::writeLog('新增惠农政策',$model->id,'',$model->attributes);
            return $this->redirect(['huinongindex']);
        } else {
            return $this->render('huinongcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Huinong model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinongupdate($id)
    {
        $model = $this->findModel($id);
		if ($model->load(Yii::$app->request->post())) {
//			if(Yii::$app->request->post('goodseed'))
//				$model->typeid = Yii::$app->request->post('goodseed');
			if(Yii::$app->request->post('plant'))
				$model->typeid = Yii::$app->request->post('plant');
			$model->create_at = time();
			$model->update_at = $model->create_at;
			$model->year = (string)$model->year;
//			if(Yii::$app->request->post('goodseed'))
//				$model->totalsubsidiesarea = sprintf("%.2f", Plantingstructurecheck::find()->where(['goodseed_id'=>$model->typeid,'year'=>$model->year])->sum('area'));
			if(Yii::$app->request->post('plant'))
				$model->totalsubsidiesarea = sprintf("%.2f", Plantingstructurecheck::find()->where(['plant_id'=>$model->typeid,'year'=>$model->year])->sum('area'));
//			if(Yii::$app->request->post('goodseed'))
//				$model->totalamount = sprintf("%.2f", Plantingstructurecheck::find()->where(['goodseed_id'=>$model->typeid,'year'=>$model->year])->sum('area')*$model->subsidiesmoney);
			if(Yii::$app->request->post('plant'))
				$model->totalamount = Plantingstructurecheck::find()->where(['plant_id'=>$model->typeid,'year'=>$model->year])->sum('area')*$model->subsidiesmoney;
			$model->save();
//			if($model->save()) {
////				var_dump($model);exit;
//				if(Yii::$app->request->post('goodseed'))
//					$plantingsructure = Plantingstructurecheck::find()->andFilterWhere(['goodseed_id'=>$model->typeid,'year'=>$model->year])->all();
//				if(Yii::$app->request->post('plant'))
//					$plantingsructure = Plantingstructurecheck::find()->andFilterWhere(['plant_id'=>$model->typeid,'year'=>$model->year])->all();
//
////         		foreach ($plantingsructure as $val) {
////         			$temp = new Tempprogress();
////         			$temp->id = $val['id'];
////         			$temp->save();
////         		}
////				var_dump($model);
////				var_dump($plantingsructure);exit;
//				foreach ($plantingsructure as $value) {
//					$huinongascription = Lease::getHuinonginfo($value['lease_id']);
////					var_dump($huinongascription);
//					if($huinongascription) {
//						$plant = Plant::findOne($model->typeid);
//						$bfb = ['farmer' => 0, 'lessee' => 0];
//						switch ($plant->typename) {
//							case '大豆':
//								$bfb['farmer'] = Lease::getBFBnumber($huinongascription['farmer']['ddcj']);
//								$bfb['lessee'] = Lease::getBFBnumber($huinongascription['lessee']['ddcj']);
//								break;
//							case '玉米':
//								$bfb['farmer'] = Lease::getBFBnumber($huinongascription['farmer']['ymcj']);
//								$bfb['lessee'] = Lease::getBFBnumber($huinongascription['lessee']['ymcj']);
//								break;
//						}
//						var_dump($bfb);
//						if (bccomp($bfb['farmer'], 0) == 1) {
//							$huinonggrantModel = Huinonggrant::find()->where(['farms_id' => $value['farms_id'], 'lease_id' => 0, 'huinong_id' => $model->id, 'typeid' => $model->typeid])->one();
//							if (empty($huinonggrantModel)) {
//								$huinonggrantModel = new Huinonggrant();
//								$huinonggrantModel->create_at = time();
//								$huinonggrantModel->update_at = $huinonggrantModel->create_at;
//							} else {
//								$huinonggrantModel->update_at = time();
//							}
//
//							$huinonggrantModel->farms_id = $value['farms_id'];
//							$huinonggrantModel->management_area = $value['management_area'];
//							$huinonggrantModel->huinong_id = $model->id;
//							$huinonggrantModel->subsidiestype_id = $model->subsidiestype_id;
//							$huinonggrantModel->typeid = $model->typeid;
//							$huinonggrantModel->lease_id = 0;
//							$huinonggrantModel->money = $model->subsidiesarea * 0.01 * $value['area'] * $model->subsidiesmoney * ($bfb['farmer'] / 100);
//							$huinonggrantModel->area = $value['area'];
//							$huinonggrantModel->state = 0;
//							$huinonggrantModel->issubmit = 0;
//							$huinonggrantModel->subsidyobject = Farms::find()->where(['id'=>$value['farms_id']])->one()['farmername'];
//							$huinonggrantModel->proportion = $bfb['farmer'].'%';
//							$huinonggrantModel->year = $model->year;
//							$huinonggrantModel->save();
//							var_dump($huinonggrantModel->getErrors());
//							Logs::writeLogs('建立所有符合条件用户数据', $huinonggrantModel);
//						}
//						if (bccomp($bfb['lessee'], 0) == 1) {
//							$huinonggrantModel = Huinonggrant::find()->where(['farms_id' => $value['farms_id'], 'lease_id' => $value['lease_id'], 'huinong_id' => $model->id, 'typeid' => $model->typeid])->one();
//							if (empty($huinonggrantModel)) {
//								$huinonggrantModel = new Huinonggrant();
//								$huinonggrantModel->create_at = time();
//								$huinonggrantModel->update_at = $huinonggrantModel->create_at;
//							} else {
//								$huinonggrantModel->update_at = time();
//							}
//
//							$huinonggrantModel->farms_id = $value['farms_id'];
//							$huinonggrantModel->management_area = $value['management_area'];
//							$huinonggrantModel->huinong_id = $model->id;
//							$huinonggrantModel->subsidiestype_id = $model->subsidiestype_id;
//							$huinonggrantModel->typeid = $model->typeid;
//							$huinonggrantModel->lease_id = $value['lease_id'];
//							$huinonggrantModel->money = $model->subsidiesarea * 0.01 * $value['area'] * $model->subsidiesmoney * ($bfb['lessee'] / 100);
//							$huinonggrantModel->area = $value['area'];
//							$huinonggrantModel->state = 0;
//							$huinonggrantModel->issubmit = 0;
//							$huinonggrantModel->subsidyobject = Lease::find()->where(['id'=>$value['lease_id']])->one()['lessee'];
//							$huinonggrantModel->proportion = $bfb['lessee'].'%';
//							$huinonggrantModel->year = $model->year;
//							$huinonggrantModel->save();
//							Logs::writeLogs('建立所有符合条件用户数据', $huinonggrantModel);
//						}
//					}
//				}
//			}
//			exit;
			Logs::writeLogs('更新惠农政策',$model);
			return $this->redirect(['huinongindex']);
		} else {
			return $this->render('huinongcreate', [
				'model' => $model,
			]);
		}
    }

    /**
     * Deletes an existing Huinong model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinongdelete($id)
    {
        $model = $this->findModel($id);
		Huinonggrant::deleteAll(['huinong_id'=>$id]);
		$model->delete();
		Logs::writeLogs('删除惠农政策',$model);
        return $this->redirect(['huinongindex']);
    }

    /**
     * Finds the Huinong model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Huinong the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Huinong::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
