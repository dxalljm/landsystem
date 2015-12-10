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
    	$huinongs = Huinong::find()->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->all();
    	return $this->render('huinonglist', [
    			'huinongs' => $huinongs,
    	]);
    }
    
   //惠农政策获取相关数据列表
   public function actionHuinongdata($id)
   {
   		$model = $this->findModel($id);
   		switch ($model->subsidiestype_id) {
   			case 'plant':
   				$classname = 'plantingstructure';
   				$data = Plantingstructure::find()->where(['plant_id'=>$model->typeid])->all();
   				break;
   			case 'goodseed':
   				$classname = 'plantingstructure';
   				$data = Plantingstructure::find()->where(['goodseed_id'=>$model->typeid])->all();
   				break;
   		}
   		$isSubmit = Yii::$app->request->post('isSubmit');
   		
   		if($isSubmit) {
//    			var_dump($isSubmit);exit;
   			$huinonggrantModel = new Huinonggrant();
   			foreach ($isSubmit as $value) {
   				$plantInfo = explode('/', $value);
   				$farms_id = $plantInfo[0];
   				$huinong_id = $plantInfo[1];
   				$money = $plantInfo[2];
   				$area = $plantInfo[3];
   				$farm = Plantingstructure::find()->where(['farms_id'=>$farms_id])->one();
   				$huinonggrantModel->farms_id = $farms_id;
   				$huinonggrantModel->huinong_id = $huinong_id;
   				$huinonggrantModel->money = $money;
   				$huinonggrantModel->area = $area;
   				$huinonggrantModel->state = 0;
   				$huinonggrantModel->create_at = time();
   				$huinonggrantModel->update_at = $huinonggrantModel->create_at;
   				$huinonggrantModel->save();
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
    
   public function actionHuinongsend()
   {
  	 	return $this->render('collectionfinished',['farms_id'=>$farms_id]);
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
