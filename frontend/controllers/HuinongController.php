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
    	$whereArray = Farms::getManagementArea();
    	$huinongs = Huinong::find()->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->all();
    	return $this->render('huinonglist', [
    			'huinongs' => $huinongs,
    	]);
    }
    
    public function actionHuinonginfo()
    {
    	$whereArray = Farms::getManagementArea();
    	$huinongs = Huinong::find()->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->all();
    	return $this->render('huinonginfo', [
    			'huinongs' => $huinongs,
    	]);
    }
    
    public function actionHuinongprovidelist()
    {
    	$huinongs = Huinong::find()->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->all();
    	return $this->render('huinongprovidelist', [
    			'huinongs' => $huinongs,
    	]);
    }
   //惠农政策获取相关数据列表
   public function actionHuinongdata($id)
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
   		$isSubmit = Yii::$app->request->post('isSubmit');
//    		var_dump($isSubmit);
   		if($isSubmit) {
//    			var_dump($isSubmit);exit;
   			
   			foreach ($isSubmit as $value) {
   				$huinonggrantModel = new Huinonggrant();
   				$plantInfo = explode('/', $value);
   				$farms_id = $plantInfo[0];
   				$lease_id = $plantInfo[1];
   				$money = $plantInfo[2];
   				$area = $plantInfo[3];
   				$farm = Plantingstructure::find()->where(['farms_id'=>$farms_id])->one();
   				$huinonggrantModel->farms_id = $farms_id;
   				$huinonggrantModel->management_area = Farms::find()->where(['id'=>$farms_id])->one()['management_area'];
   				$huinonggrantModel->huinong_id = $id;
   				$huinonggrantModel->lease_id = $lease_id;
   				$huinonggrantModel->money = $money;
   				$huinonggrantModel->area = $area;
   				$huinonggrantModel->state = 0;
   				$huinonggrantModel->create_at = time();
   				$huinonggrantModel->update_at = $huinonggrantModel->create_at;
   				$huinonggrantModel->save();
   				$huinongModel = $this->findModel($id);
   				$huinongModel->totalamount = Huinonggrant::find()->where(['huinong_id'=>$id])->sum('money');
   				$huinongModel->realtotalamount = Huinonggrant::find()->where(['huinong_id'=>$id,'state'=>1])->sum('money');
   				$huinongModel->save();
   				Logs::writeLog('地产科提交符合惠农政策条件的农场用户',$huinonggrantModel->id,'',$huinonggrantModel->attributes);
   			}
   			return $this->redirect(['huinongsend']);
   		}
        return $this->render('huinongdata', [
        		'data' => $data,
        		'classname' => $classname,
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
   		
	   	$post = Yii::$app->request->post();
	    
	   	switch ($model->subsidiestype_id) {
	   		case 'plant':
	   			$classname = 'plantingstructure';
	   			break;
	   		case 'goodseed':
	   			$classname = 'plantingstructure';
	   			break;
	   	}
	   	
	   	if($post) {
// 	   		var_dump($_POST);exit;
	   		$query = Huinonggrant::find();
	   		if($post['farmname']) {
	   			$farmid = [];
	   			$farm = Farms::find()->orFilterWhere(['like','farmname',$post['farmname']])->orFilterWhere(['like','pinyin',$post['farmname']])->all();
// 	   			var_dump($farm);exit;
	   			foreach ($farm as $value) {
	   				$farmid[] = $value['id'];
	   			}
	   			$query->andFilterWhere(['farms_id'=>$farmid]);
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
	   	$data = $query->all();
// 	   	var_dump($data);exit;
		   	return $this->render('huinongprovide', [
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
        	$model->typeid = Yii::$app->request->post($model->subsidiestype_id);
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->begindate = (string)strtotime($model->begindate);
        	$model->enddate = (string)strtotime($model->enddate);
//         	var_dump($model);exit;
        	$model->save();
        	
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
