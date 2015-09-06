<?php

namespace frontend\controllers;

use Yii;
use app\models\Plantingstructure;
use frontend\models\plantingstructureSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Parcel;
use app\models\Lease;
use frontend\models\leaseSearch;
use app\models\Plantinputproduct;
use app\models\Plantpesticides;
/**
 * PlantingstructureController implements the CRUD actions for Plantingstructure model.
 */
class PlantingstructureController extends Controller
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
     * Lists all Plantingstructure models.
     * @return mixed
     */
    public function actionPlantingstructureindex($farms_id)
    {
        $lease = Lease::find()->where(['farms_id'=>$farms_id])->all();
		//$this->getView()->registerJsFile($url)
        return $this->render('Plantingstructureindex', [
             'leases' => $lease,
        ]);
    }

    /**
     * Displays a single Plantingstructure model.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructureview($id)
    {
        return $this->render('plantingstructureview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Plantingstructure model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPlantingstructurecreate($lease_id,$farms_id)
    {
        $model = new Plantingstructure();
        $plantinputproductModel = new Plantinputproduct();
        $plantpesticidesModel = new Plantpesticides();
        $plantinputproductData = Plantinputproduct::find()->where(['farms_id'=>$farms_id,'lessee_id'=>$lease_id])->all();
        $plantpesticidesData = Plantpesticides::find()->where(['farms_id'=>$farms_id,'lessee_id'=>$lease_id])->all();
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		$lease = Lease::find()->where(['id'=>$lease_id])->one();
		$zongdiarr = explode('、', $lease['lease_area']);
		foreach($zongdiarr as $value) {
			$zongdi[]['unifiedserialnumber'] = $value;
		}
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plantingstructureindex', 'farms_id' => $farms_id]);
        } else {
            return $this->render('plantingstructurecreate', [
                'model' => $model,
            	'plantpesticidesModel' => $plantpesticidesModel,
            	'plantinputproductModel' => $plantinputproductModel,
            	'plantinputproductData' => $plantinputproductData,
            	'plantpesticidesData' => $plantpesticidesData,
            	'farm' => $farm,
            	'zongdi' => $zongdi,
            ]);
        }
    }

    public function actionPlantingstructuregetarea($zongdi) 
    {
    	$area = Lease::getArea($zongdi);
    	echo json_encode(['status'=>1,'area'=>$area]);
    }
    
    /**
     * Updates an existing Plantingstructure model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructureupdate($id)
    {
        $model = $this->findModel($id);
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();
        $lease = Lease::find()->where(['id'=>$model->lease_id])->one();
        $zongdiarr = explode('、', $lease['lease_area']);
        foreach($zongdiarr as $value) {
        	$zongdi[]['unifiedserialnumber'] = $value;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plantingstructureview', 'id' => $model->id]);
        } else {
            return $this->render('plantingstructureupdate', [
                'model' => $model,
            	'farm' => $farm,
            	'zongdi' => $zongdi,
            ]);
        }
    }

    /**
     * Deletes an existing Plantingstructure model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructuredelete($id,$farms_id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['plantingstructureindex','farms_id'=>$farms_id]);
    }

    /**
     * Finds the Plantingstructure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plantingstructure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plantingstructure::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}