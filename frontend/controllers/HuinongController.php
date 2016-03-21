<?php

namespace frontend\controllers;

use Yii;
use app\models\Huinong;
use frontend\models\HuinongSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;
use app\models\Plantingstructure;
use app\models\Huinonggrant;
use app\models\Farms;
use frontend\models\HuinonggrantSearch;
use yii\data\ActiveDataProvider;
use app\models\Lease;
use app\models\Subsidiestype;
use app\models\Goodseed;
use app\models\Tempprogress;

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

    /**
     * Lists all Huinong models.
     * @return mixed
     */
    public function actionHuinongindex()
    {
        $searchModel = new HuinongSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
			$huinongs->andFilterWhere(['begindate'=>$post]);
		} else 
			$huinongs->andFilterWhere(['begindate'=>date('Y')]);
    	$data = $huinongs->all();
    	return $this->render('huinonglist', [
    			'huinongs' => $data,
    			'date' => $post,
    	]);
    }
    
    public function actionHuinonginfo()
    {
    	$whereArray = Farms::getManagementArea();
    	$huinongs = Huinong::find()->all();
    	return $this->render('huinonginfo', [
    			'huinongs' => $huinongs,
    	]);
    }
    
    public function actionHuinongprovidelist()
    {
    	$huinongs = Huinong::find()->all();
    	return $this->render('huinongprovidelist', [
    			'huinongs' => $huinongs,
    	]);
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
   				Logs::writeLog('地产科提交符合惠农政策条件的农场用户',$huinonggrantModel->id,'',$huinonggrantModel->attributes);
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
	   			$data = Plantingstructure::find()->where(['plant_id'=>$model->typeid,'farms_id'=>$farmsallid])->all();
	   			break;
	   		case 'goodseed':
	   			$classname = 'plantingstructure';
	   			$data = Plantingstructure::find()->where(['goodseed_id'=>$model->typeid,'farms_id'=>$farmsallid])->all();
	   			break;
	   	}
	   	$whereArray = Farms::getManagementArea()['id'];
		$data = Huinonggrant::find()->where(['huinong_id'=>$id,'management_area'=>$whereArray])->all();
   		
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
   			$data = Plantingstructure::find()->where(['plant_id'=>$model->typeid,'farms_id'=>$farmsallid])->all();
   			break;
   		case 'goodseed':
   			$classname = 'plantingstructure';
   			$data = Plantingstructure::find()->where(['goodseed_id'=>$model->typeid,'farms_id'=>$farmsallid])->all();
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
	   			$classname = 'plantingstructure';
	   			break;
	   		case 'Goodseed':
	   			$classname = 'plantingstructure';
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
   public function actionHuinongsend()
   {
  	 	return $this->render('collectionsend');
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
        	$model->begindate = (string)$model->begindate;
        	if(Yii::$app->request->post('goodseed'))
        		$model->totalsubsidiesarea = Plantingstructure::find()->where(['goodseed_id'=>$model->typeid])->andFilterWhere(['between','create_at',strtotime($model->begindate.'-01-01'),strtotime($model->begindate.'-12-31')])->sum('area');
        	if(Yii::$app->request->post('plant'))
        		$model->totalsubsidiesarea = Plantingstructure::find()->where(['plant_id'=>$model->typeid])->andFilterWhere(['between','create_at',strtotime($model->begindate.'-01-01'),strtotime($model->begindate.'-12-31')])->sum('area');
        	if(Yii::$app->request->post('goodseed'))
        		$model->totalamount = Plantingstructure::find()->where(['goodseed_id'=>$model->typeid])->andFilterWhere(['between','create_at',strtotime($model->begindate.'-01-01'),strtotime($model->begindate.'-12-31')])->sum('area')*$model->subsidiesmoney;
        	if(Yii::$app->request->post('plant'))
        		$model->totalamount = Plantingstructure::find()->where(['plant_id'=>$model->typeid])->andFilterWhere(['between','create_at',strtotime($model->begindate.'-01-01'),strtotime($model->begindate.'-12-31')])->sum('area')*$model->subsidiesmoney;
        	
        	if($model->save()) {
        		if(Yii::$app->request->post('goodseed'))
        			$plantingsructure = Plantingstructure::find()->andFilterWhere(['goodseed_id'=>$model->typeid])->andFilterWhere(['between','create_at',strtotime($model->begindate.'-01-01'),strtotime($model->begindate.'-12-31')])->all();
        		if(Yii::$app->request->post('plant'))
        			$plantingsructure = Plantingstructure::find()->andFilterWhere(['plant_id'=>$model->typeid])->andFilterWhere(['between','create_at',strtotime($model->begindate.'-01-01'),strtotime($model->begindate.'-12-31')])->all();
        		
//         		foreach ($plantingsructure as $val) {
//         			$temp = new Tempprogress();
//         			$temp->id = $val['id'];
//         			$temp->save();
//         		}
        		
        		foreach ($plantingsructure as $value) {
	        		$huinonggrantModel = new Huinonggrant();

	        		$huinonggrantModel->farms_id = $value['farms_id'];
	        		$huinonggrantModel->management_area = $value['management_area'];
	        		$huinonggrantModel->huinong_id = $model->id;
	        		$huinonggrantModel->subsidiestype_id = $model->subsidiestype_id;
	        		$huinonggrantModel->typeid = $model->typeid;
	        		$huinonggrantModel->lease_id = $value['lease_id'];
	        		$huinonggrantModel->money = $model->subsidiesarea*0.01*$value['area'] * $model->subsidiesmoney;
	        		$huinonggrantModel->area = $value['area'];
	        		$huinonggrantModel->state = 0;
	        		$huinonggrantModel->issubmit = 0;
	        		$huinonggrantModel->create_at = time();
	        		$huinonggrantModel->update_at = $huinonggrantModel->create_at;
	        		$huinonggrantModel->save();
	        		Logs::writeLog('建立所有符合条件用户数据',$huinonggrantModel->id,'',$huinonggrantModel->attributes);
// 	        		$tempModel = Tempprogress::findOne($value['id']);
// 	        		$tempModel->delete();
        		}
        	}
        	
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
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post())) {
        	$model->typeid = Yii::$app->request->post($model->subsidiestype_id);
        	$model->update_at = time();
        	$model->begindate = (string)strtotime($model->begindate);
        	$model->enddate = (string)strtotime($model->enddate);
//         	var_dump($model->begindate);exit;
        	$model->save();
//         	var_dump($model->getErrors());exit;
        	$new = $model->attributes;
        	Logs::writeLog('更新惠农政策',$model->id,$old,$new);
            return $this->redirect(['huinongindex']);
        } else {
            return $this->render('huinongupdate', [
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
        $this->findModel($id)->delete();

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
