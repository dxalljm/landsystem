<?php

namespace frontend\controllers;

use Yii;
use app\models\Lease;
use frontend\models\leaseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farmer;
use app\models\Farms;
use app\models\Theyear;
use app\models\Logs;

/**
 * LeaseController implements the CRUD actions for Lease model.
 */
class LeaseController extends Controller
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

//     public function beforeAction($action)
//     {
//     	$action = Yii::$app->controller->action->id;
//     	if(\Yii::$app->user->can($action)){
//     		return true;
//     	}else{
//     		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//     	}
//     }
    /**
     * Lists all Lease models.
     * @return mixed
     */
    public function actionLeaseindex($farms_id)
    {
    	//$farmerarea = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0])->one()['area'];
         $searchModel = new leaseSearch();
         $params = Yii::$app->request->queryParams;
         $params['leaseSearch']['farms_id'] = $farms_id;
         $dataProvider = $searchModel->search($params);
		//$this->getView()->registerJsFile($url)
        //$notclear = Farms::find()->where(['id'=>$farms_id])->one()['notclear'];
		Logs::writeLog('租赁');
        return $this->render('leaseindex', [
             'searchModel' => $searchModel,
             'dataProvider' => $dataProvider,
        	 'areas' => Lease::getNoArea($farms_id),
        	//'farmerarea' => $farmerarea,
        ]);
    }
	
    public function actionLeaseallview($farms_id)
    {
    	$lease = Lease::find()->where(['farms_id'=>$farms_id])->all();
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
    	return $this->render('leaseallview', [
    			'leases'=>$lease,
    			'farm' => $farm,
    	]);
    }
    
    /**
     * Displays a single Lease model.
     * @param integer $id
     * @return mixed
     */
    public function actionLeaseview($id)
    {
    	$model = $this->findModel($id);
    	$farm = Farms::find()->where(['id'=>$model->farms_id])->one();
    	$farmer = Farmer::find()->where(['farms_id'=>$model->farms_id])->one();
    	Logs::writeLog('查看租赁信息',$id);
        return $this->render('leaseview', [
            'model' => $model,
        	'farm' => $farm,
        	'farmer' => $farmer,
        	'noarea' => Lease::getNoArea($model->farms_id),
        ]);
    }
    
    public function actionGetoverarea()
    {
    	$lease = new Lease();
    	$area = $lease::getOverArea();
    	echo Json::encode($area);
    }

    /**
     * Creates a new Lease model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionLeasecreate($farms_id)
    {
    	//$this->layout='@app/views/layouts/nomain.php';
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
    	$farmer = Farmer::find()->where(['farms_id'=>$farms_id])->one();
        $model = new lease();
        
		$overarea = Lease::getOverArea($farms_id);
		$noarea = $model::getNoArea($farms_id);
        if ($model->load(Yii::$app->request->post())) {
        	$model->farms_id = $farms_id;
        	
        	$model->create_at = time();
        	$model->update_at = time();
        	$model->save();
        	$new = $model->attributes;
        	Logs::writeLog('创建租赁信息',$model->id,'',$new);
        	//var_dump($model->getErrors());
            return $this->redirect(['leaseindex', 'farms_id' => $farms_id]);
        } else {
            return $this->render('leasecreate', [
                'model' => $model,
            	'overarea' => $overarea,
            	'noarea' => $noarea,
            	'farm' => $farm,
            	'farmer' => $farmer,
            ]);
        }
    }

    /**
     * Updates an existing Lease model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLeaseupdate($id,$farms_id)
    {
        $model = $this->findModel($id);
        $old = $model->attributes;
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();
        $farmer = Farmer::find()->where(['farms_id'=>$model->farms_id])->one();
        $overarea = $model::getOverArea($farms_id);
		$noarea = $model::getNoArea($farms_id);
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
        	$new = $model->attributes;
        	Logs::writeLog('更新租赁信息',$id,$old,$new);
            return $this->redirect(['leaseview', 'id' => $model->id, 'farms_id'=>$model->farms_id]);
        } else {
            return $this->render('leaseupdate', [
                'model' => $model,
            	'farm' => $farm,
            	'farmer' => $farmer,
            	'overarea' => $overarea,
            	'noarea' => $noarea,
            ]);
        }
    }

    /**
     * Deletes an existing Lease model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLeasedelete($id)
    {
    	$lease = $this->findModel($id);
    	$old = $lease->attributes;
    	Logs::writeLog('删除租赁信息',$id,$old);
        $lease->delete();
		
        return $this->redirect(['leaseindex','farms_id'=>$lease['farms_id']]);
    }

    /**
     * Finds the Lease model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lease the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lease::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function getAreas($id) {
    	$areas = 0;
    	if(($model = Lease::find()->where(['farms_id'=>$id])->all()) !== null) {
    		foreach($model as $val) {
    			$areas+=$val['lease_area'];
    		}
    	}
    	return $areas;
    }
}
