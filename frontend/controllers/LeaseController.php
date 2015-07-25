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

    public function beforeAction($action)
    {
    	$action = Yii::$app->controller->action->id;
    	if(\Yii::$app->user->can($action)){
    		return true;
    	}else{
    		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
    	}
    }
    /**
     * Lists all Lease models.
     * @return mixed
     */
    public function actionLeaseindex($id)
    {
    	//$this->layout='@app/views/layouts/nomain.php';
    	//$lease = Lease::find()->where(['farms_id'=>$id,'years'=>Theyear::findOne(1)['years']])->all();
         $searchModel = new leaseSearch();
         $dataProvider = $searchModel->search(['farms_id'=>$id,'years'=>Theyear::findOne(1)['years']]);
		//$this->getView()->registerJsFile($url)
        return $this->renderAjax('leaseindex', [
             'searchModel' => $searchModel,
             'dataProvider' => $dataProvider,
        	 'areas' => $this->getAreas($id),
        ]);
    }

    /**
     * Displays a single Lease model.
     * @param integer $id
     * @return mixed
     */
    public function actionLeaseview($id)
    {
        return $this->render('leaseview', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionGetoverarea()
    {
    	$lease = new Lease();
    	$area = $lease->getOverArea();
    	echo Json::encode($area);
    }

    /**
     * Creates a new Lease model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionLeasecreate($id)
    {
        
    	//$this->layout='@app/views/layouts/nomain.php';
    	$farm = Farms::find()->where(['id'=>$id])->one();
    	$farmer = Farmer::find()->where(['farms_id'=>$id])->one();
        $model = new leaseSearch();
        
		$areas = $model->getOverArea();
        if ($model->load(Yii::$app->request->post())) {
        	$model->farms_id = $id;
        	$model->save();
            return $this->redirect(['leaseindex', 'id' => $id]);
        } else {
            return $this->renderAjax('leasecreate', [
                'model' => $model,
            	'areas' => $areas,
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
    public function actionLeaseupdate($id)
    {
        $model = $this->findModel($id);
        $areas = $model->getOverArea();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['leaseview', 'id' => $model->id]);
        } else {
            return $this->render('leaseupdate', [
                'model' => $model,
            	'areas' => $areas,
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
    	$farms = $this->findModel($id);
        $this->findModel($id)->delete();
		
        return $this->redirect(['leaseindex','id'=>$farms['farms_id']]);
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
